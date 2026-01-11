import React, { useState, useEffect } from 'react';
import { View, Text, TouchableOpacity, StyleSheet, Alert, ActivityIndicator } from 'react-native';
import { useRouter } from 'expo-router';
import { Ionicons } from '@expo/vector-icons';
import { checkEmailVerificationStatus, resendVerificationEmail } from '../services/auth';
import { useAuth } from '../context/AuthContext';
import { useAlert } from '../context/AlertContext';

export default function VerifyEmailScreen() {
  const [loading, setLoading] = useState(false);
  const [checking, setChecking] = useState(true);
  const [verified, setVerified] = useState(false);
  const router = useRouter();
  const { user, setUser, logout } = useAuth();
  const { showAlert } = useAlert();
  
  console.log('VerifyEmailScreen rendering. User ID:', user?.id);

  const checkStatus = async () => {
    try {
      const response = await checkEmailVerificationStatus();
      if (response.verified) {
        setVerified(true);
        
        // Critical: Update local user state to prevent redirect loop
        if (user) {
            setUser({ ...user, email_verified_at: new Date().toISOString() });
        }
        
        showAlert(
          '✅ Email Verified!',
          'Your account is now fully activated.',
          [{ text: 'Continue', onPress: () => router.replace('/(tabs)') }]
        );
      }
    } catch (error) {
      console.log('Not verified yet');
    } finally {
      setChecking(false);
    }
  };

  useEffect(() => {
    checkStatus();

    // Limit listening scope
    if (user?.id) {
        console.log(`Listening on App.Models.User.${user.id}`);
        // @ts-ignore
        import('../services/echo').then(({ default: echo }) => {
            echo.private(`App.Models.User.${user.id}`)
                .listen('UserVerified', (e: any) => {
                    console.log('UserVerified Event:', e);
                    setVerified(true);
                    
                    if (user) {
                         setUser({ ...user, email_verified_at: new Date().toISOString() });
                    }
                    
                    showAlert(
                        '✅ Email Verified!',
                         'Your account is now fully activated.',
                        [{ text: 'Continue', onPress: () => router.replace('/(tabs)') }]
                    );
                });
        });
        
        return () => {
             import('../services/echo').then(({ default: echo }) => {
                 echo.leave(`App.Models.User.${user?.id}`);
             });
        }
    }
  }, [user?.id]);

  const handleResend = async () => {
    setLoading(true);
    try {
      await resendVerificationEmail();
      showAlert('Success', 'Verification email sent! Please check your inbox.');
    } catch (e: any) {
      showAlert('Error', e.message || 'Failed to resend email');
    } finally {
      setLoading(false);
    }
  };

  if (checking) {
    return (
      <View style={styles.container}>
        <ActivityIndicator size="large" color="#FF9800" />
        <Text style={styles.checkingText}>Checking verification status...</Text>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <View style={styles.iconContainer}>
        <Ionicons name="mail-outline" size={80} color="#FF9800" />
      </View>

      <Text style={styles.title}>Verify Your Email</Text>
      <Text style={styles.description}>
        We've sent a verification link to{'\n'}
        <Text style={styles.email}>{user?.email}</Text>
      </Text>

      <Text style={styles.instructions}>
        Click the link in your email to activate your account and unlock all features.
      </Text>

      <TouchableOpacity 
        style={styles.button} 
        onPress={handleResend}
        disabled={loading}
      >
        {loading ? (
          <ActivityIndicator color="#fff" />
        ) : (
          <>
            <Ionicons name="refresh" size={20} color="#fff" style={{ marginRight: 8 }} />
            <Text style={styles.buttonText}>Resend Email</Text>
          </>
        )}
      </TouchableOpacity>

      <TouchableOpacity 
        style={styles.checkButton} 
        onPress={checkStatus}
      >
        <Ionicons name="checkmark-circle" size={20} color="#FF9800" style={{ marginRight: 8 }} />
        <Text style={styles.checkButtonText}>I've Verified</Text>
      </TouchableOpacity>

      {/* Mandatory Verification: Skip button removed */}
      <TouchableOpacity onPress={() => { logout(); }} style={styles.skipButton}>
        <Text style={styles.skipText}>Sign Out</Text>
      </TouchableOpacity>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#342056',
    padding: 24,
    justifyContent: 'center',
    alignItems: 'center',
  },
  checkingText: {
    color: '#BBAADD',
    marginTop: 16,
    fontSize: 16,
  },
  iconContainer: {
    width: 120,
    height: 120,
    borderRadius: 60,
    backgroundColor: 'rgba(255, 152, 0, 0.1)',
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 30,
  },
  title: {
    fontSize: 28,
    fontWeight: 'bold',
    color: '#fff',
    marginBottom: 16,
    textAlign: 'center',
  },
  description: {
    fontSize: 16,
    color: '#BBAADD',
    textAlign: 'center',
    marginBottom: 10,
    lineHeight: 24,
  },
  email: {
    color: '#FF9800',
    fontWeight: 'bold',
  },
  instructions: {
    fontSize: 14,
    color: '#BBAADD',
    textAlign: 'center',
    marginTop: 10,
    marginBottom: 40,
    paddingHorizontal: 20,
    lineHeight: 20,
  },
  button: {
    backgroundColor: '#FF9800',
    padding: 16,
    borderRadius: 12,
    alignItems: 'center',
    width: '100%',
    flexDirection: 'row',
    justifyContent: 'center',
    elevation: 4,
  },
  buttonText: {
    color: '#fff',
    fontWeight: 'bold',
    fontSize: 16,
  },
  checkButton: {
    backgroundColor: 'transparent',
    borderWidth: 2,
    borderColor: '#FF9800',
    padding: 16,
    borderRadius: 12,
    alignItems: 'center',
    width: '100%',
    flexDirection: 'row',
    justifyContent: 'center',
    marginTop: 12,
  },
  checkButtonText: {
    color: '#FF9800',
    fontWeight: 'bold',
    fontSize: 16,
  },
  skipButton: {
    marginTop: 20,
    padding: 10,
  },
  skipText: {
    color: '#BBAADD',
    fontSize: 14,
  },
});
