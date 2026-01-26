<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DataStatusUpdated extends Notification
{
    use Queueable;

    public $pju;
    public $status;

    /**
     * Create a new notification instance.
     */
    public function __construct($pju, $status)
    {
        $this->pju = $pju;
        $this->status = $status;
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
        $statusText = $this->status === 'verified' ? 'Diverifikasi' : 'Ditolak';
        $color = $this->status === 'verified' ? 'text-green-500' : 'text-red-500';

        return [
            'type' => 'status_update',
            'title' => "Data {$statusText}",
            'message' => "Data PJU (ID: {$this->pju->id_pelanggan}) Anda telah {$statusText} oleh Verifikator.",
            'url' => route('pju.index', ['search' => $this->pju->id_pelanggan]),
            'status_color' => $color,
        ];
    }
}
