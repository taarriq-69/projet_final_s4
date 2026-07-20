<?= view('partials/client_header', ['pageTitle' => 'Solde', 'backUrl' => '/home', 'showBrand' => false]) ?>

<div class="balance-card">
    <span class="eyebrow">Solde disponible</span>
    <div class="balance-value"><?= montant_ar($solde) ?></div>
    <div class="balance-numero"><?= esc($client['nom']) ?> · <?= esc($client['numero']) ?></div>
</div>

<div class="card">
    <h2>Historique des transactions</h2>

    <?php if (empty($transactions)): ?>
        <p class="hint">Aucune transaction pour le moment.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Opération</th>
                    <th class="num">Montant</th>
                    <th class="num">Frais</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $t): ?>
                    <tr>
                        <td><?= esc($t['date_transaction']) ?></td>
                        <td><span class="badge <?= badge_operation($t['operation']) ?>"><?= esc($t['operation']) ?></span></td>
                        <td class="num"><?= montant_ar($t['valeur']) ?></td>
                        <td class="num"><?= montant_ar($t['frais']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?= view('partials/client_footer') ?>
