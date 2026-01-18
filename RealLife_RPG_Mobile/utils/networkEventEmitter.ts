type NetworkListener = (isConnected: boolean) => void;

const listeners: NetworkListener[] = [];

export const networkEvents = {
  addListener: (listener: NetworkListener) => {
    listeners.push(listener);
    return () => {
      const index = listeners.indexOf(listener);
      if (index > -1) {
        listeners.splice(index, 1);
      }
    };
  },

  emitStatus: (isConnected: boolean) => {
    listeners.forEach((listener) => listener(isConnected));
  },
};
