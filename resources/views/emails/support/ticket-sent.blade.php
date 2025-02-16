@component('mail::message')
Your ticket (#{{ $ticket->id }}) has been created.

A member of staff will reply shortly.

@component('mail::button', ['url' => route('support.ticket.show', $ticket)])
View Ticket
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
