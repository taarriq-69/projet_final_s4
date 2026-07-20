<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Transfert</title>
</head>
<body>
    <h1>Faire un transfert</h1>

    <?php if (session()->getFlashdata('message')): ?>
        <p style="color:green;"><?= session()->getFlashdata('message') ?></p>
    <?php endif; ?>

    <p>Fonctionnalité à venir.</p>

    <a href="/home">Retour</a>
</body>
</html>