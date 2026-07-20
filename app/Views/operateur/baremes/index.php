<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Barèmes - Opérateur</title>
</head>
<body>
    <h1>Barèmes</h1>

    <?php if (session()->getFlashdata('message')): ?>
        <p style="color:green;"><?= session()->getFlashdata('message') ?></p>
    <?php elseif (session()->getFlashdata('error')): ?>
        <p style="color:red;"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <p><a href="/operateur/baremes/ajouter">Ajouter un barème</a></p>

    <?php if (!empty($baremes)): ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>Opération</th>
                <th>Valeur min</th>
                <th>Valeur max</th>
                <th>Frais</th>
                <th></th>
            </tr>
            <?php foreach ($baremes as $b): ?>
                <tr>
                    <td><?= esc($b->type_operation) ?></td>
                    <td><?= esc($b->valeur_min) ?></td>
                    <td><?= esc($b->valeur_max) ?></td>
                    <td><?= esc($b->frais) ?></td>
                    <td>
                        <a href="/operateur/baremes/modifier/<?= esc($b->id) ?>">Modifier</a>
                        <form method="post" action="/operateur/baremes/supprimer/<?= esc($b->id) ?>" style="display:inline" onsubmit="return confirm('Supprimer ce barème ?');">
                            <button type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Aucun barème trouvé.</p>
    <?php endif; ?>

    <br>
    <a href="/operateur">Retour</a>
</body>
</html>
