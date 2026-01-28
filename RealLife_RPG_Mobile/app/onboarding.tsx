import { View, Text, StyleSheet, TouchableOpacity, ScrollView, Image, ActivityIndicator } from 'react-native';
import { useState } from 'react';
import { useRouter } from 'expo-router';
import { Ionicons } from '@expo/vector-icons';
import { Colors } from '../constants/theme';
import { seedOnboarding } from '../services/auth';
import { useAuth } from '../context/AuthContext';
import { useAlert } from '../context/AlertContext';

type PlanId = 'student' | 'fitness' | 'developer' | 'entrepreneur' | null;

interface Plan {
    id: PlanId;
    title: string;
    description: string;
    icon: any;
    color: string;
}

const PLANS: Plan[] = [
    {
        id: 'student',
        title: 'Student Path',
        description: 'Study routines, homework tracking, and reading habits',
        icon: 'school',
        color: '#4CAF50',
    },
    {
        id: 'fitness',
        title: 'Fitness Journey',
        description: 'Workouts, hydration, stretching, and meal tracking',
        icon: 'fitness',
        color: '#FF5722',
    },
    {
        id: 'developer',
        title: 'Developer Quest',
        description: 'Coding practice, learning, and open source contributions',
        icon: 'code-slash',
        color: '#2196F3',
    },
    {
        id: 'entrepreneur',
        title: 'Business Builder',
        description: 'Goal planning, networking, and financial tracking',
        icon: 'briefcase',
        color: '#FF9800',
    },
];

export default function OnboardingScreen() {
    const router = useRouter();
    const { setUser, user } = useAuth();
    const { showAlert } = useAlert();
    const [selectedPlan, setSelectedPlan] = useState<PlanId>(null);
    const [loading, setLoading] = useState(false);

    const handleContinue = async () => {
        setLoading(true);
        try {
            const response = await seedOnboarding(selectedPlan);
            
            // Update user in context
            if (user) {
                setUser({ ...user, is_onboarded: true });
            }

            showAlert('Welcome!', selectedPlan ? 'Your tasks have been created!' : 'You can create tasks anytime from the Quest Log.');
            router.replace('/(tabs)');
        } catch (e: any) {
            showAlert('Error', e.message || 'Failed to complete onboarding');
        } finally {
            setLoading(false);
        }
    };

    const handleSkip = async () => {
        setLoading(true);
        try {
            await seedOnboarding(null);
            
            if (user) {
                setUser({ ...user, is_onboarded: true });
            }

            router.replace('/(tabs)');
        } catch (e: any) {
            showAlert('Error', e.message || 'Failed to skip onboarding');
        } finally {
            setLoading(false);
        }
    };

    return (
        <View style={styles.container}>
            <View style={styles.header}>
                <Text style={styles.title}>Choose Your Path</Text>
                <Text style={styles.subtitle}>Select a template to get started with pre-made quests, or start from scratch</Text>
            </View>

            <ScrollView style={styles.scrollView} contentContainerStyle={styles.scrollContent}>
                {PLANS.map((plan) => (
                    <TouchableOpacity
                        key={plan.id}
                        style={[
                            styles.planCard,
                            selectedPlan === plan.id && { borderColor: plan.color, borderWidth: 3 }
                        ]}
                        onPress={() => setSelectedPlan(plan.id)}
                    >
                        <View style={[styles.iconContainer, { backgroundColor: plan.color }]}>
                            <Ionicons name={plan.icon} size={32} color="white" />
                        </View>
                        <View style={styles.planContent}>
                            <Text style={styles.planTitle}>{plan.title}</Text>
                            <Text style={styles.planDesc}>{plan.description}</Text>
                        </View>
                        {selectedPlan === plan.id && (
                            <Ionicons name="checkmark-circle" size={28} color={plan.color} />
                        )}
                    </TouchableOpacity>
                ))}
            </ScrollView>

            <View style={styles.footer}>
                <TouchableOpacity 
                    style={styles.skipButton} 
                    onPress={handleSkip}
                    disabled={loading}
                >
                    <Text style={styles.skipText}>Start from Scratch</Text>
                </TouchableOpacity>

                <TouchableOpacity 
                    style={[styles.continueButton, !selectedPlan && styles.continueButtonDisabled]} 
                    onPress={handleContinue}
                    disabled={!selectedPlan || loading}
                >
                    {loading ? (
                        <ActivityIndicator color="white" />
                    ) : (
                        <Text style={styles.continueText}>Begin My Journey</Text>
                    )}
                </TouchableOpacity>
            </View>
        </View>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: Colors.rpg.background,
    },
    header: {
        paddingHorizontal: 20,
        paddingTop: 60,
        paddingBottom: 30,
        backgroundColor: '#432874',
    },
    title: {
        fontSize: 32,
        fontWeight: 'bold',
        color: 'white',
        marginBottom: 8,
    },
    subtitle: {
        fontSize: 16,
        color: '#BBAADD',
        lineHeight: 22,
    },
    scrollView: {
        flex: 1,
    },
    scrollContent: {
        padding: 20,
        paddingBottom: 180,
    },
    planCard: {
        flexDirection: 'row',
        alignItems: 'center',
        backgroundColor: 'white',
        borderRadius: 16,
        padding: 16,
        marginBottom: 12,
        elevation: 2,
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.1,
        shadowRadius: 4,
        borderWidth: 2,
        borderColor: 'transparent',
    },
    iconContainer: {
        width: 60,
        height: 60,
        borderRadius: 30,
        justifyContent: 'center',
        alignItems: 'center',
        marginRight: 16,
    },
    planContent: {
        flex: 1,
    },
    planTitle: {
        fontSize: 18,
        fontWeight: 'bold',
        color: '#333',
        marginBottom: 4,
    },
    planDesc: {
        fontSize: 14,
        color: '#666',
        lineHeight: 20,
    },
    footer: {
        position: 'absolute',
        bottom: 0,
        left: 0,
        right: 0,
        padding: 20,
        backgroundColor: Colors.rpg.background,
        borderTopWidth: 1,
        borderTopColor: 'rgba(255,255,255,0.1)',
    },
    skipButton: {
        backgroundColor: 'transparent',
        padding: 16,
        borderRadius: 12,
        alignItems: 'center',
        marginBottom: 10,
        borderWidth: 2,
        borderColor: '#BBAADD',
    },
    skipText: {
        color: '#BBAADD',
        fontSize: 16,
        fontWeight: 'bold',
    },
    continueButton: {
        backgroundColor: Colors.rpg.accent,
        padding: 18,
        borderRadius: 12,
        alignItems: 'center',
        elevation: 4,
    },
    continueButtonDisabled: {
        backgroundColor: '#666',
        opacity: 0.5,
    },
    continueText: {
        color: 'white',
        fontSize: 18,
        fontWeight: 'bold',
    },
});
