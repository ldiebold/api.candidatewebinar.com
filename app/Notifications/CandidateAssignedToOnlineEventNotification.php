<?php

namespace App\Notifications;

use App\Models\OnlineEvent;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;

class CandidateAssignedToOnlineEventNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public OnlineEvent $onlineEvent;
    public User $candidate;
    public User $upline;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $candidate, OnlineEvent $onlineEvent)
    {
        $this->onlineEvent = $onlineEvent;
        $this->candidate = $candidate;
        $this->upline = $candidate->upline;
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
        return (new MailMessage)
            ->subject($this->upline->name . ' has secured you an invite to ' . $this->onlineEvent->title)
            ->greeting($this->upline->name . " secured you a spot on " . $this->onlineEvent->start_time->format('l jS F'))
            ->line("You've been invited to attend the Candidate Education Session.")
            ->line("START TIME: " . $this->onlineEvent->start_time->format('l jS F') . ".")
            ->line("Please click the button below to test your login.")
            ->action('Login', url(env('EVENT_APP_URL')))
            ->line("You will receive a reminder email - with your login link - 15 minutes prior. Be sure to allow yourself adequate space to be there on time.")
            ->line('Remember to bring a pen and paper so you can write down any questions!')
            ->salutation('Regards, The Events Team.');
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
