import * as SecureStore from 'expo-secure-store';
import { Platform } from 'react-native';

const REVERB_APP_KEY = process.env.EXPO_PUBLIC_REVERB_APP_KEY || '';
const REVERB_HOST = process.env.EXPO_PUBLIC_REVERB_HOST || 'localhost';
const REVERB_PORT = process.env.EXPO_PUBLIC_REVERB_PORT || '6001';
const REVERB_SCHEME = process.env.EXPO_PUBLIC_REVERB_SCHEME || 'https';

const API_URL = process.env.EXPO_PUBLIC_API_URL || 'http://localhost:8000/api/v1';
const AUTH_ENDPOINT = API_URL.replace('/v1', '/broadcasting/auth');

type EventCallback = (data: any) => void;
type ConnectionCallback = () => void;

interface ChannelSubscription {
    channelName: string;
    events: Map<string, EventCallback[]>;
}

class ReverbWebSocket {
    private ws: WebSocket | null = null;
    private socketId: string | null = null;
    private channels: Map<string, ChannelSubscription> = new Map();
    private reconnectAttempts = 0;
    private maxReconnectAttempts = 5;
    private reconnectDelay = 1000;
    private isConnecting = false;
    private connectionPromise: Promise<void> | null = null;
    
    private onConnectedCallbacks: ConnectionCallback[] = [];
    private onDisconnectedCallbacks: ConnectionCallback[] = [];

    /**
     * Get the WebSocket URL for Reverb
     */
    private getWsUrl(): string {
        const protocol = REVERB_SCHEME === 'https' ? 'wss' : 'ws';
        return `${protocol}://${REVERB_HOST}:${REVERB_PORT}/app/${REVERB_APP_KEY}?protocol=7&client=js&version=8.4.0-rc2&flash=false`;
    }

    /**
     * Connect to Reverb WebSocket server
     */
    async connect(): Promise<void> {
        if (this.ws?.readyState === WebSocket.OPEN) {
            return;
        }

        if (this.isConnecting && this.connectionPromise) {
            return this.connectionPromise;
        }

        // Don't try to connect if we've exhausted retries
        if (this.reconnectAttempts >= this.maxReconnectAttempts) {
            console.log('[WebSocket] Max reconnect attempts reached, using polling fallback');
            return Promise.reject(new Error('Max reconnect attempts reached'));
        }

        this.isConnecting = true;
        this.connectionPromise = new Promise((resolve, reject) => {
            try {
                const url = this.getWsUrl();
                console.log('[WebSocket] Connecting to:', url);
                
                this.ws = new WebSocket(url);

                this.ws.onopen = () => {
                    console.log('[WebSocket] Connected');
                    this.reconnectAttempts = 0;
                    this.isConnecting = false;
                };

                this.ws.onmessage = (event) => {
                    this.handleMessage(event.data);
                    
                    // On first message with socket_id, resolve the connection
                    if (this.socketId && this.isConnecting) {
                        this.isConnecting = false;
                        resolve();
                    }
                };

                this.ws.onerror = () => {
                    // Silently handle error - onclose will handle reconnection
                    this.isConnecting = false;
                };

                this.ws.onclose = (event) => {
                    this.socketId = null;
                    this.isConnecting = false;
                    this.onDisconnectedCallbacks.forEach(cb => cb());
                    
                    // Auto-reconnect with backoff
                    if (this.reconnectAttempts < this.maxReconnectAttempts) {
                        this.reconnectAttempts++;
                        const delay = this.reconnectDelay * this.reconnectAttempts;
                        console.log(`[WebSocket] Will retry in ${delay}ms (${this.reconnectAttempts}/${this.maxReconnectAttempts})`);
                        setTimeout(() => this.connect().catch(() => {}), delay);
                    } else {
                        console.log('[WebSocket] Giving up, falling back to polling');
                    }
                    
                    reject(new Error(event.reason || 'Connection closed'));
                };

                // Timeout for initial connection
                setTimeout(() => {
                    if (this.isConnecting) {
                        this.isConnecting = false;
                        reject(new Error('Connection timeout'));
                    }
                }, 10000);

            } catch (error) {
                this.isConnecting = false;
                reject(error);
            }
        });

        return this.connectionPromise;
    }

    /**
     * Handle incoming WebSocket messages
     */
    private handleMessage(data: string): void {
        try {
            const message = JSON.parse(data);
            
            // Handle Pusher protocol events
            switch (message.event) {
                case 'pusher:connection_established':
                    const connectionData = JSON.parse(message.data);
                    this.socketId = connectionData.socket_id;
                    console.log('[WebSocket] Socket ID:', this.socketId);
                    this.onConnectedCallbacks.forEach(cb => cb());
                    break;

                case 'pusher:error':
                    console.error('[WebSocket] Pusher error:', message.data);
                    break;

                case 'pusher_internal:subscription_succeeded':
                    console.log('[WebSocket] Subscribed to:', message.channel);
                    break;

                default:
                    // Handle channel events
                    if (message.channel && message.event) {
                        console.log('[WebSocket] Event received:', message.channel, message.event);
                        this.dispatchEvent(message.channel, message.event, message.data);
                    }
                    break;
            }
        } catch (error) {
            console.error('[WebSocket] Failed to parse message:', error);
        }
    }

    /**
     * Dispatch event to channel listeners
     */
    private dispatchEvent(channel: string, event: string, data: any): void {
        const subscription = this.channels.get(channel);
        if (!subscription) return;

        const callbacks = subscription.events.get(event);
        if (!callbacks) return;

        // Parse data if it's a string
        const parsedData = typeof data === 'string' ? JSON.parse(data) : data;
        
        callbacks.forEach(callback => {
            try {
                callback(parsedData);
            } catch (error) {
                console.error('[WebSocket] Event callback error:', error);
            }
        });
    }

    /**
     * Send a message through the WebSocket
     */
    private send(data: object): void {
        if (this.ws?.readyState === WebSocket.OPEN) {
            this.ws.send(JSON.stringify(data));
        } else {
            console.warn('[WebSocket] Cannot send, not connected');
        }
    }

    /**
     * Subscribe to a public channel
     */
    subscribePublic(channelName: string): ChannelHandle {
        this.send({
            event: 'pusher:subscribe',
            data: { channel: channelName }
        });

        this.channels.set(channelName, {
            channelName,
            events: new Map()
        });

        return new ChannelHandle(this, channelName);
    }

    /**
     * Subscribe to a private channel (requires auth)
     */
    async subscribePrivate(channelName: string): Promise<ChannelHandle> {
        const fullChannelName = channelName.startsWith('private-') ? channelName : `private-${channelName}`;
        
        if (!this.socketId) {
            await this.connect();
        }

        // Authenticate with Laravel
        const auth = await this.authenticate(fullChannelName);
        
        this.send({
            event: 'pusher:subscribe',
            data: {
                channel: fullChannelName,
                auth: auth.auth
            }
        });

        this.channels.set(fullChannelName, {
            channelName: fullChannelName,
            events: new Map()
        });

        return new ChannelHandle(this, fullChannelName);
    }

    /**
     * Authenticate with Laravel's broadcasting auth endpoint
     */
    private async authenticate(channelName: string): Promise<{ auth: string }> {
        let token: string | null;
        
        if (Platform.OS === 'web') {
            token = localStorage.getItem('token');
        } else {
            token = await SecureStore.getItemAsync('token');
        }

        if (!token) {
            throw new Error('No auth token found');
        }

        const response = await fetch(AUTH_ENDPOINT, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({
                socket_id: this.socketId,
                channel_name: channelName
            })
        });

        if (!response.ok) {
            throw new Error(`Auth failed: ${response.status}`);
        }

        return response.json();
    }

    /**
     * Unsubscribe from a channel
     */
    unsubscribe(channelName: string): void {
        this.send({
            event: 'pusher:unsubscribe',
            data: { channel: channelName }
        });

        this.channels.delete(channelName);
    }

    /**
     * Add event listener to a channel
     */
    on(channelName: string, event: string, callback: EventCallback): void {
        const subscription = this.channels.get(channelName);
        if (!subscription) {
            console.warn('[WebSocket] Channel not subscribed:', channelName);
            return;
        }

        if (!subscription.events.has(event)) {
            subscription.events.set(event, []);
        }

        subscription.events.get(event)!.push(callback);
    }

    /**
     * Remove event listener from a channel
     */
    off(channelName: string, event: string, callback?: EventCallback): void {
        const subscription = this.channels.get(channelName);
        if (!subscription) return;

        if (callback) {
            const callbacks = subscription.events.get(event);
            if (callbacks) {
                const index = callbacks.indexOf(callback);
                if (index > -1) {
                    callbacks.splice(index, 1);
                }
            }
        } else {
            subscription.events.delete(event);
        }
    }

    /**
     * Register connection callback
     */
    onConnected(callback: ConnectionCallback): void {
        this.onConnectedCallbacks.push(callback);
    }

    /**
     * Register disconnection callback
     */
    onDisconnected(callback: ConnectionCallback): void {
        this.onDisconnectedCallbacks.push(callback);
    }

    /**
     * Disconnect from WebSocket
     */
    disconnect(): void {
        if (this.ws) {
            this.ws.close();
            this.ws = null;
        }
        this.socketId = null;
        this.channels.clear();
    }

    /**
     * Check if connected
     */
    isConnected(): boolean {
        return this.ws?.readyState === WebSocket.OPEN && !!this.socketId;
    }
}

/**
 * Handle for a subscribed channel
 */
class ChannelHandle {
    constructor(
        private client: ReverbWebSocket,
        private channelName: string
    ) {}

    /**
     * Listen for an event on this channel
     * Registers both 'EventName' and '.EventName' (Laravel broadcastAs format)
     */
    listen(event: string, callback: EventCallback): this {
        // Laravel's broadcastAs() sends events with a dot prefix
        this.client.on(this.channelName, event, callback);
        this.client.on(this.channelName, `.${event}`, callback);
        return this;
    }

    /**
     * Stop listening to an event
     */
    stopListening(event: string): this {
        this.client.off(this.channelName, event);
        return this;
    }

    /**
     * Unsubscribe from this channel
     */
    unsubscribe(): void {
        this.client.unsubscribe(this.channelName);
    }
}

// Export singleton instance
export const reverb = new ReverbWebSocket();
