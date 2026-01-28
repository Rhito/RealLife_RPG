import api from './api';

export enum TaskType {
    HABIT = 'habit',
    DAILY = 'daily',
    TODO = 'once',
}

export enum TaskDifficulty {
    EASY = 'easy',
    MEDIUM = 'medium',
    HARD = 'hard',
}

export interface Task {
    id: number;
    title: string;
    description?: string;
    type: TaskType;
    difficulty: TaskDifficulty;
    repeat_days: string[] | null;
    reward_exp: number;
    reward_coins: number;
    due_date?: string;
}

export interface TaskInstance {
    id: number;
    task: Task;
    status: string;
    scheduled_date: string;
    generated_by?: string;
}

export interface TasksResponse {
    habits: any[]; // Use proper Task interface if available
    dailies: TaskInstance[];
    todos: TaskInstance[];
}

export const fetchTasks = async () => {
  try {
    const response = await api.get('/tasks');
    return response.data as { data: TasksResponse }; // Backend return { data: { habits: [], ... } }
  } catch (error: any) {
    throw error.response?.data || error.message;
  }
};

export const scoreHabit = async (id: number) => {
    try {
        const response = await api.post(`/tasks/${id}/score`);
        return response.data;
    } catch (error: any) {
        throw error.response?.data || error.message;
    }
};

export const completeTask = async (id: number) => {
  try {
    const response = await api.post(`/tasks/${id}/complete`);
    return response.data;
  } catch (error: any) {
    throw error.response?.data || error.message;
  }
};

export const completeFocusTask = async (id: number) => {
    try {
      const response = await api.post(`/tasks/${id}/focus`);
      return response.data;
    } catch (error: any) {
      throw error.response?.data || error.message;
    }
};


export const createTask = async (data: Partial<Task>) => {
    try {
        const response = await api.post('/tasks', data);
        return response.data;
    } catch (error: any) {
        throw error.response?.data || error.message;
    }
}

export const updateTask = async (id: number, data: Partial<Task>) => {
    try {
        const response = await api.put(`/tasks/${id}`, data);
        return response.data;
    } catch (error: any) {
        throw error.response?.data || error.message;
    }
}

export const generateDailyTasks = async () => {
    try {
        const response = await api.post('/tasks/daily');
        return response.data;
    } catch (error: any) {
        throw error.response?.data || error.message;
    }
}
export const deleteTask = async (taskId: number) => {
    try {
        const response = await api.delete(`/tasks/${taskId}`);
        return response.data;
    } catch (error: any) {
        throw error.response?.data || error.message;
    }
};

export const failTask = async (instanceId: number) => {
    try {
        const response = await api.post(`/tasks/${instanceId}/fail`);
        return response.data;
    } catch (error: any) {
        throw error.response?.data || error.message;
    }
};
