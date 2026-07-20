<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gains - Opérateur</title>
</head>
<body>
    <h1>Gains par opération</h1>

    <p>Gain global : <strong><?= number_format($gainGlobal, 0, ',', ' ') ?></strong></p>

    <form method="get" action="/operateur/gains">
        <label>Opération :</label>
        <select name="operation">
            <option value="">Toutes</option>
            <?php foreach ($operations as $op): ?>
                <option value="<?= esc($op) ?>" <?= $operation === $op ? 'selected' : '' ?>><?= esc($op) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Recherche :</label>
        <input type="text" name="recherche" value="<?= esc($recherche) ?>">

        <button type="submit">Filtrer</button>
        <a href="/operateur/gains">Réinitialiser</a>
    </form>

    <br>

    <?php if (!empty($gains)): ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>Opération</th>
                <th>Nombre de transactions</th>
                <th>Gain total</th>
            </tr>
            <?php foreach ($gains as $g): ?>
                <tr>
                    <td><?= esc($g['type_operation']) ?></td>
                    <td><?= esc($g['nombre_transactions']) ?></td>
                    <td><?= esc($g['gain_total']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Aucun résultat.</p>
    <?php endif; ?>

    <br>
    <a href="/home">Retour</a>
</body>
</html>
