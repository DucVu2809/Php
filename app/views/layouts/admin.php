<?php

use App\Core\Auth;

$title = (isset($pageTitle) && $pageTitle !== '' ? $pageTitle . ' - ' : '') . 'Quản trị XINMAI';

$adminPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
if (BASE_URL !== '' && str_starts_with($adminPath, BASE_URL)) {
    $adminPath = substr($adminPath, strlen(BASE_URL));
}
$adminPath = '/' . trim((string) $adminPath, '/');

$isActive = static function (string $path, bool $exact = false) use ($adminPath): string {
    if ($exact) {
        return $adminPath === $path ? 'active' : '';
    }
    return str_starts_with($adminPath, $path) ? 'active' : '';
};

$menu = [
    ['/admin', 'fa-gauge-high', 'Tổng quan', true],
    ['/admin/san-pham', 'fa-box', 'Sản phẩm', false],
    ['/admin/don-hang', 'fa-receipt', 'Đơn hàng', false],
    ['/admin/nguoi-dung', 'fa-users', 'Người dùng', false],
    ['/admin/ma-giam-gia', 'fa-ticket', 'Mã giảm giá', false],
    ['/admin/kho', 'fa-warehouse', 'Nhập kho', false],
    ['/admin/danh-gia', 'fa-star', 'Đánh giá', false],
    ['/admin/cau-hinh', 'fa-gear', 'Cấu hình', false],
];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.quilljs.com/1.3.7/quill.snow.css">
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
</head>
<body data-base="<?= e(BASE_URL) ?>">
<div class="admin">
    <aside class="admin-side">
        <a class="admin-side__brand" href="<?= url('admin') ?>">
            <i class="fa-solid fa-helmet-safety"></i> XIN<strong>MAI</strong>
        </a>
        <nav class="admin-side__nav">
            <?php foreach ($menu as [$path, $icon, $label, $exact]): ?>
                <a class="<?= $isActive($path, $exact) ?>" href="<?= url(ltrim($path, '/')) ?>">
                    <i class="fa-solid <?= $icon ?>"></i> <span><?= e($label) ?></span>
                </a>
            <?php endforeach; ?>
            <div class="admin-side__sep"></div>
            <a href="<?= url('/') ?>"><i class="fa-solid fa-globe"></i> <span>Về website</span></a>
            <a href="<?= url('dang-xuat') ?>"><i class="fa-solid fa-right-from-bracket"></i> <span>Đăng xuất</span></a>
        </nav>
    </aside>

    <div class="admin-main">
        <?php require VIEW_PATH . '/layouts/admin_header.php'; ?>

        <?php $okMsg = flash('success'); $errMsg = flash('error'); ?>
        <?php if ($okMsg): ?><div class="admin-alert admin-alert--ok"><i class="fa-solid fa-circle-check"></i> <?= e($okMsg) ?></div><?php endif; ?>
        <?php if ($errMsg): ?><div class="admin-alert admin-alert--err"><i class="fa-solid fa-circle-exclamation"></i> <?= e($errMsg) ?></div><?php endif; ?>

        <div class="admin-content">
            <?= $content ?>
        </div>
    </div>
</div>

<div class="modal" id="confirmModal">
    <div class="modal__box modal__box--sm">
        <div class="modal__icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <h3 class="modal__title-c">Xác nhận</h3>
        <p class="modal__msg" data-confirm-msg>Bạn có chắc chắn muốn thực hiện?</p>
        <div class="modal__actions">
            <button type="button" class="abtn abtn--ghost" data-modal-close>Hủy</button>
            <button type="button" class="abtn abtn--danger" data-confirm-ok>Đồng ý</button>
        </div>
    </div>
</div>

<script src="<?= asset('js/admin.js') ?>"></script>
</body>
</html>
