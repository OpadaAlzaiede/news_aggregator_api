<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Validation\ValidationException;

class CustomPasswordResetNotification extends ResetPassword
{

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable) {

        $url = $this->getNotificationEndpoint($notifiable);

        return $this->buildMailMessage($url);
    }

    /**
     * Here the method returns the frontend endpoint to show a form to update the user password.
     *
     * @param $notifiable
     *
     * @return string
     * @throws ValidationException
     */
    public function getNotificationEndpoint($notifiable): string {

        if(! $endpoint = config('app.new_password_form_url')) {
            throw ValidationException::withMessages([
                'message' => 'please add a frontend endpoint to send email with the link.'
            ]);
        }
        return $endpoint . "?token={$this->token}&email={$notifiable->getEmailForPasswordReset()}";
    }
}
