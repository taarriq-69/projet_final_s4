<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Solde</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .solde-box { font-size: 1.3em; margin-top: 10px; padding: 10px; background: #eef7ee; border: 1px solid #cde5cd; display: inline-block; }
    </style>
</head>
<body>
    <h1>Solde de <?= esc($client['nom']) ?></h1>
    <p>Numéro : <?= esc($client['numero']) ?></p>

    <div class="solde-box">
        Solde total : <strong><?= number_format($solde, 0, ',', ' ') ?> Ar</strong>
    </div>

    <h2>Historique des transactions</h2>

    <?php if (empty($transactions)): ?>
        <p>Aucune transaction pour le moment.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Opération</th>
                    <th>Montant</th>
                    <th>Frais</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $t): ?>
                    <tr>
                        <td><?= esc($t['date_transaction']) ?></td>
                        <td><?= esc($t['operation']) ?></td>
                        <td><?= number_format($t['valeur'], 0, ',', ' ') ?> Ar</td>
                        <td><?= number_format($t['frais'], 0, ',', ' ') ?> Ar</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <br>
    <a href="/home">Retour</a>
</body>
</html>