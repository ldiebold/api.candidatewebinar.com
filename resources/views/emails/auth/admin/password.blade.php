@component('mail::message')
# Hey {{ $user->name }}!

{{ $uplineName }} has created you an account for [{{ env('APP_NAME') }}]({{ env('HOME_URL') }}).
Here are your login details...

Email: **{{ $user->email }}**<br>
Password: **{{ $password }}**

And these are the apps you have access to with this account:

@component('mail::button', ['url' => env('IBO_APP_URL')])
Partners
@endcomponent
The Partners (or IBO) app is for creating candidates, and sending them invitations to events.

@component('mail::button', ['url' => env('EVENTS_APP_URL')])
Events
@endcomponent
The Events app is where you, and candidates can watch information sessions.

@if($user->role === 'admin' || $user->role == 'super admin')
@component('mail::button', ['url' => env('ADMIN_APP_URL')])
Admin
@endcomponent
This is where you can create IBOs{{ $user->role === 'super admin' ? ' and information sessions.' : '.' }}<br>
@endif
<br>

**We highly recommend logging in now, just to check that everything is working, and to watch the introduction video!**

Regards,<br>
The Candidate Webinars Team
@endcomponent
