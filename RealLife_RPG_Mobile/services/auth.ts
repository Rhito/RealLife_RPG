import api from './api';
import * as SecureStore from 'expo-secure-store';

export const login = async (email: string, password: string) => {
  try {
    const response = await api.post('/login', { email, password });
    return response.data;
  } catch (error: any) {
    throw error.response?.data || error.message;
  }
};

export const register = async (name: string, email: string, password: string, password_confirmation: string) => {
  try {
    const response = await api.post('/register', { name, email, password, password_confirmation });
    return response.data;
  } catch (error: any) {
    throw error.response?.data || error.message;
  }
};

export const logout = async () => {
    try {
        await api.get('/logout');
    } catch (e) {
        // ignore
    } finally {
        await SecureStore.deleteItemAsync('token');
        await SecureStore.deleteItemAsync('user');
    }
}

export const checkEmailVerificationStatus = async () => {
  try {
    const response = await api.get('/email/verify-status');
    return response.data;
  } catch (error: any) {
    throw error.response?.data || error.message;
  }
};

export const resendVerificationEmail = async () => {
  try {
    const response = await api.post('/email/verify-notification');
    return response.data;
  } catch (error: any) {
    throw error.response?.data || error.message;
  }
};
