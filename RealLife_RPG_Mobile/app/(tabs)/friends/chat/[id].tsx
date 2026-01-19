import React, { useEffect, useState, useRef } from 'react';
import { View, Text, TextInput, TouchableOpacity, FlatList, StyleSheet, KeyboardAvoidingView, Platform, ActivityIndicator } from 'react-native';
import { useLocalSearchParams, useRouter, Stack } from 'expo-router';
import { getMessages, sendMessage, Message } from '../../../../services/MessageService';
import { useAuth } from '../../../../context/AuthContext';
import { useAlert } from '../../../../context/AlertContext';
import { Ionicons } from '@expo/vector-icons';

export default function ChatScreen() {
    const { id, name } = useLocalSearchParams();
    const router = useRouter();
    const { showAlert } = useAlert();
    const { user } = useAuth(); // Call hook here at top level
    const [messages, setMessages] = useState<Message[]>([]);
    const [newMessage, setNewMessage] = useState('');
    const [loading, setLoading] = useState(true);
    const [sending, setSending] = useState(false);
    const flatListRef = useRef<FlatList>(null);
    const friendId = Number(id);

    // Fetch messages
    const fetchMessages = async () => {
        try {
            const data = await getMessages(friendId);
            setMessages(data);
        } catch (error) {
            console.error('Failed to load messages', error);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchMessages();
        
        // Disable polling for AI Bot (ID 0) as it has no persistence/history yet
        if (friendId === 0) return;

        // Poll for new messages every 5 seconds (temporary solution until websockets)
        const interval = setInterval(fetchMessages, 5000);
        return () => clearInterval(interval);
    }, [friendId]);

    const handleSend = async () => {
        if (!newMessage.trim()) return;
        
        const contentToSend = newMessage;
        setNewMessage(''); // Clear input immediately for better UX
        setSending(true);

        // Optimistic update for User's message
        const optimisticMsg: Message = {
            id: Date.now(),
            sender_id: user?.id || 999, // Use stored user variable
            receiver_id: friendId,
            content: contentToSend,
            read_at: null,
            created_at: new Date().toISOString(),
            updated_at: new Date().toISOString(),
        };

        if (friendId === 0) {
            setMessages(prev => [...prev, optimisticMsg]);
        }

        try {
            const message = await sendMessage(friendId, contentToSend);
            
            if (friendId === 0) {
                // For AI, the response is the AI's reply
                setMessages(prev => [...prev, message]);
            } else {
                // For normal chat, the response is the saved message
                // We reload to ensure sync or replace optimistic
                // For now, simpler to just fetch or append if valid
                setMessages(prev => [...prev, message]);
            }

            // Scroll to bottom
            setTimeout(() => flatListRef.current?.scrollToEnd({ animated: true }), 100);
        } catch (error: any) {
             // Remove optimistic message on fail if strictly managing state
            showAlert('Error', error.message || 'Failed to send message');
        } finally {
            setSending(false);
        }
    };

    const renderItem = ({ item }: { item: Message }) => {
        const isMyMessage = item.sender_id !== friendId; // If sender is not friend, it's me
        
        return (
            <View style={[
                styles.messageContainer, 
                isMyMessage ? styles.myMessage : styles.theirMessage
            ]}>
                <Text style={[
                    styles.messageText,
                    isMyMessage ? styles.myMessageText : styles.theirMessageText
                ]}>{item.content}</Text>
                <Text style={[
                    styles.timestamp, 
                    isMyMessage ? styles.myTimestamp : styles.theirTimestamp
                ]}>
                    {new Date(item.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                </Text>
            </View>
        );
    };

    return (
        <KeyboardAvoidingView 
            style={styles.container} 
            behavior={Platform.OS === 'ios' ? 'padding' : undefined} 
            keyboardVerticalOffset={Platform.OS === 'ios' ? 90 : 0}
        >
            <Stack.Screen options={{ 
                title: (name as string) || 'Chat',
                headerBackTitle: '',
                headerStyle: { backgroundColor: '#432874' },
                headerTintColor: '#fff',
            }} />
            
            {loading ? (
                <View style={styles.center}>
                    <ActivityIndicator size="large" color="#FF9800" />
                </View>
            ) : (
                <FlatList
                    ref={flatListRef}
                    data={messages}
                    renderItem={renderItem}
                    keyExtractor={(item) => item.id.toString()}
                    contentContainerStyle={styles.listContent}
                    onContentSizeChange={() => flatListRef.current?.scrollToEnd({ animated: true })}
                />
            )}

            <View style={styles.inputContainer}>
                <TextInput
                    style={styles.input}
                    value={newMessage}
                    onChangeText={setNewMessage}
                    placeholder="Type a message..."
                    placeholderTextColor="#BBAADD"
                    multiline
                />
                <TouchableOpacity 
                    style={[styles.sendButton, !newMessage.trim() && styles.sendButtonDisabled]} 
                    onPress={handleSend}
                    disabled={!newMessage.trim() || sending}
                >
                    {sending ? (
                         <ActivityIndicator size="small" color="#FFF" />
                    ) : (
                        <Ionicons name="send" size={20} color="#FFF" />
                    )}
                </TouchableOpacity>
            </View>
        </KeyboardAvoidingView>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#342056', // Deep Purple Background
    },
    center: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
    },
    listContent: {
        padding: 16,
        paddingBottom: 20,
    },
    messageContainer: {
        maxWidth: '80%',
        padding: 12,
        borderRadius: 16,
        marginBottom: 8,
    },
    myMessage: {
        alignSelf: 'flex-end',
        backgroundColor: '#4F46E5', // Indigo
        borderBottomRightRadius: 4,
    },
    theirMessage: {
        alignSelf: 'flex-start',
        backgroundColor: '#4a3b72', // Darker purple tint
        borderBottomLeftRadius: 4,
    },
    messageText: {
        fontSize: 16,
    },
    myMessageText: {
        color: '#FFFFFF',
    },
    theirMessageText: {
        color: '#FFFFFF', // White text for improved contrast on dark purple
    },
    timestamp: {
        fontSize: 10,
        marginTop: 4,
        alignSelf: 'flex-end',
    },
    myTimestamp: {
        color: 'rgba(255,255,255,0.7)',
    },
    theirTimestamp: {
        color: 'rgba(255,255,255,0.5)',
    },
    inputContainer: {
        flexDirection: 'row',
        padding: 16,
        backgroundColor: '#432874', // Match Header
        borderTopWidth: 1,
        borderTopColor: 'rgba(255,255,255,0.1)',
        alignItems: 'center',
    },
    input: {
        flex: 1,
        backgroundColor: 'rgba(255,255,255,0.1)',
        borderRadius: 20,
        paddingHorizontal: 16,
        paddingVertical: 10,
        marginRight: 10,
        maxHeight: 100,
        fontSize: 16,
        color: '#fff',
    },
    sendButton: {
        backgroundColor: '#FF9800', // Gold Accent
        width: 40,
        height: 40,
        borderRadius: 20,
        justifyContent: 'center',
        alignItems: 'center',
    },
    sendButtonDisabled: {
        backgroundColor: 'rgba(255,255,255,0.2)',
    },
});
