import { View, Text, StyleSheet, ScrollView, RefreshControl, ActivityIndicator, Platform, FlatList, Dimensions, TouchableOpacity } from 'react-native';
import React, { useCallback, useState } from 'react';
import { useFocusEffect } from 'expo-router';
import { Ionicons } from '@expo/vector-icons';
import { fetchFeed, FeedItem } from '../../services/profile';
import { Colors } from '../../constants/theme';

export default function AdventureLogScreen() {
    const [feed, setFeed] = useState<FeedItem[]>([]);
    const [loading, setLoading] = useState(true);
    const [refreshing, setRefreshing] = useState(false);

    const loadFeed = async () => {
        try {
            const res = await fetchFeed();
            setFeed(res.data);
        } catch (e) {
            console.error(e);
        } finally {
            setLoading(false);
            setRefreshing(false);
        }
    };

    useFocusEffect(
        useCallback(() => {
            loadFeed();
        }, [])
    );

    const onRefresh = () => {
        setRefreshing(true);
        loadFeed();
    };

    const groupFeedByDate = (items: FeedItem[]) => {
        const groups: { [key: string]: FeedItem[] } = {};
        items.forEach(item => {
            const date = new Date(item.created_at).toDateString();
            if (!groups[date]) groups[date] = [];
            groups[date].push(item);
        });
        return groups;
    };

    const renderFeedItem = (item: FeedItem) => {
        let text = '';
        let iconName: any = 'help-circle-outline';
        let iconColor = '#999';
        let isHighlight = false;
        let isDamage = false;

        if (item.activity_type === 'task_completed') {
            text = `Completed "${item.data.task_name}"`;
        } else if (item.activity_type === 'level_up') {
            text = `You reached Level ${item.data.new_level}! A momentous occasion.`;
            isHighlight = true;
            iconColor = '#E65100';
        } else if (item.activity_type === 'badge_earned') {
             text = `Badge earned: ${item.data.badge_name}`;
             isHighlight = true;
             iconColor = '#4A148C';
        } else if (item.activity_type === 'damage_taken') {
            // "data":{"damage":5,"reason":"Gave up on 'va'"}
            text = `Took ${item.data.damage} damage. ${item.data.reason || 'Unknown cause'}.`;
            isDamage = true;
        } else if (item.activity_type === 'item_used') {
            text = item.data.effect || `Used ${item.data.item_name}`;
            iconName = 'flask';
            iconColor = '#4CAF50';
        } else {
            text = 'Unknown Activity';
        }

        // Return just the content View, not the container, because the container is now 'journalEntry' in the map loop
        return (
            <View style={styles.feedContent}> 
                <Text style={[
                    styles.feedText, 
                    isHighlight && styles.highlightText,
                    isDamage && styles.damageText
                ]}>{text}</Text>
                {item.activity_type === 'task_completed' && (
                    <Text style={styles.feedRewards}>
                        Gained {item.data.earned_exp} XP and found {item.data.earned_coins} Coins.
                    </Text>
                )}
                <Text style={styles.feedTime}>
                    {new Date(item.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                </Text>
            </View>
        );
    };

    const [activeIndex, setActiveIndex] = useState(0);
    const flatListRef = React.useRef<FlatList>(null);

    const onViewableItemsChanged = React.useRef(({ viewableItems }: any) => {
        if (viewableItems.length > 0) {
            setActiveIndex(viewableItems[0].index || 0);
        }
    }).current;

    const scrollToIndex = (index: number) => {
        flatListRef.current?.scrollToIndex({ index, animated: true });
    };

    const groupedFeed = groupFeedByDate(feed);
    const sortedDates = Object.keys(groupedFeed).sort((a, b) => new Date(b).getTime() - new Date(a).getTime());

    const renderPage = ({ item: date }: { item: string }) => {
        let title = date;
        const today = new Date().toDateString();
        const yesterday = new Date(Date.now() - 86400000).toDateString();
        if (date === today) title = 'Today`s Entry';
        else if (date === yesterday) title = 'Yesterday';

        return (
            <View style={styles.pageContainer}>
                <View style={styles.pageHeaderContainer}>
                    <Text style={styles.pageTitle}>Adventure Journal</Text>
                    <View style={styles.divider} />
                    <Text style={styles.heroName}>Chronicles of a Hero</Text>
                </View>

                <View style={styles.dayChapter}>
                    <View style={styles.chapterHeader}>
                        <Text style={styles.chapterTitle}>{title}</Text>
                        <View style={styles.chapterLine} />
                    </View>
                    <ScrollView showsVerticalScrollIndicator={false}>
                        {groupedFeed[date].map((feedItem) => (
                             <View key={feedItem.id} style={styles.journalEntry}>
                                <View style={styles.entryBullet}>
                                    <Ionicons 
                                        name={getIconName(feedItem)} 
                                        size={16} 
                                        color="#5D4037" 
                                    />
                                </View>
                                {renderFeedItem(feedItem)}
                            </View>
                        ))}
                         <View style={styles.pageFooter}>
                            <Text style={styles.pageNumber}>{date}</Text>
                        </View>
                    </ScrollView>
                </View>
            </View>
        );
    };

    return (
        <View style={styles.bookContainer}>
            {/* Book Spine/Background */}
            <View style={styles.spine}>
                <View style={styles.spineDecor} />
            </View>
            
            {loading ? (
                <ActivityIndicator color="#5D4037" style={{ marginTop: 50 }} />
            ) : feed.length === 0 ? (
                <View style={styles.emptyPage}>
                     <View style={styles.emptyState}>
                        <Ionicons name="book-outline" size={64} color="#D7CCC8" />
                        <Text style={styles.emptyText}>The pages are blank...</Text>
                        <Text style={styles.emptySubText}>Go forth and create your story!</Text>
                    </View>
                </View>
            ) : (
                <>
                    <FlatList
                        ref={flatListRef}
                        data={sortedDates}
                        renderItem={renderPage}
                        keyExtractor={(item) => item}
                        horizontal
                        pagingEnabled
                        showsHorizontalScrollIndicator={false}
                        style={styles.bookList}
                        contentContainerStyle={styles.bookContent}
                        refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor="#5D4037" />}
                        onViewableItemsChanged={onViewableItemsChanged}
                        viewabilityConfig={{ itemVisiblePercentThreshold: 50 }}
                    />

                    {/* Navigation Arrows */}
                    {activeIndex > 0 && (
                        <TouchableOpacity 
                            style={[styles.arrowButton, styles.leftArrow]} 
                            onPress={() => scrollToIndex(activeIndex - 1)}
                        >
                            <Ionicons name="chevron-back" size={24} color="#EEE" />
                        </TouchableOpacity>
                    )}

                    {activeIndex < sortedDates.length - 1 && (
                        <TouchableOpacity 
                            style={[styles.arrowButton, styles.rightArrow]} 
                            onPress={() => scrollToIndex(activeIndex + 1)}
                        >
                            <Ionicons name="chevron-forward" size={24} color="#EEE" />
                        </TouchableOpacity>
                    )}
                </>
            )}
        </View>
    );
}

const getIconName = (item: FeedItem): any => {
    if (item.activity_type === 'task_completed') return 'checkbox';
    if (item.activity_type === 'level_up') return 'arrow-up';
    if (item.activity_type === 'badge_earned') return 'ribbon';
    if (item.activity_type === 'damage_taken') return 'skull';
    if (item.activity_type === 'item_used') return 'flask';
    return 'ellipse';
};

const styles = StyleSheet.create({
    bookContainer: {
        flex: 1,
        backgroundColor: '#3E2723', // Dark Leather
        paddingLeft: 20, // Space for spine
        paddingRight: 0,
        paddingTop: Platform.OS === 'android' ? 40 : 60, // Safe Area / Status Bar space
        paddingBottom: 20,
    },
    spine: {
        position: 'absolute',
        top: 0,
        bottom: 0,
        left: 0,
        width: 24,
        backgroundColor: '#3E2723',
        zIndex: 10,
        justifyContent: 'center',
        borderRightWidth: 1,
        borderRightColor: '#2D1B18',
    },
    spineDecor: {
        width: 2,
        height: '90%',
        backgroundColor: '#5D4037',
        alignSelf: 'center',
        opacity: 0.5,
    },
    bookList: {
        flex: 1,
    },
    bookContent: {
        
    },
    pageContainer: {
        width: Dimensions.get('window').width - 40, // Width minus spine and right padding
        backgroundColor: '#FDF5E6', // Old Lace / Paper
        marginRight: 10,
        borderRadius: 8,
        padding: 24,
        height: '100%',
        elevation: 5,
        shadowColor: '#000',
        shadowOffset: { width: 4, height: 4 },
        shadowOpacity: 0.3,
        shadowRadius: 5,
        overflow: 'hidden',
    },
    emptyPage: {
        flex: 1,
        backgroundColor: '#FDF5E6',
        borderRadius: 8,
        marginRight: 20,
        height: '100%',
    },
    pageHeaderContainer: {
        alignItems: 'center',
        marginBottom: 20,
        marginTop: 0,
    },
    pageTitle: {
        fontSize: 24,
        fontWeight: 'bold', // Serif if possible
        color: '#3E2723',
        fontFamily: Platform.OS === 'ios' ? 'Georgia' : 'serif',
    },
    divider: {
        width: 80,
        height: 2,
        backgroundColor: '#8D6E63',
        marginVertical: 8,
    },
    heroName: {
        fontSize: 12,
        fontStyle: 'italic',
        color: '#5D4037',
        fontFamily: Platform.OS === 'ios' ? 'Georgia-Italic' : 'serif',
    },
    dayChapter: {
        flex: 1,
    },
    chapterHeader: {
        flexDirection: 'row',
        alignItems: 'center',
        marginBottom: 16,
    },
    chapterTitle: {
        fontSize: 18,
        fontWeight: 'bold',
        color: '#5D4037',
        marginRight: 10,
        fontFamily: Platform.OS === 'ios' ? 'Georgia-Bold' : 'serif',
        textDecorationLine: 'underline',
    },
    chapterLine: {
        flex: 1,
        height: 1,
        backgroundColor: '#D7CCC8',
    },
    journalEntry: {
        flexDirection: 'row',
        marginBottom: 16,
        alignItems: 'flex-start',
    },
    entryBullet: {
        marginTop: 2,
        marginRight: 12,
        width: 24,
        alignItems: 'center',
    },
    pageFooter: {
        alignItems: 'center',
        marginTop: 20,
        paddingBottom: 10,
        borderTopWidth: 1,
        borderTopColor: '#EFEBE9',
        paddingTop: 10,
    },
    pageNumber: {
        fontSize: 10,
        color: '#A1887F',
    },
    emptyState: {
        alignItems: 'center',
        justifyContent: 'center',
        marginTop: 60,
    },
    emptyText: {
        color: '#8D6E63',
        fontSize: 18,
        fontFamily: Platform.OS === 'ios' ? 'Georgia' : 'serif',
        marginTop: 16,
    },
    emptySubText: {
        color: '#A1887F',
        fontSize: 14,
        marginTop: 8,
        fontStyle: 'italic',
    },
    // Feed Item Styles
    feedContent: {
        flex: 1,
    },
    feedText: {
        fontSize: 15,
        color: '#4E342E',
        fontFamily: Platform.OS === 'ios' ? 'Georgia' : 'serif',
        lineHeight: 22,
    },
    highlightText: {
        fontWeight: 'bold',
        color: '#BF360C', // Deep Orange for highlights
    },
    damageText: {
        color: '#D32F2F', // Red for damage
        fontWeight: '500', 
    },
    feedRewards: {
        fontSize: 13,
        color: '#FF6F00', // Amber
        fontWeight: 'bold',
        marginTop: 2,
        fontStyle: 'italic',
    },
    feedTime: {
        fontSize: 11,
        color: '#A1887F',
        marginTop: 4,
    },
    arrowButton: {
        position: 'absolute',
        top: '50%',
        width: 40,
        height: 40,
        borderRadius: 20,
        backgroundColor: 'rgba(93, 64, 55, 0.8)', // Semi-transparent brown
        justifyContent: 'center',
        alignItems: 'center',
        elevation: 6,
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.3,
        shadowRadius: 3,
        zIndex: 20,
    },
    leftArrow: {
        left: 10,
    },
    rightArrow: {
        right: 10,
    },
});
