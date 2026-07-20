<?= view('partials/operateur_header', ['pageTitle' => 'Comptes clients', 'activeNav' => 'clients']) ?>

<div class="card">
    <h2>Liste des clients</h2>
    <p class="hint" style="margin-bottom:16px;"><?= count($clients ?? []) ?> client(s) enregistré(s).</p>

    <?php if (!empty($clients)): ?>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Numéro</th>
                    <th>Date de création</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $c): ?>
                    <tr>
                        <td><?= esc($c['nom']) ?></td>
                        <td class="mono"><?= esc($c['numero']) ?></td>
                        <td><?= esc($c['date_creation']) ?></td>
                        <td><a href="/operateur/clients/<?= esc($c['id']) ?>" class="btn btn-ghost btn-sm">Voir les transactions</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="hint">Aucun client trouvé.</p>
    <?php endif; ?>
</div>

<?= view('partials/operateur_footer') ?>
