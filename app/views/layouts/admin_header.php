<?php

use App\Core\Auth;

$adminUser = Auth::user();
?>
<header class="admin-top">
    <h1 class="admin-top__title"><?= e($pageTitle ?? 'Tổng quan') ?></h1>
    <div class="admin-top__user">
        <i class="fa-solid fa-circle-user"></i>
        <span><?= e($adminUser['name'] ?? 'Admin') ?></span>
    </div>
</header>
