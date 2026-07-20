<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Retrait</title>
</head>
<body>
    <h1>Faire un retrait</h1>

    <?php if (session()->getFlashdata('message')): ?>
        <p style="color:green;"><?= session()->getFlashdata('message') ?></p>
    <?php elseif (session()->getFlashdata('error')): ?>
        <p style="color:red;"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <form method="post" action="/retrait">
        <label>Montant :</label>
        <input type="number" name="valeur" required>
        <br><br>
        <button type="submit">Retirer</button>
    </form>
</body>
</html>