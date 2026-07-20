<?php helper('badge'); $activeNav = $activeNav ?? ''; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($pageTitle ?? 'AriaryPay') ?> · AriaryPay opérateur</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
<div class="op-shell">
    <aside class="op-sidebar">
        <div class="op-logo">
            <div class="op-logo-mark"></div>
            <div class="op-logo-text">AriaryPay<small>Console opérateur</small></div>
        </div>
        <ul class="op-nav">
            <li><a href="/operateur/gains" class="<?= $activeNav === 'gains' ? 'is-active' : '' ?>">Gains</a></li>
            <li><a href="/operateur/montants-a-envoyer" class="<?= $activeNav === 'montants' ? 'is-active' : '' ?>">Montants à envoyer</a></li>
            <li><a href="/operateur/baremes" class="<?= $activeNav === 'baremes' ? 'is-active' : '' ?>">Barèmes</a></li>
            <li><a href="/operateur/clients" class="<?= $activeNav === 'clients' ? 'is-active' : '' ?>">Comptes clients</a></li>
        </ul>
    </aside>
    <div class="op-main">
        <div class="op-topbar">
            <div>
                <span class="op-crumb">Espace opérateur</span>
                <h1><?= esc($pageTitle ?? '') ?></h1>
            </div>
            <div class="op-topbar-right">
                <div class="pill-toggle">
                    <a href="/operateur" class="is-active">Opérateur</a>
                    <a href="/">Client</a>
                </div>
            </div>
        </div>
        <div class="op-content">
            <?php if (session()->getFlashdata('message')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>
