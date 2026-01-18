import React, { useEffect, useState } from 'react';
import { View, Text, StyleSheet, Modal, TouchableOpacity, Dimensions } from 'react-native';
import { useTour } from '../context/TourContext';
import { Ionicons } from '@expo/vector-icons';

const { width, height } = Dimensions.get('window');

export const TourOverlay = () => {
    const { currentStep, activeStepData, targets, nextStep, stopTour } = useTour();
    
    if (!currentStep || !activeStepData) return null;

    const targetLayout = targets[activeStepData.targetId];
    
    // If target isn't visible/measured yet, maybe show nothing or a loader?
    // We'll just hide for now until it pops in.
    if (!targetLayout) return null;

    const { x, y, width: tWidth, height: tHeight } = targetLayout;

    // Calculate tooltip position (above or below target)
    const isBottomHalf = y > height / 2;
    const tooltipTop = isBottomHalf ? y - 160 : y + tHeight + 20;

    return (
        <Modal transparent visible={!!currentStep} animationType="fade">
            <View style={styles.container}>
                {/* 
                   We need to draw 4 rectangles to dim the screen AROUND the target.
                   Top, Bottom, Left of target, Right of target.
                */}
                
                {/* Top Dim */}
                <View style={[styles.dim, { top: 0, left: 0, right: 0, height: y }]} />
                
                {/* Bottom Dim */}
                <View style={[styles.dim, { top: y + tHeight, left: 0, right: 0, bottom: 0 }]} />
                
                {/* Left Dim */}
                <View style={[styles.dim, { top: y, left: 0, width: x, height: tHeight }]} />
                
                {/* Right Dim */}
                <View style={[styles.dim, { top: y, left: x + tWidth, right: 0, height: tHeight }]} />

                {/* Spotlight Border (Optional) */}
                <View 
                    style={[
                        styles.spotlight, 
                        { top: y - 4, left: x - 4, width: tWidth + 8, height: tHeight + 8 }
                    ]} 
                    pointerEvents="none" 
                />

                {/* Tooltip Bubble */}
                <View style={[styles.tooltip, { top: tooltipTop, left: 20, right: 20 }]}>
                    <View style={styles.header}>
                        <Text style={styles.title}>Quick Tip</Text>
                        <TouchableOpacity onPress={stopTour}>
                            <Ionicons name="close" size={20} color="#999" />
                        </TouchableOpacity>
                    </View>
                    <Text style={styles.text}>{activeStepData.text}</Text>
                    
                    <TouchableOpacity style={styles.button} onPress={nextStep}>
                        <Text style={styles.buttonText}>
                            {activeStepData.next ? 'Next' : 'Finish'}
                        </Text>
                    </TouchableOpacity>

                    {/* Arrow Pointer */}
                    <View style={[
                        styles.arrow, 
                        isBottomHalf ? { bottom: -10, borderTopColor: 'white' } : { top: -10, borderBottomColor: 'white' }
                    ]} />
                </View>
                
                {/* Allow tapping the target through? 
                    Actually, for a strict tour, usually we block interaction unless the user follows the instruction.
                    But for this "Next" button flow, we can just let them click Next.
                    If we want them to click the BUTTON to proceed, that's harder to coordinate.
                    Let's stick to "Next" button on tooltip for simplicity and robustness.
                */}
            </View>
        </Modal>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
    },
    dim: {
        position: 'absolute',
        backgroundColor: 'rgba(0,0,0,0.7)',
    },
    spotlight: {
        position: 'absolute',
        borderColor: 'white',
        borderWidth: 2,
        borderRadius: 8,
        borderStyle: 'dashed',
    },
    tooltip: {
        position: 'absolute',
        backgroundColor: 'white',
        borderRadius: 12,
        padding: 20,
        shadowColor: "#000",
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.3,
        shadowRadius: 8,
        elevation: 10,
    },
    header: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        marginBottom: 10,
    },
    title: {
        fontWeight: 'bold',
        fontSize: 14,
        color: '#6C5CE7',
        textTransform: 'uppercase',
    },
    text: {
        color: '#333',
        fontSize: 16,
        marginBottom: 20,
        lineHeight: 22,
    },
    button: {
        backgroundColor: '#6C5CE7',
        paddingVertical: 12,
        borderRadius: 8,
        alignItems: 'center',
    },
    buttonText: {
        color: 'white',
        fontWeight: 'bold',
        fontSize: 16,
    },
    arrow: {
        position: 'absolute',
        left: '50%',
        marginLeft: -10,
        width: 0,
        height: 0,
        borderLeftWidth: 10,
        borderRightWidth: 10,
        borderLeftColor: 'transparent',
        borderRightColor: 'transparent',
        borderStyle: 'solid',
        borderBottomWidth: 10, // Default pointing up
        borderTopWidth: 10,    // Default pointing down
        borderTopColor: 'transparent',
        borderBottomColor: 'transparent',
    }
});
