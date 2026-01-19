import React, { createContext, useContext, useState, ReactNode, useEffect } from 'react';
import * as SecureStore from 'expo-secure-store';
import { LayoutRectangle } from 'react-native';

export type TourStep = {
    id: string;
    text: string;
    position?: 'top' | 'bottom';
};

// Define the steps - prioritizing Quest/Task creation as requested
const STEPS: TourStep[] = [
    { id: 'avatar', text: "Check your Level, HP, and XP here." },
    { id: 'tab-tasks', text: "This is your Quest Log. Manage Habits, Dailies, and To-Dos." },
    { id: 'add-task', text: "Tap '+' to create a new Quest or Habit.", position: 'top' },
    { id: 'create-types', text: "Choose your Quest Type: Habit, Daily, or To-Do.", position: 'bottom' },
    { id: 'create-submit', text: "Fill in the details and Create your Quest!", position: 'top' }
];

interface TourContextData {
    activeStep: TourStep | null;
    targets: Record<string, LayoutRectangle>;
    registerTarget: (id: string, layout: LayoutRectangle) => void;
    unregisterTarget: (id: string) => void;
    startTour: () => void;
    nextStep: () => void;
    stopTour: () => void;
}

const TourContext = createContext<TourContextData | undefined>(undefined);

export const TourProvider = ({ children }: { children: ReactNode }) => {
    const [activeStepIndex, setActiveStepIndex] = useState<number>(-1);
    const [targets, setTargets] = useState<Record<string, LayoutRectangle>>({});

    const registerTarget = (id: string, layout: LayoutRectangle) => {
        setTargets(prev => ({ ...prev, [id]: layout }));
    };

    const unregisterTarget = (id: string) => {
        setTargets(prev => {
            const next = { ...prev };
            delete next[id];
            return next;
        });
    };

    const startTour = async () => {
        setActiveStepIndex(0);
    };

    const stopTour = async () => {
        setActiveStepIndex(-1);
        await SecureStore.setItemAsync('HAS_SEEN_INTERACTIVE_TOUR', 'true');
    };

    const nextStep = () => {
        if (activeStepIndex < STEPS.length - 1) {
            setActiveStepIndex(prev => prev + 1);
        } else {
            stopTour();
        }
    };

    // Check on mount if we should start (only if tutorial is done? or separate?)
    // Let's make it manual for now or triggered by Tutorial completion.
    
    const activeStep = activeStepIndex >= 0 ? STEPS[activeStepIndex] : null;

    return (
        <TourContext.Provider value={{ activeStep, targets, registerTarget, unregisterTarget, startTour, nextStep, stopTour }}>
            {children}
        </TourContext.Provider>
    );
};

export const useTour = () => {
    const context = useContext(TourContext);
    if (!context) throw new Error("useTour must be used within TourProvider");
    return context;
};
