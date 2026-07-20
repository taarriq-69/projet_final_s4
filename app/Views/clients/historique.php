<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique</title>
</head>
<body>
    <h1>Historique des transactions</h1>

    <form method="get" action="/historique">
        <label>Date début :</label>
        <input type="date" name="date_debut" value="<?= $date_debut ?? '' ?>">
        <label>Date fin :</label>
        <input type="date" name="date_fin" value="<?= $date_fin ?? '' ?>">
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
                    <td><?= $t->date_transaction ?></td>
                    <td><?= $t->operation ?></td>
                    <td><?= $t->valeur ?></td>
                    <td><?= $t->frais ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Aucune transaction trouvée.</p>
    <?php endif; ?>

    <br>
    <a href="/home">Retour</a>
</body>
</html>