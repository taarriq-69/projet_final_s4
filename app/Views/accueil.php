<?= view('partials/client_header', ['pageTitle' => 'AriaryPay', 'backUrl' => null]) ?>

<div class="admin-link">
    <a href="/operateur" class="btn btn-ghost btn-sm">Admin</a>
</div>

<div style="text-align:center; margin-bottom:20px;">
    <div class="op-logo-mark" style="margin:0 auto 12px;"></div>
    <p class="hint">Votre numéro est votre compte. Aucune inscription requise.</p>
</div>

<div class="card">
    <form method="post" action="/">
        <div class="field">
            <label>Numéro de téléphone</label>
            <div class="input-group">
                <span class="prefix">+261</span>
                <input type="text" name="numero" placeholder="XX XX XXX XX" required maxlength="9" autofocus>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Valider</button>
    </form>
</div>

<?= view('partials/client_footer') ?>
