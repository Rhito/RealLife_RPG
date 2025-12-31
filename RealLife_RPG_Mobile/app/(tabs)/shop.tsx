import { View, Text, StyleSheet, FlatList, TouchableOpacity, Image, Alert, RefreshControl, Platform } from 'react-native';
import { useAuth } from '../../context/AuthContext';
import { useCallback, useState } from 'react';
import { useFocusEffect } from 'expo-router';
import { fetchItems, fetchInventory, buyItem, useItem, Item, UserItem } from '../../services/shop';
import { Ionicons } from '@expo/vector-icons';

type ViewMode = 'shop' | 'inventory';

export default function ShopScreen() {
    const { user, setUser } = useAuth();
    const [mode, setMode] = useState<ViewMode>('shop');
    const [items, setItems] = useState<Item[]>([]);
    const [inventory, setInventory] = useState<UserItem[]>([]);
    const [refreshing, setRefreshing] = useState(false);

    const loadData = async () => {
        try {
            if (mode === 'shop') {
                console.log('Fetching shop items...');
                const res = await fetchItems();
                console.log('Shop items fetched:', res.data);
                setItems(res.data.data); // Access nested data
            } else {
                console.log('Fetching inventory...');
                const res = await fetchInventory();
                console.log('Inventory fetched:', res.data);
                setInventory(res.data.data); // Access nested data
            }
        } catch (e: any) {
            console.error('Error fetching shop data:', e.message, e.response?.data);
        }
    };

    useFocusEffect(
        useCallback(() => {
            loadData();
        }, [mode])
    );

    const onRefresh = async () => {
        setRefreshing(true);
        await loadData();
        setRefreshing(false);
    };

    const handleBuy = async (item: Item) => {
        if ((user?.coins || 0) < item.cost) {
            Alert.alert('Not enough coins', 'Complete more tasks to earn coins!');
            return;
        }

        try {
            const res = await buyItem(item.id);
            // Axios response structure: res.data is the payload
            Alert.alert('Success', res.data.message);
            // Update coins
            if (user) {
                setUser({ ...user, coins: res.data.data.coins });
            }
        } catch (e: any) {
             const msg = e.response?.data?.message || 'Failed to buy item';
             Alert.alert('Error', msg);
        }
    };

    const handleUse = async (userItem: UserItem) => {
        try {
            const res = await useItem(userItem.id);
             // Axios response structure: res.data is the payload
            Alert.alert('Used!', res.data.message);
            
            // Update user stats
            if (user && res.data.rewards) {
                setUser({ 
                    ...user, 
                    hp: res.data.rewards.hp ?? user.hp,
                    coins: res.data.rewards.coins ?? user.coins
                });
            }
            // Refresh inventory
            loadData();
        } catch (e: any) {
            const msg = e.response?.data?.message || 'Failed to use item';
            Alert.alert('Error', msg);
        }
    };

    const getIconInfo = (name: string) => {
        if (name.includes('Potion')) return { name: 'flask', color: '#F44336' };
        if (name.includes('Freeze')) return { name: 'snow', color: '#03A9F4' };
        if (name.includes('Scroll')) return { name: 'book', color: '#8D6E63' };
        if (name.includes('Apple')) return { name: 'nutrition', color: '#FFC107' };
        return { name: 'cube', color: '#757575' };
    };

    const renderShopItem = ({ item }: { item: Item }) => {
        const icon = getIconInfo(item.name);
        return (
            <View style={styles.card}>
                <View style={[styles.iconContainer, { backgroundColor: icon.color + '20' }]}>
                     <Ionicons 
                        name={icon.name as any} 
                        size={32} 
                        color={icon.color} 
                     />
                </View>
                <View style={styles.contentContainer}>
                    <Text style={styles.itemName}>{item.name}</Text>
                    <Text style={styles.itemDesc}>{item.description}</Text>
                    <Text style={styles.itemCost}>
                        <Ionicons name="logo-bitcoin" size={14} color="#FFC107" /> {item.cost} Coins
                    </Text>
                </View>
                <TouchableOpacity style={styles.buyButton} onPress={() => handleBuy(item)}>
                    <Text style={styles.buyButtonText}>Buy</Text>
                </TouchableOpacity>
            </View>
        );
    };

    const renderInventoryItem = ({ item }: { item: UserItem }) => {
        if (!item.item) return null; // Safety check
        const icon = getIconInfo(item.item.name);
        return (
            <View style={styles.card}>
                <View style={[styles.iconContainer, { backgroundColor: icon.color + '20' }]}>
                     <Ionicons 
                        name={icon.name as any} 
                        size={32} 
                        color={icon.color} 
                     />
                </View>
                <View style={styles.contentContainer}>
                    <Text style={styles.itemName}>{item.item.name}</Text>
                    <Text style={styles.itemDesc}>{item.item.description}</Text>
                </View>
                {item.quantity > 1 && (
                    <View style={styles.qtyBadge}>
                        <Text style={styles.qtyText}>x{item.quantity}</Text>
                    </View>
                )}
                <TouchableOpacity style={styles.useButton} onPress={() => handleUse(item)}>
                    <Text style={styles.useButtonText}>Use</Text>
                </TouchableOpacity>
            </View>
        );
    };

    return (
        <View style={styles.container}>
            <View style={styles.header}>
                <Text style={styles.headerTitle}>Marketplace</Text>
                <View style={styles.coinBadge}>
                    <Ionicons name="logo-bitcoin" size={16} color="#FFD700" />
                    <Text style={styles.coinText}>{user?.coins || 0}</Text>
                </View>
            </View>

            <View style={styles.tabs}>
                <TouchableOpacity 
                    style={[styles.tab, mode === 'shop' && styles.activeTab]} 
                    onPress={() => setMode('shop')}
                >
                    <Text style={[styles.tabText, mode === 'shop' && styles.activeTabText]}>Shop</Text>
                </TouchableOpacity>
                <TouchableOpacity 
                    style={[styles.tab, mode === 'inventory' && styles.activeTab]} 
                    onPress={() => setMode('inventory')}
                >
                    <Text style={[styles.tabText, mode === 'inventory' && styles.activeTabText]}>Inventory</Text>
                </TouchableOpacity>
            </View>

            {mode === 'shop' ? (
                 <FlatList
                    data={items}
                    keyExtractor={(item) => item.id.toString()}
                    renderItem={renderShopItem}
                    contentContainerStyle={styles.list}
                    refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor="#fff" />}
                    ListEmptyComponent={<Text style={styles.emptyText}>No items available</Text>}
                />
            ) : (
                 <FlatList
                    data={inventory}
                    keyExtractor={(item) => item.id.toString()}
                    renderItem={renderInventoryItem}
                    contentContainerStyle={styles.list}
                    refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor="#fff" />}
                    ListEmptyComponent={<Text style={styles.emptyText}>Your inventory is empty</Text>}
                />
            )}
        </View>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#342056',
    },
    header: {
        paddingTop: Platform.OS === 'android' ? 40 : 60,
        paddingHorizontal: 20,
        paddingBottom: 20,
        backgroundColor: '#432874', // Darker purple
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
    },
    headerTitle: {
        fontSize: 24,
        fontWeight: 'bold',
        color: '#fff',
    },
    coinBadge: {
        flexDirection: 'row',
        alignItems: 'center',
        backgroundColor: 'rgba(0,0,0,0.3)',
        paddingHorizontal: 12,
        paddingVertical: 6,
        borderRadius: 20,
    },
    coinText: {
        color: '#FFD700',
        fontWeight: 'bold',
        marginLeft: 6,
    },
    tabs: {
        flexDirection: 'row',
        padding: 16,
    },
    tab: {
        flex: 1,
        paddingVertical: 10,
        alignItems: 'center',
        borderBottomWidth: 2,
        borderBottomColor: 'rgba(255,255,255,0.1)',
    },
    activeTab: {
        borderBottomColor: '#FF9800',
    },
    tabText: {
        color: 'rgba(255,255,255,0.6)',
        fontWeight: '600',
    },
    activeTabText: {
        color: '#fff',
        fontWeight: 'bold',
    },
    list: {
        padding: 16,
    },
    card: {
        backgroundColor: '#fff',
        borderRadius: 12,
        padding: 16,
        marginBottom: 12,
        flexDirection: 'row',
        alignItems: 'center',
    },
    iconContainer: {
        width: 48,
        height: 48,
        borderRadius: 24,
        backgroundColor: '#F5F5F5',
        justifyContent: 'center',
        alignItems: 'center',
        marginRight: 16,
    },
    contentContainer: {
        flex: 1,
    },
    itemName: {
        fontSize: 16,
        fontWeight: 'bold',
        color: '#333',
    },
    itemDesc: {
        fontSize: 12,
        color: '#666',
        marginTop: 2,
    },
    itemCost: {
        marginTop: 4,
        fontSize: 12,
        fontWeight: 'bold',
        color: '#432874',
    },
    buyButton: {
        backgroundColor: '#4CAF50',
        paddingHorizontal: 16,
        paddingVertical: 8,
        borderRadius: 20,
    },
    buyButtonText: {
        color: '#fff',
        fontWeight: 'bold',
        fontSize: 12,
    },
    useButton: {
        backgroundColor: '#2196F3',
        paddingHorizontal: 16,
        paddingVertical: 8,
        borderRadius: 20,
    },
    useButtonText: {
        color: '#fff',
        fontWeight: 'bold',
        fontSize: 12,
    },
    emptyText: {
        textAlign: 'center',
        color: 'rgba(255,255,255,0.5)',
        marginTop: 40,
        fontStyle: 'italic',
    },
    qtyBadge: {
        backgroundColor: '#FF9800',
        paddingHorizontal: 8,
        paddingVertical: 4,
        borderRadius: 12,
        marginRight: 8,
    },
    qtyText: {
        color: '#fff',
        fontSize: 12,
        fontWeight: 'bold',
    },
});
