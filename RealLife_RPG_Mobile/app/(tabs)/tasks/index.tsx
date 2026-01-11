import { View, Text, StyleSheet, FlatList, TouchableOpacity, Alert, RefreshControl, Image, Dimensions } from 'react-native';
import { useAuth } from '../../../context/AuthContext';
import { useFocusEffect, useRouter } from 'expo-router';
import { useCallback, useState } from 'react';
import { fetchTasks, completeTask, scoreHabit, generateDailyTasks, TaskInstance, deleteTask, failTask } from '../../../services/tasks';
import { Ionicons } from '@expo/vector-icons';
import { Card } from '../../../components/Card';
import { useAlert } from '../../../context/AlertContext';

const { width } = Dimensions.get('window');

type TabType = 'habit' | 'daily' | 'todo';

export default function TasksScreen() {
  const { user, setUser } = useAuth();
  const router = useRouter();
  const { showAlert } = useAlert();
  
  const [activeTab, setActiveTab] = useState<TabType>('habit');
  
  // Data State
  const [habits, setHabits] = useState<any[]>([]); // Task definitions
  const [dailies, setDailies] = useState<TaskInstance[]>([]);
  const [todos, setTodos] = useState<TaskInstance[]>([]);
  
  const [refreshing, setRefreshing] = useState(false);

  const loadTasks = useCallback(async () => {
    try {
      const res = await fetchTasks();
      // res.data contains { habits, dailies, todos }
      setHabits(res.data.habits || []);
      setDailies(res.data.dailies || []);
      setTodos(res.data.todos || []);
    } catch (error) {
      console.error(error);
    }
  }, []);

  useFocusEffect(
    useCallback(() => {
        if (user?.id) loadTasks();
    }, [user?.id, loadTasks])
  );

  const onRefresh = useCallback(async () => {
      setRefreshing(true);
      try {
          await generateDailyTasks();
          await loadTasks();
      } catch (e) {
          console.error(e);
      } finally {
          setRefreshing(false);
      }
  }, [loadTasks]);

  const [processing, setProcessing] = useState(false);

  const handleScoreHabit = async (id: number) => {
      if (processing) return;
      setProcessing(true);
      try {
          const res = await scoreHabit(id);
          updateUserStats(res.rewards);
          showAlert('Good Job!', `+${res.rewards.exp} XP, +${res.rewards.coins} Coins, +1 HP!`);
          loadTasks();
      } catch (e: any) {
          showAlert('Error', e.message || 'Failed to score habit');
      } finally {
          setTimeout(() => setProcessing(false), 500); // Small delay to prevent rapid taps
      }
  };

  const handleComplete = async (id: number) => {
      if (processing) return;
      setProcessing(true);
      try {
          const res = await completeTask(id);
          updateUserStats(res.rewards);
           if (res.rewards.level > (user?.level || 1)) {
              showAlert('🎉 LEVEL UP! 🎉', `You reached Level ${res.rewards.level}!`);
          }
          loadTasks(); // Reload to remove from list
      } catch (e: any) {
          showAlert('Error', e.message || 'Failed to complete task');
      } finally {
          setTimeout(() => setProcessing(false), 500);
      }
  };

  const handleDelete = (id: number, title: string) => {
      if (processing) return;
      showAlert(
          'Delete Quest',
          `Are you sure you want to delete "${title}" forever?`,
          [
              { text: 'Cancel', style: 'cancel' },
              { 
                  text: 'Delete', 
                  style: 'destructive',
                  onPress: async () => {
                      if (processing) return;
                      setProcessing(true);
                      try {
                          await deleteTask(id);
                          loadTasks();
                      } catch (e: any) {
                          showAlert('Error', e.message);
                      } finally {
                          setProcessing(false);
                      }
                  }
              }
          ]
      );
  };

  const handleFail = (id: number, title: string) => {
      if (processing) return;
      showAlert(
          'Give Up?',
          `Are you sure you want to give up on "${title}"? You will take damage!`,
          [
              { text: 'Never!', style: 'cancel' },
              { 
                  text: 'I Give Up', 
                  style: 'destructive',
                  onPress: async () => {
                      if (processing) return;
                      setProcessing(true);
                      try {
                          const res = await failTask(id);
                          updateUserStats(res.rewards);
                          showAlert('Failed!', `You took damage. HP is now ${res.rewards.hp}.`);
                          loadTasks();
                      } catch (e: any) {
                          showAlert('Error', e.message);
                      } finally {
                          setProcessing(false);
                      }
                  }
              }
          ]
      );
  };

  const updateUserStats = (rewards: any) => {
      if (user) {
         setUser({ ...user, level: rewards.level, exp: rewards.exp, coins: rewards.coins, hp: rewards.hp });
      }
  };

  const renderTabButton = (tab: TabType, label: string, icon: any) => (
      <TouchableOpacity 
        style={[styles.tabButton, activeTab === tab && styles.activeTabButton]} 
        onPress={() => setActiveTab(tab)}
        disabled={processing}
      >
          <View style={[styles.tabIconContainer, activeTab === tab && styles.activeTabIconContainer]}>
            <Ionicons name={icon} size={20} color={activeTab === tab ? '#432874' : '#BBAADD'} />
          </View>
          <Text style={[styles.tabText, activeTab === tab && styles.activeTabText]}>{label}</Text>
      </TouchableOpacity>
  );

  const renderHabitItem = ({ item }: { item: any }) => (
      <View style={[styles.taskCard, styles.habitCard]}>
          <TouchableOpacity 
            style={styles.deleteButtonSmall} 
            onPress={() => handleDelete(item.id, item.title)}
          >
              <Ionicons name="trash-outline" size={16} color="#B0BEC5" />
          </TouchableOpacity>
          <View style={styles.taskContent}>
              <Text style={styles.taskTitle}>{item.title}</Text>
              {item.description ? <Text style={styles.taskDesc}>{item.description}</Text> : null}
              {item.today_count > 0 && (
                  <View style={styles.habitCounter}>
                      <Ionicons name="checkmark-circle" size={14} color="#FFC107" />
                      <Text style={styles.habitCounterText}>{item.today_count}x today</Text>
                  </View>
              )}
          </View>
          <TouchableOpacity style={styles.actionButton} onPress={() => handleScoreHabit(item.id)}>
              <Ionicons name="add" size={28} color="white" />
          </TouchableOpacity>
      </View>
  );

  const renderDailyItem = ({ item }: { item: TaskInstance }) => (
      <View style={[styles.taskCard, styles.dailyCard]}>
           <TouchableOpacity style={styles.checkBox} onPress={() => handleComplete(item.id)}>
               <View style={styles.checkBoxInner} />
           </TouchableOpacity>
           <View style={styles.taskContent}>
              <Text style={styles.taskTitle}>{item.task.title}</Text>
              <View style={styles.metaRow}>
                  <View style={styles.tagContainer}>
                      <Text style={styles.tagText}>{item.task.difficulty.toUpperCase()}</Text>
                  </View>
                  <View style={styles.actionRow}>
                        <TouchableOpacity style={styles.iconBtn} onPress={() => handleFail(item.id, item.task.title)}>
                            <Ionicons name="close-circle-outline" size={20} color="#EF9A9A" />
                        </TouchableOpacity>
                        <TouchableOpacity style={styles.iconBtn} onPress={() => handleDelete(item.task.id, item.task.title)}>
                            <Ionicons name="trash-outline" size={18} color="#B0BEC5" />
                        </TouchableOpacity>
                  </View>
              </View>
           </View>
      </View>
  );

  const renderTodoItem = ({ item }: { item: TaskInstance }) => (
      <View style={[styles.taskCard, styles.todoCard]}>
          <TouchableOpacity style={[styles.checkBox, styles.todoCheckBox]} onPress={() => handleComplete(item.id)}>
               <View style={[styles.checkBoxInner, styles.todoCheckBoxInner]} />
           </TouchableOpacity>
           <View style={styles.taskContent}>
              <Text style={styles.taskTitle}>{item.task.title}</Text>
              <View style={styles.metaRow}>
                  {item.scheduled_date && (
                    <View style={styles.dateTag}>
                        <Ionicons name="calendar-outline" size={12} color="#666" style={{ marginRight: 4 }} />
                        <Text style={styles.dateText}>
                            {new Date(item.scheduled_date).toLocaleDateString()} {new Date(item.scheduled_date).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                        </Text>
                    </View>
                  )}
                  <View style={styles.actionRow}>
                        <TouchableOpacity style={styles.iconBtn} onPress={() => handleFail(item.id, item.task.title)}>
                            <Ionicons name="close-circle-outline" size={20} color="#EF9A9A" />
                        </TouchableOpacity>
                        <TouchableOpacity style={styles.iconBtn} onPress={() => handleDelete(item.task.id, item.task.title)}>
                            <Ionicons name="trash-outline" size={18} color="#B0BEC5" />
                        </TouchableOpacity>
                  </View>
              </View>
           </View>
      </View>
  );

  const getData = () => {
      switch(activeTab) {
          case 'habit': return habits;
          case 'daily': return dailies;
          case 'todo': return todos;
          default: return [];
      }
  };

  const getEmptyMessage = () => {
      switch(activeTab) {
          case 'habit': return "Forge new habits to strengthen your character.";
          case 'daily': return "No daily duties. Enjoy your rest, hero.";
          case 'todo': return "Your quest log is clear.";
      }
  };

  return (
    <View style={styles.container}>
       {/* Header */}
       <View style={styles.header}>
           <Text style={styles.headerTitle}>Quest Log</Text>
           <Text style={styles.headerSubtitle}>{new Date().toDateString()}</Text>
       </View>

       {/* Tabs */}
       <View style={styles.tabContainer}>
           {renderTabButton('habit', 'Habits', 'bicycle')}
           {renderTabButton('daily', 'Dailies', 'calendar')}
           {renderTabButton('todo', 'To-Dos', 'list')}
       </View>

        {/* Content */}
       <FlatList 
          data={getData()}
          keyExtractor={(item) => item.id.toString()}
          renderItem={
              activeTab === 'habit' ? renderHabitItem : 
              activeTab === 'daily' ? renderDailyItem : renderTodoItem
          }
          refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor="#fff" />}
          ListEmptyComponent={
             <View style={styles.emptyContainer}>
                 <Ionicons name="book-outline" size={64} color="rgba(255,255,255,0.3)" />
                 <Text style={styles.emptyText}>{getEmptyMessage()}</Text>
                 <TouchableOpacity onPress={() => router.push('/tasks/create')} style={styles.createNowButton}>
                     <Text style={styles.createNowText}>New Quest</Text>
                 </TouchableOpacity>
             </View>
          }
          contentContainerStyle={styles.listContent}
       />

        {/* FAB */}
        <TouchableOpacity onPress={() => router.push('/tasks/create')} style={styles.fab}>
             <Ionicons name="add" size={32} color="#432874" />
        </TouchableOpacity>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#342056', // Deep purple
  },
  header: {
      paddingHorizontal: 20,
      paddingTop: 60,
      paddingBottom: 20,
      backgroundColor: '#432874',
  },
  headerTitle: {
      fontSize: 28,
      fontWeight: 'bold',
      color: '#fff',
      fontFamily: 'System', 
  },
  headerSubtitle: {
      fontSize: 14,
      color: '#BBAADD',
      marginTop: 4,
  },
  tabContainer: {
      flexDirection: 'row',
      backgroundColor: '#432874',
      paddingHorizontal: 10,
      paddingBottom: 15,
      borderBottomLeftRadius: 24,
      borderBottomRightRadius: 24,
      marginBottom: 10,
      elevation: 4,
      shadowColor: '#000',
      shadowOffset: { width: 0, height: 4 },
      shadowOpacity: 0.2,
      shadowRadius: 4,
      zIndex: 10,
  },
  tabButton: {
      flex: 1,
      alignItems: 'center',
      paddingVertical: 8,
  },
  tabIconContainer: {
      width: 40,
      height: 40,
      borderRadius: 20,
      backgroundColor: 'rgba(255,255,255,0.1)',
      justifyContent: 'center',
      alignItems: 'center',
      marginBottom: 6,
  },
  activeTabIconContainer: {
      backgroundColor: '#FF9800', // Gold
  },
  activeTabButton: {
  },
  tabText: {
      color: '#BBAADD',
      fontSize: 12,
      fontWeight: '600',
  },
  activeTabText: {
      color: '#FF9800',
      fontWeight: 'bold',
  },
  listContent: {
      padding: 16,
      paddingBottom: 100,
  },
  taskCard: {
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
  },
  habitCard: {
      borderLeftWidth: 6,
      borderLeftColor: '#FFC107', // Amber
  },
  dailyCard: {
      borderLeftWidth: 6,
      borderLeftColor: '#9E9E9E', // Grey
  },
  todoCard: {
      borderLeftWidth: 6,
      borderLeftColor: '#F44336', // Red
  },
  taskContent: {
      flex: 1,
      paddingHorizontal: 12,
  },
  taskTitle: {
      fontSize: 16,
      fontWeight: 'bold',
      color: '#333',
      marginBottom: 4,
  },
  taskDesc: {
      fontSize: 12,
      color: '#666',
  },
  habitCounter: {
      flexDirection: 'row',
      alignItems: 'center',
      marginTop: 6,
      backgroundColor: '#FFF8E1',
      alignSelf: 'flex-start',
      paddingHorizontal: 8,
      paddingVertical: 3,
      borderRadius: 12,
  },
  habitCounterText: {
      fontSize: 11,
      color: '#F57C00',
      fontWeight: 'bold',
      marginLeft: 4,
  },
  actionButton: {
      width: 44,
      height: 44,
      backgroundColor: '#FFC107',
      borderRadius: 14,
      justifyContent: 'center',
      alignItems: 'center',
      elevation: 2,
  },
  checkBox: {
      width: 28,
      height: 28,
      borderRadius: 8,
      borderWidth: 2,
      borderColor: '#9E9E9E',
      justifyContent: 'center',
      alignItems: 'center',
  },
  checkBoxInner: {
      width: 16,
      height: 16,
      borderRadius: 4,
      backgroundColor: 'transparent',
  },
  todoCheckBox: {
      borderColor: '#F44336',
  },
  todoCheckBoxInner: {
  },
  tagContainer: {
      backgroundColor: '#F5F5F5',
      alignSelf: 'flex-start',
      paddingHorizontal: 8,
      paddingVertical: 2,
      borderRadius: 4,
      marginTop: 4,
  },
  tagText: {
      fontSize: 10,
      color: '#666',
      fontWeight: 'bold',
  },
  dateTag: {
      flexDirection: 'row',
      alignItems: 'center',
      marginTop: 4,
  },
  dateText: {
      fontSize: 12,
      color: '#666',
  },
  emptyContainer: {
      alignItems: 'center',
      justifyContent: 'center',
      marginTop: 60,
  },
  emptyText: {
      color: 'rgba(255,255,255,0.6)',
      fontSize: 16,
      fontStyle: 'italic',
      marginTop: 16,
      textAlign: 'center',
      maxWidth: '80%',
  },
  createNowButton: {
      marginTop: 24,
      backgroundColor: 'rgba(255,255,255,0.1)',
      paddingVertical: 12,
      paddingHorizontal: 24,
      borderRadius: 24,
      borderWidth: 1,
      borderColor: 'rgba(255,255,255,0.3)',
  },
  createNowText: {
      color: '#fff',
      fontWeight: 'bold',
  },
  fab: {
      position: 'absolute',
      bottom: 24,
      right: 24,
      width: 64,
      height: 64,
      borderRadius: 32,
      backgroundColor: '#FF9800', 
      justifyContent: 'center',
      alignItems: 'center',
      elevation: 6,
      shadowColor: '#000',
      shadowOffset: { width: 0, height: 4 },
      shadowOpacity: 0.3,
      shadowRadius: 5,
      borderWidth: 4,
      borderColor: 'rgba(255,255,255,0.2)',
  },
  // New Styles
  deleteButtonSmall: {
      marginRight: 4,
      padding: 4,
  },
  metaRow: {
      flexDirection: 'row',
      justifyContent: 'space-between',
      alignItems: 'center',
      marginTop: 4,
  },
  actionRow: {
      flexDirection: 'row',
      alignItems: 'center',
  },
  iconBtn: {
      marginLeft: 12,
      padding: 4,
  }
});
