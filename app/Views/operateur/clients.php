<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Clients - Opérateur</title>
</head>
<body>
    <h1>Liste des clients</h1>

    <?php if (!empty($clients)): ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>Nom</th>
                <th>Numéro</th>
                <th>Date de création</th>
                <th></th>
            </tr>
            <?php foreach ($clients as $c): ?>
                <tr>
                    <td><?= esc($c['nom']) ?></td>
                    <td><?= esc($c['numero']) ?></td>
                    <td><?= esc($c['date_creation']) ?></td>
                    <td><a href="/operateur/clients/<?= esc($c['id']) ?>">Voir les transactions</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Aucun client trouvé.</p>
    <?php endif; ?>

    <br>
    <a href="/operateur">Retour</a>
</body>
</html>
