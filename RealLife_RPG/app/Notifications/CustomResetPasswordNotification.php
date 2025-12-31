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
        
        // For native app, use deep link as primary
        $appDeepLink = config('app.mobile_url', 'realliferpg://') . 'reset-password?token=' . $this->token . '&email=' . urlencode($notifiable->email);

        return (new MailMessage)
            ->subject('Reset Your Password')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->line('Tap the button below to reset your password in the app:')
            ->action('Reset Password', $appDeepLink)
            ->line('If the button doesn\'t work, copy and paste this link into your browser:')
            ->line($resetUrl)
            ->line('This password reset link will expire in ' . config('auth.passwords.'.config('auth.defaults.passwords').'.expire') . ' minutes.')
            ->line('If you did not request a password reset, no further action is required.');
    }

    /**
     * Get the reset URL for the given notifiable.
     * This is a fallback web URL that redirects to the mobile app.
     */
    protected function resetUrl($notifiable): string
    {
        // Use Laravel backend URL instead of non-existent frontend
        $backendUrl = config('app.url');
        
        return $backendUrl . '/password-reset-web?token=' . $this->token . '&email=' . urlencode($notifiable->getEmailForPasswordReset());
    }
}
