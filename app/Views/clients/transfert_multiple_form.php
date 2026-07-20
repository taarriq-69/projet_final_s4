<?= view('partials/client_header', ['pageTitle' => 'Envoi multiple', 'backUrl' => '/home', 'showBrand' => false]) ?>

<div class="card">
    <p class="hint" style="margin-bottom:16px;">Le montant est réparti à parts égales entre tous les numéros. Tous les numéros doivent appartenir au même opérateur.</p>

    <form method="post" action="/transfert-multiple">
        <div id="numeros-container">
            <div class="ligne-numero field" style="display:flex; gap:8px; align-items:center;">
                <input type="text" name="numeros[]" placeholder="Numéro" required style="flex:1;">
            </div>
        </div>

        <button type="button" id="ajouter-numero" class="btn btn-ghost btn-sm" style="margin-bottom:16px;">+ Ajouter un numéro</button>

        <div class="field">
            <label>Montant total (Ar)</label>
            <input type="number" name="montant" min="1" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Transférer</button>
    </form>
</div>

<p class="hint" style="text-align:center;">
    <a href="/transfert">Transfert simple</a>
</p>

<script>
    document.getElementById('ajouter-numero').addEventListener('click', function () {
        const container = document.getElementById('numeros-container');
        const ligne = document.createElement('div');
        ligne.className = 'ligne-numero field';
        ligne.style.cssText = 'display:flex; gap:8px; align-items:center;';
        ligne.innerHTML = '<input type="text" name="numeros[]" placeholder="Numéro" required style="flex:1;">' +
            '<button type="button" class="btn btn-danger btn-sm retirer-numero">−</button>';
        container.appendChild(ligne);

        ligne.querySelector('.retirer-numero').addEventListener('click', function () {
            ligne.remove();
        });
    });
</script>

<?= view('partials/client_footer') ?>
