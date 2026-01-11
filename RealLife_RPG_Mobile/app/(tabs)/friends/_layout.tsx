import { Stack } from 'expo-router';

export default function FriendsLayout() {
  return (
    <Stack screenOptions={{
      headerStyle: {
        backgroundColor: '#432874',
      },
      headerTintColor: '#fff',
      headerTitleStyle: {
        fontWeight: 'bold',
      },
      headerShadowVisible: false, // Remove shadow/elevation
      contentStyle: { backgroundColor: '#342056' } // Match background
    }}>
      <Stack.Screen 
        name="index" 
        options={{ 
          title: 'Guild',
          headerShown: false // Hide header for the main friends list if the Tab Layout already shows it?
          // Actually, Tab Layout DOES show header. 
          // If we are inside a Stack, the Stack might double the header or hide the Tab header.
          // Usually, when using a Stack inside a Tab, we want to hide the Tab Header for this tab 
          // and let the Stack manage headers.
          // BUT, to keep it simple, let's see. 
          // If Tab Layout has `headerShown: true` for "friends", it shows "Guild".
          // If we navigate deeper, the Tab Header stays. 
          // To have "Back Button" and "Name Title", the Chat screen MUST have a header.
          // This usually means the STACK should handle headers.
          // SO: In (tabs)/_layout.tsx, we should set `headerShown: false` for "friends" tab 
          // and let this Stack handle it.
        }} 
      />
      <Stack.Screen 
        name="chat/[id]" 
        options={{ 
          title: 'Chat',
          headerBackTitle: '', // Hide back title on iOS
        }} 
      />
    </Stack>
  );
}
