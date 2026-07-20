<?= view('partials/client_header', ['pageTitle' => 'Historique', 'backUrl' => '/home', 'showBrand' => false]) ?>

<div class="card">
    <form method="get" action="/historique" style="display:flex; gap:10px; align-items:end; flex-wrap:wrap; margin-bottom:16px;">
        <div class="field" style="margin-bottom:0; flex:1; min-width:130px;">
            <label>Date début</label>
            <input type="date" name="date_debut" value="<?= esc($date_debut ?? '') ?>">
        </div>
        <div class="field" style="margin-bottom:0; flex:1; min-width:130px;">
            <label>Date fin</label>
            <input type="date" name="date_fin" value="<?= esc($date_fin ?? '') ?>">
        </div>
        <button type="submit" class="btn btn-primary">Filtrer</button>
    </form>

    <?php if (!empty($transactions)): ?>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Opération</th>
                    <th class="num">Valeur</th>
                    <th class="num">Frais</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $t): ?>
                    <tr>
                        <td><?= esc($t->date_transaction) ?></td>
                        <td><span class="badge <?= badge_operation($t->operation) ?>"><?= esc($t->operation) ?></span></td>
                        <td class="num"><?= montant_ar($t->valeur) ?></td>
                        <td class="num"><?= montant_ar($t->frais) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="hint">Aucune transaction trouvée.</p>
    <?php endif; ?>
</div>

<?= view('partials/client_footer') ?>
