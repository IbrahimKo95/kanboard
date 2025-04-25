<h1>Formulaire d'inscription</h1>
<form method="POST" action="{{ route('register') }}">
    @csrf
    <input type="text" name="firstname" placeholder="Prénom">
    <input type="text" name="lastname" placeholder="Nom">
    <input type="email" name="email" placeholder="Adresse email">
    <input type="password" name="password" placeholder="Mot de passe">
    <input type="password" name="password_confirmation" placeholder="Confirmation">
    <button type="submit">S'inscrire</button>
</form>
