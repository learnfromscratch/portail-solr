@component('mail::message')
# Nouveaux articles

De nouveaux articles sont disponibles, visitez notre portail pour les consulter.

@component('mail::button', ['url' => 'https://app.dev/home'])
Visiter le site
@endcomponent

Merci,<br>
{{ config('app.name') }}
@endcomponent
