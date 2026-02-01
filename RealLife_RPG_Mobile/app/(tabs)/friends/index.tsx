import { View, Text, FlatList, StyleSheet, TouchableOpacity, TextInput, Alert,  RefreshControl, Image } from 'react-native';
import { useEffect, useState } from 'react';
import { fetchFriends, fetchRequests, searchUsers, sendRequest, acceptRequest, removeFriend, User, Friendship } from '../../../services/friends';
import { fetchGlobalLeaderboard, fetchFriendsLeaderboard } from '../../../services/leaderboard';
import { Ionicons } from '@expo/vector-icons';
import { useAuth } from '../../../context/AuthContext';
import { useRouter } from 'expo-router';
import { useAlert } from '../../../context/AlertContext';
import { Avatar } from '../../../components/Avatar';

type Tab = 'friends' | 'requests' | 'search' | 'ranking';
type RankingType = 'global' | 'friends';

export default function SocialScreen() {
    const { user: currentUser } = useAuth();
    const router = useRouter();
    const { showAlert } = useAlert();
    const [tab, setTab] = useState<Tab>('friends');
    const [rankingType, setRankingType] = useState<RankingType>('global');
    
    // Friends Data
    const [friends, setFriends] = useState<User[]>([]);
    const [requests, setRequests] = useState<Friendship[]>([]);
    const [searchResults, setSearchResults] = useState<User[]>([]);
    const [searchQuery, setSearchQuery] = useState('');
    
    // Leaderboard Data
    const [rankings, setRankings] = useState<User[]>([]);
    
    const [refreshing, setRefreshing] = useState(false);

    const loadData = async () => {
        try {
            if (tab === 'friends') {
                const res = await fetchFriends();
                setFriends(res.data);
            } else if (tab === 'requests') {
                const res = await fetchRequests();
                setRequests(res.data);
            } else if (tab === 'ranking') {
                if (rankingType === 'global') {
                    const res = await fetchGlobalLeaderboard();
                    setRankings(res.data);
                } else {
                    const res = await fetchFriendsLeaderboard();
                    setRankings(res.data);
                }
            }
        } catch (e) {
            console.log(e);
        } finally {
            setRefreshing(false);
        }
    };

    useEffect(() => {
        loadData();
    }, [tab, rankingType]);

    const handleSearch = async () => {
        if (!searchQuery) return;
        try {
            const res = await searchUsers(searchQuery);
            setSearchResults(res.data);
        } catch (e) {
            console.log(e);
        }
    }

    const handleAdd = async (id: number) => {
        try {
            await sendRequest(id);
            showAlert('Success', 'Friend request sent!');
        } catch (e: any) {
            showAlert('Error', e.message);
        }
    }

    const handleAccept = async (id: number) => {
        try {
            await acceptRequest(id);
            showAlert('Success', 'Friend added!');
            loadData();
        } catch (e: any) {
            showAlert('Error', e.message);
        }
    }

    const renderFriend = ({ item }: { item: User }) => (
        <View style={styles.card}>
            <TouchableOpacity style={{ flexDirection: 'row', alignItems: 'center', flex: 1 }} onPress={() => router.push({ pathname: '/users/[id]', params: { id: item.id } })}>
                <Avatar name={item.name} size={40} />
                <View style={{ flex: 1, marginLeft: 10 }}>
                    <Text style={styles.name}>{item.name}</Text>
                    <Text style={styles.level}>Lvl {item.level ?? 1}</Text>
                </View>
            </TouchableOpacity>
            <TouchableOpacity style={styles.iconButton} onPress={() => router.push({ pathname: '/(tabs)/friends/chat/[id]', params: { id: item.id, name: item.name } })}>
                <Ionicons name="chatbubble-ellipses" size={24} color="#FF9800" />
                {(item.unread_count || 0) > 0 && (
                    <View style={styles.unreadBadge}>
                        <Text style={styles.unreadText}>{item.unread_count}</Text>
                    </View>
                )}
            </TouchableOpacity>
        </View>
    );

    const renderRequest = ({ item }: { item: Friendship }) => (
        <View style={styles.card}>
             <Avatar name={item.user?.name} size={40} />
             <View style={{ flex: 1, marginLeft: 10 }}>
                 <Text style={styles.name}>{item.user?.name}</Text>
                 <Text style={styles.level}>Sent you a request</Text>
             </View>
             <TouchableOpacity style={styles.addButton} onPress={() => handleAccept(item.id)}>
                 <Text style={styles.addButtonText}>Accept</Text>
             </TouchableOpacity>
        </View>
    );

    const renderSearchUser = ({ item }: { item: User }) => (
        <View style={styles.card}>
             <Avatar name={item.name} size={40} />
             <View style={{ flex: 1, marginLeft: 10 }}>
                 <Text style={styles.name}>{item.name}</Text>
                 <Text style={styles.level}>Lvl {item.level ?? 1}</Text>
             </View>
             <TouchableOpacity style={styles.addButton} onPress={() => handleAdd(item.id)}>
                 <Text style={styles.addButtonText}>Add</Text>
             </TouchableOpacity>
        </View>
    );

    const renderRankingItem = ({ item, index }: { item: User, index: number }) => {
        const isMe = item.id === currentUser?.id;
        
        // Calculate Rank (Standard Competition Ranking 1224)
        // Find the index of the first user with the same stats
        // Since the list is sorted, this gives us the correct "Rank"
        // Note: For large lists, pre-calculating ranks is better, but for 50 items this is fine.
        const rankIndex = rankings.findIndex(u => u.level === item.level && u.exp === item.exp);
        const rank = rankIndex !== -1 ? rankIndex + 1 : index + 1;

        return (
            <View style={[styles.card, isMe && styles.myRankCard]}>
                <Text style={styles.rank}>#{rank}</Text>
                <Avatar name={item.name} size={36} />
                <View style={{ flex: 1, marginLeft: 10 }}>
                    <Text style={[styles.name, isMe && { color: '#FF9800' }]}>{item.name}</Text>
                    <Text style={styles.level}>Lvl {item.level ?? 1}</Text>
                </View>
                <Text style={styles.exp}>{item.exp} XP</Text>
            </View>
        );
    };

  return (
    <View style={styles.container}>
      <View style={styles.tabs}>
          <TouchableOpacity onPress={() => setTab('friends')} style={[styles.tab, tab === 'friends' && styles.activeTab]}>
              <Text style={[styles.tabText, tab === 'friends' && styles.activeTabText]}>Friends</Text>
          </TouchableOpacity>
          <TouchableOpacity onPress={() => setTab('requests')} style={[styles.tab, tab === 'requests' && styles.activeTab]}>
              <Text style={[styles.tabText, tab === 'requests' && styles.activeTabText]}>Reqs</Text>
          </TouchableOpacity>
           <TouchableOpacity onPress={() => setTab('ranking')} style={[styles.tab, tab === 'ranking' && styles.activeTab]}>
              <Text style={[styles.tabText, tab === 'ranking' && styles.activeTabText]}>Rank</Text>
          </TouchableOpacity>
          <TouchableOpacity onPress={() => setTab('search')} style={[styles.tab, tab === 'search' && styles.activeTab]}>
              <Text style={[styles.tabText, tab === 'search' && styles.activeTabText]}>Find</Text>
          </TouchableOpacity>
      </View>

      {tab === 'friends' && (
          <View style={{ flex: 1 }}>
                <TouchableOpacity 
                    style={styles.aiCard} 
                    onPress={() => router.push({ pathname: '/(tabs)/friends/chat/[id]', params: { id: 0, name: 'Gemini AI' } })}
                >
                    <View style={styles.aiAvatarContainer}>
                        <Image 
                            source={require('../../../assets/images/gemini-logo.jpg')} 
                            style={styles.aiAvatar} 
                        />
                        <View style={styles.aiOnlineBadge} />
                    </View>
                    <View style={styles.aiInfo}>
                        <Text style={styles.aiName}>Gemini AI</Text>
                        <Text style={styles.aiStatus}>Always here to help</Text>
                    </View>
                    <Ionicons name="chatbubble-ellipses" size={24} color="#FF9800" />
                </TouchableOpacity>

              <FlatList
                 data={friends}
                 keyExtractor={(item) => item.id.toString()}
                 renderItem={renderFriend}
                 refreshControl={<RefreshControl refreshing={refreshing} onRefresh={() => { setRefreshing(true); loadData(); }} tintColor="#FFF" />}
                 ListEmptyComponent={<Text style={styles.empty}>No friends yet.</Text>}
              />
          </View>
      )}

      {tab === 'requests' && (
          <FlatList
             data={requests}
             keyExtractor={(item) => item.id.toString()}
             renderItem={renderRequest}
             refreshControl={<RefreshControl refreshing={refreshing} onRefresh={() => { setRefreshing(true); loadData(); }} tintColor="#FFF" />}
             ListEmptyComponent={<Text style={styles.empty}>No pending requests.</Text>}
          />
      )}

      {tab === 'ranking' && (
          <View style={{ flex: 1 }}>
              <View style={styles.subTabs}>
                  <TouchableOpacity onPress={() => setRankingType('global')} style={[styles.subTab, rankingType === 'global' && styles.activeSubTab]}>
                      <Text style={[styles.subTabText, rankingType === 'global' && styles.activeSubTabText]}>Global</Text>
                  </TouchableOpacity>
                  <TouchableOpacity onPress={() => setRankingType('friends')} style={[styles.subTab, rankingType === 'friends' && styles.activeSubTab]}>
                      <Text style={[styles.subTabText, rankingType === 'friends' && styles.activeSubTabText]}>Friends</Text>
                  </TouchableOpacity>
              </View>
              <FlatList
                  data={rankings}
                  keyExtractor={(item) => item.id.toString()}
                  renderItem={renderRankingItem}
                  refreshControl={<RefreshControl refreshing={refreshing} onRefresh={() => { setRefreshing(true); loadData(); }} tintColor="#FFF" />}
              />
          </View>
      )}

      {tab === 'search' && (
          <View>
              <View style={styles.searchBox}>
                  <TextInput 
                    style={styles.searchInput} 
                    placeholder="Search name or email..." 
                    placeholderTextColor="#BBAADD"
                    value={searchQuery}
                    onChangeText={setSearchQuery}
                  />
                  <TouchableOpacity onPress={handleSearch}>
                      <Ionicons name="search" size={24} color="#FF9800" />
                  </TouchableOpacity>
              </View>
              <FlatList
                 data={searchResults}
                 keyExtractor={(item) => item.id.toString()}
                 renderItem={renderSearchUser}
              />
          </View>
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 10,
    paddingTop: 60, // Add gap for Status Bar / Overlay
    backgroundColor: '#342056', // Deep Purple
  },
  tabs: {
      flexDirection: 'row',
      marginBottom: 10,
      backgroundColor: '#432874', // Lighter Purple
      borderRadius: 10,
      padding: 5,
  },
  tab: {
      flex: 1,
      padding: 10,
      alignItems: 'center',
      borderRadius: 8,
  },
  activeTab: {
      backgroundColor: '#FF9800', // Gold for Active
  },
  tabText: {
      color: '#BBAADD', // Lavender Gray
      fontWeight: '600',
      fontSize: 12, 
  },
  activeTabText: {
      color: '#FFF',
      fontWeight: 'bold',
  },
  subTabs: {
      flexDirection: 'row',
      marginBottom: 10,
      justifyContent: 'center',
      gap: 10,
  },
  subTab: {
      paddingVertical: 6,
      paddingHorizontal: 16,
      borderRadius: 20,
      backgroundColor: 'rgba(255,255,255,0.1)',
      borderWidth: 1,
      borderColor: '#5B4290',
  },
  activeSubTab: {
      backgroundColor: '#FF9800',
      borderColor: '#FF9800',
  },
  subTabText: {
      color: '#BBAADD',
  },
  activeSubTabText: {
      color: '#FFF',
      fontWeight: 'bold',
  },
  card: {
    backgroundColor: '#432874',
    padding: 15,
    borderRadius: 10,
    marginBottom: 10,
    flexDirection: 'row',
    alignItems: 'center',
    borderWidth: 1,
    borderColor: '#5B4290',
  },
  myRankCard: {
      borderWidth: 2,
      borderColor: '#FF9800',
      backgroundColor: 'rgba(255, 152, 0, 0.1)',
  },
    // AI Card Styles
    aiCard: {
        flexDirection: 'row',
        alignItems: 'center',
        backgroundColor: '#FFF8E1', // Light Gold/Yellow bg to distinguish
        padding: 16,
        borderRadius: 16,
        marginBottom: 16,
        marginHorizontal: 10, 
        marginTop: 5,
        borderWidth: 1,
        borderColor: '#FF9800',
        elevation: 2,
    },
    aiAvatarContainer: {
        position: 'relative',
        marginRight: 15,
    },
    aiAvatar: {
        width: 50,
        height: 50,
        borderRadius: 25,
        backgroundColor: '#fff',
    },
    aiOnlineBadge: {
        position: 'absolute',
        bottom: 0,
        right: 0,
        width: 14,
        height: 14,
        borderRadius: 7,
        backgroundColor: '#4CAF50',
        borderWidth: 2,
        borderColor: '#FFF8E1',
    },
    aiInfo: {
        flex: 1,
    },
    aiName: {
        fontSize: 16,
        fontWeight: 'bold',
        color: '#E65100', // Dark Orange
    },
    aiStatus: {
        fontSize: 12,
        color: '#F57C00',
    },
  rank: {
      fontSize: 16,
      fontWeight: 'bold',
      marginRight: 10,
      width: 30,
      textAlign: 'center',
      color: '#FFD700', // Gold
  },
  name: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#FFF',
  },
  level: {
      color: '#BBAADD',
      fontSize: 14,
  },
  exp: {
      fontSize: 14,
      fontWeight: '600',
      color: '#FFD700',
  },
  addButton: {
      backgroundColor: '#FF9800',
      paddingVertical: 6,
      paddingHorizontal: 12,
      borderRadius: 5,
  },
  addButtonText: {
      color: 'white',
      fontWeight: 'bold',
      fontSize: 12,
  },
  iconButton: {
      padding: 5,
      marginLeft: 10,
  },
  searchBox: {
      flexDirection: 'row',
      backgroundColor: '#432874',
      padding: 10,
      borderRadius: 10,
      alignItems: 'center',
      marginBottom: 10,
      borderWidth: 1,
      borderColor: '#5B4290',
  },
  searchInput: {
      flex: 1,
      marginRight: 10,
      color: '#FFF',
  },
  empty: {
      textAlign: 'center',
      marginTop: 20,
      color: '#BBAADD',
  },
  unreadBadge: {
      position: 'absolute',
      right: -2,
      top: -2,
      backgroundColor: '#F44336',
      borderRadius: 10,
      minWidth: 18,
      height: 18,
      justifyContent: 'center',
      alignItems: 'center',
      paddingHorizontal: 4,
  },
  unreadText: {
      color: 'white',
      fontSize: 10,
      fontWeight: 'bold',
  }
});
