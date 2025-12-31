import { Slot, useRouter, useSegments } from 'expo-router';
import { StatusBar } from 'expo-status-bar';
import { AuthProvider, useAuth } from '../context/AuthContext';
import { useEffect } from 'react';
import { View, ActivityIndicator } from 'react-native';
import { syncPushToken } from '../services/notifications';

const InitialLayout = () => {
  const { user, isLoading } = useAuth();
  const router = useRouter();
  const segments = useSegments();

  useEffect(() => {
    if (isLoading) return;

    const inTabsGroup = segments[0] === '(tabs)';

    console.log('User state changed', { user: !!user, inTabsGroup });

    if (user && !inTabsGroup) {
      if (!user.email_verified_at && segments[0] !== 'verify-email') {
          router.replace('/verify-email');
      } else if (user.email_verified_at) {
          // Only redirect to tabs if we are on login, register, or root
          const ispublicRoute = ['login', 'register', 'index', ''].includes(segments[0] || '');
          if (ispublicRoute) {
               router.replace('/(tabs)');
          }
      }
    } else if (user && inTabsGroup && !user.email_verified_at) {
        // If they are inside tabs but unverified (e.g. state refresh), kick them out
        router.replace('/verify-email');
    } else if (!user && inTabsGroup) {
      router.replace('/login');
    }
  }, [user, isLoading, segments]);

  useEffect(() => {
      if (user) {
          syncPushToken();
      }
  }, [user]);

  if (isLoading) {
    return (
      <View style={{ flex: 1, justifyContent: 'center', alignItems: 'center' }}>
        <ActivityIndicator size="large" />
      </View>
    );
  }

  return <Slot />;
};

export default function RootLayout() {
  return (
    <AuthProvider>
      <StatusBar style="light" backgroundColor="#432874" />
      <InitialLayout />
    </AuthProvider>
  );
}

import * as NavigationBar from 'expo-navigation-bar';
import { Platform } from 'react-native';

if (Platform.OS === 'android') {
  NavigationBar.setBackgroundColorAsync('#432874');
}
