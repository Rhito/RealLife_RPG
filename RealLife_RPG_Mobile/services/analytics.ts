import api from './api';

export interface AnalyticsData {
    streak: number;
    rank?: number;
    coins?: number;
    history: {
        date: string;
        day: string;
        count: number;
    }[];
}

export const fetchAnalytics = async () => {
    try {
        const response = await api.get('/analytics');
        return response.data;
    } catch (error: any) {
        throw error.response?.data || error.message;
    }
}
