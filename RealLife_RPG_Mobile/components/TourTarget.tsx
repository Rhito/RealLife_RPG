import React, { useRef, useEffect } from 'react';
import { View, ViewProps, LayoutRectangle } from 'react-native';
import { useTour } from '../context/TourContext';

interface TourTargetProps extends ViewProps {
    id: string;
    children: React.ReactNode;
}

export const TourTarget: React.FC<TourTargetProps> = ({ id, children, style, ...props }) => {
    const { registerTarget, unregisterTarget } = useTour();
    const viewRef = useRef<View>(null);

    const onLayout = () => {
        viewRef.current?.measureInWindow((x, y, width, height) => {
            if (width > 0 && height > 0) {
                 registerTarget(id, { x, y, width, height });
            }
        });
    };

    useEffect(() => {
        // Initial measure check
        const timer = setTimeout(onLayout, 500); 
        return () => {
            clearTimeout(timer);
            unregisterTarget(id);
        }
    }, [id]);

    return (
        <View 
            ref={viewRef} 
            onLayout={onLayout} 
            style={style} 
            {...props}
            collapsable={false} // Important for measureInWindow on Android sometimes
        >
            {children}
        </View>
    );
};
