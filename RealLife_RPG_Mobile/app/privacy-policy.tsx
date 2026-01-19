import { View, Text, StyleSheet, ScrollView, TouchableOpacity } from 'react-native';
import { useRouter } from 'expo-router';
import { useAuth } from '../context/AuthContext';
import { Ionicons } from '@expo/vector-icons';
import { useState } from 'react';
import * as SecureStore from 'expo-secure-store';

export default function PrivacyPolicyScreen() {
    const { user } = useAuth();
    const router = useRouter();
    const [accepted, setAccepted] = useState(false);

    const handleAccept = async () => {
        setAccepted(true);
        await SecureStore.setItemAsync('HAS_ACCEPTED_PRIVACY_POLICY', 'true');
        setTimeout(() => router.back(), 500);
    };

    return (
        <View style={styles.container}>
            <View style={styles.header}>
                <TouchableOpacity onPress={() => router.back()} style={styles.backButton}>
                    <Ionicons name="arrow-back" size={24} color="#FFF" />
                </TouchableOpacity>
                <Text style={styles.headerTitle}>Privacy & Data</Text>
            </View>

            <ScrollView style={styles.content} contentContainerStyle={{ paddingBottom: 40 }}>
                <Text style={styles.sectionTitle}>1. Data Transparency</Text>
                <Text style={styles.text}>
                    We believe in full transparency. Here is the data currently associated with your account in RealLife RPG:
                </Text>

                <View style={styles.dataCard}>
                    <Text style={styles.dataLabel}>User ID: <Text style={styles.dataValue}>{user?.id}</Text></Text>
                    <Text style={styles.dataLabel}>Name: <Text style={styles.dataValue}>{user?.name}</Text></Text>
                    <Text style={styles.dataLabel}>Email: <Text style={styles.dataValue}>{user?.email}</Text></Text>
                    <Text style={styles.dataLabel}>Level: <Text style={styles.dataValue}>{user?.level}</Text></Text>
                    <Text style={styles.dataLabel}>Experience: <Text style={styles.dataValue}>{user?.exp} XP</Text></Text>
                    <Text style={styles.dataLabel}>Gold: <Text style={styles.dataValue}>{user?.coins}</Text></Text>
                </View>

                <Text style={styles.sectionTitle}>2. How We Use Your Data</Text>
                <Text style={styles.text}>
                    • <Text style={styles.bold}>Game Progress:</Text> Your level, XP, and inventory are stored to maintain your game state across devices.
                </Text>
                <Text style={styles.text}>
                    • <Text style={styles.bold}>Social Features:</Text> Your name and avatar are visible to your friends and on the leaderboard.
                </Text>
                <Text style={styles.text}>
                    • <Text style={styles.bold}>Analytics:</Text> We track quest completion rates to balance the game difficulty.
                </Text>

                <Text style={styles.sectionTitle}>3. Your Rights</Text>
                <Text style={styles.text}>
                    You have the right to request deletion of your data at any time by contacting support or using the "Delete Account" option in settings (coming soon).
                </Text>

                <View style={{ height: 20 }} />
            </ScrollView>

            <View style={styles.footer}>
                <TouchableOpacity 
                    style={[styles.acceptButton, accepted && styles.acceptedButton]} 
                    onPress={handleAccept}
                    disabled={accepted}
                >
                    <Text style={styles.acceptbuttonText}>
                        {accepted ? "Accepted" : "I Accept the Policy"}
                    </Text>
                    {accepted && <Ionicons name="checkmark" size={24} color="#FFF" style={{ marginLeft: 10 }} />}
                </TouchableOpacity>
            </View>
        </View>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#342056',
    },
    header: {
        paddingTop: 60,
        paddingBottom: 20,
        paddingHorizontal: 20,
        backgroundColor: '#432874',
        flexDirection: 'row',
        alignItems: 'center',
    },
    backButton: {
        padding: 5,
        marginRight: 15,
    },
    headerTitle: {
        fontSize: 20,
        fontWeight: 'bold',
        color: '#FFF',
    },
    content: {
        flex: 1,
        padding: 20,
    },
    sectionTitle: {
        fontSize: 18,
        fontWeight: 'bold',
        color: '#FF9800',
        marginTop: 20,
        marginBottom: 10,
    },
    text: {
        fontSize: 16,
        color: '#BBAADD',
        lineHeight: 24,
        marginBottom: 10,
    },
    bold: {
        fontWeight: 'bold',
        color: '#FFF',
    },
    dataCard: {
        backgroundColor: 'rgba(0,0,0,0.2)',
        padding: 15,
        borderRadius: 10,
        borderWidth: 1,
        borderColor: '#5B4290',
        marginVertical: 10,
    },
    dataLabel: {
        color: '#BBAADD',
        fontSize: 14,
        marginBottom: 5,
    },
    dataValue: {
        color: '#FFF',
        fontWeight: 'bold',
    },
    footer: {
        padding: 20,
        backgroundColor: '#432874',
        borderTopWidth: 1,
        borderTopColor: '#5B4290',
    },
    acceptButton: {
        backgroundColor: '#FF9800',
        padding: 16,
        borderRadius: 12,
        alignItems: 'center',
        flexDirection: 'row',
        justifyContent: 'center',
    },
    acceptedButton: {
        backgroundColor: '#4CAF50',
    },
    acceptbuttonText: {
        color: '#FFF',
        fontWeight: 'bold',
        fontSize: 16,
    }
});
