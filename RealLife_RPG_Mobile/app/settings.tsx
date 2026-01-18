import { View, Text, TextInput, StyleSheet, TouchableOpacity, Alert, Image, ScrollView } from 'react-native';
import { useState } from 'react';
import { useAuth } from '../context/AuthContext';
import { updateProfile } from '../services/profile';
import { useRouter } from 'expo-router';
import { Ionicons } from '@expo/vector-icons';
import { useAlert } from '../context/AlertContext';
import { Avatar } from '../components/Avatar';

// Pre-defined avatars (using icons/emojis or placeholders only for now as per "simple" requirements, 
// but to make it premium, let's use some cool gradient circles or image assets if available. 
// For now, I'll use a set of urls or icon names.)
const AVATARS = [
    'https://api.dicebear.com/7.x/avataaars/png?seed=Felix',
    'https://api.dicebear.com/7.x/avataaars/png?seed=Aneka',
    'https://api.dicebear.com/7.x/avataaars/png?seed=Zack',
    'https://api.dicebear.com/7.x/avataaars/png?seed=Trou',
    'https://api.dicebear.com/7.x/avataaars/png?seed=Molly',
    'https://api.dicebear.com/7.x/avataaars/png?seed=Leo',
];

export default function SettingsScreen() {
    const { user, updateUser } = useAuth();
    const router = useRouter();
    const { showAlert } = useAlert();
    const [name, setName] = useState(user?.name || '');
    const [avatar, setAvatar] = useState<string | null>(user?.avatar || null);
    const [loading, setLoading] = useState(false);

    const handleSave = async () => {
        if (!name) {
            showAlert('Error', 'Name is required');
            return;
        }
        setLoading(true);
        try {
            const res = await updateProfile({ name, avatar });
            if (user) {
                await updateUser({ ...user, name, avatar });
            }
            showAlert('Success', 'Profile updated!');
            router.back();
        } catch (e: any) {
            showAlert('Error', e.message);
        } finally {
            setLoading(false);
        }
    };

    return (
        <ScrollView style={styles.container}>
             <TouchableOpacity style={styles.headerBackButton} onPress={() => router.back()}>
                <Ionicons name="arrow-back" size={24} color="#333" />
            </TouchableOpacity>
            <Text style={styles.label}>Display Name</Text>
            <TextInput 
                style={styles.input} 
                value={name} 
                onChangeText={setName} 
                placeholder="Your Name" 
            />

            <Text style={styles.label}>Choose Avatar</Text>
            <View style={styles.avatarGrid}>
                 {/* Default / Initials Option */}
                <TouchableOpacity 
                    onPress={() => setAvatar(null)}
                    style={[
                        styles.avatarOption, 
                        !avatar && styles.selectedAvatar
                    ]}
                >
                    <View style={[styles.avatarImage, { backgroundColor: '#6C5CE7', justifyContent: 'center', alignItems: 'center' }]}>
                         <Text style={{ color: 'white', fontWeight: 'bold' }}>{name ? name.substring(0, 2).toUpperCase() : '?'}</Text>
                    </View>
                </TouchableOpacity>

                {AVATARS.map((url, index) => (
                    <TouchableOpacity 
                        key={index} 
                        onPress={() => setAvatar(url)}
                        style={[
                            styles.avatarOption, 
                            avatar === url && styles.selectedAvatar
                        ]}
                    >
                        <Avatar name="Avatar" image={url} size={60} />
                    </TouchableOpacity>
                ))}
            </View>
            
            <View style={styles.previewContainer}>
                 <Text style={styles.previewLabel}>Preview</Text>
                 <View style={styles.previewCard}>
                     <Avatar name={name || 'User Name'} image={avatar} size={50} style={styles.previewAvatar} />
                     <View>
                        <Text style={styles.previewName}>{name || 'User Name'}</Text>
                        <Text style={styles.previewLevel}>Level {user?.level ?? 1}</Text>
                     </View>
                 </View>
            </View>

            <TouchableOpacity style={styles.saveButton} onPress={handleSave} disabled={loading}>
                <Text style={styles.saveButtonText}>{loading ? 'Saving...' : 'Save Changes'}</Text>
            </TouchableOpacity>

            <TouchableOpacity 
                style={[styles.saveButton, { marginTop: 10, backgroundColor: '#6C5CE7', marginBottom: 40 }]} 
                onPress={() => router.push('/tutorial')}
            >
                <Text style={styles.saveButtonText}>Replay Tutorial</Text>
            </TouchableOpacity>
        </ScrollView>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        padding: 20,
        backgroundColor: '#f5f5f5',
    },
    label: {
        fontSize: 16,
        fontWeight: 'bold',
        marginBottom: 10,
        marginTop: 10,
        color: '#333',
    },
    input: {
        backgroundColor: 'white',
        padding: 15,
        borderRadius: 12,
        marginBottom: 20,
        borderWidth: 1,
        borderColor: '#eee',
        fontSize: 16,
    },
    avatarGrid: {
        flexDirection: 'row',
        flexWrap: 'wrap',
        justifyContent: 'space-around',
        marginBottom: 20,
    },
    avatarOption: {
        padding: 4,
        borderRadius: 40,
        borderWidth: 2,
        borderColor: 'transparent',
    },
    selectedAvatar: {
        borderColor: '#007AFF',
    },
    avatarImage: {
        width: 60,
        height: 60,
        borderRadius: 30,
        backgroundColor: '#ddd',
    },
    previewContainer: {
        marginTop: 10,
        marginBottom: 30,
    },
    previewLabel: {
        color: '#666',
        fontSize: 12,
        marginBottom: 5,
        textAlign: 'center',
    },
    previewCard: {
        flexDirection: 'row',
        alignItems: 'center',
        backgroundColor: 'white',
        padding: 20,
        borderRadius: 15,
        elevation: 3,
        shadowColor: '#000',
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.1,
        shadowRadius: 4,
    },
    previewAvatar: {
        width: 50,
        height: 50,
        borderRadius: 25,
        marginRight: 15,
        backgroundColor: '#eee',
    },
    previewName: {
        fontSize: 18,
        fontWeight: 'bold',
        color: '#333',
    },
    previewLevel: {
        color: '#666',
        fontSize: 14,
    },
    saveButton: {
        backgroundColor: '#007AFF',
        padding: 18,
        borderRadius: 12,
        alignItems: 'center',
        shadowColor: '#007AFF',
        shadowOffset: { width: 0, height: 4 },
        shadowOpacity: 0.3,
        shadowRadius: 5,
        marginBottom: 40,
    },
    saveButtonText: {
        color: 'white',
        fontSize: 18,
        fontWeight: 'bold',
    },
    headerBackButton: {
        marginBottom: 20,
        padding: 8,
        alignSelf: 'flex-start',
        backgroundColor: 'rgba(0,0,0,0.05)',
        borderRadius: 20,
    }
});
