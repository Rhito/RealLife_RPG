import { View, Text, FlatList, StyleSheet, RefreshControl } from 'react-native';
import { useEffect, useState } from 'react';
import { fetchAchievements, Achievement } from '../../services/achievements';
import { Ionicons } from '@expo/vector-icons';

export default function AchievementsScreen() {
  const [achievements, setAchievements] = useState<Achievement[]>([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  const loadAchievements = async () => {
    try {
      const data = await fetchAchievements();
      setAchievements(data.data); // Controller returns ['data' => $data]
    } catch (e) {
      console.log('Failed to fetch achievements', e);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  useEffect(() => {
    loadAchievements();
  }, []);

  const renderItem = ({ item }: { item: Achievement }) => (
    <View style={[styles.card, !item.is_unlocked && styles.lockedCard]}>
      <View style={[styles.iconContainer, !item.is_unlocked && styles.lockedIcon]}>
          <Ionicons 
            name={item.is_unlocked ? "trophy" : "lock-closed"} 
            size={30} 
            color={item.is_unlocked ? "#FFD700" : "#666"} 
          />
      </View>
      <View style={{ flex: 1, marginLeft: 10 }}>
        <Text style={[styles.name, !item.is_unlocked && styles.lockedText]}>
            {item.name}
        </Text>
        <Text style={[styles.condition, !item.is_unlocked && styles.lockedText]}>
            {item.description}
        </Text>
        <View style={styles.rewards}>
            {(item.reward_exp || 0) > 0 && <Text style={styles.rewardText}>+{item.reward_exp} EXP</Text>}
            {(item.reward_coins || 0) > 0 && <Text style={[styles.rewardText, { marginLeft: 10 }]}>+{item.reward_coins} Coins</Text>}
        </View>
        {item.is_unlocked && item.unlocked_at && (
             <Text style={styles.unlockedAt}>Unlocked: {new Date(item.unlocked_at).toLocaleDateString()}</Text>
        )}
      </View>
    </View>
  );

  return (
    <View style={styles.container}>
      <FlatList
        data={achievements}
        keyExtractor={(item) => item.id.toString()}
        renderItem={renderItem}
        refreshControl={
            <RefreshControl refreshing={refreshing} onRefresh={() => { setRefreshing(true); loadAchievements(); }} />
        }
        ListEmptyComponent={<Text style={styles.empty}>No achievements loaded</Text>}
      />
    </View>
  );
}

import { Platform } from 'react-native';

const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 10,
    backgroundColor: '#342056', // Update to Dark Theme while we are at it
  },
  card: {
    backgroundColor: '#432874', // Darker card background
    padding: 15,
    borderRadius: 10,
    marginBottom: 10,
    flexDirection: 'row',
    alignItems: 'center',
    // elevation: 2, // remove elevation for flat dark look or keep
    borderWidth: 1,
    borderColor: '#5B4290'
  },
  lockedCard: {
      backgroundColor: '#2D1B4E',
      opacity: 0.6,
      borderColor: '#333'
  },
  iconContainer: {
      width: 50,
      height: 50,
      backgroundColor: 'rgba(255, 215, 0, 0.1)', // Gold tint
      borderRadius: 25,
      justifyContent: 'center',
      alignItems: 'center',
  },
  lockedIcon: {
      backgroundColor: '#1E1E1E',
  },
  name: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#fff',
  },
  condition: {
      color: '#E0E7FF', // Lighter text (was #BBAADD)
      fontSize: 14,
      marginBottom: 5,
  },
  lockedText: {
      color: '#9CA3AF', // Lighter gray (was #666)
  },
  rewards: {
      flexDirection: 'row',
  },
  rewardText: {
      fontSize: 12,
      fontWeight: 'bold',
      color: '#FFD700', // Brighter Gold
  },
  unlockedAt: {
      fontSize: 10,
      color: '#4ADE80', // Brighter Green
      marginTop: 5,
  },
  empty: {
      textAlign: 'center',
      marginTop: 20,
      color: '#E0E7FF', // Lighter empty text
  }
});
