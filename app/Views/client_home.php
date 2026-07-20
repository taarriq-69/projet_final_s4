<?= view('partials/client_header', ['pageTitle' => 'Accueil', 'backUrl' => null, 'showBrand' => false, 'screenClass' => 'is-dashboard']) ?>

<div style="margin-bottom:14px;">
    <h2 style="font-size:20px;">Salama, <?= esc($client['nom'] ?? '') ?></h2>
    <p class="hint">Voici l'aperçu de votre compte.</p>
</div>

<div class="home-grid">
    <div>
        <div class="balance-card">
            <span class="eyebrow">Solde disponible</span>
            <div class="balance-value"><?= montant_ar($solde) ?></div>
        </div>

        <div class="action-row">
            <a href="/depot" class="action-btn action-btn-primary"><span class="icn"></span>Dépôt</a>
            <a href="/retrait" class="action-btn action-btn-dark"><span class="icn"></span>Retrait</a>
            <a href="/transfert" class="action-btn action-btn-ghost"><span class="icn"></span>Transfert</a>
        </div>

        <p class="hint" style="text-align:center; margin-bottom:0;">
            <a href="/transfert-multiple">Envoi vers plusieurs numéros</a>
        </p>
    </div>

    <div class="card" style="margin-bottom:0;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
            <h2 style="font-size:15px;">Activités récentes</h2>
            <a href="/historique" style="font-size:12.5px; font-weight:600;">Voir tout</a>
        </div>

        <?php if (empty($activitesRecentes)): ?>
            <p class="hint">Aucune activité pour le moment.</p>
        <?php else: ?>
            <?php foreach ($activitesRecentes as $t): ?>
                <?php $positif = $t->operation === 'DEPOT'; ?>
                <div class="list-row">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <span class="list-icn <?= badge_operation($t->operation) ?>"></span>
                        <div>
                            <div style="font-weight:600; font-size:13.5px;"><?= esc(ucfirst(strtolower($t->operation))) ?></div>
                            <div class="meta"><?= esc($t->date_transaction) ?></div>
                        </div>
                    </div>
                    <div class="amount"><?= $positif ? '+' : '-' ?> <?= montant_ar($t->valeur) ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?= view('partials/client_footer') ?>
