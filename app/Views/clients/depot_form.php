<?= view('partials/client_header', ['pageTitle' => 'Dépôt', 'backUrl' => '/home', 'showBrand' => false]) ?>

<div class="card">
    <form method="post" action="/depot">
        <div class="field">
            <label>Montant à déposer (Ar)</label>
            <input type="number" name="valeur" min="1" required autofocus>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Déposer</button>
    </form>
</div>

<?= view('partials/client_footer') ?>
