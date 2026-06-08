<?php

use App\Helpers\FormatHelper;

$discount = $coupon['discount'] ?? 0;
$total    = max(0, $subtotal - $discount);
?>
<div class="container breadcrumb">
    <a href="<?= url('/') ?>">Trang chủ</a>
    <i class="fa-solid fa-angle-right"></i>
    <a href="<?= url('gio-hang') ?>">Giỏ hàng</a>
    <i class="fa-solid fa-angle-right"></i>
    <span>Thanh toán</span>
</div>

<div class="container section">
    <h1 class="page-title"><i class="fa-solid fa-credit-card"></i> Thanh toán đơn hàng</h1>

    <form method="post" action="<?= url('dat-hang') ?>" class="checkout">
        <div class="checkout__form">
            <h3 class="checkout__heading">Thông tin người nhận</h3>
            <div class="form-row">
                <label>Họ và tên <span class="req">*</span></label>
                <input type="text" name="customer_name" required
                       value="<?= e(old('customer_name', $user['name'] ?? '')) ?>" placeholder="Người nhận hàng">
            </div>
            <div class="form-row">
                <label>Số điện thoại <span class="req">*</span></label>
                <input type="tel" name="customer_phone" required
                       value="<?= e(old('customer_phone', $user['phone'] ?? '')) ?>" placeholder="VD: 0901234567">
            </div>
            <div class="form-row">
                <label>Địa chỉ giao hàng <span class="req">*</span></label>
                <textarea name="customer_address" rows="3" required placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành"><?= e(old('customer_address', $user['address'] ?? '')) ?></textarea>
            </div>
            <div class="form-row">
                <label>Email (không bắt buộc)</label>
                <input type="email" name="customer_email"
                       value="<?= e(old('customer_email', $user['email'] ?? '')) ?>" placeholder="Email nhận thông tin đơn">
            </div>
            <div class="form-row">
                <label>Ghi chú</label>
                <textarea name="note" rows="2" placeholder="Ghi chú thêm cho đơn hàng"><?= e(old('note')) ?></textarea>
            </div>

            <h3 class="checkout__heading">Hình thức thanh toán</h3>
            <label class="pay-option">
                <input type="radio" name="payment_method" value="cod" checked>
                <span><i class="fa-solid fa-money-bill-wave"></i> Thanh toán khi nhận hàng (COD)</span>
            </label>
            <label class="pay-option">
                <input type="radio" name="payment_method" value="bank">
                <span><i class="fa-solid fa-building-columns"></i> Chuyển khoản ngân hàng</span>
            </label>
        </div>

        <aside class="checkout__summary">
            <h3>Đơn hàng của bạn</h3>
            <div class="checkout__items">
                <?php foreach ($items as $item): ?>
                    <div class="checkout__item">
                        <img src="<?= product_image($item['image']) ?>" alt="<?= e($item['name']) ?>">
                        <div class="checkout__item-info">
                            <span class="checkout__item-name"><?= e($item['name']) ?></span>
                            <span class="checkout__item-qty">SL: <?= (int) $item['quantity'] ?></span>
                        </div>
                        <span class="checkout__item-price"><?= FormatHelper::price($item['price'] * $item['quantity']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="checkout__coupon">
                <input type="text" id="couponCode" placeholder="Nhập mã giảm giá"
                       value="<?= e($coupon['code'] ?? '') ?>">
                <button type="button" class="btn btn-outline" data-apply-coupon>Áp dụng</button>
            </div>
            <p class="checkout__coupon-hint">Thử mã: <strong>XINMAI5</strong> hoặc <strong>GIAM200K</strong></p>

            <div class="checkout__row">
                <span>Tạm tính</span>
                <strong id="ckSubtotal" data-subtotal="<?= (int) $subtotal ?>"><?= FormatHelper::price($subtotal) ?></strong>
            </div>
            <div class="checkout__row">
                <span>Giảm giá</span>
                <strong id="ckDiscount">- <?= FormatHelper::price($discount) ?></strong>
            </div>
            <div class="checkout__total">
                <span>Tổng cộng</span>
                <strong id="ckTotal"><?= FormatHelper::price($total) ?></strong>
            </div>

            <button type="submit" class="btn btn-accent btn-block btn-lg">
                <i class="fa-solid fa-bag-shopping"></i> Đặt hàng
            </button>
        </aside>
    </form>
</div>
