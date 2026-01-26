<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewDataSubmission extends Notification
{
    use Queueable;

    public $pju;
    public $senderName;

    /**
     * Create a new notification instance.
     */
    public function __construct($pju, $senderName)
    {
        $this->pju = $pju;
        $this->senderName = $senderName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'submission',
            'title' => 'Input Data Baru',
            'message' => "Petugas {$this->senderName} menginput data PJU baru (ID: {$this->pju->id_pelanggan}).",
            'url' => route('pju.verification', ['search' => $this->pju->id_pelanggan]),
            'icon' => '<svg class="fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>'
        ];
    }
}
