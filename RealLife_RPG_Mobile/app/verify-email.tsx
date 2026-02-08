import React, { useState } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, ActivityIndicator } from 'react-native';
import { useRouter } from 'expo-router';
import { useAuth } from '../context/AuthContext';
import { Ionicons } from '@expo/vector-icons';
import api from '../services/api';

export default function VerifyEmailScreen() {
    const router = useRouter();
    const { user, logout, updateUser } = useAuth();
    const [sending, setSending] = useState(false);
    const [sent, setSent] = useState(false);
    const [checking, setChecking] = useState(false);

    const handleResend = async () => {
        setSending(true);
        try {
            await api.post('/email/verify-notification');
            setSent(true);
        } catch (error) {
            console.error('Failed to resend verification email', error);
        } finally {
            setSending(false);
        }
    };

    const handleCheckStatus = async () => {
        setChecking(true);
        try {
            const response = await api.get('/email/verify-status');
            if (response.data.verified) {
                // Update user context with verified status
                const updatedUser = { ...user, email_verified_at: new Date().toISOString() };
                await updateUser(updatedUser);
                // Navigation will happen automatically via _layout.tsx
            }
        } catch (error) {
            console.error('Failed to check status', error);
        } finally {
            setChecking(false);
        }
    };

    return (
        <View style={styles.container}>
            <Ionicons name="mail-outline" size={80} color="#FF9800" />
            <Text style={styles.title}>Verify Your Email</Text>
            <Text style={styles.subtitle}>
                We've sent a verification link to:
            </Text>
            <Text style={styles.email}>{user?.email}</Text>
            
            <Text style={styles.instructions}>
                Please check your inbox and click the verification link to continue.
            </Text>

            {sent && (
                <Text style={styles.success}>Verification email sent!</Text>
            )}

            <TouchableOpacity 
                style={styles.button} 
                onPress={handleResend}
                disabled={sending}
            >
                {sending ? (
                    <ActivityIndicator color="#fff" />
                ) : (
                    <Text style={styles.buttonText}>Resend Email</Text>
                )}
            </TouchableOpacity>

            <TouchableOpacity 
                style={styles.secondaryButton} 
                onPress={handleCheckStatus}
                disabled={checking}
            >
                {checking ? (
                    <ActivityIndicator color="#FF9800" />
                ) : (
                    <Text style={styles.secondaryButtonText}>I've Verified, Continue</Text>
                )}
            </TouchableOpacity>

            <TouchableOpacity 
                style={styles.logoutButton} 
                onPress={logout}
            >
                <Text style={styles.logoutText}>Logout</Text>
            </TouchableOpacity>
        </View>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#1a1a2e',
        justifyContent: 'center',
        alignItems: 'center',
        padding: 20,
    },
    title: {
        fontSize: 28,
        fontWeight: 'bold',
        color: '#fff',
        marginTop: 20,
        marginBottom: 10,
    },
    subtitle: {
        fontSize: 16,
        color: '#aaa',
        textAlign: 'center',
    },
    email: {
        fontSize: 16,
        color: '#FF9800',
        fontWeight: 'bold',
        marginTop: 5,
        marginBottom: 20,
    },
    instructions: {
        fontSize: 14,
        color: '#888',
        textAlign: 'center',
        marginBottom: 30,
        paddingHorizontal: 20,
    },
    success: {
        fontSize: 14,
        color: '#4CAF50',
        marginBottom: 20,
    },
    button: {
        backgroundColor: '#FF9800',
        paddingVertical: 15,
        paddingHorizontal: 40,
        borderRadius: 25,
        marginBottom: 15,
    },
    buttonText: {
        color: '#fff',
        fontSize: 16,
        fontWeight: 'bold',
    },
    secondaryButton: {
        borderWidth: 1,
        borderColor: '#FF9800',
        paddingVertical: 15,
        paddingHorizontal: 40,
        borderRadius: 25,
        marginBottom: 30,
    },
    secondaryButtonText: {
        color: '#FF9800',
        fontSize: 16,
    },
    logoutButton: {
        marginTop: 20,
    },
    logoutText: {
        color: '#666',
        fontSize: 14,
    },
});
