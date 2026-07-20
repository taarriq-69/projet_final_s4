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

    <?php if (session()->getFlashdata('error')): ?>
        <p style="color:red;"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <form method="post" action="/transfert">
        <label>Numéro du destinataire :</label>
        <input type="text" name="numero_destinataire" required>
        <br><br>
        <label>Montant :</label>
        <input type="number" name="valeur" min="1" required>
        <br><br>
        <label for="fraisRetrait">Envoyer avec frais de retrait : </label>
        <input type="checkbox" name="fraisRetrait" id="fraisRetrait" value="1">
        <br><br>
        <button type="submit">Transférer</button>
    </form>

    <br>
    <a href="/home">Retour</a>
</body>
</html>