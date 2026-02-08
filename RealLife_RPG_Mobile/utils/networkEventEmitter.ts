type NetworkListener = (isConnected: boolean) => void;
type AuthListener = (error: any) => void;

const networkListeners: NetworkListener[] = [];
const authListeners: AuthListener[] = [];

export const networkEvents = {
  addListener: (listener: NetworkListener) => {
    networkListeners.push(listener);
    return () => {
      const index = networkListeners.indexOf(listener);
      if (index > -1) {
        networkListeners.splice(index, 1);
      }
    };
  },

  emitStatus: (isConnected: boolean) => {
    networkListeners.forEach((listener) => listener(isConnected));
  },
};

export const authEvents = {
  addListener: (listener: AuthListener) => {
    authListeners.push(listener);
    return () => {
      const index = authListeners.indexOf(listener);
      if (index > -1) {
        authListeners.splice(index, 1);
      }
    };
  },

  emitSessionExpired: (error: any) => {
    authListeners.forEach((listener) => listener(error));
  },
};
