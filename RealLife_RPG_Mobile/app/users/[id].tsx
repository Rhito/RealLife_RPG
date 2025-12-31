import { View, Text, StyleSheet, Image, ActivityIndicator, ScrollView, TouchableOpacity } from 'react-native';
import { useLocalSearchParams, useRouter } from 'expo-router';
import { useEffect, useState } from 'react';
import { getUserProfile } from '../../services/profile';
import { Ionicons } from '@expo/vector-icons';
import { Card } from '../../components/Card';
import { useAuth } from '../../context/AuthContext';

interface PublicProfile {
    id: number;
    name: string;
    avatar?: string;
    level: number;
    exp: number;
    hp: number;
    max_hp: number;
    created_at: string;
    stats?: {
        tasks_completed: number;
        achievements_unlocked: number;
    }
}

export default function UserProfileScreen() {
    const { id } = useLocalSearchParams();
    const router = useRouter();
    const [profile, setProfile] = useState<PublicProfile | null>(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const { user } = useAuth(); // To check if viewing self

    useEffect(() => {
        if (id) {
            fetchUser();
        }
    }, [id]);

    const fetchUser = async () => {
        try {
            const data = await getUserProfile(Number(id));
            setProfile(data);
        } catch (e: any) {
            setError(e.message || 'Failed to load profile');
        } finally {
            setLoading(false);
        }
    };

    if (loading) {
        return (
            <View style={styles.center}>
                <ActivityIndicator size="large" color="#007AFF" />
            </View>
        );
    }

    if (error || !profile) {
        return (
            <View style={styles.center}>
                <Text style={styles.errorText}>{error || 'User not found'}</Text>
                <TouchableOpacity onPress={router.back} style={styles.backButton}>
                    <Text style={styles.backButtonText}>Go Back</Text>
                </TouchableOpacity>
            </View>
        );
    }

    const isMe = user?.id === profile.id;

    return (
        <ScrollView style={styles.container}>
            <View style={styles.header}>
                <TouchableOpacity style={styles.headerBackButton} onPress={() => router.back()}>
                    <Ionicons name="arrow-back" size={24} color="#333" />
                </TouchableOpacity>
                <Image 
                    source={{ uri: profile.avatar || 'https://api.dicebear.com/7.x/avataaars/png?seed=User' }} 
                    style={styles.avatar} 
                />
                <Text style={styles.name}>{profile.name}</Text>
                <Text style={styles.level}>Level {profile.level}</Text>
                
                {isMe && (
                     <TouchableOpacity style={styles.editButton} onPress={() => router.push('/settings')}>
                         <Ionicons name="settings-outline" size={20} color="white" />
                         <Text style={styles.editButtonText}>Edit Profile</Text>
                     </TouchableOpacity>
                )}
            </View>

            <View style={styles.statsContainer}>
                <Card>
                    <Text style={styles.sectionTitle}>Stats</Text>
                    <View style={styles.statRow}>
                        <View style={styles.statItem}>
                            <Ionicons name="heart" size={24} color="#ef4444" />
                            <Text style={styles.statValue}>{profile.hp}/{profile.max_hp}</Text>
                            <Text style={styles.statLabel}>Health</Text>
                        </View>
                        <View style={styles.statItem}>
                            <Ionicons name="star" size={24} color="#eab308" />
                            <Text style={styles.statValue}>{profile.exp}</Text>
                            <Text style={styles.statLabel}>Exp</Text>
                        </View>
                    </View>
                </Card>
            </View>

            <View style={styles.achievementsContainer}>
                <Card>
                    <Text style={styles.sectionTitle}>Accomplishments</Text>
                    <View style={styles.listItem}>
                        <Text style={styles.listLabel}>Tasks Completed</Text>
                        <Text style={styles.listValue}>{profile.stats?.tasks_completed ?? 0}</Text>
                    </View>
                    <View style={styles.separator} />
                    <View style={styles.listItem}>
                        <Text style={styles.listLabel}>Achievements</Text>
                        <Text style={styles.listValue}>{profile.stats?.achievements_unlocked ?? 0}</Text>
                    </View>
                     <View style={styles.separator} />
                    <View style={styles.listItem}>
                        <Text style={styles.listLabel}>Joined</Text>
                        <Text style={styles.listValue}>{new Date(profile.created_at).toLocaleDateString()}</Text>
                    </View>
                </Card>
            </View>
        </ScrollView>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#f5f5f5',
    },
    center: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
    },
    header: {
        alignItems: 'center',
        padding: 30,
        backgroundColor: '#fff',
        borderBottomWidth: 1,
        borderBottomColor: '#eee',
    },
    avatar: {
        width: 100,
        height: 100,
        borderRadius: 50,
        marginBottom: 15,
        backgroundColor: '#eee',
    },
    name: {
        fontSize: 24,
        fontWeight: 'bold',
        color: '#333',
        marginBottom: 5,
    },
    level: {
        fontSize: 16,
        color: '#666',
        marginBottom: 15,
    },
    editButton: {
        flexDirection: 'row',
        backgroundColor: '#007AFF',
        paddingHorizontal: 20,
        paddingVertical: 10,
        borderRadius: 20,
        alignItems: 'center',
        gap: 5,
    },
    editButtonText: {
        color: 'white',
        fontWeight: '600',
    },
    statsContainer: {
        padding: 20,
    },
    sectionTitle: {
        fontSize: 18,
        fontWeight: 'bold',
        marginBottom: 15,
        color: '#333',
    },
    statRow: {
        flexDirection: 'row',
        justifyContent: 'space-around',
    },
    statItem: {
        alignItems: 'center',
    },
    statValue: {
        fontSize: 18,
        fontWeight: 'bold',
        marginTop: 5,
        color: '#333',
    },
    statLabel: {
        fontSize: 14,
        color: '#666',
    },
    achievementsContainer: {
        paddingHorizontal: 20,
        paddingBottom: 20,
    },
    listItem: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        paddingVertical: 12,
    },
    listLabel: {
        fontSize: 16,
        color: '#444',
    },
    listValue: {
        fontSize: 16,
        fontWeight: 'bold',
        color: '#333',
    },
    separator: {
        height: 1,
        backgroundColor: '#f0f0f0',
    },
    errorText: {
        fontSize: 16,
        color: 'red',
        marginBottom: 20,
    },
    backButton: {
        padding: 10,
    },
    backButtonText: {
        color: '#007AFF',
        fontSize: 16,
    },
    headerBackButton: {
        position: 'absolute',
        top: 20, // Adjust based on SafeArea or StatusBar
        left: 20,
        zIndex: 10,
        padding: 8,
        backgroundColor: 'rgba(255,255,255,0.8)',
        borderRadius: 20,
    }
});
