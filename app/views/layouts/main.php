<?php
/**
 * Layout chính bao toàn bộ trang Frontend.
 *
 * Nhận sẵn biến $content (thân trang đã render) và $pageTitle từ controller.
 *
 * @var string      $content
 * @var string|null $pageTitle
 */

use App\Core\Cart;

$title = isset($pageTitle) && $pageTitle !== ''
    ? $pageTitle . ' - ' . APP_NAME
    : APP_NAME;

// Xác định có phải trang chủ không (để mở sẵn panel danh mục, ẩn dropdown lặp)
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
if (BASE_URL !== '' && str_starts_with($currentPath, BASE_URL)) {
    $currentPath = substr($currentPath, strlen(BASE_URL));
}
$bodyClass = ('/' . trim((string) $currentPath, '/') === '/') ? 'home' : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?></title>
    <meta name="description" content="Tổng kho máy xây dựng chính hãng: máy phát điện, máy bơm, máy hàn, máy nén khí, giá tốt, bảo hành uy tín.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="<?= asset('css/main.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/home.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/category.css') ?>">
</head>
<body class="<?= e($bodyClass) ?>" data-base="<?= e(BASE_URL) ?>">

    <?php require VIEW_PATH . '/layouts/header.php'; ?>

    <?php $flashSuccess = flash('success'); $flashError = flash('error'); ?>
    <?php if ($flashSuccess || $flashError): ?>
        <div class="container">
            <?php if ($flashSuccess): ?>
                <div class="alert alert--success"><i class="fa-solid fa-circle-check"></i> <?= e($flashSuccess) ?></div>
            <?php endif; ?>
            <?php if ($flashError): ?>
                <div class="alert alert--error"><i class="fa-solid fa-circle-exclamation"></i> <?= e($flashError) ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <main class="site-main">
        <?= $content ?>
    </main>

    <?php require VIEW_PATH . '/layouts/footer.php'; ?>

    <!-- Toast thông báo (thêm giỏ hàng...) -->
    <div id="toast" class="toast" role="status" aria-live="polite"></div>

    <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
