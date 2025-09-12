<?php
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('dashboard'));
});

Breadcrumbs::for('overnight.walk-in', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Shipments / Overnight - Walkin');
});

Breadcrumbs::for('overnight.on-account', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Shipments / Overnight - On-Account');
});

Breadcrumbs::for('sameday.walk-in', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Shipments / Sameday - Walkin');
});

Breadcrumbs::for('sameday.on-account', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Shipments / Sameday - On-Account');
});

Breadcrumbs::for('loading_sheets.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Shipments / Dispatch process');
});