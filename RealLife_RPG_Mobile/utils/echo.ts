import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import * as SecureStore from 'expo-secure-store';
import { Platform } from 'react-native';

// Explicitly define global Pusher for laravel-echo to find
// @ts-ignore
window.Pusher = Pusher;

const REVERB_APP_KEY = process.env.EXPO_PUBLIC_REVERB_APP_KEY;
const REVERB_HOST = process.env.EXPO_PUBLIC_REVERB_HOST;
const REVERB_PORT = process.env.EXPO_PUBLIC_REVERB_PORT || '443';
const REVERB_SCHEME = process.env.EXPO_PUBLIC_REVERB_SCHEME || 'https';

// Construct the base URL for auth. 
// EXPO_PUBLIC_API_URL e.g., "https://host/api/v1"
// We need "https://host/api/broadcasting/auth"
// So we strip "/v1" if present, or just use the host.
// But safer to replace "/api/v1" with "/api/broadcasting/auth" or just append to root if we can parse it.
const API_URL = process.env.EXPO_PUBLIC_API_URL || 'http://localhost:8000/api/v1';
const AUTH_ENDPOINT = API_URL.replace('/v1', '/broadcasting/auth'); 

const createEcho = () => {
    return new Echo({
        broadcaster: 'reverb',
        key: REVERB_APP_KEY,
        wsHost: REVERB_HOST,
        wsPort: Number(REVERB_PORT),
        wssPort: Number(REVERB_PORT),
        forceTLS: REVERB_SCHEME === 'https',
        enabledTransports: ['ws', 'wss'],
        authorizer: (channel: any, options: any) => {
            return {
                authorize: async (socketId: any, callback: any) => {
                    try {
                        let token;
                        if (Platform.OS === 'web') {
                            token = localStorage.getItem('token');
                        } else {
                            token = await SecureStore.getItemAsync('token');
                        }

                        if (!token) {
                            console.warn('[Echo] No token found for auth');
                            callback(true, 'No token');
                            return;
                        }

                        const response = await fetch(AUTH_ENDPOINT, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Authorization': `Bearer ${token}`
                            },
                            body: JSON.stringify({
                                socket_id: socketId,
                                channel_name: channel.name
                            })
                        });

                        if (!response.ok) {
                            throw new Error(`Auth failed: ${response.status}`);
                        }

                        const data = await response.json();
                        callback(false, data);
                    } catch (error) {
                        console.error('[Echo] Auth Error:', error);
                        callback(true, error);
                    }
                }
            };
        }
    });
};

export const echo = createEcho();
