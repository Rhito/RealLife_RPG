import { Stack, useRouter, useSegments, useRootNavigationState } from 'expo-router';
import { StatusBar } from 'expo-status-bar';
import { AuthProvider, useAuth } from '../context/AuthContext';
import { useEffect } from 'react';
import { View, ActivityIndicator } from 'react-native';
import { syncPushToken } from '../services/notifications';
import * as SecureStore from 'expo-secure-store';

const InitialLayout = () => {
  const { user, isLoading } = useAuth();
  const router = useRouter();
  const segments = useSegments();
  const rootNavigationState = useRootNavigationState();

  useEffect(() => {
    if (isLoading || !rootNavigationState?.key) return;

    const checkTutorial = async () => {
        try {
             // Only check for logged in users
             if (user) {
                 const hasSeen = await SecureStore.getItemAsync('HAS_SEEN_TUTORIAL');
                 if (hasSeen !== 'true') {
                     // Check if we are already there to avoid loop (though replace works fine)
                     if (segments[0] !== 'tutorial') {
                         router.replace('/tutorial');
                     }
                     return; 
                 }
             }
        } catch (e) {
            console.error(e);
        }
    };

    const inTabsGroup = segments[0] === '(tabs)';
    console.log('User state changed', { user: !!user, inTabsGroup, segment: segments[0] });

    if (user && !inTabsGroup) {
      if (!user.email_verified_at && segments[0] !== 'verify-email') {
          router.replace('/verify-email');
      } else if (user.email_verified_at) {
          // Check tutorial ONLY if we are heading to tabs/index from a Public route
          // OR if we are explicitly checking on mount.
          // BUT, we need to allow 'tutorial' to be a valid route.
          
          if (segments[0] === 'tutorial') return; // Stay on tutorial

          const ispublicRoute = ['login', 'register', 'index', ''].includes(segments[0] || '');
          if (ispublicRoute) {
               checkTutorial().then(() => {
                   // If checkTutorial didn't redirect, we go to tabs
                   // Wait, checkTutorial is async. We might have a race condition.
                   // Let's rely on the checkTutorial inside the effect for "first load" logic.
                   // But here we are redirecting to tabs immediately.
                   
                   // Better logic: Always go to tabs, let tabs/index check? 
                   // No, global layout is better.
                   
                   // Let's do:
                   SecureStore.getItemAsync('HAS_SEEN_TUTORIAL').then(hasSeen => {
                       if (hasSeen !== 'true') {
                           router.replace('/tutorial');
                       } else {
                           router.replace('/(tabs)');
                       }
                   });
               });
          }
      }
    } else if (!user && !['login', 'register', 'index', 'forgot-password', 'reset-password'].includes(segments[0] || '')) {
         router.replace('/login');
    } else if (user && inTabsGroup && !user.email_verified_at) {
        router.replace('/verify-email');
    } else if (!user && inTabsGroup) {
      router.replace('/login');
    }
  }, [user, isLoading, segments, rootNavigationState?.key]);

  useEffect(() => {
      if (user) {
          syncPushToken();
      }
  }, [user]);

  // Handle Onboarding Redirect
  useEffect(() => {
    if (user && !isLoading) {
        // Check if user is NOT onboarded (handles 0, false, null, undefined)
        if (!user.is_onboarded) { 
             // Check if we are already in onboarding to avoid loop
             if (segments[0] !== 'onboarding') {
                 router.replace('/onboarding' as any);
             }
        }
    }
  }, [user, isLoading, segments]);

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
