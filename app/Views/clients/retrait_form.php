<?= view('partials/client_header', ['pageTitle' => 'Retrait', 'backUrl' => '/home', 'showBrand' => false]) ?>

<div class="card">
    <form method="post" action="/retrait">
        <div class="field">
            <label>Montant à retirer (Ar)</label>
            <input type="number" name="valeur" min="1" required autofocus>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Retirer</button>
    </form>
</div>

<?= view('partials/client_footer') ?>
