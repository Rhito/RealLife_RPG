import React, { createContext, useContext, useState, useEffect } from 'react';
import * as SecureStore from 'expo-secure-store';
import { login as loginService, logout as logoutService } from '../services/auth';
import { Platform } from 'react-native';

interface AuthContextType {
  user: any;
  isLoading: boolean;
  login: (email: string, password: string) => Promise<void>;
  register: (name: string, email: string, password: string, passwordConfirmation: string) => Promise<void>;
  logout: () => Promise<void>;
  setUser: (user: any) => void;
}

const AuthContext = createContext<AuthContextType>({} as AuthContextType);

export const useAuth = () => useContext(AuthContext);

// Helper to abstract storage
const saveItem = async (key: string, value: string) => {
    if (Platform.OS === 'web') {
        localStorage.setItem(key, value);
    } else {
        await SecureStore.setItemAsync(key, value);
    }
};

const getItem = async (key: string) => {
    if (Platform.OS === 'web') {
        return localStorage.getItem(key);
    } else {
        return await SecureStore.getItemAsync(key);
    }
};

const deleteItem = async (key: string) => {
    if (Platform.OS === 'web') {
        localStorage.removeItem(key);
    } else {
        await SecureStore.deleteItemAsync(key);
    }
};

export const AuthProvider = ({ children }: { children: React.ReactNode }) => {
  const [user, setUser] = useState<any>(null);
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    const loadUser = async () => {
      try {
        const storedUser = await getItem('user');
        const token = await getItem('token');
        if (storedUser && token) {
          setUser(JSON.parse(storedUser));
        }
      } catch (e) {
        console.log('Failed to load user', e);
      } finally {
        setIsLoading(false);
      }
    };
    loadUser();
  }, []);

  const login = async (email: string, password: string) => {
    try {
      const response = await loginService(email, password);
      // Backend returns { success: true, data: { user: ..., token: ... } }
        
        console.log('Login response:', response);
        
        if (response.success && response.data) {
             const { token, user } = response.data;
             setUser(user);
             await saveItem('token', token);
             await saveItem('user', JSON.stringify(user));
        }
    } catch (error) {
      throw error;
    }
  };

  const register = async (name: string, email: string, password: string, passwordConfirmation: string) => {
    try {
      const response = await (await import('../services/auth')).register(name, email, password, passwordConfirmation);
      
      console.log('Register response:', response);
      
      if (response.success && response.data) {
        const { token, user } = response.data;
        setUser(user);
        await saveItem('token', token);
        await saveItem('user', JSON.stringify(user));
      }
    } catch (error) {
      throw error;
    }
  };

  const logout = async () => {
    await logoutService();
    setUser(null);
    await deleteItem('token');
    await deleteItem('user');
  };

  return (
    <AuthContext.Provider value={{ user, isLoading, login, register, logout, setUser }}>
      {children}
    </AuthContext.Provider>
  );
};
