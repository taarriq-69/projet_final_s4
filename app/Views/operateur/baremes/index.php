<?php
$groupes = [];
foreach ($baremes as $b) {
    $groupes[$b->type_operation][] = $b;
}

$simulateurData = [];
foreach ($groupes as $libelle => $tranches) {
    $simulateurData[$libelle] = array_map(static fn ($t) => [
        'min'   => (int) $t->valeur_min,
        'max'   => (int) $t->valeur_max,
        'frais' => (int) $t->frais,
    ], $tranches);
}
?>
<?= view('partials/operateur_header', ['pageTitle' => 'Barèmes', 'activeNav' => 'baremes']) ?>

<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
        <h2>Simulateur de frais</h2>
        <a href="/operateur/baremes/ajouter" class="btn btn-primary btn-sm">+ Ajouter une tranche</a>
    </div>
    <p class="hint" style="margin-bottom:16px;">Choisissez une opération et un montant pour voir le frais appliqué.</p>

    <div style="display:flex; gap:16px; flex-wrap:wrap;">
        <div class="field" style="max-width:280px; flex:1; min-width:200px;">
            <label>Opération</label>
            <select id="sim-operation">
                <?php foreach (array_keys($groupes) as $libelle): ?>
                    <option value="<?= esc($libelle) ?>"><?= esc($libelle) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="field" style="max-width:280px; flex:1; min-width:200px;">
            <label>Montant simulé (Ar)</label>
            <input type="number" id="sim-montant" min="0" placeholder="Ex. 25000">
        </div>
    </div>

    <div class="simulateur-result">
        <div><span class="label">Montant</span><span class="value" id="res-montant">—</span></div>
        <div><span class="label">Tranche</span><span class="value" id="res-tranche">—</span></div>
        <div><span class="label">Frais appliqué</span><span class="value" id="res-frais">—</span></div>
    </div>
</div>

<div class="card">
    <h2>Toutes les tranches</h2>

    <?php if (!empty($baremes)): ?>
        <table>
            <thead>
                <tr>
                    <th>Opération</th>
                    <th class="num">De</th>
                    <th class="num">À</th>
                    <th class="num">Frais</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($baremes as $b): ?>
                    <tr>
                        <td><span class="badge <?= badge_operation($b->type_operation) ?>"><?= esc($b->type_operation) ?></span></td>
                        <td class="num"><?= montant_ar($b->valeur_min) ?></td>
                        <td class="num"><?= montant_ar($b->valeur_max) ?></td>
                        <td class="num"><?= montant_ar($b->frais) ?></td>
                        <td style="white-space:nowrap;">
                            <a href="/operateur/baremes/modifier/<?= esc($b->id) ?>" class="btn btn-ghost btn-sm">Modifier</a>
                            <form method="post" action="/operateur/baremes/supprimer/<?= esc($b->id) ?>" style="display:inline" onsubmit="return confirm('Supprimer ce barème ?');">
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="hint">Aucun barème trouvé.</p>
    <?php endif; ?>
</div>

<script>
    const baremesData = <?= json_encode($simulateurData) ?>;
    const selectOp      = document.getElementById('sim-operation');
    const inputMontant  = document.getElementById('sim-montant');
    const resMontant    = document.getElementById('res-montant');
    const resTranche    = document.getElementById('res-tranche');
    const resFrais      = document.getElementById('res-frais');

    function formatAr(n) {
        return new Intl.NumberFormat('fr-FR').format(n) + ' Ar';
    }

    function trouverTranche(tranches, montant) {
        return tranches.find(t => montant >= t.min && montant <= t.max) || null;
    }

    function simuler() {
        const tranches = baremesData[selectOp.value] || [];
        const montant = inputMontant.value !== '' ? parseInt(inputMontant.value, 10) : null;

        if (montant === null || isNaN(montant)) {
            resMontant.textContent = '—';
            resTranche.textContent = '—';
            resFrais.textContent = '—';
            return;
        }

        const tranche = trouverTranche(tranches, montant);
        resMontant.textContent = formatAr(montant);
        resTranche.textContent = tranche ? (formatAr(tranche.min) + ' – ' + formatAr(tranche.max)) : 'Hors barème';
        resFrais.textContent = tranche ? formatAr(tranche.frais) : '—';
    }

    selectOp.addEventListener('change', simuler);
    inputMontant.addEventListener('input', simuler);
    simuler();
</script>

<?= view('partials/operateur_footer') ?>
