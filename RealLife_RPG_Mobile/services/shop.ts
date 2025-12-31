import api from './api';

export interface Item {
    id: number;
    name: string;
    description: string;
    cost: number;
    type: string;
    image_url?: string;
    effects?: any;
}

export interface UserItem {
    id: number;
    user_id: number;
    item_id: number;
    quantity: number; // Pivot normally has quantity but our simple implementation might not use it yet, assumes 1 per row?
    // Wait, UserItem migration has quantity default 1. But my controller creates a new row per buy?
    // UserItem::create creates a new row.
    // So quantity is likely 1 per instance.
    used: boolean;
    item: Item;
}

export const fetchItems = async () => {
    return api.get('/items');
};

export const buyItem = async (itemId: number) => {
    return api.post(`/items/${itemId}/buy`);
};

export const fetchInventory = async () => {
    return api.get('/inventory');
};

export const useItem = async (userItemId: number) => {
    return api.post('/inventory/use', { user_item_id: userItemId });
};
