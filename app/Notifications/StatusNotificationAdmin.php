<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StatusNotificationAdmin extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public $tasklist)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Checksheet marked as due')
            ->greeting('Hello ' . $notifiable->name)
            ->line("This is to inform you that new checksheet has been marked as due todday.")
            ->action('Check Details', route('tasklists.show', $this->tasklist->id))
            ->line("You are supposed to carefully review this update.")
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'id' => $this->tasklist->id,
            // 'type' => $this->tasklist->readableName,
            'title' => 'New ' . $this->tasklist->type . ' checksheet marked as due',
            'user' => $this->tasklist->assignee->name,
        ];
    }
}
