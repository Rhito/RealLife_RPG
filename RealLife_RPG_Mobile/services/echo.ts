// Polyfill for Pusher Worker Build in React Native
if (!global.crypto) {
  // @ts-ignore
  global.crypto = {
    getRandomValues: (arr: any) => {
      for (let i = 0; i < arr.length; i++) {
        arr[i] = Math.floor(Math.random() * 256);
      }
      return arr;
    },
  };
}

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// @ts-ignore
window.Pusher = Pusher;

// Defaults provided by Laravel Reverb
const REVERB_APP_KEY = process.env.EXPO_PUBLIC_REVERB_APP_KEY || 'my-app-key';
const REVERB_HOST = process.env.EXPO_PUBLIC_REVERB_HOST || 'localhost';
const REVERB_PORT = process.env.EXPO_PUBLIC_REVERB_PORT || '8080';
const REVERB_SCHEME = process.env.EXPO_PUBLIC_REVERB_SCHEME || 'http';

const echo = new Echo({
    broadcaster: 'reverb',
    key: REVERB_APP_KEY,
    wsHost: REVERB_HOST,
    wsPort: REVERB_PORT ? parseInt(REVERB_PORT) : 80,
    wssPort: REVERB_PORT ? parseInt(REVERB_PORT) : 443,
    forceTLS: (REVERB_SCHEME === 'https'),
    enabledTransports: ['ws', 'wss'],
});

export default echo;
