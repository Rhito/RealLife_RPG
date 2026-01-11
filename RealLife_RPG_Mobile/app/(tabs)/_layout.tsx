import { Tabs } from 'expo-router';
import { Ionicons } from '@expo/vector-icons';
import { Platform } from 'react-native';
import { useTranslation } from 'react-i18next';

export default function TabLayout() {
  const { t } = useTranslation();

  return (
    <Tabs screenOptions={{ 
        tabBarActiveTintColor: '#FF9800', // Gold for active
        tabBarInactiveTintColor: '#BBAADD',
        tabBarStyle: {
            backgroundColor: '#432874', // Deep Purple
            borderTopWidth: 0,
            elevation: 0,
            paddingBottom: 8, // Little padding for content
            paddingTop: 8,
            // height: removed to let Safe Area Insets determine height automatically
            height: Platform.OS === 'android' ? 70 : undefined, // Android needs height usually, iOS handles safe area auto?
            // Actually, best to remove height entirely and let flex work, OR standard height + safe area.
            // React Navigation 6/7 default is usually good.
            // But if I want custom look, I often need minHeight.
            // Let's try removing height entirely for iOS, maybe nice height for Android.
            minHeight: 60,
        },
        headerStyle: {
            backgroundColor: '#432874',
            elevation: 0, // Android shadow remove
            shadowOpacity: 0, // iOS shadow remove
        },
        headerTintColor: '#fff',
        headerTitleStyle: {
            fontWeight: 'bold',
        }
    }}>
      <Tabs.Screen
        name="index"
        options={{
          headerShown: false,
          title: t('tabs.hero'), // More RPG-like than Dashboard
          tabBarIcon: ({ color }) => <Ionicons name="shield" size={24} color={color} />,
        }}
      />
      <Tabs.Screen
        name="tasks"
        options={{
          headerShown: false, // Tasks screen has its own custom header/tabs
          title: t('tabs.quests'),
          tabBarIcon: ({ color }) => <Ionicons name="document-text" size={24} color={color} />,
        }}
      />
      <Tabs.Screen
        name="shop"
        options={{
          headerShown: false,
          title: t('tabs.market'),
          tabBarIcon: ({ color }) => <Ionicons name="basket" size={24} color={color} />,
        }}
      />
      {/* Hidden tabs or secondary tabs can go to "More" or keep them if space permits. 
          Standard bottom bar has 3-5 items. We have 6. That's crowded. 
          Let's consolidate. 
          We have: Hero, Quests, Market. 
          Achievements/Inventory/Friends -> Maybe "Profile" or "Menu"?
          For now, I'll keep them but use standard icons.
      */}
      <Tabs.Screen
        name="adventure-log"
        options={{
          title: t('tabs.codex'),
          headerShown: false,
          tabBarIcon: ({ color }) => <Ionicons name="journal" size={24} color={color} />,
        }}
      />
      {/* <Tabs.Screen
        name="inventory"
        options={{
          headerShown: false,
          title: 'Bag',
          tabBarIcon: ({ color }) => <Ionicons name="cube" size={24} color={color} />,
        }}
      /> */}
      <Tabs.Screen
        name="achievements"
        options={{
          title: t('tabs.awards'),
          tabBarIcon: ({ color }) => <Ionicons name="trophy" size={24} color={color} />,
        }}
      />
       <Tabs.Screen
        name="friends"
        options={{
          title: t('tabs.guild'), // Social -> Guild
          headerShown: false, // Let Stack handle header
          tabBarIcon: ({ color }) => <Ionicons name="people" size={24} color={color} />,
        }}
      />
    </Tabs>
  );
}
