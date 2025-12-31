import React from 'react';
import { View, Text, StyleSheet } from 'react-native';
import { Ionicons } from '@expo/vector-icons';

interface StatBadgeProps {
  icon: keyof typeof Ionicons.glyphMap;
  value: string | number;
  label: string;
  color?: string;
  backgroundColor?: string;
}

export const StatBadge = ({ icon, value, label, color = '#333', backgroundColor = 'white' }: StatBadgeProps) => {
  return (
    <View style={[styles.container, { backgroundColor }]}>
      <View style={[styles.iconContainer, { backgroundColor: `${color}20` }]}>
        <Ionicons name={icon} size={20} color={color} />
      </View>
      <View style={styles.textContainer}>
        <Text style={[styles.value, { color }]}>{value}</Text>
        <Text style={styles.label}>{label}</Text>
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flexDirection: 'row',
    alignItems: 'center',
    padding: 10,
    borderRadius: 12,
    minWidth: 100,
    // Shadow for iOS
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 3,
    // Elevation for Android
    elevation: 3,
    marginRight: 10,
    marginBottom: 10,
  },
  iconContainer: {
    padding: 8,
    borderRadius: 8,
    marginRight: 10,
  },
  textContainer: {
    flexDirection: 'column',
  },
  value: {
    fontSize: 16,
    fontWeight: 'bold',
  },
  label: {
    fontSize: 12,
    color: '#666',
  },
});
