import { View, Text, StyleSheet, FlatList, TouchableOpacity, Alert, RefreshControl, Image, Dimensions, Modal, ScrollView } from 'react-native';
import { useAuth } from '../../../context/AuthContext';
import { useFocusEffect, useRouter } from 'expo-router';
import { useCallback, useState } from 'react';
import { fetchTasks, completeTask, scoreHabit, generateDailyTasks, TaskInstance, deleteTask, deleteTaskInstance, failTask, pinTask } from '../../../services/tasks';
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
  const [dailyDefinitions, setDailyDefinitions] = useState<any[]>([]); // New State
  const [todos, setTodos] = useState<TaskInstance[]>([]);
  const [showManageDailies, setShowManageDailies] = useState(false); // New State
  const [selectedTask, setSelectedTask] = useState<any | null>(null);
  
  const [refreshing, setRefreshing] = useState(false);

  const loadTasks = useCallback(async () => {
    try {
      const res = await fetchTasks();
      // res.data contains { habits, dailies, todos }
      setHabits(res.data.habits || []);
      setDailies(res.data.dailies || []);
      setTodos(res.data.todos || []);
      // @ts-ignore
      setDailyDefinitions(res.data.daily_definitions || []); // Fetch definitions
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
          const gainedExp = res.rewards.exp - (user?.exp || 0);
          const gainedCoins = res.rewards.coins - (user?.coins || 0);
          
          updateUserStats(res.rewards);
          
          // Show Reward Alert
          let rewardMsg = `+${gainedExp} XP, +${gainedCoins} Coins`;
          showAlert('Quest Complete!', rewardMsg);

          // Check for Level Up
           if (res.rewards.level > (user?.level || 1)) {
              setTimeout(() => {
                  showAlert('🎉 LEVEL UP! 🎉', `You reached Level ${res.rewards.level}!`);
              }, 500);
          }
          
          // Check for Achievements
          if (res.rewards.achievements && res.rewards.achievements.length > 0) {
              const message = res.rewards.achievements.join('\n');
              setTimeout(() => {
                  showAlert('🏆 Achievement Unlocked!', message);
              }, 1000); 
          }

          loadTasks(); // Reload to remove from list
      } catch (e: any) {
          showAlert('Error', e.message || 'Failed to complete task');
      } finally {
          setTimeout(() => setProcessing(false), 500);
      }
  };

  const handleDelete = (id: number, title: string, type?: 'habit' | 'daily' | 'todo', instanceId?: number) => {
      if (processing) return;
      
      // For daily tasks, show two options
      if (type === 'daily' && instanceId) {
          showAlert(
              'Delete Daily Quest',
              `"${title}" - Choose deletion type:`,
              [
                  { text: 'Cancel', style: 'cancel' },
                  { 
                      text: 'Today Only', 
                      onPress: async () => {
                          if (processing) return;
                          setProcessing(true);
                          try {
                              await deleteTaskInstance(instanceId);
                              loadTasks();
                              setSelectedTask(null);
                              showAlert('Removed', 'Today\'s task removed. It will reappear on scheduled days.');
                          } catch (e: any) {
                              showAlert('Error', e.message || 'Failed to delete task instance');
                          } finally {
                              setProcessing(false);
                          }
                      }
                  },
                  { 
                      text: 'Forever', 
                      style: 'destructive',
                      onPress: async () => {
                          if (processing) return;
                          setProcessing(true);
                          try {
                              await deleteTask(id);
                              loadTasks();
                              setSelectedTask(null);
                              showAlert('Deleted', 'Task deleted permanently. It will not reappear.');
                          } catch (e: any) {
                              showAlert('Error', e.message || 'Failed to delete task');
                          } finally {
                              setProcessing(false);
                          }
                      }
                  }
              ]
          );
      } else {
          // For habits and todos, show normal delete
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
                              setSelectedTask(null);
                          } catch (e: any) {
                              showAlert('Error', e.message || 'Failed to delete task');
                          } finally {
                              setProcessing(false);
                          }
                      }
                  }
              ]
          );
      }
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
                          showAlert('Error', e.message || 'Failed to fail task');
                      } finally {
                          setProcessing(false);
                      }
                  }
              }
          ]
      );
  };

/*
  const handlePin = async (id: number, isInstance: boolean) => {
    if (processing) return;
    setProcessing(true);
    try {
        // If instance, we need to pass Task ID or Instance ID depending on backend logic.
        // Backend `pin` method checks if ID is task or instance and handles it.
        // So we can just pass the ID we have.
        // BUT if it's an instance, we assume the ID is the instance ID.
        // Backend logic: "Check if ID matches a Task... if not, check Instance".
        // Habits use Task ID. Dailies use Instance ID. Backend handles both!
        
        await pinTask(id);
        
        // Update local state immediately for responsiveness (optional but better)
        // Or just reload
        loadTasks();
        
        // Close modal or update selectedTask's pinned status if modal is open
        if (selectedTask) {
             // We need to know if we acted on the selected task
             const isSelected = selectedTask.id === id || (selectedTask.task && selectedTask.task.id === id); // This logic is approximate
             // Actually backend toggle. We don't know the new state without response.
        }
        setSelectedTask(null);

    } catch (e: any) {
        showAlert('Error', e.message || 'Failed to pin task/instance');
    } finally {
        setProcessing(false);
    }
  };
*/

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
            onPress={() => handleDelete(item.id, item.title, 'habit')}
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
              {/* {Boolean(item.is_pinned) && (
                 <View style={styles.pinnedBadge}>
                     <Ionicons name="push" size={12} color="#FF9800" />
                 </View>
              )} */}
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
                  {/* {Boolean(item.task.is_pinned) && (
                    <View style={styles.pinnedBadge}>
                        <Ionicons name="push" size={12} color="#FF9800" />
                    </View>
                  )} */}
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
          case 'habit': return habits; // Habits don't have 'status' per se, they are perpetual
          case 'daily': return dailies.filter(t => t.status === 'pending');
          case 'todo': return todos.filter(t => t.status === 'pending');
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
                               
                               {/* <TouchableOpacity 
                                    onPress={() => handlePin(selectedTask.id, isInstance)}
                                    style={styles.pinButtonModal}
                                >
                                    <Ionicons 
                                        name={task.is_pinned ? "push" : "push-outline"} 
                                        size={20} 
                                        color={task.is_pinned ? "#FF9800" : "#BBAADD"} 
                                    />
                                    <Text style={[styles.pinText, task.is_pinned && styles.pinTextActive]}>
                                        {task.is_pinned ? 'Pinned' : 'Pin'}
                                    </Text>
                               </TouchableOpacity> */}
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

       {activeTab === 'daily' && (
           <TouchableOpacity 
                style={styles.manageButton}
                onPress={() => setShowManageDailies(true)}
           >
               <Ionicons name="list" size={16} color="#BBAADD" />
               <Text style={styles.manageButtonText}>Manage Recurring</Text>
           </TouchableOpacity>
       )}

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
        {/* Manage Modal */}
        <Modal
            animationType="slide"
            transparent={true}
            visible={showManageDailies}
            onRequestClose={() => setShowManageDailies(false)}
        >
            <View style={styles.modalOverlay}>
                <View style={[styles.modalContent, { maxHeight: '80%' }]}>
                    <View style={[styles.modalHeader, styles.dailyHeader]}>
                        <Text style={styles.modalTitle}>Recurring Dailies</Text>
                        <TouchableOpacity onPress={() => setShowManageDailies(false)} style={styles.closeButton}>
                            <Ionicons name="close" size={24} color="white" />
                        </TouchableOpacity>
                    </View>
                    
                    <View style={{ padding: 16, backgroundColor: '#f9f9f9', borderBottomWidth: 1, borderColor: '#eee' }}>
                        <Text style={{ fontSize: 13, color: '#666' }}>
                           These are your configured Daily Quests. They will auto-generate tasks on the scheduled days.
                        </Text>
                    </View>

                    <FlatList
                        data={dailyDefinitions}
                        keyExtractor={(item) => item.id.toString()}
                        contentContainerStyle={{ padding: 16 }}
                        ListEmptyComponent={
                            <View style={{ alignItems: 'center', padding: 20 }}>
                                <Text style={{ color: '#999', fontStyle: 'italic' }}>No recurring tasks set up.</Text>
                            </View>
                        }
                        renderItem={({ item }) => (
                            <View style={[styles.taskCard, styles.recurringDailyCard]}>
                                <View style={styles.taskContent}>
                                    <Text style={styles.taskTitle}>{item.title}</Text>
                                    <View style={styles.metaRow}>
                                        <Text style={styles.modalTagText}>{item.repeat_days?.join(', ') || 'No repeat'}</Text>
                                    </View>
                                </View>
                                <TouchableOpacity 
                                    style={styles.deleteActionButton} 
                                    onPress={() => handleDelete(item.id, item.title, 'daily')}
                                    // Use actionButton style but red background maybe? Or just trash icon
                                >
                                    <Ionicons name="trash-outline" size={20} color="#D32F2F" />
                                </TouchableOpacity>
                            </View>
                        )}
                    />
                </View>
            </View>
        </Modal>
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
  },
  pinButtonModal: {
      flexDirection: 'row',
      alignItems: 'center',
  },
  pinText: {
      marginLeft: 4,
      fontSize: 14,
      fontWeight: '600',
      color: '#BBAADD',
  },
  pinTextActive: {
      color: '#FF9800',
  },
  manageButton: {
      flexDirection: 'row',
      alignItems: 'center',
      alignSelf: 'flex-end',
      marginRight: 16,
      marginBottom: 8,
      backgroundColor: 'rgba(255,255,255,0.1)',
      paddingVertical: 6,
      paddingHorizontal: 12,
      borderRadius: 16,
  },
  manageButtonText: {
      color: '#BBAADD',
      fontSize: 12,
      fontWeight: '600',
      marginLeft: 6,
  },
  pinnedBadge: {
      backgroundColor: 'rgba(255, 152, 0, 0.15)',
      paddingHorizontal: 6,
      paddingVertical: 2,
      borderRadius: 4,
      marginLeft: 6,
      alignSelf: 'center',
  },
  dailyHeader: {
      backgroundColor: '#7E57C2',
  },
  recurringDailyCard: {
      borderLeftColor: '#7E57C2',
      borderLeftWidth: 4,
  },
  modalTagText: { // Renamed from tagText to avoid conflict
      fontSize: 12,
      color: '#7E57C2',
      backgroundColor: '#EDE7F6',
      paddingHorizontal: 8,
      paddingVertical: 2,
      borderRadius: 4,
      overflow: 'hidden',
  },
  deleteActionButton: { // Renamed from actionButton to avoid conflict
      padding: 8,
      backgroundColor: '#FFEBEE',
      borderRadius: 8,
      justifyContent: 'center',
      alignItems: 'center',
  }
});
