import api from '@/services/api';
import { Item } from './items';

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

export const updateProfile = async (data: { name: string; avatar?: string }) => {
    try {
        const response = await api.post('/profile', data);
        return response.data;
    } catch (error: any) {
        throw error.response?.data || error.message;
    }
}

export const getUserProfile = async (id: number) => {
    try {
        const response = await api.get(`/users/${id}`);
        return response.data.data;
    } catch (error: any) {
        throw error.response?.data || error.message;
    }
}
