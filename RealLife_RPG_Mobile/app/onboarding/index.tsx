import { View, Text, StyleSheet, TouchableOpacity, ScrollView, Animated } from 'react-native';
import { useRouter } from 'expo-router';
import { useState, useRef } from 'react';
import { useAuth } from '../../context/AuthContext';
import { seedOnboarding } from '../../services/auth';
import { Ionicons } from '@expo/vector-icons';
import { Colors } from '../../constants/theme';
import { useAlert } from '../../context/AlertContext';
import { scaleFont, spacing } from '../../utils/responsive';

const PLANS = [
    {
        id: 'fitness',
        title: 'The Athlete',
        icon: 'fitness',
        color: '#F44336',
        description: 'Build stamina and strength. Includes daily jogs and water tracking.',
        tasks: ['Morning Jog (Habit)', 'Drink Water (Daily)', 'No Sugar (Habit)']
    },
    {
        id: 'student',
        title: 'The Scholar',
        icon: 'school',
        color: '#2196F3',
        description: 'Focus on studies and grades. Includes study sessions and assignments.',
        tasks: ['Study Session (Habit)', 'Review Notes (Daily)', 'Assignments (Weekend)']
    },
    {
        id: 'creative',
        title: 'The Creator',
        icon: 'brush',
        color: '#9C27B0',
        description: 'Unleash your imagination. Includes sketching and inspiration hunts.',
        tasks: ['Sketch/Write (Habit)', 'Inspiration Hunt (Daily)']
    }
];

export default function OnboardingScreen() {
    const router = useRouter();
    const { user, setUser } = useAuth(); // We might need to update user context
    const { showAlert } = useAlert();
    const [loading, setLoading] = useState(false);
    const [selectedPlan, setSelectedPlan] = useState<string | null>(null);

    const handleSelect = async (planId: string | null) => {
        if (loading) return;
        setLoading(true);

        try {
            const res = await seedOnboarding(planId);
            
            // Update local user state to reflect onboarding completion
            if (user) {
                setUser({ ...user, is_onboarded: true });
            }

            if (planId) {
                showAlert('Welcome!', `You have chosen the path of ${PLANS.find(p => p.id === planId)?.title}.`);
            } else {
                showAlert('Welcome!', 'You have chosen to forge your own path.');
            }

            router.replace('/(tabs)');
        } catch (e: any) {
            // If already onboarded, just redirect to tabs
            if (e.message?.includes('already onboarded') || e.response?.status === 400) {
                if (user) {
                    setUser({ ...user, is_onboarded: true });
                }
                router.replace('/(tabs)');
                return;
            }
            showAlert('Error', e.message || 'Failed to setup account');
            setLoading(false);
        }
    };

    return (
        <View style={styles.container}>
            <View style={styles.header}>
                <Text style={styles.title}>Choose Your Path</Text>
                <Text style={styles.subtitle}>Select a starter plan to seed your quest log, or skip to start fresh.</Text>
            </View>

            <ScrollView contentContainerStyle={styles.scrollContent}>
                {PLANS.map((plan) => (
                    <TouchableOpacity 
                        key={plan.id} 
                        style={[styles.card, selectedPlan === plan.id && styles.selectedCard]}
                        onPress={() => setSelectedPlan(plan.id)}
                    >
                        <View style={[styles.iconContainer, { backgroundColor: plan.color }]}>
                            <Ionicons name={plan.icon as any} size={32} color="white" />
                        </View>
                        <View style={styles.cardContent}>
                            <Text style={styles.cardTitle}>{plan.title}</Text>
                            <Text style={styles.cardDesc}>{plan.description}</Text>
                            <View style={styles.taskList}>
                                {plan.tasks.map((t, i) => (
                                    <Text key={i} style={styles.taskItem}>• {t}</Text>
                                ))}
                            </View>
                        </View>
                        {selectedPlan === plan.id && (
                             <Ionicons name="checkmark-circle" size={24} color={Colors.rpg.success} style={{ position: 'absolute', top: 10, right: 10 }} />
                        )}
                    </TouchableOpacity>
                ))}
            </ScrollView>

            <View style={styles.footer}>
                <TouchableOpacity 
                    style={[styles.button, styles.primaryButton, !selectedPlan && styles.disabledButton]} 
                    onPress={() => selectedPlan && handleSelect(selectedPlan)}
                    disabled={!selectedPlan || loading}
                >
                    <Text style={styles.buttonText}>Confirm Path</Text>
                </TouchableOpacity>

                <TouchableOpacity 
                    style={styles.skipButton} 
                    onPress={() => handleSelect(null)}
                    disabled={loading}
                >
                    <Text style={styles.skipText}>Skip (Start Empty)</Text>
                </TouchableOpacity>
            </View>
        </View>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#342056',
        paddingTop: spacing.xxl * 2,
    },
    header: {
        paddingHorizontal: spacing.lg,
        marginBottom: spacing.lg,
    },
    title: {
        fontSize: scaleFont(28),
        fontWeight: 'bold',
        color: 'white',
        marginBottom: spacing.sm,
    },
    subtitle: {
        fontSize: scaleFont(16),
        color: '#BBAADD',
        lineHeight: scaleFont(22),
    },
    scrollContent: {
        paddingHorizontal: spacing.lg,
        paddingBottom: 100,
    },
    card: {
        backgroundColor: 'rgba(255,255,255,0.05)',
        borderRadius: spacing.md,
        padding: spacing.md,
        marginBottom: spacing.md,
        flexDirection: 'row',
        borderWidth: 2,
        borderColor: 'transparent',
    },
    selectedCard: {
        backgroundColor: 'rgba(255,255,255,0.1)',
        borderColor: '#FFC107',
    },
    iconContainer: {
        width: spacing.xxl * 2,
        height: spacing.xxl * 2,
        borderRadius: spacing.xxl,
        justifyContent: 'center',
        alignItems: 'center',
        marginRight: spacing.md,
    },
    cardContent: {
        flex: 1,
    },
    cardTitle: {
        fontSize: scaleFont(18),
        fontWeight: 'bold',
        color: 'white',
        marginBottom: spacing.xs,
    },
    cardDesc: {
        fontSize: scaleFont(14),
        color: '#ccc',
        marginBottom: spacing.sm,
    },
    taskList: {
        marginTop: spacing.xs,
    },
    taskItem: {
        fontSize: scaleFont(12),
        color: '#BBAADD',
    },
    footer: {
        padding: spacing.lg,
        backgroundColor: '#342056',
        borderTopWidth: 1,
        borderTopColor: 'rgba(255,255,255,0.1)',
    },
    button: {
        padding: spacing.md,
        borderRadius: spacing.sm,
        alignItems: 'center',
        marginBottom: spacing.sm,
    },
    primaryButton: {
        backgroundColor: '#FFC107',
    },
    disabledButton: {
        backgroundColor: '#666',
        opacity: 0.5,
    },
    buttonText: {
        color: '#342056',
        fontSize: scaleFont(18),
        fontWeight: 'bold',
    },
    skipButton: {
        padding: spacing.sm,
        alignItems: 'center',
    },
    skipText: {
        color: '#ccc',
        fontSize: scaleFont(14),
        textDecorationLine: 'underline',
    },
});
