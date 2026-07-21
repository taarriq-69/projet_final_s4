<?= view('partials/operateur_header', ['pageTitle' => ($bareme ? 'Modifier' : 'Ajouter') . ' un barème', 'activeNav' => 'baremes']) ?>

<div class="card" style="max-width:480px;">
    <h2><?= $bareme ? 'Modifier' : 'Ajouter' ?> un barème</h2>
    <p class="hint" style="margin-bottom:16px;">Définissez la tranche de montant et le frais appliqué.</p>

    <form method="post" action="<?= $bareme ? '/operateur/baremes/modifier/' . $bareme['id'] : '/operateur/baremes/ajouter' ?>">
        <div class="field">
            <label>Type d'opération</label>
            <select name="type_operation_id" required>
                <option value="">-- Choisir --</option>
                <?php foreach ($typesOperation as $type): ?>
                    <option value="<?= esc($type['id']) ?>" <?= old('type_operation_id', $bareme['type_operation_id'] ?? '') == $type['id'] ? 'selected' : '' ?>><?= esc($type['libelle']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="field">
            <label>Valeur min (Ar)</label>
            <input type="number" name="valeur_min" value="<?= old('valeur_min', $bareme['valeur_min'] ?? '') ?>" required>
        </div>

        <div class="field">
            <label>Valeur max (Ar)</label>
            <input type="number" name="valeur_max" value="<?= old('valeur_max', $bareme['valeur_max'] ?? '') ?>" required>
        </div>

        <div class="field">
            <label>Frais (Ar)</label>
            <input type="number" name="frais" value="<?= old('frais', $bareme['frais'] ?? '') ?>" required>
        </div>
        <div class="field">
            <label for="">
                <input type="checkbox" name="meme_operateur" value="1">
                <?= old('meme_operateur',$bareme['meme_operateur'] ?? 0) ? 'checked' : '' ?>
            </label>
        </div>
        <div style="display:flex; gap:10px;">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="/operateur/baremes" class="btn btn-ghost">Annuler</a>
        </div>
    </form>
</div>

<?= view('partials/operateur_footer') ?>
