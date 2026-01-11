import { View, Text, TextInput, StyleSheet, TouchableOpacity, Image, ScrollView } from 'react-native';
import { useState } from 'react';
import { useAuth } from '../context/AuthContext';
import { updateProfile } from '../services/profile';
import { useRouter } from 'expo-router';
import { Ionicons } from '@expo/vector-icons';
import { useAlert } from '../context/AlertContext';
import { useTranslation } from 'react-i18next';
import * as SecureStore from 'expo-secure-store';

// Pre-defined avatars
const AVATARS = [
    'https://api.dicebear.com/7.x/avataaars/png?seed=Felix',
    'https://api.dicebear.com/7.x/avataaars/png?seed=Aneka',
    'https://api.dicebear.com/7.x/avataaars/png?seed=Zack',
    'https://api.dicebear.com/7.x/avataaars/png?seed=Trou',
    'https://api.dicebear.com/7.x/avataaars/png?seed=Molly',
    'https://api.dicebear.com/7.x/avataaars/png?seed=Leo',
];

export default function SettingsScreen() {
    const { user, setUser } = useAuth();
    const router = useRouter();
    const { showAlert } = useAlert();
    const { t, i18n } = useTranslation();
    
    const [name, setName] = useState(user?.name || '');
    const [avatar, setAvatar] = useState(user?.avatar || AVATARS[0]);
    const [loading, setLoading] = useState(false);

    const changeLanguage = async (lang: string) => {
        try {
            await i18n.changeLanguage(lang);
            await SecureStore.setItemAsync('user-language', lang);
        } catch (e) {
            console.log(e);
        }
    };

    const handleSave = async () => {
        if (!name) {
            showAlert(t('common.error'), 'Name is required');
            return;
        }
        setLoading(true);
        try {
            await updateProfile({ name, avatar });
            if (user) {
                setUser({ ...user, name, avatar });
            }
            showAlert(t('common.success'), 'Profile updated!');
            router.back();
        } catch (e: any) {
            showAlert(t('common.error'), e.message);
        } finally {
            setLoading(false);
        }
    };

    return (
        <ScrollView style={styles.container}>
             <TouchableOpacity style={styles.headerBackButton} onPress={() => router.back()}>
                <Ionicons name="arrow-back" size={24} color="#FFF" />
            </TouchableOpacity>

            <Text style={styles.sectionTitle}>{t('settings.profile')}</Text>
            
            <Text style={styles.label}>{t('auth.email')}</Text>
            <View style={styles.readOnlyInput}>
                <Text style={styles.readOnlyText}>{user?.email}</Text>
            </View>

            <Text style={styles.label}>Display Name</Text>
            <TextInput 
                style={styles.input} 
                value={name} 
                onChangeText={setName} 
                placeholder="Your Name"
                placeholderTextColor="#BBAADD"
            />

            <Text style={styles.label}>Choose Avatar</Text>
            <View style={styles.avatarGrid}>
                {AVATARS.map((url, index) => (
                    <TouchableOpacity 
                        key={index} 
                        onPress={() => setAvatar(url)}
                        style={[
                            styles.avatarOption, 
                            avatar === url && styles.selectedAvatar
                        ]}
                    >
                        <Image source={{ uri: url }} style={styles.avatarImage} />
                    </TouchableOpacity>
                ))}
            </View>
            
            <View style={styles.previewContainer}>
                 <Text style={styles.previewLabel}>Preview</Text>
                 <View style={styles.previewCard}>
                     <Image source={{ uri: avatar }} style={styles.previewAvatar} />
                     <View>
                        <Text style={styles.previewName}>{name || 'User Name'}</Text>
                        <Text style={styles.previewLevel}>Level {user?.level ?? 1}</Text>
                     </View>
                 </View>
            </View>

            <Text style={styles.sectionTitle}>{t('settings.language')}</Text>
            <View style={styles.langContainer}>
                <TouchableOpacity 
                    style={[styles.langButton, i18n.language === 'en' && styles.selectedLang]}
                    onPress={() => changeLanguage('en')}
                >
                    <Text style={[styles.langText, i18n.language === 'en' && styles.selectedLangText]}>English</Text>
                </TouchableOpacity>
                <TouchableOpacity 
                    style={[styles.langButton, i18n.language === 'vi' && styles.selectedLang]}
                    onPress={() => changeLanguage('vi')}
                >
                    <Text style={[styles.langText, i18n.language === 'vi' && styles.selectedLangText]}>Tiếng Việt</Text>
                </TouchableOpacity>
            </View>

            <TouchableOpacity style={styles.saveButton} onPress={handleSave} disabled={loading}>
                <Text style={styles.saveButtonText}>{loading ? t('common.loading') : t('common.save')}</Text>
            </TouchableOpacity>
        </ScrollView>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        padding: 20,
        backgroundColor: '#342056',
        paddingTop: 60,
    },
    sectionTitle: {
        fontSize: 20,
        fontWeight: 'bold',
        color: '#FF9800',
        marginBottom: 15,
        marginTop: 10,
    },
    label: {
        fontSize: 16,
        fontWeight: 'bold',
        marginBottom: 8,
        marginTop: 10,
        color: '#E0E7FF',
    },
    input: {
        backgroundColor: '#432874',
        padding: 15,
        borderRadius: 12,
        marginBottom: 20,
        borderWidth: 1,
        borderColor: '#5B4290',
        fontSize: 16,
        color: '#FFF',
    },
    readOnlyInput: {
        backgroundColor: '#2D1B4E',
        padding: 15,
        borderRadius: 12,
        marginBottom: 20,
        borderWidth: 1,
        borderColor: '#333',
    },
    readOnlyText: {
        color: '#888',
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
        borderColor: '#FF9800',
    },
    avatarImage: {
        width: 60,
        height: 60,
        borderRadius: 30,
        backgroundColor: '#432874',
    },
    previewContainer: {
        marginTop: 10,
        marginBottom: 30,
    },
    previewLabel: {
        color: '#BBAADD',
        fontSize: 12,
        marginBottom: 5,
        textAlign: 'center',
    },
    previewCard: {
        flexDirection: 'row',
        alignItems: 'center',
        backgroundColor: '#432874',
        padding: 20,
        borderRadius: 15,
        borderWidth: 1,
        borderColor: '#5B4290',
    },
    previewAvatar: {
        width: 50,
        height: 50,
        borderRadius: 25,
        marginRight: 15,
        backgroundColor: '#333',
    },
    previewName: {
        fontSize: 18,
        fontWeight: 'bold',
        color: '#FFF',
    },
    previewLevel: {
        color: '#FFD700',
        fontSize: 14,
    },
    langContainer: {
        flexDirection: 'row',
        gap: 10,
        marginBottom: 30,
    },
    langButton: {
        flex: 1,
        padding: 12,
        backgroundColor: '#432874',
        borderRadius: 10,
        alignItems: 'center',
        borderWidth: 1,
        borderColor: '#5B4290',
    },
    selectedLang: {
        backgroundColor: '#FF9800',
        borderColor: '#FF9800',
    },
    langText: {
        color: '#E0E7FF',
        fontWeight: '600',
    },
    selectedLangText: {
        color: '#FFF',
        fontWeight: 'bold',
    },
    saveButton: {
        backgroundColor: '#FF9800',
        padding: 18,
        borderRadius: 12,
        alignItems: 'center',
        shadowColor: '#FF9800',
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
        marginBottom: 10,
        padding: 8,
        alignSelf: 'flex-start',
        backgroundColor: '#432874',
        borderRadius: 20,
        borderWidth: 1,
        borderColor: '#5B4290',
    }
});
