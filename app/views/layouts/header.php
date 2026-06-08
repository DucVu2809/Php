<?php
/**
 * Phần đầu trang: thanh trên cùng, logo, ô tìm kiếm, giỏ hàng và menu danh mục.
 */

use App\Core\Cart;
use App\Models\Category;
use App\Models\Setting;

$setting       = new Setting();
$navCategories = (new Category())->active();
$hotlineNorth  = $setting->get('hotline_north', $setting->get('hotline', '0901 234 567'));
$hotlineSouth  = $setting->get('hotline_south', '0915 463 433');
$workingHours  = $setting->get('working_hours', '8h00 - 18h00');
$keyword       = trim((string) ($_GET['q'] ?? ''));

// Trang chủ đã có sidebar danh mục cố định -> nút nav KHÔNG render dropdown (tránh trùng lặp)
$headerPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
if (BASE_URL !== '' && str_starts_with($headerPath, BASE_URL)) {
    $headerPath = substr($headerPath, strlen(BASE_URL));
}
$isHome = ('/' . trim((string) $headerPath, '/') === '/');
?>
<!-- Thanh thông tin trên cùng -->
<div class="topbar">
    <div class="container topbar__inner">
        <div class="topbar__left">
            <span><i class="fa-solid fa-truck-fast"></i> Giao hàng toàn quốc</span>
            <span><i class="fa-solid fa-shield-halved"></i> Bảo hành chính hãng</span>
        </div>
        <div class="topbar__right">
            <span><i class="fa-regular fa-clock"></i> <?= e($workingHours) ?></span>
            <?php if (\App\Core\Auth::check()): ?>
                <?php $authUser = \App\Core\Auth::user(); ?>
                <?php if (\App\Core\Auth::isAdmin()): ?>
                    <a href="<?= url('admin') ?>"><i class="fa-solid fa-gauge"></i> Quản trị</a>
                <?php endif; ?>
                <span><i class="fa-solid fa-user"></i> <?= e($authUser['name'] ?? 'Tài khoản') ?></span>
                <a href="<?= url('dang-xuat') ?>">Đăng xuất</a>
            <?php else: ?>
                <a href="<?= url('dang-nhap') ?>">Đăng nhập</a>
                <a href="<?= url('dang-ky') ?>">Đăng ký</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Header chính -->
<header class="header">
    <div class="container header__inner">
        <a class="logo" href="<?= url('/') ?>">
            <i class="fa-solid fa-helmet-safety"></i>
            <span class="logo__text">XIN<strong>MAI</strong><small>Tổng kho máy xây dựng</small></span>
        </a>

        <form class="search" action="<?= url('san-pham') ?>" method="get" role="search">
            <input type="text" name="q" value="<?= e($keyword) ?>"
                   placeholder="Bạn cần tìm máy gì? (máy phát điện, máy bơm...)" autocomplete="off">
            <button type="submit" aria-label="Tìm kiếm"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>

        <div class="header__actions">
            <a class="hotline" href="tel:<?= e(preg_replace('/\s+/', '', $hotlineSouth)) ?>">
                <i class="fa-solid fa-comment-dots"></i>
                <span>
                    <small>Hotline Miền Nam</small>
                    <strong><?= e($hotlineSouth) ?></strong>
                </span>
            </a>
            <a class="hotline" href="tel:<?= e(preg_replace('/\s+/', '', $hotlineNorth)) ?>">
                <i class="fa-solid fa-headset"></i>
                <span>
                    <small>Hotline Miền Bắc</small>
                    <strong><?= e($hotlineNorth) ?></strong>
                </span>
            </a>
            <a class="cart-link" href="<?= url('gio-hang') ?>">
                <i class="fa-solid fa-cart-shopping"></i>
                <span class="cart-link__badge" id="cart-count"><?= Cart::totalQuantity() ?></span>
                <span class="cart-link__label">Giỏ hàng</span>
            </a>
            <?php if (\App\Core\Auth::check() && \App\Core\Auth::isAdmin()): ?>
                <?php $unreadCount = \App\Models\Notification::countUnread(); ?>
                <a class="cart-link notification-bell" href="<?= url('admin') ?>" 
                   onclick="const b = this.querySelector('.cart-link__badge'); if(b) b.remove();" 
                   style="margin-left: 10px; position: relative;">
                    <i class="fa-solid fa-bell"></i>
                    <?php if ($unreadCount > 0): ?>
                        <span class="cart-link__badge" style="background-color: #ff4d4f; color: white;">
                            <?= $unreadCount ?>
                        </span>
                    <?php endif; ?>
                    <span class="cart-link__label">Thông báo</span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>

<!-- Thanh điều hướng + dropdown danh mục -->
<nav class="mainnav">
    <div class="container mainnav__inner">
        <!-- Nút danh mục. Trang chủ: chỉ là tiêu đề của sidebar bên dưới.
             Trang khác: kèm dropdown xổ xuống. -->
        <div class="mainnav__cat">
            <?php if ($isHome): ?>
                <div class="mainnav__cattoggle">
                    <i class="fa-solid fa-bars"></i>
                    <span>Danh mục sản phẩm</span>
                </div>
            <?php else: ?>
                <button class="mainnav__cattoggle" type="button">
                    <i class="fa-solid fa-bars"></i>
                    <span>Danh mục sản phẩm</span>
                    <i class="fa-solid fa-chevron-down mainnav__caret"></i>
                </button>
                <ul class="catmenu">
                    <?php foreach ($navCategories as $cat): ?>
                        <li>
                            <a href="<?= url('danh-muc/' . $cat['slug']) ?>">
                                <i class="fa-solid <?= e($cat['icon'] ?: 'fa-cube') ?> catmenu__ic"></i>
                                <span class="catmenu__info">
                                    <strong><?= e($cat['name']) ?></strong>
                                    <?php if (!empty($cat['description'])): ?>
                                        <small><?= e($cat['description']) ?></small>
                                    <?php endif; ?>
                                </span>
                                <i class="fa-solid fa-angle-right catmenu__arrow"></i>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <!-- Menu điều hướng chính -->
        <ul class="mainnav__menu">
            <li><a href="<?= url('/') ?>">Trang chủ</a></li>
            <li><a href="<?= url('san-pham') ?>">Sản phẩm</a></li>
            <li><a href="<?= url('san-pham') ?>?sort=sale" class="<?= (isset($_GET['sort']) && $_GET['sort'] === 'sale') ? 'active' : '' ?>">Khuyến mãi</a></li>
            <li><a href="<?= url('gioi-thieu') ?>">Tin tức</a></li>
            <li><a href="<?= url('lien-he') ?>">Liên hệ</a></li>
            <li><a href="<?= url('gioi-thieu') ?>">Giới thiệu</a></li>
        </ul>
    </div>
</nav>
