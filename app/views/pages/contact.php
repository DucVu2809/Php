<?php
/**
 * Trang liên hệ.
 */

use App\Models\Setting;

$setting = new Setting();
?>
<div class="container breadcrumb">
    <a href="<?= url('/') ?>">Trang chủ</a>
    <i class="fa-solid fa-angle-right"></i>
    <span>Liên hệ</span>
</div>

<div class="container section contact">
    <div class="contact__info">
        <h1 class="page-title">Liên hệ với chúng tôi</h1>
        <p>Quý khách cần tư vấn sản phẩm hoặc báo giá, vui lòng liên hệ:</p>
        <ul class="contact__list">
            <li><i class="fa-solid fa-location-dot"></i> <?= e($setting->get('address', 'Hà Nội')) ?></li>
            <li><i class="fa-solid fa-phone"></i> <?= e($setting->get('hotline', '0901 234 567')) ?></li>
            <li><i class="fa-solid fa-envelope"></i> <?= e($setting->get('email', 'lienhe@xinmai.vn')) ?></li>
            <li><i class="fa-regular fa-clock"></i> <?= e($setting->get('working_hours', '8h - 18h')) ?></li>
        </ul>
    </div>

    <form class="contact__form" onsubmit="return false;">
        <h3>Gửi yêu cầu tư vấn</h3>
        <div class="form-row"><input type="text" placeholder="Họ và tên" required></div>
        <div class="form-row"><input type="tel" placeholder="Số điện thoại" required></div>
        <div class="form-row"><input type="email" placeholder="Email"></div>
        <div class="form-row"><textarea rows="4" placeholder="Nội dung cần tư vấn"></textarea></div>
        <button class="btn btn-primary btn-block" type="submit">Gửi yêu cầu</button>
    </form>
</div>
