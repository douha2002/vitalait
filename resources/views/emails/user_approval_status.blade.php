<!DOCTYPE html>
<html>
<head>
    <title>Statut de votre inscription</title>
</head>
<body>
    <p>Bonjour {{ $user->name }},</p>

    @if($status == 'approved')
        <p>Félicitations ! Votre inscription a été approuvée. Vous pouvez maintenant vous connecter à votre compte.</p>
        <p><a href="{{ url('/login') }}">Se connecter</a></p>
    @else
        <p>Nous sommes désolés, mais votre demande d'inscription a été rejetée.</p>
    @endif

    <p>Cordialement,</p>
    <p>L'équipe de gestion du parc informatique</p>
</body>
</html>
