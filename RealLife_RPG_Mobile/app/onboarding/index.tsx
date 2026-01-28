import { View, Text, StyleSheet, TouchableOpacity, ScrollView, Animated, Dimensions } from 'react-native';
import { useRouter } from 'expo-router';
import { useState, useRef } from 'react';
import { useAuth } from '../../context/AuthContext';
import { seedOnboarding } from '../../services/auth';
import { Ionicons } from '@expo/vector-icons';
import { Colors } from '../../constants/theme';
import { useAlert } from '../../context/AlertContext';

const { width } = Dimensions.get('window');

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
            // We assume the backend returns the updated user or we manually update
            if (user) {
                // Determine new tasks count or similar? 
                // Mostly we just need to set is_onboarded = true to pass the layout check
                // However, our AuthContext might not automatically refresh the user.
                // We should probably manually update it.
                // But `setUser` type might be strict.
                setUser({ ...user, is_onboarded: true });
            }

            if (planId) {
                showAlert('Welcome!', `You have chosen the path of ${PLANS.find(p => p.id === planId)?.title}.`);
            } else {
                showAlert('Welcome!', 'You have chosen to forge your own path.');
            }

            router.replace('/(tabs)');
        } catch (e: any) {
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
        paddingTop: 60,
    },
    header: {
        paddingHorizontal: 20,
        marginBottom: 20,
    },
    title: {
        fontSize: 28,
        fontWeight: 'bold',
        color: 'white',
        marginBottom: 10,
    },
    subtitle: {
        fontSize: 16,
        color: '#BBAADD',
        lineHeight: 22,
    },
    scrollContent: {
        paddingHorizontal: 20,
        paddingBottom: 100,
    },
    card: {
        backgroundColor: 'rgba(255,255,255,0.05)',
        borderRadius: 16,
        padding: 16,
        marginBottom: 16,
        flexDirection: 'row',
        borderWidth: 2,
        borderColor: 'transparent',
    },
    selectedCard: {
        backgroundColor: 'rgba(255,255,255,0.1)',
        borderColor: '#FFC107',
    },
    iconContainer: {
        width: 60,
        height: 60,
        borderRadius: 30,
        justifyContent: 'center',
        alignItems: 'center',
        marginRight: 16,
    },
    cardContent: {
        flex: 1,
    },
    cardTitle: {
        fontSize: 18,
        fontWeight: 'bold',
        color: 'white',
        marginBottom: 4,
    },
    cardDesc: {
        fontSize: 14,
        color: '#ccc',
        marginBottom: 8,
    },
    taskList: {
        marginTop: 4,
    },
    taskItem: {
        fontSize: 12,
        color: '#BBAADD',
    },
    footer: {
        padding: 20,
        backgroundColor: '#342056',
        borderTopWidth: 1,
        borderTopColor: 'rgba(255,255,255,0.1)',
    },
    button: {
        padding: 16,
        borderRadius: 12,
        alignItems: 'center',
        marginBottom: 10,
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
        fontSize: 18,
        fontWeight: 'bold',
    },
    skipButton: {
        padding: 12,
        alignItems: 'center',
    },
    skipText: {
        color: '#ccc',
        fontSize: 14,
        textDecorationLine: 'underline',
    },
});
