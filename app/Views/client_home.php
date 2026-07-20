<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Orange Money - Accueil</title>
</head>
<body>
    <h1>Bienvenue <?= $client['nom'] ?? $client->nom ?></h1>
    <p>Numéro : <?= $client['numero'] ?? $client->numero ?></p>

    <ul>
        <li><a href="/depot">Dépôt</a></li>
        <li><a href="/solde">Solde</a></li>
        <li><a href="/retrait">Retrait</a></li>
        <li><a href="/transfert">Transfert</a></li>
        <li><a href="/transfert-multiple">Transfert multiple</a></li>
        <li><a href="/historique">Historique</a></li>
    </ul>
</body>
</html>