import { View, Text, TextInput, StyleSheet, TouchableOpacity, ScrollView, Alert, ActivityIndicator, Platform } from 'react-native';
import DateTimePicker from '@react-native-community/datetimepicker';
import { useState } from 'react';
import { createTask, TaskType, TaskDifficulty } from '../../../services/tasks';
import { useRouter } from 'expo-router';
import { Colors } from '../../../constants/theme';
import { Ionicons } from '@expo/vector-icons';
import { useAlert } from '../../../context/AlertContext';

const DAYS = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

export default function CreateTaskScreen() {
    const router = useRouter();
    const { showAlert } = useAlert();
    const [title, setTitle] = useState('');
    const [description, setDescription] = useState('');
    
    // Use Enum values
    const [type, setType] = useState<TaskType>(TaskType.HABIT);
    const [difficulty, setDifficulty] = useState<TaskDifficulty>(TaskDifficulty.EASY);
    
    const [repeatDays, setRepeatDays] = useState<string[]>(['Mon', 'Tue', 'Wed', 'Thu', 'Fri']);
    
    // Date Picker State
    const [date, setDate] = useState(new Date());
    const [showDatePicker, setShowDatePicker] = useState(false);
    const [showTimePicker, setShowTimePicker] = useState(false);

    const onChangeDate = (event: any, selectedDate?: Date) => {
        const currentDate = selectedDate || date;
        setShowDatePicker(Platform.OS === 'ios'); 
        setDate(currentDate);
    };

    const onChangeTime = (event: any, selectedDate?: Date) => {
        const currentDate = selectedDate || date;
        setShowTimePicker(Platform.OS === 'ios');
        setDate(currentDate);
    };
    
    const [loading, setLoading] = useState(false);

    const toggleDay = (day: string) => {
        if (repeatDays.includes(day)) {
            setRepeatDays(repeatDays.filter(d => d !== day));
        } else {
            setRepeatDays([...repeatDays, day]);
        }
    };

    const handleCreate = async () => {
        if (!title) {
            showAlert('Error', 'Title is required');
            return;
        }

        setLoading(true);
        try {
            await createTask({
                title,
                description,
                type,
                difficulty,
                repeat_days: type === TaskType.TODO ? [] : repeatDays, // clear repeat for todo
                due_date: type === TaskType.TODO ? date.toISOString() : undefined, // send full ISO string
            } as any); // cast as any because interface might mismatch slightly with partial helpers
            
            showAlert('Success', 'Task created!');
            router.back();
        } catch (e: any) {
            showAlert('Error', e.message || 'Failed to create task');
        } finally {
            setLoading(false);
        }
    };

    const getTypeColor = (t: TaskType) => {
        switch(t) {
            case TaskType.HABIT: return Colors.rpg.accent; // Yellow/Gold
            case TaskType.DAILY: return '#9E9E9E'; // Grey
            case TaskType.TODO: return Colors.rpg.danger; // Red
            default: return '#ccc';
        }
    };

    return (
        <View style={styles.mainContainer}>
            <View style={styles.header}>
                <TouchableOpacity onPress={() => router.back()} style={styles.backButton}>
                    <Ionicons name="arrow-back" size={24} color="white" />
                </TouchableOpacity>
                <Text style={styles.headerTitle}>New Quest</Text>
            </View>

            <ScrollView style={styles.container} contentContainerStyle={{ paddingBottom: 40 }}>
                
                <Text style={styles.sectionLabel}>Quest Type</Text>
                <View style={styles.row}>
                    {Object.values(TaskType).map(t => (
                        <TouchableOpacity 
                            key={t} 
                            style={[
                                styles.typeOption, 
                                type === t ? { backgroundColor: getTypeColor(t), borderColor: getTypeColor(t) } : {}
                            ]} 
                            onPress={() => setType(t)}
                        >
                            <Ionicons 
                                name={t === 'habit' ? 'bicycle' : t === 'daily' ? 'calendar' : 'list'} 
                                size={20} 
                                color={type === t ? 'white' : '#666'} 
                                style={{ marginBottom: 4 }}
                            />
                            <Text style={[styles.optionText, type === t && styles.selectedOptionText]}>
                                {t.toUpperCase()}
                            </Text>
                        </TouchableOpacity>
                    ))}
                </View>

                <View style={styles.card}>
                    <Text style={styles.inputLabel}>Title</Text>
                    <TextInput 
                        style={styles.input} 
                        value={title} 
                        onChangeText={setTitle} 
                        placeholder="e.g. Defeat the Dragon (Read Book)" 
                        placeholderTextColor="#999"
                    />

                    <Text style={styles.inputLabel}>Notes</Text>
                    <TextInput 
                        style={[styles.input, { height: 80, textAlignVertical: 'top' }]} 
                        value={description} 
                        onChangeText={setDescription} 
                        placeholder="Details about your quest..." 
                        placeholderTextColor="#999"
                        multiline
                    />
                </View>

                <Text style={styles.sectionLabel}>Difficulty (Rewards)</Text>
                <View style={styles.row}>
                    {Object.values(TaskDifficulty).map(d => (
                        <TouchableOpacity 
                            key={d} 
                            style={[
                                styles.diffOption, 
                                difficulty === d && styles.selectedDiffOption
                            ]} 
                            onPress={() => setDifficulty(d)}
                        >
                            <Text style={[styles.optionText, difficulty === d && styles.selectedOptionText]}>
                                {d.toUpperCase()}
                            </Text>
                        </TouchableOpacity>
                    ))}
                </View>

                {/* Conditional Settings */}
                {type !== TaskType.TODO && (
                    <>
                        <Text style={styles.sectionLabel}>Repeats On</Text>
                        <View style={styles.daysRow}>
                            {DAYS.map(day => (
                                <TouchableOpacity 
                                    key={day} 
                                    style={[styles.dayOption, repeatDays.includes(day) && styles.selectedDayOption]} 
                                    onPress={() => toggleDay(day)}
                                >
                                    <Text style={[styles.dayText, repeatDays.includes(day) && styles.selectedDayText]}>{day}</Text>
                                </TouchableOpacity>
                            ))}
                        </View>
                    </>
                )}

                {type === TaskType.TODO && (
                    <>
                        <Text style={styles.sectionLabel}>Due Date</Text>
                        <View style={{ flexDirection: 'row', gap: 10 }}>
                            <TouchableOpacity 
                                style={[styles.dateInput, { flex: 1 }]} 
                                onPress={() => setShowDatePicker(true)}
                            >
                                <Text style={{ fontSize: 16, color: '#333' }}>
                                    {date.toLocaleDateString()}
                                </Text>
                                <Ionicons name="calendar-outline" size={20} color="#666" style={{ position: 'absolute', right: 15, top: 15 }} />
                            </TouchableOpacity>

                            <TouchableOpacity 
                                style={[styles.dateInput, { flex: 1 }]} 
                                onPress={() => setShowTimePicker(true)}
                            >
                                <Text style={{ fontSize: 16, color: '#333' }}>
                                    {date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                                </Text>
                                <Ionicons name="time-outline" size={20} color="#666" style={{ position: 'absolute', right: 15, top: 15 }} />
                            </TouchableOpacity>
                        </View>
                        
                        {showDatePicker && (
                            <DateTimePicker
                                testID="dateTimePicker"
                                value={date}
                                mode="date"
                                is24Hour={true}
                                display="default"
                                onChange={onChangeDate}
                            />
                        )}

                        {showTimePicker && (
                            <DateTimePicker
                                testID="timePicker"
                                value={date}
                                mode="time"
                                is24Hour={true}
                                display="default"
                                onChange={onChangeTime}
                            />
                        )}
                    </>
                )}

                <TouchableOpacity style={styles.createButton} onPress={handleCreate} disabled={loading}>
                    {loading ? <ActivityIndicator color="white" /> : <Text style={styles.createButtonText}>Create Quest</Text>}
                </TouchableOpacity>

            </ScrollView>
        </View>
    );
}

const styles = StyleSheet.create({
    mainContainer: {
        flex: 1,
        backgroundColor: Colors.rpg.background,
    },
    header: {
        flexDirection: 'row',
        alignItems: 'center',
        padding: 20,
        paddingTop: 60, // Safe area
        backgroundColor: '#432874',
    },
    backButton: {
        marginRight: 15,
        padding: 8,
    },
    headerTitle: {
        color: 'white',
        fontSize: 24,
        fontWeight: 'bold',
    },
    container: {
        flex: 1,
        padding: 20,
    },
    card: {
        backgroundColor: 'white',
        borderRadius: 12,
        padding: 15,
        marginBottom: 20,
    },
    sectionLabel: {
        color: Colors.rpg.subtext,
        fontSize: 14,
        textTransform: 'uppercase',
        fontWeight: 'bold',
        marginBottom: 10,
        marginTop: 5,
    },
    inputLabel: {
        fontSize: 12,
        color: '#666',
        fontWeight: 'bold',
        marginBottom: 5,
    },
    input: {
        backgroundColor: '#F5F5F9',
        padding: 12,
        borderRadius: 8,
        borderWidth: 1,
        borderColor: '#E0E0E0',
        marginBottom: 15,
        fontSize: 16,
    },
    dateInput: {
        backgroundColor: 'white',
        padding: 15,
        borderRadius: 8,
        fontSize: 16,
        color: '#333',
        marginBottom: 20,
    },
    row: {
        flexDirection: 'row',
        marginBottom: 20,
        justifyContent: 'space-between',
    },
    typeOption: {
        flex: 1,
        alignItems: 'center',
        paddingVertical: 15,
        marginHorizontal: 4,
        borderRadius: 10,
        backgroundColor: 'rgba(255,255,255,0.1)', // Translucent
        borderWidth: 2,
        borderColor: 'transparent',
    },
    diffOption: {
        flex: 1,
        alignItems: 'center',
        paddingVertical: 12,
        marginHorizontal: 4,
        borderRadius: 20,
        backgroundColor: 'rgba(255,255,255,0.1)',
        borderWidth: 1,
        borderColor: '#666',
    },
    selectedDiffOption: {
        backgroundColor: Colors.rpg.primaryLight,
        borderColor: Colors.rpg.accent,
    },
    optionText: {
        color: '#ccc',
        fontSize: 12,
        fontWeight: 'bold',
    },
    selectedOptionText: {
        color: 'white',
    },
    daysRow: {
        flexDirection: 'row',
        flexWrap: 'wrap',
        marginBottom: 20,
        justifyContent: 'space-between',
    },
    dayOption: {
        width: 40,
        height: 40,
        borderRadius: 20,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: 'rgba(255,255,255,0.1)',
        marginBottom: 5,
    },
    selectedDayOption: {
        backgroundColor: Colors.rpg.accent,
    },
    dayText: {
        color: '#ccc',
        fontSize: 12,
    },
    selectedDayText: {
        color: 'white',
        fontWeight: 'bold',
    },
    createButton: {
        backgroundColor: Colors.rpg.accent,
        padding: 18,
        borderRadius: 12,
        alignItems: 'center',
        marginTop: 10,
        elevation: 4,
    },
    createButtonText: {
        color: 'white',
        fontSize: 18,
        fontWeight: 'bold',
        textTransform: 'uppercase',
    }
});
