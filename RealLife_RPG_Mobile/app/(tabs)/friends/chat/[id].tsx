import React, { useEffect, useState, useRef, useCallback } from 'react';
import { View, Text, TextInput, TouchableOpacity, FlatList, StyleSheet, KeyboardAvoidingView, Platform, ActivityIndicator, Alert } from 'react-native';
import { useLocalSearchParams, useRouter, Stack } from 'expo-router';
import { getMessages, sendMessage, Message } from '../../../../services/MessageService';
import { useAuth } from '../../../../context/AuthContext';
import { useAlert } from '../../../../context/AlertContext';
import { Ionicons } from '@expo/vector-icons';
import * as SecureStore from 'expo-secure-store';
import { reverb } from '../../../../utils/websocket';

const MessageItem = React.memo(({ item, isMyMessage }: { item: Message, isMyMessage: boolean }) => (
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
));

export default function ChatScreen() {
    const { id, name } = useLocalSearchParams();
    const router = useRouter();
    const { showAlert } = useAlert();
    const { user } = useAuth();
    const [messages, setMessages] = useState<Message[]>([]);
    const [newMessage, setNewMessage] = useState('');
    const [loading, setLoading] = useState(true);
    const [sending, setSending] = useState(false);
    const [wsConnected, setWsConnected] = useState(false);
    const flatListRef = useRef<FlatList>(null);
    const friendId = Number(id);

    // Check Privacy for AI
    useEffect(() => {
        if (friendId === 0) {
            SecureStore.getItemAsync('HAS_ACCEPTED_PRIVACY_POLICY').then(accepted => {
                if (accepted !== 'true') {
                    Alert.alert(
                        'Privacy Policy Required',
                        'To use the AI Chat feature, you must review and accept our Privacy & Data Policy.',
                        [
                            { text: 'Cancel', style: 'cancel', onPress: () => router.back() },
                            { text: 'Review Policy', onPress: () => router.push('/privacy-policy') }
                        ]
                    );
                }
            });
        }
    }, [friendId]);

    // Fetch messages
    const fetchMessages = async () => {
        // Don't fetch if user is logged out
        if (!user) {
            setLoading(false);
            return;
        }
        
        try {
            const data = await getMessages(friendId);
            setMessages(data);
        } catch (error: any) {
            // Ignore 401 errors (user logged out)
            if (error?.response?.status !== 401) {
                console.error('Failed to load messages', error);
            }
        } finally {
            setLoading(false);
        }
    };

    // WebSocket subscription for real-time messages
    useEffect(() => {
        fetchMessages();
        
        // AI Bot (ID 0) doesn't use real-time
        if (friendId === 0 || !user) return;

        let channel: any = null;
        let pollingInterval: ReturnType<typeof setInterval> | null = null;

        const setupWebSocket = async () => {
            try {
                await reverb.connect();
                setWsConnected(true);
                
                // Subscribe to my private channel to receive messages
                channel = await reverb.subscribePrivate(`chat.${user.id}`);
                
                channel.listen('MessageSent', (event: any) => {
                    console.log('[WS] New message event:', event);
                    const incomingMessage = event.message;
                    
                    console.log(`[WS] Checking sender: ${incomingMessage.sender_id} vs Friend: ${friendId}`);

                    // Only append if from current chat partner
                    if (Number(incomingMessage.sender_id) === Number(friendId)) {
                        console.log('[WS] Sender matches! Updating UI...');
                        setMessages(prev => {
                            if (prev.some(m => m.id === incomingMessage.id)) {
                                console.log('[WS] Message already exists in state, skipping.');
                                return prev;
                            }
                            console.log('[WS] Adding new message to state.');
                            return [...prev, incomingMessage];
                        });
                        setTimeout(() => flatListRef.current?.scrollToEnd({ animated: true }), 100);
                    } else {
                        console.log('[WS] Sender mismatch - verification failed.');
                    }
                });
                
            } catch (error) {
                console.warn('[WS] Failed to connect, falling back to polling:', error);
                setWsConnected(false);
                // Fallback to polling
                pollingInterval = setInterval(fetchMessages, 5000);
            }
        };

        setupWebSocket();

        return () => {
            if (channel) {
                channel.unsubscribe();
            }
            if (pollingInterval) {
                clearInterval(pollingInterval);
            }
        };
    }, [friendId, user]);

    const handleSend = async () => {
        if (!newMessage.trim()) return;
        
        // Double check policy for AI before sending
        if (friendId === 0) {
            const accepted = await SecureStore.getItemAsync('HAS_ACCEPTED_PRIVACY_POLICY');
            if (accepted !== 'true') {
                 router.push('/privacy-policy');
                 return;
            }
        }

        const contentToSend = newMessage;
        setNewMessage(''); // Clear input immediately for better UX
        setSending(true);

        // Optimistic update for User's message
        const optimisticMsg: Message = {
            id: Date.now(),
            sender_id: user?.id || 999,
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
                const aiReply = message; // Rename for clarity
                setMessages(prev => [...prev, aiReply]);
            } else {
                setMessages(prev => [...prev, message]);
            }

            // Scroll to bottom
            setTimeout(() => flatListRef.current?.scrollToEnd({ animated: true }), 100);
        } catch (error: any) {
            showAlert('Error', error.message || 'Failed to send message');
        } finally {
            setSending(false);
        }
    };

    const renderItem = useCallback(({ item }: { item: Message }) => (
        <MessageItem item={item} isMyMessage={item.sender_id !== friendId} />
    ), [friendId]);

    return (
        <KeyboardAvoidingView 
            style={styles.container} 
            behavior={Platform.OS === 'ios' ? 'padding' : 'padding'} 
            keyboardVerticalOffset={Platform.OS === 'ios' ? 90 : 100}
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
                    initialNumToRender={15}
                    maxToRenderPerBatch={10}
                    windowSize={10}
                    removeClippedSubviews={Platform.OS === 'android'}
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
