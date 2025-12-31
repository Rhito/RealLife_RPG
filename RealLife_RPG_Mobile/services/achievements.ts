import api from './api';

export interface Achievement {
  id: number;
  name: string;
  description: string; // Add description
  condition: string;
  reward_exp: number;
  reward_coins: number;
  icon?: string;
  is_unlocked: boolean;
  unlocked_at?: string;
}

export const fetchAchievements = async () => {
  try {
    const response = await api.get('/achievements');
    return response.data;
  } catch (error: any) {
    throw error.response?.data || error.message;
  }
};
