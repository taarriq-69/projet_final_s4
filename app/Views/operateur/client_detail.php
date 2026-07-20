<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Transactions de <?= esc($client['nom']) ?></title>
</head>
<body>
    <h1>Transactions de <?= esc($client['nom']) ?> (<?= esc($client['numero']) ?>)</h1>

    <form method="get" action="/operateur/clients/<?= esc($client['id']) ?>">
        <label>Date début :</label>
        <input type="date" name="date_debut" value="<?= esc($date_debut ?? '') ?>">
        <label>Date fin :</label>
        <input type="date" name="date_fin" value="<?= esc($date_fin ?? '') ?>">
        <button type="submit">Filtrer</button>
    </form>

    <br>

    <?php if (!empty($transactions)): ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>Date</th>
                <th>Opération</th>
                <th>Valeur</th>
                <th>Frais</th>
            </tr>
            <?php foreach ($transactions as $t): ?>
                <tr>
                    <td><?= esc($t->date_transaction) ?></td>
                    <td><?= esc($t->operation) ?></td>
                    <td><?= esc($t->valeur) ?></td>
                    <td><?= esc($t->frais) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Aucune transaction trouvée.</p>
    <?php endif; ?>

    <br>
    <a href="/operateur/clients">Retour</a>
</body>
</html>
