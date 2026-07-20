<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Orange Money - Accueil</title>
</head>
<body>
    <h1>Orange Money</h1>

    <?php if (session()->getFlashdata('error')): ?>
        <p style="color:red;"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <form method="post" action="/">
        <label>Numéro :</label>
        <input type="text" name="numero" placeholder="XX XX XXX XX" required maxlength="9">
        <br><br>
        <button type="submit">Valider</button>
    </form>
</body>
</html>