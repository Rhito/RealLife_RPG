import React from 'react';
import { Modal, View, Text, StyleSheet, TouchableOpacity, Dimensions } from 'react-native';

interface CustomAlertButton {
  text: string;
  onPress?: () => void;
  style?: 'default' | 'cancel' | 'destructive';
}

interface CustomAlertProps {
  visible: boolean;
  title: string;
  message: string;
  buttons?: CustomAlertButton[];
  onDismiss: () => void;
}

export const CustomAlert: React.FC<CustomAlertProps> = ({
  visible,
  title,
  message,
  buttons = [],
  onDismiss,
}) => {
  // If no buttons provided, show a default OK button
  const alertButtons = buttons.length > 0 ? buttons : [{ text: 'OK', onPress: onDismiss }];

  return (
    <Modal
      transparent
      visible={visible}
      animationType="fade"
      onRequestClose={onDismiss}
    >
      <View style={styles.overlay}>
        <View style={styles.alertContainer}>
          <Text style={styles.title}>{title}</Text>
          <Text style={styles.message}>{message}</Text>
          
          <View style={styles.buttonContainer}>
            {alertButtons.map((btn, index) => (
              <TouchableOpacity
                key={index}
                style={[
                  styles.button,
                  btn.style === 'cancel' && styles.cancelButton,
                  btn.style === 'destructive' && styles.destructiveButton,
                  // Add margin if not the last button
                  index < alertButtons.length - 1 && styles.buttonMargin
                ]}
                onPress={() => {
                  if (btn.onPress) btn.onPress();
                  onDismiss();
                }}
              >
                <Text style={[
                  styles.buttonText,
                  btn.style === 'cancel' && styles.cancelButtonText,
                  btn.style === 'destructive' && styles.destructiveButtonText
                ]}>
                  {btn.text}
                </Text>
              </TouchableOpacity>
            ))}
          </View>
        </View>
      </View>
    </Modal>
  );
};

const styles = StyleSheet.create({
  overlay: {
    flex: 1,
    backgroundColor: 'rgba(0, 0, 0, 0.7)',
    justifyContent: 'center',
    alignItems: 'center',
    padding: 24,
  },
  alertContainer: {
    backgroundColor: '#1e293b', // Slate 800
    borderRadius: 16,
    padding: 24,
    width: '100%',
    maxWidth: 340,
    borderWidth: 1,
    borderColor: '#334155', // Slate 700
    shadowColor: '#000',
    shadowOffset: {
      width: 0,
      height: 10,
    },
    shadowOpacity: 0.5,
    shadowRadius: 20,
    elevation: 20,
  },
  title: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#f8fafc', // Slate 50
    marginBottom: 8,
    textAlign: 'center',
    letterSpacing: 0.5,
  },
  message: {
    fontSize: 16,
    color: '#94a3b8', // Slate 400
    marginBottom: 24,
    textAlign: 'center',
    lineHeight: 22,
  },
  buttonContainer: {
    flexDirection: 'row',
    justifyContent: 'flex-end',
    flexWrap: 'wrap-reverse', // Allows stacking if too many buttons
    gap: 12,
  },
  button: {
    backgroundColor: '#6366f1', // Indigo 500
    paddingVertical: 12,
    paddingHorizontal: 16,
    borderRadius: 8,
    minWidth: 80,
    alignItems: 'center',
    justifyContent: 'center',
    flex: 1,
  },
  buttonMargin: {
    marginRight: 0, 
  },
  cancelButton: {
    backgroundColor: 'transparent',
    borderWidth: 1,
    borderColor: '#475569', // Slate 600
  },
  destructiveButton: {
    backgroundColor: '#b91c1c', // Red 700
  },
  buttonText: {
    color: '#fff',
    fontWeight: '600',
    fontSize: 16,
  },
  cancelButtonText: {
    color: '#94a3b8', // Slate 400
  },
  destructiveButtonText: {
    color: '#fff',
  },
});
