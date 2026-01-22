import { View, Text, StyleSheet, FlatList, TouchableOpacity, Alert, RefreshControl, Image, Dimensions, Modal, ScrollView } from 'react-native';
import { useAuth } from '../../../context/AuthContext';
import { useFocusEffect, useRouter } from 'expo-router';
import { useCallback, useState } from 'react';
import { fetchTasks, completeTask, scoreHabit, generateDailyTasks, TaskInstance, deleteTask, failTask } from '../../../services/tasks';
import { Ionicons } from '@expo/vector-icons';
import { LinearGradient } from 'expo-linear-gradient';
import { TourTarget } from '../../../components/TourTarget';
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
  const [selectedTask, setSelectedTask] = useState<any | null>(null);
  
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
          
          let message = `+${res.rewards.exp} XP, +${res.rewards.coins} Coins`;
          if (res.rewards.achievements && res.rewards.achievements.length > 0) {
              message += `\n\n🏆 ${res.rewards.achievements.join('\n🏆 ')}`;
          }
          
          showAlert(res.rewards.achievements?.length ? 'Great Work!' : 'Good Job!', message);
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
          
          // Check for Level Up
           if (res.rewards.level > (user?.level || 1)) {
              showAlert('🎉 LEVEL UP! 🎉', `You reached Level ${res.rewards.level}!`);
          }
          
          // Check for Achievements
          if (res.rewards.achievements && res.rewards.achievements.length > 0) {
              const message = res.rewards.achievements.join('\n');
              setTimeout(() => {
                  showAlert('🏆 Achievement Unlocked!', message);
              }, 500); 
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
                          setSelectedTask(null); // Close modal if open
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
                          setSelectedTask(null); // Close modal
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
          <TouchableOpacity 
            style={styles.taskContent} 
            onPress={() => setSelectedTask({...item, type: 'habit'})}
          >
              <Text style={styles.taskTitle}>{item.title}</Text>
              {item.description ? <Text style={styles.taskDesc} numberOfLines={1}>{item.description}</Text> : null}
              {item.today_count > 0 && (
                  <View style={styles.habitCounter}>
                      <Ionicons name="checkmark-circle" size={14} color="#FFC107" />
                      <Text style={styles.habitCounterText}>{item.today_count}x today</Text>
                  </View>
              )}
          </TouchableOpacity>
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
           <TouchableOpacity 
                style={styles.taskContent}
                onPress={() => setSelectedTask({...item, type: 'daily'})}
           >
              <Text style={styles.taskTitle}>{item.task.title}</Text>
              <View style={styles.metaRow}>
                  <View style={styles.tagContainer}>
                      <Text style={styles.tagText}>{item.task.difficulty.toUpperCase()}</Text>
                  </View>
              </View>
           </TouchableOpacity>
           <View style={styles.actionRow}>
                <TouchableOpacity style={styles.iconBtn} onPress={() => handleFail(item.id, item.task.title)}>
                    <Ionicons name="close-circle-outline" size={20} color="#EF9A9A" />
                </TouchableOpacity>
                <TouchableOpacity style={styles.iconBtn} onPress={() => handleDelete(item.task.id, item.task.title)}>
                    <Ionicons name="trash-outline" size={18} color="#B0BEC5" />
                </TouchableOpacity>
            </View>
      </View>
  );

  const renderTodoItem = ({ item }: { item: TaskInstance }) => (
      <View style={[styles.taskCard, styles.todoCard]}>
          <TouchableOpacity style={[styles.checkBox, styles.todoCheckBox]} onPress={() => handleComplete(item.id)}>
               <View style={[styles.checkBoxInner, styles.todoCheckBoxInner]} />
           </TouchableOpacity>
           <TouchableOpacity 
                style={styles.taskContent}
                onPress={() => setSelectedTask({...item, type: 'todo'})}
           >
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
              </View>
           </TouchableOpacity>
           <View style={styles.actionRow}>
                <TouchableOpacity style={styles.iconBtn} onPress={() => handleFail(item.id, item.task.title)}>
                    <Ionicons name="close-circle-outline" size={20} color="#EF9A9A" />
                </TouchableOpacity>
                <TouchableOpacity style={styles.iconBtn} onPress={() => handleDelete(item.task.id, item.task.title)}>
                    <Ionicons name="trash-outline" size={18} color="#B0BEC5" />
                </TouchableOpacity>
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

  const renderDetailModal = () => {
      if (!selectedTask) return null;
      
      const isInstance = selectedTask.type === 'daily' || selectedTask.type === 'todo';
      const task = isInstance ? selectedTask.task : selectedTask;
      
      return (
          <Modal
              animationType="fade"
              transparent={true}
              visible={!!selectedTask}
              onRequestClose={() => setSelectedTask(null)}
          >
              <View style={styles.modalOverlay}>
                  <View style={styles.modalContent}>
                      <View style={[styles.modalHeader, 
                          selectedTask.type === 'habit' ? styles.habitHeader : 
                          selectedTask.type === 'daily' ? styles.dailyHeader : styles.todoHeader
                      ]}>
                          <Text style={styles.modalTitle}>Mission Details</Text>
                          <TouchableOpacity onPress={() => setSelectedTask(null)} style={styles.closeButton}>
                              <Ionicons name="close" size={24} color="white" />
                          </TouchableOpacity>
                      </View>
                      
                      <ScrollView style={styles.modalBody}>
                          <Text style={styles.detailTitle}>{task.title}</Text>
                          <View style={styles.tagRow}>
                               <View style={styles.difficultyTag}>
                                   <Text style={styles.tagTextLabel}>{task.difficulty?.toUpperCase() || 'NORMAL'}</Text>
                               </View>
                               <View style={{ flex: 1 }} />
                          </View>
                          
                          <Text style={styles.sectionHeader}>Description</Text>
                          <Text style={styles.detailDesc}>{task.description || "No description provided."}</Text>
                          
                          <Text style={styles.sectionHeader}>Rewards</Text>
                          <View style={styles.rewardsRow}>
                              <View style={styles.rewardBadge}>
                                  <Ionicons name="star" size={16} color="#FFC107" />
                                  <Text style={styles.rewardText}>XP</Text>
                              </View>
                              <View style={styles.rewardBadge}>
                                  <Ionicons name="wallet" size={16} color="#FF9800" />
                                  <Text style={styles.rewardText}>Coins</Text>
                              </View>
                          </View>
                          
                          {selectedTask.type === 'todo' && selectedTask.scheduled_date && (
                              <>
                                <Text style={styles.sectionHeader}>Due Date</Text>
                                <Text style={styles.detailDesc}>
                                    {new Date(selectedTask.scheduled_date).toLocaleString()}
                                </Text>
                              </>
                          )}
                      </ScrollView>
                  </View>
              </View>
          </Modal>
      );
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
        <TourTarget name="add-task" style={styles.fabContainer}>
            <TouchableOpacity onPress={() => router.push('/tasks/create')} style={styles.fab}>
                 <Ionicons name="add" size={32} color="#432874" />
            </TouchableOpacity>
        </TourTarget>
        
        {renderDetailModal()}
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
  fabContainer: {
      position: 'absolute',
      bottom: 24,
      right: 24,
      zIndex: 100, // Ensure it's on top
  },
  fab: {
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
      marginLeft: 8,
  },
  iconBtn: {
      marginLeft: 8,
      padding: 4,
  },
  // Modal Styles
  modalOverlay: {
      flex: 1,
      backgroundColor: 'rgba(0,0,0,0.6)',
      justifyContent: 'center',
      padding: 24,
  },
  modalContent: {
      backgroundColor: 'white',
      borderRadius: 24,
      overflow: 'hidden',
  },
  modalHeader: {
      padding: 20,
      flexDirection: 'row',
      justifyContent: 'space-between',
      alignItems: 'center',
  },
  habitHeader: { backgroundColor: '#FFC107' },
  dailyHeader: { backgroundColor: '#9E9E9E' },
  todoHeader: { backgroundColor: '#F44336' },
  modalTitle: {
      color: 'white',
      fontWeight: 'bold',
      fontSize: 18,
  },
  closeButton: {
      padding: 4,
  },
  modalBody: {
      padding: 24,
  },
  detailTitle: {
      fontSize: 24,
      fontWeight: 'bold',
      color: '#333',
      marginBottom: 12,
  },
  tagRow: {
      flexDirection: 'row',
      marginBottom: 24,
  },
  difficultyTag: {
      backgroundColor: '#eee',
      paddingVertical: 4,
      paddingHorizontal: 12,
      borderRadius: 12,
  },
  tagTextLabel: {
      fontSize: 12,
      fontWeight: 'bold',
      color: '#666',
  },
  sectionHeader: {
      fontSize: 14,
      fontWeight: 'bold',
      color: '#BBAADD',
      textTransform: 'uppercase',
      marginBottom: 8,
  },
  detailDesc: {
      fontSize: 16,
      color: '#555',
      lineHeight: 24,
      marginBottom: 24,
  },
  rewardsRow: {
      flexDirection: 'row',
      gap: 12,
      marginBottom: 24,
  },
  rewardBadge: {
      flexDirection: 'row',
      alignItems: 'center',
      backgroundColor: '#FFF8E1',
      paddingVertical: 6,
      paddingHorizontal: 12,
      borderRadius: 20,
      borderWidth: 1,
      borderColor: '#FFECB3',
      gap: 6,
  },
  rewardText: {
      fontWeight: 'bold',
      color: '#F57C00',
  }
});
