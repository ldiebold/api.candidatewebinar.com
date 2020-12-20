<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;

class SendFullUserAccountPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public User $user;
    public User $upline;
    public string $password;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, string $password)
    {
        $this->user = $user;
        $this->upline = $user->upline;
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $via = ['mail'];

        // if ($this->user->phone_number) {
        //     array_push($via, 'nexmo');
        // }

        return $via;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $name = $this->user->name;
        $uplineName = $this->upline->name;

        return (new MailMessage)
            ->subject("Candidate Webinars - Account Details")
            ->markdown('emails.auth.admin.password', [
                'user' => $this->user,
                'uplineName' => $uplineName,
                'password' => $this->password
            ]);

        // $mailMessage = (new MailMessage)
        //     ->subject("Candidate Webinars Account Details")
        //     ->greeting("Hey $name!")
        //     ->line("$uplineName has created you an account for CandidateWebinar.com")
        //     ->line('Here are your login details...')
        //     ->line('Email: ' . $this->user->email)
        //     ->line('Password: ' . $this->password)
        //     ->line('Here are the apps you can login to with this account:')
        //     ->action('Partners', url(env('IBO_APP_URL')))
        //     ->line('The Partners (or IBO) app is for creating candidates, and assigning them to events.')
        //     ->action('Events', url(env('EVENTS_APP_URL')))
        //     ->line('The Events app if where you, and candidates can watch information sessions.');

        // if ($this->user->role === 'admin' || $this->user->role === 'super admin') {
        //     $mailMessage->action('Admin', url(env('ADMIN_APP_URL')))
        //         ->line('This is where you can create IBOs' . $this->user->role === 'super admin' ? ', and information sessions.' : '.');
        // }

        // $mailMessage->line('We highly recommend logging in now, just to check that everything is working, and to watch the introduction video!')
        //     ->salutation('Regards, The Events Team');

        // return $mailMessage;
    }

    /**
     * Get the Nexmo / SMS representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return NexmoMessage
     */
    public function toNexmo($notifiable)
    {
        // $recipient =  $this->user;
        // $upline =  $this->upline;
        // $password =  $this->password;
        // $loginUrl = url(env('EVENTS_APP_URL'));
        // return (new NexmoMessage)
        //     ->content("Hey $recipient->name! $upline->name has invited you to an information sessions. To test your login: 1. Go to $loginUrl 2. Enter the email $recipient->email 3. Your password id $password");
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
