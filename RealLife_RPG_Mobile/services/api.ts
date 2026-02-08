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
  async (error) => {
    const { networkEvents } = require('../utils/networkEventEmitter');
    
    // Check for network error
    if (error.message === 'Network Error' || error.code === 'ERR_NETWORK' || !error.response) {
         networkEvents.emitStatus(false);
         // Reject with a special flag so AlertContext can ignore it
         error.isNetworkError = true; 
         return Promise.reject(error);
    }
    
    // Handle 401 Unauthorized (expired/invalid token)
    // Don't logout if this is an authentication attempt (login, register, password reset, etc.)
    const authEndpoints = ['/login', '/register', '/password', '/email/verify'];
    const isAuthEndpoint = authEndpoints.some(endpoint => error.config.url?.includes(endpoint));
    
    if (error.response?.status === 401 && !isAuthEndpoint) {
      console.log('[API] 401 Unauthorized - Session expired, logging out user');
      console.log('[API] Request URL:', error.config.url);
      
      // Clear auth data from storage
      try {
        if (Platform.OS === 'web') {
          localStorage.removeItem('token');
          localStorage.removeItem('user');
        } else {
          await SecureStore.deleteItemAsync('token');
          await SecureStore.deleteItemAsync('user');
        }
      } catch (e) {
        console.error('[API] Error clearing auth data:', e);
      }
      
      // Mark error as session expired so UI can handle it
      error.isSessionExpired = true;
      
      // Emit auth error event using our emitter
      const { authEvents } = require('../utils/networkEventEmitter');
      authEvents.emitSessionExpired(error);
    }
    
    return Promise.reject(error);
  }
);

export default api;
