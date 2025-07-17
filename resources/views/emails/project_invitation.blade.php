<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h1>Invitation à rejoindre le projet "{{ $invitation->project->name }}"</h1>

    <p>Bonjour,</p>

    <p>{{ $invitation->sender->name }} vous invite à rejoindre le projet <strong>{{ $invitation->project->name }}</strong>.</p>

    <p>
        <a href="{{ $acceptUrl }}" style="background-color: #3b82f6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
            Accepter l'invitation
        </a>
    </p>

    <p>Si vous n'avez pas de compte, vous devrez vous inscrire avant d'accéder au projet.</p>

    <p>Merci.</p>
</body>
</html>
