import api from './api';
import { User } from './profile';

export interface Message {
    id: number;
    sender_id: number;
    receiver_id: number;
    content: string;
    read_at: string | null;
    created_at: string;
    updated_at: string;
}

export const getAiProfile = async (): Promise<User> => {
    const response = await api.get('/ai/profile');
    return response.data;
};

export const getMessages = async (friendId: number): Promise<Message[]> => {
    const response = await api.get(`/messages/${friendId}`);
    return response.data;
};

export const sendMessage = async (friendId: number, content: string, isAi: boolean = false): Promise<Message> => {
    if (isAi) {
        // Chat with AI
        const response = await api.post('/ai/chat', { content });
        return response.data;
    }
    const response = await api.post(`/messages/${friendId}`, { content });
    return response.data;
};
