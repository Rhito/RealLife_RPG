import { View, Text, StyleSheet, TouchableOpacity, ScrollView, Image, useWindowDimensions } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { useRouter } from 'expo-router';
import * as SecureStore from 'expo-secure-store';
import { Ionicons } from '@expo/vector-icons';
import { useRef, useState } from 'react';
import { wp, hp, scaleFont, spacing } from '../utils/responsive';

const SLIDES = [
    {
        id: 1,
        title: "Welcome to RealLife RPG",
        description: "Turn your daily life into an epic adventure. Complete tasks, earn XP, and level up your hero.",
        icon: "game-controller", 
        color: "#6C5CE7"
    },
    {
        id: 2,
        title: "Master Your Tasks",
        description: "Build Habits, finish Daily quests, and crush your To-Do list. Consistency is key to becoming a legend.",
        icon: "checkbox",
        color: "#00B894"
    },
    {
        id: 3,
        title: "Earn Rewards",
        description: "Gain Gold and XP for every achievement. Buy items, unlock avatars, and customize your profile.",
        icon: "trophy",
        color: "#FDCB6E"
    },
    {
        id: 4,
        title: "Join the Guild",
        description: "Connect with friends, compete in leaderboards, and keep each other accountable.",
        icon: "people",
        color: "#FF7675"
    }
];

export default function TutorialScreen() {
    const router = useRouter();
    const { width } = useWindowDimensions();
    const [activeIndex, setActiveIndex] = useState(0);
    const scrollRef = useRef<ScrollView>(null);

    const handleNext = async () => {
        if (activeIndex < SLIDES.length - 1) {
            scrollRef.current?.scrollTo({ x: width * (activeIndex + 1), animated: true });
        } else {
            await finishTutorial();
        }
    };

    const finishTutorial = async () => {
        try {
            await SecureStore.setItemAsync('HAS_SEEN_TUTORIAL', 'true');
            router.replace('/(tabs)');
        } catch (e) {
            console.error("Failed to save tutorial status", e);
            router.replace('/(tabs)');
        }
    };

    const onScroll = (event: any) => {
        const slideSize = event.nativeEvent.layoutMeasurement.width;
        const index = event.nativeEvent.contentOffset.x / slideSize;
        const roundIndex = Math.round(index);
        setActiveIndex(roundIndex);
    };

    return (
        <SafeAreaView style={styles.container}>
            <View style={styles.header}>
                <TouchableOpacity onPress={finishTutorial}>
                    <Text style={styles.skipText}>Skip</Text>
                </TouchableOpacity>
            </View>

            <ScrollView 
                ref={scrollRef}
                horizontal 
                pagingEnabled 
                showsHorizontalScrollIndicator={false}
                onScroll={onScroll}
                scrollEventThrottle={16}
                style={styles.scrollView}
            >
                {SLIDES.map((slide, index) => (
                    <View key={slide.id} style={styles.slide}>
                        <View style={[styles.iconContainer, { backgroundColor: slide.color }]}>
                            <Ionicons name={slide.icon as any} size={80} color="white" />
                        </View>
                        <Text style={styles.title}>{slide.title}</Text>
                        <Text style={styles.description}>{slide.description}</Text>
                    </View>
                ))}
            </ScrollView>

            <View style={styles.footer}>
                {/* Pagination Dots */}
                <View style={styles.pagination}>
                    {SLIDES.map((_, index) => (
                        <View 
                            key={index} 
                            style={[
                                styles.dot, 
                                activeIndex === index && styles.activeDot,
                                { backgroundColor: activeIndex === index ? SLIDES[activeIndex].color : '#ddd' }
                            ]} 
                        />
                    ))}
                </View>

                <TouchableOpacity 
                    style={[styles.button, { backgroundColor: SLIDES[activeIndex].color }]} 
                    onPress={handleNext}
                >
                    <Text style={styles.buttonText}>
                        {activeIndex === SLIDES.length - 1 ? "Get Started" : "Next"}
                    </Text>
                </TouchableOpacity>
            </View>
        </SafeAreaView>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#fff',
    },
    header: {
        flexDirection: 'row',
        justifyContent: 'flex-end',
        padding: spacing.lg,
    },
    skipText: {
        fontSize: scaleFont(16),
        color: '#999',
        fontWeight: '600',
    },
    scrollView: {
        flex: 1,
    },
    slide: {
        width: wp(100),
        alignItems: 'center',
        justifyContent: 'center',
        paddingHorizontal: spacing.xxl,
    },
    iconContainer: {
        width: wp(35),
        height: wp(35),
        borderRadius: wp(17.5),
        justifyContent: 'center',
        alignItems: 'center',
        marginBottom: spacing.xxl,
        shadowColor: "#000",
        shadowOffset: { width: 0, height: 10 },
        shadowOpacity: 0.2,
        shadowRadius: 15,
        elevation: 10,
    },
    title: {
        fontSize: scaleFont(28),
        fontWeight: 'bold',
        color: '#333',
        marginBottom: spacing.md,
        textAlign: 'center',
    },
    description: {
        fontSize: scaleFont(16),
        color: '#666',
        textAlign: 'center',
        lineHeight: scaleFont(24),
        paddingHorizontal: spacing.md,
    },
    footer: {
        padding: spacing.xxl,
        alignItems: 'center',
    },
    pagination: {
        flexDirection: 'row',
        marginBottom: spacing.xl,
    },
    dot: {
        width: wp(2.5),
        height: wp(2.5),
        borderRadius: wp(1.25),
        marginHorizontal: spacing.xs,
    },
    activeDot: {
        width: wp(5),
    },
    button: {
        width: '100%',
        paddingVertical: spacing.lg,
        borderRadius: spacing.md,
        alignItems: 'center',
        shadowColor: "#000",
        shadowOffset: { width: 0, height: 5 },
        shadowOpacity: 0.2,
        shadowRadius: 10,
        elevation: 5,
    },
    buttonText: {
        color: 'white',
        fontSize: scaleFont(18),
        fontWeight: 'bold',
    }
});
