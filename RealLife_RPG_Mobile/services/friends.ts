import api from './api';

import { User } from './profile';

export interface Friendship {
    id: number;
    user_id: number;
    friend_id: number;
    status: string;
    user?: User; // The sender in requests
}

export const fetchFriends = async () => {
    try {
        const response = await api.get('/friends');
        return response.data;
    } catch (e: any) {
        throw e.response?.data || e.message;
    }
}

export const fetchRequests = async () => {
    try {
        const response = await api.get('/friends/requests');
        return response.data;
    } catch (e: any) {
        throw e.response?.data || e.message;
    }
}

export const searchUsers = async (query: string) => {
    try {
        const response = await api.get(`/friends/search?query=${query}`);
        return response.data;
    } catch (e: any) {
        throw e.response?.data || e.message;
    }
}

export const sendRequest = async (friendId: number) => {
    try {
        const response = await api.post('/friends', { friend_id: friendId });
        return response.data;
    } catch (e: any) {
        throw e.response?.data || e.message;
    }
}

export const acceptRequest = async (id: number) => {
    try {
        const response = await api.put(`/friends/${id}`);
        return response.data;
    } catch (e: any) {
        throw e.response?.data || e.message;
    }
}

export const removeFriend = async (id: number) => {
    try {
        const response = await api.delete(`/friends/${id}`);
        return response.data;
    } catch (e: any) {
        throw e.response?.data || e.message;
    }
}
