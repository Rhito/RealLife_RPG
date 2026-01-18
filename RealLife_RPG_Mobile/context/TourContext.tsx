import React, { createContext, useContext, useState, useEffect, useRef } from 'react';
import { LayoutRectangle } from 'react-native';
import * as SecureStore from 'expo-secure-store';

export interface TourStep {
    id: string;
    text: string;
    next?: string;
    targetId: string;
    screen?: string; // Route to navigate to if needed (optional)
}

interface TourContextType {
    currentStep: string | null;
    startTour: () => void;
    nextStep: () => void;
    stopTour: () => void;
    registerTarget: (id: string, layout: LayoutRectangle) => void;
    unregisterTarget: (id: string) => void;
    targets: Record<string, LayoutRectangle>;
    activeStepData: TourStep | null;
}

const TOUR_STEPS: TourStep[] = [
    { 
        id: 'intro_avatar', 
        targetId: 'avatar', 
        text: 'This is you! Tap here anytime to customize your look and view your hero stats.', 
        next: 'intro_tasks_tab' 
    },
    { 
        id: 'intro_tasks_tab', 
        targetId: 'tab_tasks', 
        text: 'Go to the Tasks tab to manage your Habits, Dailies, and To-Dos.', 
        next: 'intro_create_task' 
    },
    { 
        id: 'intro_create_task', 
        targetId: 'add_task_btn', 
        text: 'Tap this button to create your first task and start earning XP!', 
        next: 'intro_shop_tab' 
    },
    { 
        id: 'intro_shop_tab', 
        targetId: 'tab_shop', 
        text: 'Visit the Shop to spend your Gold on rewards and items.', 
        next: 'intro_guild_tab' 
    },
    { 
        id: 'intro_guild_tab', 
        targetId: 'tab_friends', 
        text: 'Join the Guild to chat with friends and compete on the leaderboard.', 
        next: undefined 
    }
];

const TourContext = createContext<TourContextType | undefined>(undefined);

export const TourProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
    const [currentStep, setCurrentStep] = useState<string | null>(null);
    const [targets, setTargets] = useState<Record<string, LayoutRectangle>>({});

    const activeStepData = TOUR_STEPS.find(s => s.id === currentStep) || null;

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
        // Only start if not seen? For now, let's force it if called manually, 
        // or check logic elsewhere.
        setCurrentStep(TOUR_STEPS[0].id);
    };

    const stopTour = async () => {
        setCurrentStep(null);
        await SecureStore.setItemAsync('HAS_SEEN_INTERACTIVE_TOUR', 'true');
    };

    const nextStep = () => {
        const current = TOUR_STEPS.find(s => s.id === currentStep);
        if (current && current.next) {
            setCurrentStep(current.next);
        } else {
            stopTour();
        }
    };

    // Auto-start logic could go here, checking SecureStore on mount
    useEffect(() => {
        const checkAutoStart = async () => {
             const hasSeenTour = await SecureStore.getItemAsync('HAS_SEEN_INTERACTIVE_TOUR');
             const hasSeenTutorial = await SecureStore.getItemAsync('HAS_SEEN_TUTORIAL');
             
             // Only start interactive tour if they HAVE seen the slide tutorial
             // AND have NOT seen this interactive tour.
             if (hasSeenTutorial === 'true' && hasSeenTour !== 'true') {
                 // Slight delay to allow layout to settle
                 setTimeout(() => startTour(), 1000);
             }
        };
        checkAutoStart();
    }, []);

    return (
        <TourContext.Provider value={{ 
            currentStep, 
            startTour, 
            nextStep, 
            stopTour, 
            registerTarget, 
            unregisterTarget, 
            targets,
            activeStepData
        }}>
            {children}
        </TourContext.Provider>
    );
};

export const useTour = () => {
    const context = useContext(TourContext);
    if (!context) throw new Error("useTour must be used within TourProvider");
    return context;
};
