<?php
helper('badge');
$backUrl     = $backUrl ?? null;
$showBrand   = $showBrand ?? true;
$screenClass = $screenClass ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($pageTitle ?? 'AriaryPay') ?></title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
<div class="client-shell<?= $showBrand ? '' : ' no-brand' ?>">
    <?php if ($showBrand): ?>
    <aside class="client-brand">
        <div class="client-brand-logo">
            <div class="op-logo-mark"></div>
            <span class="op-logo-text" style="color:#fff;">AriaryPay</span>
        </div>
        <h2>Votre argent, simplement.</h2>
        <p>Dépôt, retrait, transfert : gérez votre compte mobile money avec votre numéro de téléphone, sans aucune inscription.</p>
        <div class="client-brand-stats">
            <div><div class="value">0 Ar</div><div class="label">Frais d'ouverture</div></div>
            <div><div class="value">24/7</div><div class="label">Disponible</div></div>
            <div><div class="value">Ar</div><div class="label">Ariary natif</div></div>
        </div>
    </aside>
    <?php endif; ?>
    <div class="client-screen-wrap">
        <div class="client-screen <?= esc($screenClass) ?>">
            <?php if ($backUrl): ?>
                <div class="client-topbar">
                    <a href="<?= esc($backUrl) ?>" class="back" aria-label="Retour">←</a>
                    <h1><?= esc($pageTitle ?? '') ?></h1>
                </div>
            <?php else: ?>
                <div class="client-topbar" style="justify-content:center;">
                    <h1><?= esc($pageTitle ?? '') ?></h1>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('message')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>
