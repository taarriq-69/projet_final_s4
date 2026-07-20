<?= view('partials/client_header', ['pageTitle' => 'Transfert', 'backUrl' => '/home', 'showBrand' => false]) ?>

<div class="card">
    <form method="post" action="/transfert">
        <div class="field">
            <label>Numéro du destinataire</label>
            <input type="text" name="numero_destinataire" id="numero_destinataire" required>
        </div>

        <div class="field">
            <label>Montant (Ar)</label>
            <input type="number" name="valeur" min="1" required>
        </div>

        <div class="checkbox-row">
            <input type="checkbox" name="fraisRetrait" id="fraisRetrait" value="1">
            <label for="fraisRetrait" style="margin:0;">Envoyer avec frais de retrait</label>
        </div>

        <p id="note-operateur" class="hint-gold" style="display:none;">
            Le destinataire appartient à un autre opérateur : l'option « frais de retrait » ne s'applique pas,
            une commission de l'opérateur externe sera appliquée à la place.
        </p>

        <button type="submit" class="btn btn-primary btn-block" style="margin-top:8px;">Transférer</button>
    </form>
</div>

<p class="hint" style="text-align:center;">
    <a href="/transfert-multiple">Envoyer vers plusieurs numéros</a>
</p>

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

<?= view('partials/client_footer') ?>
