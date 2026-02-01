import api from '@/services/api';
import { Item } from './items';

// User definition moved from friends.ts
export interface User {
    id: number;
    name: string;
    email: string;
    level: number;
    exp: number;
    avatar?: string;
    unread_count?: number;
}

export interface FeedItem {
  id: number;
  activity_type: string;
  data: {
    task_name?: string;
    earned_exp?: number;
    earned_coins?: number;
    new_level?: number;
    badge_name?: string;
    damage?: number;
    reason?: string;
    effect?: string;
    item_name?: string;
  };
  created_at: string;
}

export interface InventoryItem extends Item {
    pivot: {
        acquired_at: string;
        used: boolean;
    }
}

export const fetchInventory = async () => {
    try {
        const response = await api.get('/inventory');
        return response.data;
    } catch (e: any) {
        throw e.response?.data || e.message;
    }
}

export const fetchFeed = async () => {
    try {
        const response = await api.get('/feed');
        return response.data;
    } catch (e: any) {
        throw e.response?.data || e.message;
    }
}

export interface UserStats {
    hp: number;
    max_hp: number;
    exp: number;
    level: number;
    next_level_exp: number;
}

export const fetchProfileStats = async () => {
    try {
        const response = await api.get('/profile/stats');
        return response.data;
    } catch (e: any) {
        throw e.response?.data || e.message;
    }
}

export interface UpdateProfileData {
  name?: string;
  avatar?: string | null;
}

export const updateProfile = async (data: UpdateProfileData): Promise<User> => {
    const res = await api.post<User>('/profile', data);
    return res.data;
};

export const getUserProfile = async (id: number) => {
    try {
        const response = await api.get(`/users/${id}`);
        return response.data.data;
    } catch (error: any) {
        throw error.response?.data || error.message;
    }
}
