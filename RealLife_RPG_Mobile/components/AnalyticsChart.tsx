import { View, Text, StyleSheet } from 'react-native';
import React from 'react';
import { AnalyticsData } from '../services/analytics';
import { Card } from './Card';
import { Ionicons } from '@expo/vector-icons';

interface AnalyticsChartProps {
    data: AnalyticsData;
}

export const AnalyticsChart: React.FC<AnalyticsChartProps> = ({ data }) => {
    return (
        <Card>
            <Text style={styles.title}>Productivity</Text>
            <View style={styles.streakRow}>
                <Ionicons name="flame" size={24} color="#FF4500" />
                <Text style={styles.streakText}>{data.streak} Day Streak!</Text>
            </View>
            <View style={styles.chart}>
                {data.history.map((day, index) => (
                    <View key={index} style={styles.barContainer}>
                        <View style={[styles.bar, { height: Math.min(day.count * 12 + 5, 80) }]} />
                        <Text style={styles.barLabel}>{day.day}</Text>
                    </View>
                ))}
            </View>
        </Card>
    );
};

const styles = StyleSheet.create({
    title: {
        fontSize: 18,
        fontWeight: 'bold',
        marginBottom: 15,
        color: '#333',
    },
    streakRow: {
        flexDirection: 'row',
        alignItems: 'center',
        marginBottom: 20,
        justifyContent: 'center',
        backgroundColor: '#fff5f5',
        padding: 10,
        borderRadius: 12,
    },
    streakText: {
        fontSize: 18,
        fontWeight: 'bold',
        marginLeft: 8,
        color: '#FF4500',
    },
    chart: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'flex-end',
        height: 100,
        paddingTop: 10,
    },
    barContainer: {
        alignItems: 'center',
        flex: 1,
    },
    bar: {
        width: 12,
        backgroundColor: '#007AFF', // Premium Blue
        borderRadius: 6,
        marginBottom: 6,
    },
    barLabel: {
        fontSize: 11,
        color: '#888',
        fontWeight: '500',
    }
});
