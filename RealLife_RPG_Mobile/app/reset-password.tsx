import React, { useState, useEffect } from 'react';
import { View, Text, TextInput, TouchableOpacity, StyleSheet, Alert, ActivityIndicator } from 'react-native';
import { useRouter, useLocalSearchParams } from 'expo-router';
import api from '../services/api';
import { useAuth } from '../context/AuthContext';
import { useAlert } from '../context/AlertContext';

export default function ResetPasswordScreen() {
  const [password, setPassword] = useState('');
  const [passwordConfirmation, setPasswordConfirmation] = useState('');
  const [loading, setLoading] = useState(false);
  const [verifying, setVerifying] = useState(true);
  const router = useRouter();
  const params = useLocalSearchParams();
  const { login } = useAuth();
  const { showAlert } = useAlert();

  const token = params.token as string;
  const email = params.email as string;

  useEffect(() => {
    if (!token || !email) {
      showAlert('Error', 'Invalid reset link', [
        { text: 'OK', onPress: () => router.replace('/login') }
      ]);
      return;
    }

    // Verify token is valid first
    verifyToken();
  }, [token, email]);

  const verifyToken = async () => {
    try {
      await api.post('/verify-reset-token', { token, email });
      setVerifying(false);
    } catch (e: any) {
      showAlert(
        'Invalid Link', 
        'This password reset link is invalid or has expired.',
        [{ text: 'OK', onPress: () => router.replace('/login') }]
      );
    }
  };

  const handleResetPassword = async () => {
    if (!password || !passwordConfirmation) {
      showAlert('Error', 'Please fill in all fields');
      return;
    }

    if (password !== passwordConfirmation) {
      showAlert('Error', 'Passwords do not match');
      return;
    }

    if (password.length < 8) {
      showAlert('Error', 'Password must be at least 8 characters');
      return;
    }

    setLoading(true);
    try {
      const response = await api.post('/reset-password', {
        token,
        email,
        password,
        password_confirmation: passwordConfirmation,
      });

      // Auto-login after successful reset
      if (response.data?.data?.token) {
        await login(email, password);
        showAlert('Success', 'Your password has been reset and you are now logged in!');
      } else {
        showAlert(
          'Success', 
          'Your password has been reset. Please login with your new password.',
          [{ text: 'OK', onPress: () => router.replace('/login') }]
        );
      }
    } catch (e: any) {
      console.error('Reset password error:', e);
      const errorMessage = e.response?.data?.message || e.message || 'Failed to reset password';
      showAlert('Error', errorMessage);
    } finally {
      setLoading(false);
    }
  };

  if (verifying) {
    return (
      <View style={styles.container}>
        <ActivityIndicator size="large" color="#007AFF" />
        <Text style={styles.verifyingText}>Verifying reset link...</Text>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Reset Password</Text>
      <Text style={styles.subtitle}>
        Enter your new password below
      </Text>

      <TextInput
        style={styles.input}
        placeholder="New Password"
        value={password}
        onChangeText={setPassword}
        secureTextEntry
      />

      <TextInput
        style={styles.input}
        placeholder="Confirm Password"
        value={passwordConfirmation}
        onChangeText={setPasswordConfirmation}
        secureTextEntry
      />

      <TouchableOpacity 
        style={[styles.button, loading && styles.buttonDisabled]} 
        onPress={handleResetPassword} 
        disabled={loading}
      >
        {loading ? (
          <ActivityIndicator color="#fff" />
        ) : (
          <Text style={styles.buttonText}>Reset Password</Text>
        )}
      </TouchableOpacity>

      <TouchableOpacity 
        style={styles.backButton} 
        onPress={() => router.replace('/login')}
      >
        <Text style={styles.backText}>Cancel</Text>
      </TouchableOpacity>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    padding: 20,
    backgroundColor: '#f5f5f5',
  },
  title: {
    fontSize: 28,
    fontWeight: 'bold',
    marginBottom: 10,
    textAlign: 'center',
    color: '#333',
  },
  subtitle: {
    fontSize: 14,
    color: '#666',
    textAlign: 'center',
    marginBottom: 30,
    paddingHorizontal: 20,
  },
  input: {
    backgroundColor: '#fff',
    padding: 15,
    borderRadius: 10,
    marginBottom: 15,
    borderWidth: 1,
    borderColor: '#ddd',
  },
  button: {
    backgroundColor: '#007AFF',
    padding: 15,
    borderRadius: 10,
    alignItems: 'center',
    marginBottom: 10,
  },
  buttonDisabled: {
    backgroundColor: '#ccc',
  },
  buttonText: {
    color: '#fff',
    fontWeight: 'bold',
    fontSize: 16,
  },
  backButton: {
    padding: 15,
    alignItems: 'center',
  },
  backText: {
    color: '#007AFF',
    fontSize: 14,
  },
  verifyingText: {
    marginTop: 20,
    color: '#666',
    textAlign: 'center',
  },
});
