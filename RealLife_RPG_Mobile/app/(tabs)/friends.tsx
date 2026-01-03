import { View, Text, FlatList, StyleSheet, TouchableOpacity, TextInput, Alert,  RefreshControl, Image } from 'react-native';
import { useEffect, useState } from 'react';
import { fetchFriends, fetchRequests, searchUsers, sendRequest, acceptRequest, removeFriend, User, Friendship } from '../../services/friends';
import { fetchGlobalLeaderboard, fetchFriendsLeaderboard } from '../../services/leaderboard';
import { Ionicons } from '@expo/vector-icons';
import { useAuth } from '../../context/AuthContext';
import { useRouter } from 'expo-router';

type Tab = 'friends' | 'requests' | 'search' | 'ranking';
type RankingType = 'global' | 'friends';

export default function SocialScreen() {
    const { user: currentUser } = useAuth();
    const router = useRouter();
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
            Alert.alert('Success', 'Friend request sent!');
        } catch (e: any) {
            Alert.alert('Error', e.message);
        }
    }

    const handleAccept = async (id: number) => {
        try {
            await acceptRequest(id);
            Alert.alert('Success', 'Friend added!');
            loadData();
        } catch (e: any) {
            Alert.alert('Error', e.message);
        }
    }

    const renderFriend = ({ item }: { item: User }) => (
        <View style={styles.card}>
            <TouchableOpacity style={{ flexDirection: 'row', alignItems: 'center', flex: 1 }} onPress={() => router.push({ pathname: '/users/[id]', params: { id: item.id } })}>
                <Ionicons name="person-circle" size={40} color="#ccc" />
                <View style={{ flex: 1, marginLeft: 10 }}>
                    <Text style={styles.name}>{item.name}</Text>
                    <Text style={styles.level}>Lvl {item.level ?? 1}</Text>
                </View>
            </TouchableOpacity>
            <TouchableOpacity style={styles.iconButton} onPress={() => router.push({ pathname: '/chat/[id]' as any, params: { id: item.id } })}>
                <Ionicons name="chatbubble-ellipses" size={24} color="#007AFF" />
            </TouchableOpacity>
        </View>
    );

    const renderRequest = ({ item }: { item: Friendship }) => (
        <View style={styles.card}>
             <Ionicons name="person-circle" size={40} color="#ccc" />
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
             <Ionicons name="person-circle" size={40} color="#ccc" />
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
        return (
            <View style={[styles.card, isMe && styles.myRankCard]}>
                <Text style={styles.rank}>#{index + 1}</Text>
                <Ionicons name="person-circle" size={36} color={isMe ? "#007AFF" : "#ccc"} />
                <View style={{ flex: 1, marginLeft: 10 }}>
                    <Text style={[styles.name, isMe && { color: '#007AFF' }]}>{item.name}</Text>
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
          <FlatList
             data={friends}
             keyExtractor={(item) => item.id.toString()}
             renderItem={renderFriend}
             refreshControl={<RefreshControl refreshing={refreshing} onRefresh={() => { setRefreshing(true); loadData(); }} />}
             ListEmptyComponent={<Text style={styles.empty}>No friends yet.</Text>}
          />
      )}

      {tab === 'requests' && (
          <FlatList
             data={requests}
             keyExtractor={(item) => item.id.toString()}
             renderItem={renderRequest}
             refreshControl={<RefreshControl refreshing={refreshing} onRefresh={() => { setRefreshing(true); loadData(); }} />}
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
                  refreshControl={<RefreshControl refreshing={refreshing} onRefresh={() => { setRefreshing(true); loadData(); }} />}
              />
          </View>
      )}

      {tab === 'search' && (
          <View>
              <View style={styles.searchBox}>
                  <TextInput 
                    style={styles.searchInput} 
                    placeholder="Search name or email..." 
                    value={searchQuery}
                    onChangeText={setSearchQuery}
                  />
                  <TouchableOpacity onPress={handleSearch}>
                      <Ionicons name="search" size={24} color="#007AFF" />
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
    backgroundColor: '#f5f5f5',
  },
  tabs: {
      flexDirection: 'row',
      marginBottom: 10,
      backgroundColor: 'white',
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
      backgroundColor: '#e6f2ff',
  },
  tabText: {
      color: '#666',
      fontWeight: '600',
      fontSize: 12, // smaller font to fit 4 tabs
  },
  activeTabText: {
      color: '#007AFF',
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
      backgroundColor: '#ddd',
  },
  activeSubTab: {
      backgroundColor: '#007AFF',
  },
  subTabText: {
      color: '#333',
  },
  activeSubTabText: {
      color: 'white',
      fontWeight: 'bold',
  },
  card: {
    backgroundColor: 'white',
    padding: 15,
    borderRadius: 10,
    marginBottom: 10,
    flexDirection: 'row',
    alignItems: 'center',
    elevation: 2,
  },
  myRankCard: {
      borderWidth: 2,
      borderColor: '#007AFF',
      backgroundColor: '#f0f8ff',
  },
  rank: {
      fontSize: 16,
      fontWeight: 'bold',
      marginRight: 10,
      width: 30,
      textAlign: 'center',
      color: '#666',
  },
  name: {
    fontSize: 16,
    fontWeight: 'bold',
  },
  level: {
      color: '#666',
      fontSize: 14,
  },
  exp: {
      fontSize: 14,
      fontWeight: '600',
      color: '#666',
  },
  addButton: {
      backgroundColor: '#007AFF',
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
      backgroundColor: 'white',
      padding: 10,
      borderRadius: 10,
      alignItems: 'center',
      marginBottom: 10,
  },
  searchInput: {
      flex: 1,
      marginRight: 10,
  },
  empty: {
      textAlign: 'center',
      marginTop: 20,
      color: '#666',
  }
});
