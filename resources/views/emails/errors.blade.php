@component('mail::message')
# Bonjour,
L'un des champs obligatoire est manquant dans le fichier suivant :
- <strong>{{$path}}</strong>

@component('mail::panel')
Veuillez Régler le problème, et reindéxer le fichier.
@endcomponent

Cordialement,<br>
{{ config('app.name') }}
@endcomponent
