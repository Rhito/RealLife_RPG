import { View, Text, StyleSheet, TouchableOpacity, Alert, Animated } from 'react-native';
import { useState, useEffect, useRef } from 'react';
import { useLocalSearchParams, useRouter } from 'expo-router';
import { completeFocusTask } from '../../services/tasks';
import { Ionicons } from '@expo/vector-icons';
import { useAuth } from '../../context/AuthContext';

const FOCUS_TIME = 25 * 60; // 25 minutes

export default function FocusTimerScreen() {
    const { id, title } = useLocalSearchParams();
    const router = useRouter();
    const { user, setUser } = useAuth();
    
    const [timeLeft, setTimeLeft] = useState(FOCUS_TIME);
    const [isActive, setIsActive] = useState(false);
    const [isFinished, setIsFinished] = useState(false);
    
    
    useEffect(() => {
        let interval: any = null;
        if (isActive && timeLeft > 0) {
            interval = setInterval(() => {
                setTimeLeft((time) => time - 1);
            }, 1000);
        } else if (timeLeft === 0) {
            if (interval) clearInterval(interval);
            setIsActive(false);
            handleFinish();
        }
        return () => {
            if (interval) clearInterval(interval);
        };
    }, [isActive, timeLeft]);

    const handleFinish = async () => {
        setIsFinished(true);
        try {
            const res = await completeFocusTask(Number(id));
            if (user) {
                setUser({ ...user, ...res.rewards });
            }
            Alert.alert(
                'Focus Session Complete!', 
                `Awesome job! You earned bonus rewards.\n+${res.rewards.exp} XP (incl. bonus)\n+${res.rewards.coins} Coins`,
                [{ text: 'Great!', onPress: () => router.back() }]
            );
        } catch (e: any) {
            Alert.alert('Error', e.message || 'Failed to submit focus session.');
            setIsFinished(false);
        }
    };

    const toggleTimer = () => {
        setIsActive(!isActive);
    };

    const resetTimer = () => {
        setIsActive(false);
        setTimeLeft(FOCUS_TIME);
    };

    const formatTime = (seconds: number) => {
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins < 10 ? '0' : ''}${mins}:${secs < 10 ? '0' : ''}${secs}`;
    };

    return (
        <View style={styles.container}>
            <TouchableOpacity style={styles.backButton} onPress={() => router.back()}>
                <Ionicons name="close" size={30} color="#333" />
            </TouchableOpacity>

            <Text style={styles.taskTitle}>Focusing on:</Text>
            <Text style={styles.taskName}>{title}</Text>

            <View style={styles.timerContainer}>
                <Text style={styles.timerText}>{formatTime(timeLeft)}</Text>
            </View>

            <View style={styles.controls}>
                {!isActive && !isFinished && timeLeft !== 0 && (
                    <TouchableOpacity style={styles.playButton} onPress={toggleTimer}>
                        <Ionicons name="play" size={50} color="white" />
                    </TouchableOpacity>
                )}
                {isActive && (
                    <TouchableOpacity style={styles.pauseButton} onPress={toggleTimer}>
                        <Ionicons name="pause" size={50} color="white" />
                    </TouchableOpacity>
                )}
            </View>

            <View style={styles.footer}>
                 <TouchableOpacity onPress={resetTimer}>
                     <Text style={styles.resetText}>Reset</Text>
                 </TouchableOpacity>
            </View>
        </View>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#1a1a2e', // Dark mode for focus
        alignItems: 'center',
        justifyContent: 'center',
        padding: 20,
    },
    backButton: {
        position: 'absolute',
        top: 50,
        right: 20,
        backgroundColor: 'rgba(255,255,255,0.2)',
        padding: 8,
        borderRadius: 20,
    },
    taskTitle: {
        color: '#a0a0d4',
        fontSize: 18,
        marginBottom: 10,
    },
    taskName: {
        color: 'white',
        fontSize: 28,
        fontWeight: 'bold',
        textAlign: 'center',
        marginBottom: 50,
    },
    timerContainer: {
        width: 300,
        height: 300,
        borderRadius: 150,
        borderWidth: 5,
        borderColor: '#0f3460',
        alignItems: 'center',
        justifyContent: 'center',
        backgroundColor: '#16213e',
        marginBottom: 50,
        elevation: 10,
    },
    timerText: {
        color: 'white',
        fontSize: 60,
        fontWeight: 'bold',
        fontVariant: ['tabular-nums'],
    },
    controls: {
        flexDirection: 'row',
        marginBottom: 30,
    },
    playButton: {
        backgroundColor: '#e94560',
        width: 80,
        height: 80,
        borderRadius: 40,
        alignItems: 'center',
        justifyContent: 'center',
        elevation: 5,
    },
    pauseButton: {
        backgroundColor: '#0f3460',
        width: 80,
        height: 80,
        borderRadius: 40,
        alignItems: 'center',
        justifyContent: 'center',
        borderWidth: 2,
        borderColor: '#e94560',
    },
    footer: {
        position: 'absolute',
        bottom: 50,
    },
    resetText: {
        color: '#666',
        fontSize: 16,
    }
});
