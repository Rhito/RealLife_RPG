import { View, Text, StyleSheet, TouchableOpacity, Dimensions, Modal } from 'react-native';
import { useTour } from '../context/TourContext';
import { Ionicons } from '@expo/vector-icons';

const { width, height } = Dimensions.get('window');

export const TourOverlay = () => {
    const { activeStep, targets, nextStep, stopTour } = useTour();

    if (!activeStep) return null;

    const targetVal = targets[activeStep.id];
    
    // If target is not yet registered/measured (e.g. screen transition), show generic or wait
    if (!targetVal) return null; 

    // Calculate holes and tooltip position
    // For simplicity, we just use 4 partial views to create a "hole" or use a full SVG mask.
    // React Native doesn't have easy masking without libraries like skia/svg.
    // Simple approach: 4 semi-transparent views around the target rect.

    const topHeight = targetVal.y;
    const bottomHeight = height - (targetVal.y + targetVal.height);
    const leftWidth = targetVal.x;
    const rightWidth = width - (targetVal.x + targetVal.width);

    // Tooltip position (above or below)
    const showTop = activeStep.position === 'top' || (targetVal.y > 150);
    const tooltipTop = showTop 
        ? targetVal.y - 120 
        : targetVal.y + targetVal.height + 20;

    return (
        <Modal transparent animationType="fade" visible={!!activeStep}>
            <View style={styles.container}>
                {/* Mask Views */}
                <View style={[styles.mask, { top: 0, height: topHeight, width: width, left: 0 }]} />
                <View style={[styles.mask, { bottom: 0, height: bottomHeight, width: width, left: 0 }]} />
                <View style={[styles.mask, { top: topHeight, height: targetVal.height, width: leftWidth, left: 0 }]} />
                <View style={[styles.mask, { top: topHeight, height: targetVal.height, width: rightWidth, right: 0 }]} />

                {/* Highlight Border */}
                <View style={[
                    styles.highlight, 
                    { 
                        top: targetVal.y - 4, 
                        left: targetVal.x - 4, 
                        width: targetVal.width + 8, 
                        height: targetVal.height + 8,
                        borderRadius: (targetVal.width > 50) ? 12 : targetVal.width / 2 
                    }
                ]} />

                {/* Tooltip */}
                <View style={[styles.tooltip, { top: tooltipTop, left: 20, right: 20 }]}>
                    <Text style={styles.tooltipText}>{activeStep.text}</Text>
                    <View style={styles.buttons}>
                        <TouchableOpacity onPress={stopTour}>
                            <Text style={styles.skipText}>Close Tour</Text>
                        </TouchableOpacity>
                        <TouchableOpacity style={styles.nextButton} onPress={nextStep}>
                            <Text style={styles.nextText}>Next</Text>
                            <Ionicons name="arrow-forward" color="white" size={16} />
                        </TouchableOpacity>
                    </View>
                </View>
            </View>
        </Modal>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        zIndex: 9999,
    },
    mask: {
        position: 'absolute',
        backgroundColor: 'rgba(0,0,0,0.7)',
    },
    highlight: {
        position: 'absolute',
        borderWidth: 2,
        borderColor: '#FFD700', // Gold color
        backgroundColor: 'transparent',
        // boxShadow equivalent for glow?
    },
    tooltip: {
        position: 'absolute',
        backgroundColor: 'white',
        padding: 20,
        borderRadius: 12,
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 5 },
        shadowOpacity: 0.3,
        shadowRadius: 10,
        elevation: 10,
    },
    tooltipText: {
        fontSize: 16,
        color: '#333',
        marginBottom: 15,
        lineHeight: 22,
    },
    buttons: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
    },
    skipText: {
        color: '#999',
        fontSize: 14,
        fontWeight: '600',
    },
    nextButton: {
        backgroundColor: '#6C5CE7',
        paddingVertical: 8,
        paddingHorizontal: 16,
        borderRadius: 20,
        flexDirection: 'row',
        alignItems: 'center',
        gap: 5,
    },
    nextText: {
        color: 'white',
        fontWeight: 'bold',
    }
});
