import api from './api';
import { User } from './friends';

export const fetchGlobalLeaderboard = async () => {
    try {
        const response = await api.get('/leaderboard');
        return response.data;
    } catch (e: any) {
        throw e.response?.data || e.message;
    }
}

export const fetchFriendsLeaderboard = async () => {
    try {
        const response = await api.get('/leaderboard/friends');
        return response.data;
    } catch (e: any) {
        throw e.response?.data || e.message;
    }
}
