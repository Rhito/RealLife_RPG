<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPasswordNotification extends ResetPasswordNotification
{
    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $resetUrl = $this->resetUrl($notifiable);
        
        return (new MailMessage)
            ->subject('Reset Your Password')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', $resetUrl)
            ->line('This password reset link will expire in ' . config('auth.passwords.'.config('auth.defaults.passwords').'.expire') . ' minutes.')
            ->line('If you did not request a password reset, no further action is required.');
    }

    /**
     * Get the reset URL for the given notifiable.
     */
    protected function resetUrl($notifiable): string
    {
        // Use Laravel backend URL for the web form
        $backendUrl = config('app.url');
        
        return $backendUrl . '/password-reset?token=' . $this->token . '&email=' . urlencode($notifiable->getEmailForPasswordReset());
    }
}
