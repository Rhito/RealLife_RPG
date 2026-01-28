import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Define global Pusher if not already defined (required for React Native sometimes)
// @ts-ignore
window.Pusher = Pusher;

const createEcho = (token: string) => {
    return new Echo({
        broadcaster: 'reverb',
        key: process.env.EXPO_PUBLIC_REVERB_APP_KEY,
        wsHost: process.env.EXPO_PUBLIC_REVERB_HOST,
        wsPort: process.env.EXPO_PUBLIC_REVERB_PORT ? Number(process.env.EXPO_PUBLIC_REVERB_PORT) : 80,
        wssPort: process.env.EXPO_PUBLIC_REVERB_PORT ? Number(process.env.EXPO_PUBLIC_REVERB_PORT) : 443,
        forceTLS: (process.env.EXPO_PUBLIC_REVERB_SCHEME ?? 'https') === 'https',
        enabledTransports: ['ws', 'wss'],
        authEndpoint: `${process.env.EXPO_PUBLIC_API_URL}/broadcasting/auth`,
        auth: {
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: 'application/json',
            },
        },
    });
};

export default createEcho;
