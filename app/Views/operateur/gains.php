<?= view('partials/operateur_header', ['pageTitle' => 'Gains', 'activeNav' => 'gains']) ?>

<?php $gainAutresTotal = array_sum(array_column($gainsAutresOperateurs, 'gain_total')); ?>

<div class="kpi-row">
    <div class="kpi kpi-gold">
        <div class="kpi-label">Gain global · notre opérateur</div>
        <div class="kpi-value"><?= montant_ar($gainGlobal) ?></div>
    </div>
    <div class="kpi">
        <div class="kpi-label">Gain via les autres opérateurs</div>
        <div class="kpi-value"><?= montant_ar($gainAutresTotal) ?></div>
    </div>
</div>

<div class="card">
    <h2>Notre opérateur</h2>
    <p class="hint" style="margin-bottom:16px;">Frais perçus par type d'opération.</p>

    <form method="get" action="/operateur/gains" style="display:flex; gap:12px; align-items:end; flex-wrap:wrap; margin-bottom:18px;">
        <div class="field" style="margin-bottom:0; min-width:160px;">
            <label>Opération</label>
            <select name="operation">
                <option value="">Toutes</option>
                <?php foreach ($operations as $op): ?>
                    <option value="<?= esc($op) ?>" <?= $operation === $op ? 'selected' : '' ?>><?= esc($op) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field" style="margin-bottom:0; min-width:200px;">
            <label>Recherche</label>
            <input type="text" name="recherche" value="<?= esc($recherche) ?>" placeholder="Ex. RETRAIT">
        </div>
        <button type="submit" class="btn btn-primary">Filtrer</button>
        <a href="/operateur/gains" class="btn btn-ghost">Réinitialiser</a>
    </form>

    <?php if (!empty($gains)): ?>
        <table>
            <thead>
                <tr>
                    <th>Opération</th>
                    <th class="num">Transactions</th>
                    <th class="num">Gain total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($gains as $g): ?>
                    <tr>
                        <td><span class="badge <?= badge_operation($g['type_operation']) ?>"><?= esc($g['type_operation']) ?></span></td>
                        <td class="num"><?= esc($g['nombre_transactions']) ?></td>
                        <td class="num"><?= montant_ar($g['gain_total']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="hint">Aucun résultat.</p>
    <?php endif; ?>
</div>

<div class="card">
    <h2>Autres opérateurs</h2>
    <p class="hint" style="margin-bottom:16px;">Commissions non incluses — ces frais restent uniquement les nôtres (frais de transfert).</p>

    <?php if (!empty($gainsAutresOperateurs)): ?>
        <table>
            <thead>
                <tr>
                    <th>Opérateur</th>
                    <th class="num">Transactions</th>
                    <th class="num">Gain total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($gainsAutresOperateurs as $g): ?>
                    <tr>
                        <td><span class="badge badge-transfert"><?= esc($g['operateur']) ?></span></td>
                        <td class="num"><?= esc($g['nombre_transactions']) ?></td>
                        <td class="num"><?= montant_ar($g['gain_total']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="hint">Aucune transaction vers un autre opérateur.</p>
    <?php endif; ?>

    <div style="margin-top:16px;">
        <a href="/operateur/montants-a-envoyer" class="btn btn-gold">Voir les montants à envoyer à chaque opérateur</a>
    </div>
</div>

<?= view('partials/operateur_footer') ?>
