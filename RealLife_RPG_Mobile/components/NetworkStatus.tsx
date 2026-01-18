import React, { useEffect, useState, useRef } from 'react';
import { View, Text, StyleSheet, Animated, SafeAreaView } from 'react-native';
import { networkEvents } from '../utils/networkEventEmitter';
import { Ionicons } from '@expo/vector-icons';

export const NetworkStatus = () => {
  const [isConnected, setIsConnected] = useState(true);
  const slideAnim = useRef(new Animated.Value(-100)).current; 

  useEffect(() => {
    const unsubscribe = networkEvents.addListener((status) => {
      setIsConnected(status);
    });
    return unsubscribe;
  }, []);

  useEffect(() => {
    if (!isConnected) {
      // Slide down
      Animated.timing(slideAnim, {
        toValue: 0,
        duration: 300,
        useNativeDriver: true,
      }).start();
    } else {
      // Slide up (hide)
      Animated.timing(slideAnim, {
        toValue: -100,
        duration: 300,
        useNativeDriver: true,
      }).start();
    }
  }, [isConnected]);

  // If connected and animation hidden (optimization), we can render null? 
  // But animation needs to exist. keeping it rendered but off-screen.

  return (
    <Animated.View style={[styles.container, { transform: [{ translateY: slideAnim }] }]}>
      <SafeAreaView>
        <View style={styles.content}>
            <Ionicons name="cloud-offline" size={20} color="white" style={{ marginRight: 8 }} />
            <Text style={styles.text}>Connection lost. Reconnecting...</Text>
        </View>
      </SafeAreaView>
    </Animated.View>
  );
};

const styles = StyleSheet.create({
  container: {
    position: 'absolute',
    top: 0,
    left: 0,
    right: 0,
    backgroundColor: '#FF5252', // Red/Orange warning
    zIndex: 9999,
    elevation: 10,
  },
  content: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    paddingVertical: 10,
  },
  text: {
    color: 'white',
    fontSize: 14,
    fontWeight: '600',
  },
});
