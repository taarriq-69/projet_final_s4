<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dépôt</title>
</head>
<body>
    <h1>Faire un dépôt</h1>

    <?php if (session()->getFlashdata('message')): ?>
        <p style="color:green;"><?= session()->getFlashdata('message') ?></p>
    <?php endif; ?>

    <form method="post" action="/depot">
        <label>Montant :</label>
        <input type="number" name="valeur" required>
        <br><br>
        <button type="submit">Déposer</button>
    </form>
</body>
</html>