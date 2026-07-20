<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $bareme ? 'Modifier' : 'Ajouter' ?> un barème</title>
</head>
<body>
    <h1><?= $bareme ? 'Modifier' : 'Ajouter' ?> un barème</h1>

    <?php if (session()->getFlashdata('error')): ?>
        <p style="color:red;"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <form method="post" action="<?= $bareme ? '/operateur/baremes/modifier/' . $bareme['id'] : '/operateur/baremes/ajouter' ?>">
        <label>Type d'opération :</label>
        <select name="type_operation_id" required>
            <option value="">-- Choisir --</option>
            <?php foreach ($typesOperation as $type): ?>
                <option value="<?= esc($type['id']) ?>" <?= old('type_operation_id', $bareme['type_operation_id'] ?? '') == $type['id'] ? 'selected' : '' ?>><?= esc($type['libelle']) ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label>Valeur min :</label>
        <input type="number" name="valeur_min" value="<?= old('valeur_min', $bareme['valeur_min'] ?? '') ?>" required>
        <br><br>

        <label>Valeur max :</label>
        <input type="number" name="valeur_max" value="<?= old('valeur_max', $bareme['valeur_max'] ?? '') ?>" required>
        <br><br>

        <label>Frais :</label>
        <input type="number" name="frais" value="<?= old('frais', $bareme['frais'] ?? '') ?>" required>
        <br><br>

        <button type="submit">Enregistrer</button>
        <a href="/operateur/baremes">Annuler</a>
    </form>
</body>
</html>
