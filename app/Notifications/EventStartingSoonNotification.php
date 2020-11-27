<?php

namespace App\Notifications;

use App\Models\OnlineEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventStartingSoonNotification extends Notification
{
    use Queueable;

    public OnlineEvent $onlineEvent;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(OnlineEvent $onlineEvent)
    {
        $this->onlineEvent = $onlineEvent;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $timeUntilStartInMinutes = now()
            ->diffInMinutes($this->onlineEvent->start_time->addMinutes(5));

        return (new MailMessage)
            ->greeting($this->onlineEvent->title . " is starting in $timeUntilStartInMinutes minutes")
            ->line('Click the button below to login and get started!')
            ->action('Notification Action', url(env('EVENT_APP_URL')))
            ->line('See you there!');
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
            //
        ];
    }
}
