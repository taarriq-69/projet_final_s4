<?= view('partials/operateur_header', ['pageTitle' => 'Transactions de ' . $client['nom'], 'activeNav' => 'clients']) ?>

<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <div>
            <h2><?= esc($client['nom']) ?></h2>
            <p class="hint mono"><?= esc($client['numero']) ?></p>
        </div>
        <a href="/operateur/clients" class="btn btn-ghost btn-sm">← Tous les clients</a>
    </div>

    <form method="get" action="/operateur/clients/<?= esc($client['id']) ?>" style="display:flex; gap:12px; align-items:end; flex-wrap:wrap; margin-bottom:18px;">
        <div class="field" style="margin-bottom:0;">
            <label>Date début</label>
            <input type="date" name="date_debut" value="<?= esc($date_debut ?? '') ?>">
        </div>
        <div class="field" style="margin-bottom:0;">
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

<?= view('partials/operateur_footer') ?>
