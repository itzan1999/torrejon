@component('mail::message')

<div style="text-align: center; margin-bottom: 20px;">
   <img src="cid:logo" alt="Logo Leche El Torrejon" style="max-width: 200px;">
</div>

# Cambiar Contraseña
Hola {{ $nombreCompleto }},

Para cambiar tu contraseña, por favor haz clic en el siguiente botón:

@component('mail::button', ['url' => url('/cambiar-password?token=' . $token)])
Cambiar Contraseña
@endcomponent

El enlace expirará en 24 horas.
Saludos,
{{ config('app.name') }}
@endcomponent