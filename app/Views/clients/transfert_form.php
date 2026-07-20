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
        <input type="text" name="numero_destinataire" id="numero_destinataire" required>
        <br><br>
        <label>Montant :</label>
        <input type="number" name="valeur" min="1" required>
        <br><br>
        <label for="fraisRetrait">Envoyer avec frais de retrait : </label>
        <input type="checkbox" name="fraisRetrait" id="fraisRetrait" value="1">
        <p id="note-operateur" style="display:none;color:#b06000;">
            Le destinataire appartient à un autre opérateur : l'option "frais de retrait"
            ne s'applique pas, une commission de l'opérateur externe sera appliquée à la place.
        </p>
        <br>
        <button type="submit">Transférer</button>
    </form>

    <br>
    <a href="/transfert-multiple">Envoyer vers plusieurs numéros</a>
    <br>
    <a href="/home">Retour</a>

    <script>
        const prefixes = <?= json_encode($prefixes ?? []) ?>;
        const operateurClient = <?= json_encode($operateurClient ?? null) ?>;

        function getOperateur(numero) {
            for (const p of prefixes) {
                const prefixeStr = String(p.prefixe);
                if (numero.startsWith(prefixeStr)) {
                    return p.operateur;
                }
            }
            return null;
        }

        document.getElementById('numero_destinataire').addEventListener('input', function () {
            const checkbox = document.getElementById('fraisRetrait');
            const note = document.getElementById('note-operateur');
            const operateurDestinataire = getOperateur(this.value.trim());

            if (operateurDestinataire !== null && operateurDestinataire !== operateurClient) {
                checkbox.checked = false;
                checkbox.disabled = true;
                note.style.display = 'block';
            } else {
                checkbox.disabled = false;
                note.style.display = 'none';
            }
        });
    </script>
</body>
</html>
