<?= view('partials/operateur_header', ['pageTitle' => 'Montants à envoyer', 'activeNav' => 'montants']) ?>

<div class="card">
    <h2>Situation des montants à envoyer à chaque opérateur</h2>
    <p class="hint" style="margin-bottom:16px;">
        Montant net dû à chaque opérateur externe (valeur transférée + commission), hors frais de transfert qui restent chez nous.
    </p>

    <?php if (!empty($montants)): ?>
        <table>
            <thead>
                <tr>
                    <th>Opérateur</th>
                    <th class="num">Transactions</th>
                    <th class="num">Montant à envoyer</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($montants as $m): ?>
                    <tr>
                        <td><span class="badge badge-transfert"><?= esc($m['operateur']) ?></span></td>
                        <td class="num"><?= esc($m['nombre_transactions']) ?></td>
                        <td class="num"><?= montant_ar($m['montant_total']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="hint">Aucun montant à envoyer pour le moment.</p>
    <?php endif; ?>
</div>

<?= view('partials/operateur_footer') ?>
