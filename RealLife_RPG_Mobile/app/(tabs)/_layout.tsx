import { Tabs } from 'expo-router';
import { Ionicons } from '@expo/vector-icons';
import { Platform, ImageBackground, StyleSheet } from 'react-native';
import { TourTarget } from '../../components/TourTarget';

export default function TabLayout() {
  return (
    <ImageBackground 
        source={require('../../assets/images/rpg-background.png')} 
        style={styles.background}
        resizeMode="cover"
    >
        <Tabs 
            screenOptions={{ 
            tabBarActiveTintColor: '#FF9800', // Gold for active
            tabBarInactiveTintColor: '#BBAADD',
            tabBarStyle: {
                backgroundColor: 'rgba(67, 40, 116, 0.95)', // Deep Purple with slight transparency
                borderTopWidth: 0,
                elevation: 0,
                paddingBottom: 8, // Little padding for content
                paddingTop: 8,
                height: Platform.OS === 'android' ? 70 : undefined, 
                minHeight: 60,
            },
            headerStyle: {
                backgroundColor: 'rgba(67, 40, 116, 0.95)',
                elevation: 0, 
                shadowOpacity: 0, 
            },
            headerTintColor: '#fff',
            headerTitleStyle: {
                fontWeight: 'bold',
            },
        }}>
        <Tabs.Screen
            name="index"
            options={{
            headerShown: false,
            title: 'Hero', // More RPG-like than Dashboard
            tabBarIcon: ({ color }) => <Ionicons name="shield" size={24} color={color} />,
            }}
        />
        <Tabs.Screen
            name="tasks"
            options={{
            headerShown: false, // Tasks screen has its own custom header/tabs
            title: 'Quests',
            tabBarIcon: ({ color }) => (
                <TourTarget name="tab-tasks">
                    <Ionicons name="document-text" size={24} color={color} />
                </TourTarget>
            ),
            }}
        />
        <Tabs.Screen
            name="shop"
            options={{
            headerShown: false,
            title: 'Market',
            tabBarIcon: ({ color }) => <Ionicons name="basket" size={24} color={color} />,
            }}
        />
        <Tabs.Screen
            name="adventure-log"
            options={{
            title: 'Codex',
            headerShown: false,
            tabBarIcon: ({ color }) => <Ionicons name="journal" size={24} color={color} />,
            }}
        />
        <Tabs.Screen
            name="achievements"
            options={{
            title: 'Awards',
            tabBarIcon: ({ color }) => <Ionicons name="trophy" size={24} color={color} />,
            }}
        />
        <Tabs.Screen
            name="friends"
            options={{
            title: 'Guild', // Social -> Guild
            headerShown: false, // Let Stack handle header
            tabBarIcon: ({ color }) => <Ionicons name="people" size={24} color={color} />,
            }}
        />
        </Tabs>
    </ImageBackground>
  );
}

const styles = StyleSheet.create({
    background: {
        flex: 1,
        backgroundColor: '#342056', // Fallback
    }
});
