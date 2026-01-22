import { View, Text, StyleSheet, ScrollView, TouchableOpacity, Image, Dimensions } from 'react-native';
import { useAuth } from '../../context/AuthContext';
import { useCallback, useState, useEffect } from 'react';
import { fetchProfileStats, UserStats } from '../../services/profile';
import { fetchAnalytics, AnalyticsData } from '../../services/analytics';
import { Ionicons } from '@expo/vector-icons';
import { useRouter, useFocusEffect } from 'expo-router';
import { Avatar } from '../../components/Avatar';
import { Card } from '../../components/Card';
import { AnalyticsChart } from '../../components/AnalyticsChart';
import { StatBadge } from '../../components/StatBadge';
import { TourTarget } from '../../components/TourTarget';
import { useTour } from '../../context/TourContext';

const { width } = Dimensions.get('window');

export default function Dashboard() {
  const { user, logout } = useAuth();
  const router = useRouter();
  const { startTour } = useTour();
  const [analytics, setAnalytics] = useState<AnalyticsData | null>(null);
  const [stats, setStats] = useState<UserStats | null>(null);
  const [checkingTour, setCheckingTour] = useState(true);

  useFocusEffect(
    useCallback(() => {
        if (user?.id) {
            fetchAnalytics().then(res => setAnalytics(res)).catch(console.error);
            fetchProfileStats().then(setStats).catch(console.error);
        }
    }, [user?.id])
  );

  useEffect(() => {
    const checkTour = async () => {
        // Simple delay to ensure layout is ready
        setTimeout(async () => {
             // We can check if we should start it.
            // For now, let's just start it if it works in context? 
            // The Context should handle persistence? 
            // Actually context as written just exposes startTour.
            // We should check storage here.
            try {
                // Import SecureStore locally or use a helper? 
                // Context uses it, maybe context should expose a "checkAndStart"
                // But context is pure logic.
                
                // Let's rely on manual start for now or check here.
                const { getItemAsync } = require('expo-secure-store');
                const hasSeen = await getItemAsync('HAS_SEEN_INTERACTIVE_TOUR');
                if (!hasSeen) {
                    startTour();
                }
            } catch(e) {
                console.log(e);
            }
        }, 1000);
    };
    checkTour();
  }, []);



  return (
    <ScrollView style={styles.container} contentContainerStyle={{ paddingBottom: 100 }} showsVerticalScrollIndicator={false}>
      {/* Hero Header */}
      <View style={styles.headerBackground}>
          <View style={styles.headerContent}>
            {/* Header / Profile Summary */}
            <View style={styles.topRow}>
                <View>
                    <Text style={styles.greeting}>Welcome back,</Text>
                    {/* UserAvatar removed as per instruction, TourTarget moved */}
                </View>
                <TouchableOpacity onPress={() => router.push('/settings')} style={styles.settingsButton}>
                    <Ionicons name="settings-outline" size={24} color="#fff" />
                </TouchableOpacity>
            </View>

            <View style={styles.heroProfile}>
                <View style={styles.avatarContainer}>
                    <TourTarget name="avatar">
                        <Avatar 
                            name={user?.name}
                            image={user?.avatar}
                            size={80}
                            style={styles.avatar} 
                        />
                    </TourTarget>
                    <View style={styles.levelBadge}>
                        <Text style={styles.levelText}>{stats?.level ?? user?.level ?? 1}</Text>
                    </View>
                </View>

                <View style={styles.statsContainer}>
                    {/* HP Bar */}
                    <View style={styles.barContainer}>
                        <View style={styles.barLabelRow}>
                            <Text style={styles.barLabel}>HP</Text>
                            <Text style={styles.barValue}>{stats?.hp ?? user?.hp ?? 50}/{stats?.max_hp ?? 100}</Text>
                        </View>
                        <View style={styles.barBackground}>
                            <View style={[styles.hpBarFill, { width: `${(stats?.hp ?? user?.hp ?? 50) / (stats?.max_hp ?? 100) * 100}%` }]} />
                        </View>
                    </View>

                    {/* XP Bar */}
                    <View style={styles.barContainer}>
                        <View style={styles.barLabelRow}>
                            <Text style={styles.barLabel}>XP</Text>
                            <Text style={styles.barValue}>{stats?.exp ?? user?.exp ?? 0}/{stats?.next_level_exp ?? 1000}</Text>
                        </View>
                        <View style={styles.barBackground}>
                            <View style={[styles.xpBarFill, { width: `${((stats?.exp ?? user?.exp ?? 0) / (stats?.next_level_exp ?? 1000)) * 100}%` }]} />
                        </View>
                    </View>
                </View>
            </View>
          </View>
          
          {/* Decorative Bottom Curve */}
          <View style={styles.headerCurve} />
      </View>

      <View style={{ backgroundColor: 'rgba(245, 246, 250, 0.9)', borderTopLeftRadius: 30, borderTopRightRadius: 30, minHeight: 500, paddingBottom: 100 }}>
       {/* Quick Stats */}
       <View style={styles.statsRow}>
          <ScrollView horizontal showsHorizontalScrollIndicator={false} contentContainerStyle={{ paddingHorizontal: 20 }}>
              <StatBadge icon="wallet" value={analytics?.coins ?? user?.coins ?? 0} label="Coins" color="#FF9800" />
              <StatBadge icon="flame" value={analytics?.streak ?? user?.current_streak ?? 0} label="Day Streak" color="#F44336" />
              <StatBadge icon="star" value={`#${analytics?.rank ?? '-'}`} label="Rank" color="#9C27B0" />
          </ScrollView>
       </View>
 
       <View style={styles.mainContent}>
         {/* Analytics Chart */}
         {analytics && (
             <View style={styles.sectionContainer}>
                 <Text style={styles.sectionTitle}>Performance</Text>
                 <Card>
                     <AnalyticsChart data={analytics} />
                 </Card>
             </View>
         )}
 
         <TouchableOpacity style={styles.logoutButton} onPress={logout}>
             <Text style={styles.logoutText}>Log Out</Text>
         </TouchableOpacity>
       </View>
      </View>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: 'transparent', // Let ImageBackground show through
  },
  headerBackground: {
      backgroundColor: '#432874',
      paddingTop: 60, // Safe area filler roughly
      position: 'relative',
      paddingBottom: 40,
  },
  headerContent: {
      paddingHorizontal: 24,
  },
  topRow: {
      flexDirection: 'row',
      justifyContent: 'space-between',
      alignItems: 'flex-start',
      marginBottom: 24,
  },
  greeting: {
      fontSize: 14,
      color: '#BBAADD',
      marginBottom: 4,
  },
  username: {
      fontSize: 24,
      fontWeight: 'bold',
      color: '#fff',
  },
  settingsButton: {
      padding: 8,
      backgroundColor: 'rgba(255,255,255,0.1)',
      borderRadius: 12,
  },
  heroProfile: {
      flexDirection: 'row',
      alignItems: 'center',
  },
  avatarContainer: {
      position: 'relative',
      marginRight: 20,
  },
  avatar: {
      width: 80,
      height: 80,
      borderRadius: 40,
      borderWidth: 3,
      borderColor: '#FF9800',
  },
  levelBadge: {
      position: 'absolute',
      bottom: -5,
      right: -5,
      backgroundColor: '#FF9800',
      width: 30,
      height: 30,
      borderRadius: 15,
      justifyContent: 'center',
      alignItems: 'center',
      borderWidth: 2,
      borderColor: '#432874',
  },
  levelText: {
      color: '#fff',
      fontWeight: 'bold',
      fontSize: 14,
  },
  statsContainer: {
      flex: 1,
  },
  barContainer: {
      marginBottom: 12,
  },
  barLabelRow: {
      flexDirection: 'row',
      justifyContent: 'space-between',
      marginBottom: 4,
  },
  barLabel: {
      color: '#BBAADD',
      fontSize: 12,
      fontWeight: 'bold',
  },
  barValue: {
      color: '#fff',
      fontSize: 12,
  },
  barBackground: {
      height: 8,
      backgroundColor: 'rgba(0,0,0,0.3)',
      borderRadius: 4,
      overflow: 'hidden',
  },
  hpBarFill: {
      height: '100%',
      backgroundColor: '#F44336',
  },
  xpBarFill: {
      height: '100%',
      backgroundColor: '#2196F3',
  },
  headerCurve: {
      position: 'absolute',
      bottom: -20,
      left: 0,
      right: 0,
      height: 40,
      backgroundColor: '#432874', // Match header to hide seam
      borderTopLeftRadius: 30,
      borderTopRightRadius: 30,
      opacity: 0.95, 
  },
  statsRow: {
      marginTop: -25, // Overlap with header
      marginBottom: 20,
      zIndex: 10,
  },
  mainContent: {
      paddingHorizontal: 20,
  },
  sectionContainer: {
      marginBottom: 24,
  },
  sectionTitle: {
      fontSize: 18,
      fontWeight: 'bold',
      color: '#333',
      marginBottom: 12,
      marginLeft: 4,
  },
  feedItem: {
      backgroundColor: 'white',
      padding: 16,
      marginBottom: 12,
      borderRadius: 16,
      flexDirection: 'row',
      alignItems: 'center',
      shadowColor: '#000',
      shadowOffset: { width: 0, height: 2 },
      shadowOpacity: 0.05,
      shadowRadius: 5,
      elevation: 2,
  },
  feedIconContainer: {
      width: 40,
      height: 40,
      borderRadius: 20,
      justifyContent: 'center',
      alignItems: 'center',
      marginRight: 12,
  },
  feedContent: {
      flex: 1,
  },
  feedText: {
      fontSize: 14,
      fontWeight: '600',
      color: '#333',
      marginBottom: 2,
  },
  feedRewards: {
      fontSize: 12,
      color: '#FF9800', // Warning color usually pops
      fontWeight: 'bold',
      marginBottom: 2,
  },
  feedTime: {
      fontSize: 11,
      color: '#999',
  },
  emptyState: {
      alignItems: 'center',
      justifyContent: 'center',
      padding: 40,
      backgroundColor: 'white',
      borderRadius: 20,
      borderStyle: 'dashed',
      borderWidth: 2,
      borderColor: '#eee',
  },
  emptyText: {
      color: '#999',
      marginTop: 10,
  },
  logoutButton: {
      backgroundColor: '#fff',
      padding: 15,
      borderRadius: 12,
      alignItems: 'center',
      borderWidth: 1,
      borderColor: '#ffdddd',
  },
  logoutText: {
      color: 'red',
      fontWeight: 'bold',
  },
  dateGroup: {
      marginBottom: 16,
  },
  dateHeader: {
      fontSize: 14,
      fontWeight: 'bold',
      color: '#BBAADD',
      marginBottom: 8,
      marginLeft: 4,
      textTransform: 'uppercase',
      letterSpacing: 1,
  },
  highlightItem: {
      backgroundColor: '#FFF8E1', // Light Gold
      borderColor: '#FF9800',
      borderWidth: 1,
  },
  highlightText: {
      fontWeight: 'bold',
      fontSize: 15,
      color: '#E65100',
  },
  highlightSubtext: {
      fontSize: 12,
      color: '#FF9800',
      fontStyle: 'italic',
  }
});
