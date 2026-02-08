import * as Device from 'expo-device';
import * as Notifications from 'expo-notifications';
import Constants from 'expo-constants';
import { Platform } from 'react-native';
import api from './api';

// Configure how notifications behave when the app is foregrounded
Notifications.setNotificationHandler({
  handleNotification: async () => ({
    shouldShowAlert: true,
    shouldPlaySound: true,
    shouldSetBadge: false,
    shouldShowBanner: true,
    shouldShowList: false,
  }),
});

export async function registerForPushNotificationsAsync() {
  let token;

  // Check if running in Expo Go - notifications don't work in Expo Go (SDK 53+)
  if (Constants.appOwnership === 'expo') {
    console.log('⚠️ Push notifications are not supported in Expo Go. Use a development build instead.');
    console.log('Learn more: https://docs.expo.dev/develop/development-builds/introduction/');
    return;
  }

  if (Platform.OS === 'android') {
    await Notifications.setNotificationChannelAsync('default', {
      name: 'default',
      importance: Notifications.AndroidImportance.MAX,
      vibrationPattern: [0, 250, 250, 250],
      lightColor: '#FF231F7C',
    });
  }

  if (Device.isDevice) {
    const { status: existingStatus } = await Notifications.getPermissionsAsync();
    let finalStatus = existingStatus;
    if (existingStatus !== 'granted') {
      const { status } = await Notifications.requestPermissionsAsync();
      finalStatus = status;
    }
    if (finalStatus !== 'granted') {
      console.log('Permission not granted for push notifications');
      return;
    }
    
    // Get the token
    try {
        const projectId = Constants?.expoConfig?.extra?.eas?.projectId ?? Constants?.easConfig?.projectId;
        if (!projectId) {
            console.log('Project ID not found. Notifications will not work until you run "eas init".');
            return;
        }
        token = (await Notifications.getExpoPushTokenAsync({ projectId })).data;
        
        console.log('Expo Push Token:', token);
    } catch (e) {
        console.log("Error getting push token.", e);
        return;
    }

  } else {
    console.log('Must use physical device for Push Notifications');
  }

  return token;
}

export const syncPushToken = async () => {
    const token = await registerForPushNotificationsAsync();
    if (token) {
        try {
            await api.post('/push/subscribe', {
                endpoint: token,
                device_name: Device.modelName || 'Unknown Device',
            });
            console.log('Push token synced with backend');
        } catch (error) {
            console.error('Failed to sync push token with backend', error);
        }
    }
};
