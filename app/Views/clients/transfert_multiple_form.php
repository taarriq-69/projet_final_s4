<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Transfert multiple</title>
</head>
<body>
    <h1>Envoyer vers plusieurs numéros</h1>

    <?php if (session()->getFlashdata('message')): ?>
        <p style="color:green;"><?= session()->getFlashdata('message') ?></p>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <p style="color:red;"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <form method="post" action="/transfert-multiple">
        <div id="numeros-container">
            <div class="ligne-numero">
                <label>Numero :</label>
                <input type="text" name="numeros[]" required>
            </div>
        </div>

        <button type="button" id="ajouter-numero">+ Ajouter un numéro</button>

        <br><br>
        <label>Montant  :</label>
        <input type="number" name="montant" min="1" required>

        <br>
        <button type="submit">Transférer</button>
    </form>

    <br>
    <a href="/transfert">Transfert simple</a>
    <br>
    <a href="/home">Retour</a>

    <script>
        document.getElementById('ajouter-numero').addEventListener('click', function () {
            const container = document.getElementById('numeros-container');
            const ligne = document.createElement('div');
            ligne.className = 'ligne-numero';
            ligne.innerHTML = '<label>Numéro :</label> <input type="text" name="numeros[]" required> ' +
                '<button type="button" class="retirer-numero">−</button>';
            container.appendChild(ligne);

            ligne.querySelector('.retirer-numero').addEventListener('click', function () {
                ligne.remove();
            });
        });
    </script>
</body>
</html>