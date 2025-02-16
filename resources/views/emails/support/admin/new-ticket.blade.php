@component('mail::message')

A new support ticket (#{{ $ticket->id }}) has been created by {{ $ticket->user->name }}.

The ticket subject is:
- {{ $ticket->subject }}

@component('mail::button', ['url' => route('backend.support.show', $ticket)])
View Ticket
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
