@component('mail::message')
You have been invited to register in {{ config('app.name') }} by your organization as {{ $role }}.

Invitation sender: {{ $inviteSender }}<br/>
Registration links expires at: {{ $expirationTime }}

@component('mail::button', ['url' => $registrationLink])
Register
@endcomponent

Best regards,<br>
{{ config('app.name') }}
@endcomponent
