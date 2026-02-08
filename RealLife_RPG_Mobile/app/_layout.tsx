import { Stack, useRouter, useSegments, useRootNavigationState } from 'expo-router';
import { StatusBar } from 'expo-status-bar';
import { AuthProvider, useAuth } from '../context/AuthContext';
import { useEffect } from 'react';
import { View, ActivityIndicator } from 'react-native';
import { syncPushToken } from '../services/notifications';
import * as SecureStore from 'expo-secure-store';
import { useAlert } from '../context/AlertContext';

const InitialLayout = () => {
  const { user, isLoading, setUser } = useAuth();
  const router = useRouter();
  const segments = useSegments();
  const rootNavigationState = useRootNavigationState();
  const { showAlert } = useAlert();

  // Handle session expiry from API interceptor
  useEffect(() => {
    const { authEvents } = require('../utils/networkEventEmitter');
    
    const handleSessionExpiry = (error: any) => {
      if (error?.isSessionExpired) {
        // Clear user state
        setUser(null);
        
        // Show alert to user
        showAlert(
          'Session Expired',
          'Your session has expired. Please log in again.',
          [
            {
              text: 'OK',
              onPress: () => router.replace('/login')
            }
          ]
        );
      }
    };

    // Listen for auth errors using our emitter
    const removeListener = authEvents.addListener(handleSessionExpiry);
    return removeListener;
  }, [showAlert, router, setUser]);

  useEffect(() => {
    if (isLoading || !rootNavigationState?.key) return;

    const currentRoute = segments[0] || '';
    const inTabsGroup = currentRoute === '(tabs)';

    // Priority 1: No user - redirect to auth screens
    if (!user) {
      const publicRoutes = ['login', 'register', 'index', 'forgot-password', 'reset-password'];
      if (!publicRoutes.includes(currentRoute)) {
        router.replace('/login');
      }
      return;
    }

    // Priority 2: Email verification required
    if (!user.email_verified_at) {
      if (currentRoute !== 'verify-email') {
        router.replace('/verify-email');
      }
      return;
    }

    // Priority 3: Onboarding required
    const isOnboarded = user.is_onboarded === true || 
                        user.is_onboarded === 1 || 
                        user.is_onboarded === '1' ||
                        user.is_onboarded === 'true';
    
    if (!isOnboarded) {
      if (currentRoute !== 'onboarding') {
        router.replace('/onboarding' as any);
      }
      return;
    }

    // Priority 4: Tutorial required (async check)
    const handleTutorialCheck = async () => {
      try {
        const hasSeen = await SecureStore.getItemAsync('HAS_SEEN_TUTORIAL');
        if (hasSeen !== 'true') {
          if (currentRoute !== 'tutorial') {
            router.replace('/tutorial');
          }
          return true; // Tutorial needed
        }
        return false; // Tutorial done
      } catch (e) {
        console.error('Tutorial check error:', e);
        return false;
      }
    };

    // Priority 5: Navigate authenticated users to tabs
    const publicRoutes = ['login', 'register', 'index', ''];
    if (publicRoutes.includes(currentRoute)) {
      handleTutorialCheck().then((tutorialNeeded) => {
        if (!tutorialNeeded && currentRoute !== '(tabs)') {
          router.replace('/(tabs)');
        }
      });
    } else if (currentRoute === 'onboarding') {
      // Onboarded users shouldn't be on onboarding screen
      router.replace('/(tabs)');
    }
  }, [user, isLoading, segments, rootNavigationState?.key]);

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

  return (
    <Stack screenOptions={{ headerShown: false }}>
      <Stack.Screen name="index" />
      <Stack.Screen name="login" />
      <Stack.Screen name="register" />
      <Stack.Screen name="forgot-password" />
      <Stack.Screen name="reset-password" />
      <Stack.Screen name="verify-email" />
      <Stack.Screen name="onboarding/index" options={{ headerShown: false, gestureEnabled: false }} />
      <Stack.Screen name="tutorial" options={{ headerShown: false, gestureEnabled: false }} />
      <Stack.Screen name="(tabs)" options={{ headerShown: false }} />
      <Stack.Screen name="focus/[id]" options={{ presentation: 'modal' }} />
    </Stack>
  );
};

import { AlertProvider } from '../context/AlertContext';
import { NetworkStatus } from '../components/NetworkStatus';
import { TourProvider } from '../context/TourContext';
import { TourOverlay } from '../components/TourOverlay';

export default function RootLayout() {
  return (
    <AuthProvider>
      <AlertProvider>
        <TourProvider>
            <StatusBar style="light" backgroundColor="#432874" />
            <InitialLayout />
            <TourOverlay />
            <NetworkStatus />
        </TourProvider>
      </AlertProvider>
    </AuthProvider>
  );
}

import * as NavigationBar from 'expo-navigation-bar';
import { Platform } from 'react-native';

if (Platform.OS === 'android') {
  NavigationBar.setBackgroundColorAsync('#432874');
}
