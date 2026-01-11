import React from 'react';
import { View, Text, Image, StyleSheet, StyleProp, ViewStyle } from 'react-native';

interface AvatarProps {
  name?: string;
  image?: string | null;
  size?: number;
  style?: StyleProp<ViewStyle>;
}

export const Avatar: React.FC<AvatarProps> = ({ name, image, size = 40, style }) => {
  const getInitials = (name?: string) => {
    if (!name) return '?';
    const parts = name.trim().split(' ');
    if (parts.length === 1) return parts[0].substring(0, 2).toUpperCase();
    return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
  };

  const getBackgroundColor = (name?: string) => {
    if (!name) return '#6C5CE7'; // Default Indigo
    const colors = [
      '#FF9800', // Orange
      '#F44336', // Red
      '#4CAF50', // Green
      '#2196F3', // Blue
      '#9C27B0', // Purple
      '#E91E63', // Pink
      '#00BCD4', // Cyan
      '#6C5CE7', // Indigo
    ];
    let hash = 0;
    for (let i = 0; i < name.length; i++) {
        hash = name.charCodeAt(i) + ((hash << 5) - hash);
    }
    return colors[Math.abs(hash) % colors.length];
  };

  if (image) {
    return (
      <Image
        source={{ uri: image }}
        style={[
          styles.image,
          { width: size, height: size, borderRadius: size / 2 },
          style,
        ]}
      />
    );
  }

  return (
    <View
      style={[
        styles.container,
        {
          width: size,
          height: size,
          borderRadius: size / 2,
          backgroundColor: getBackgroundColor(name),
        },
        style,
      ]}
    >
      <Text style={[styles.text, { fontSize: size * 0.4 }]}>
        {getInitials(name)}
      </Text>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    justifyContent: 'center',
    alignItems: 'center',
  },
  image: {
    resizeMode: 'cover',
  },
  text: {
    color: '#FFFFFF',
    fontWeight: 'bold',
  },
});
