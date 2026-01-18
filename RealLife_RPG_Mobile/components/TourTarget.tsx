import { View, LayoutChangeEvent, ViewStyle, StyleProp } from 'react-native';
import { useTour } from '../context/TourContext';
import { useEffect, useRef } from 'react';

interface TourTargetProps {
    name: string;
    children: React.ReactNode;
    style?: StyleProp<ViewStyle>;
}

export const TourTarget = ({ name, children, style }: TourTargetProps) => {
    const { registerTarget, unregisterTarget } = useTour();
    const viewRef = useRef<View>(null);

    const onLayout = () => {
        viewRef.current?.measure((x, y, width, height, pageX, pageY) => {
            registerTarget(name, { x: pageX, y: pageY, width, height });
        });
    };

    useEffect(() => {
        // Measure initial position with a slight delay to ensure layout is done
        const timeout = setTimeout(onLayout, 500);
        return () => {
            clearTimeout(timeout);
            unregisterTarget(name);
        };
    }, []);

    return (
        <View 
            ref={viewRef} 
            collapsable={false} // Important for Android measure
            onLayout={onLayout}
            style={[{ alignSelf: 'flex-start' }, style]} // Default to wrap content tightly
        >
            {children}
        </View>
    );
};
