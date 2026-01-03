import api from './api';

export interface Message {
    id: number;
    sender_id: number;
    receiver_id: number;
    content: string;
    read_at: string | null;
    created_at: string;
    updated_at: string;
}

export const getMessages = async (friendId: number): Promise<Message[]> => {
    const response = await api.get(`/messages/${friendId}`);
    return response.data;
};

export const sendMessage = async (friendId: number, content: string): Promise<Message> => {
    const response = await api.post(`/messages/${friendId}`, { content });
    return response.data;
};
