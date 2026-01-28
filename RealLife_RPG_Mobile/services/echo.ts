import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import * as SecureStore from 'expo-secure-store';

// Define the global window object for Pusher
declare global {
    interface Window {
        Pusher: any;
        Echo: any;
    }
}

window.Pusher = Pusher;

const createEcho = async () => {
    const token = await SecureStore.getItemAsync('token');

    if (!token) {
        console.warn('Echo: No token found, cannot authenticate channels.');
    }

    const host = process.env.EXPO_PUBLIC_REVERB_HOST;
    const port = process.env.EXPO_PUBLIC_REVERB_PORT;
    const scheme = process.env.EXPO_PUBLIC_REVERB_SCHEME || 'https';
    
    // Explicitly construct the wsHost to match the hostname
    // Reverb usually listens on the same host
    console.log(`Connecting to Reverb at ${scheme}://${host}:${port}`);

    return new Echo({
        broadcaster: 'reverb',
        key: process.env.EXPO_PUBLIC_REVERB_APP_KEY,
        wsHost: host,
        wsPort: port ? parseInt(port) : 443,
        wssPort: port ? parseInt(port) : 443,
        forceTLS: scheme === 'https',
        enabledTransports: ['ws', 'wss'],
        authEndpoint: `${process.env.EXPO_PUBLIC_API_URL}/broadcasting/auth`,
        auth: {
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: 'application/json',
            },
        },
        disableStats: true,
    });
};

export default createEcho;
