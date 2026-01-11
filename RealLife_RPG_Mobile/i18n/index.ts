import i18n from 'i18next';
import { initReactI18next } from 'react-i18next';
import * as SecureStore from 'expo-secure-store';
import * as Localization from 'expo-localization';

import en from './locales/en.json';
import vi from './locales/vi.json';

const RESOURCES = {
  en: { translation: en },
  vi: { translation: vi },
};

const LANGUAGE_DETECTOR: any = {
  type: 'languageDetector',
  async: true,
  detect: async (callback: (lang: string) => void) => {
    try {
      const savedLanguage = await SecureStore.getItemAsync('user-language');
      if (savedLanguage) {
        return callback(savedLanguage);
      }
    } catch (error) {
      console.log('Error reading language', error);
    }
    
    // Fallback to device locale
    const locales = Localization.getLocales();
    const deviceLanguage = locales[0]?.languageCode ?? 'en';
    callback(deviceLanguage);
  },
  init: () => {},
  cacheUserLanguage: async (language: string) => {
    try {
      await SecureStore.setItemAsync('user-language', language);
    } catch (error) {
      console.log('Error saving language', error);
    }
  },
};

i18n
  .use(LANGUAGE_DETECTOR)
  .use(initReactI18next)
  .init({
    resources: RESOURCES,
    fallbackLng: 'en',
    compatibilityJSON: 'v4',
    interpolation: {
      escapeValue: false,
    },
    react: {
        useSuspense: false, 
    }
  });

export default i18n;
