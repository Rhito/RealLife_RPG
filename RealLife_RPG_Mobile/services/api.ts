import axios from 'axios';
import * as SecureStore from 'expo-secure-store';

// Use environment variable for API base URL
// Fallback to local development URL if not set
const BASE_URL = process.env.EXPO_PUBLIC_API_URL || 'http://10.0.2.2:8000/api/v1'; 

console.log('[API Config] Base URL:', BASE_URL);
console.log('[API Config] EXPO_PUBLIC_API_URL:', process.env.EXPO_PUBLIC_API_URL); 

const api = axios.create({
  baseURL: BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

import { Platform } from 'react-native';

api.interceptors.request.use(async (config) => {
  let token;
  if (Platform.OS === 'web') {
      token = localStorage.getItem('token');
  } else {
      token = await SecureStore.getItemAsync('token');
  }
  
  console.log(`[API] Requesting: ${config.baseURL}${config.url}`);
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

api.interceptors.response.use(
  (response) => {
      // If we had a previous error, maybe we are back online?
      // simple naive check: every success means online.
      // To avoid spamming, the emitter could handle checking if value changed, but component does state diff too.
      // Importing locally to avoid cyclic deps if any (though utils is safe)
      const { networkEvents } = require('../utils/networkEventEmitter');
      networkEvents.emitStatus(true);
      return response;
  },
  (error) => {
    const { networkEvents } = require('../utils/networkEventEmitter');
    
    // Check for network error
    if (error.message === 'Network Error' || error.code === 'ERR_NETWORK' || !error.response) {
         networkEvents.emitStatus(false);
         // Reject with a special flag so AlertContext can ignore it
         error.isNetworkError = true; 
    }
    
    return Promise.reject(error);
  }
);

export const getToken = async () => {
    if (Platform.OS === 'web') {
        return localStorage.getItem('token');
    }
    return await SecureStore.getItemAsync('token');
};

export default api;
