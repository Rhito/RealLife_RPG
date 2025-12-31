import api from './api';

export interface Item {
  id: number;
  name: string;
  description: string;
  cost: number;
  image_url: string;
  effects?: any;
}

export const fetchItems = async () => {
  try {
    const response = await api.get('/items');
    return response.data;
  } catch (error: any) {
    throw error.response?.data || error.message;
  }
};

export const buyItem = async (id: number) => {
  try {
    const response = await api.post(`/items/${id}/buy`);
    return response.data;
  } catch (error: any) {
    throw error.response?.data || error.message;
  }
};
