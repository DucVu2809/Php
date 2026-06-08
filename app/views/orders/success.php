<?php

use App\Helpers\FormatHelper;

$payments = ['cod' => 'Thanh toán khi nhận hàng (COD)', 'bank' => 'Chuyển khoản ngân hàng'];
?>
<div class="container section">
    <div class="order-done">
        <div class="order-done__icon"><i class="fa-solid fa-circle-check"></i></div>
        <h1>Đặt hàng thành công!</h1>
        <p>Cảm ơn bạn đã mua hàng tại XINMAI. Chúng tôi sẽ liên hệ xác nhận trong thời gian sớm nhất.</p>
        <div class="order-done__code">
            Mã đơn hàng: <strong><?= e($order['code']) ?></strong>
        </div>
    </div>

    <div class="order-detail">
        <div class="order-detail__box">
            <h3>Thông tin giao hàng</h3>
            <p><strong>Người nhận:</strong> <?= e($order['customer_name']) ?></p>
            <p><strong>Điện thoại:</strong> <?= e($order['customer_phone']) ?></p>
            <p><strong>Địa chỉ:</strong> <?= e($order['customer_address']) ?></p>
            <?php if (!empty($order['customer_email'])): ?>
                <p><strong>Email:</strong> <?= e($order['customer_email']) ?></p>
            <?php endif; ?>
            <p><strong>Thanh toán:</strong> <?= e($payments[$order['payment_method']] ?? $order['payment_method']) ?></p>
            <?php if (!empty($order['note'])): ?>
                <p><strong>Ghi chú:</strong> <?= e($order['note']) ?></p>
            <?php endif; ?>
        </div>

        <div class="order-detail__box">
            <h3>Chi tiết đơn hàng</h3>
            <table class="order-items">
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= e($item['product_name']) ?> <span class="muted">× <?= (int) $item['quantity'] ?></span></td>
                        <td class="order-items__price"><?= FormatHelper::price($item['price'] * $item['quantity']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <div class="order-detail__row"><span>Tạm tính</span><span><?= FormatHelper::price($order['subtotal']) ?></span></div>
            <?php if ((float) $order['discount'] > 0): ?>
                <div class="order-detail__row"><span>Giảm giá</span><span>- <?= FormatHelper::price($order['discount']) ?></span></div>
            <?php endif; ?>
            <div class="order-detail__row order-detail__row--total"><span>Tổng cộng</span><span><?= FormatHelper::price($order['total']) ?></span></div>
        </div>
    </div>

    <div class="order-done__actions">
        <a class="btn btn-outline" href="<?= url('/') ?>"><i class="fa-solid fa-house"></i> Về trang chủ</a>
        <a class="btn btn-primary" href="<?= url('san-pham') ?>">Tiếp tục mua sắm <i class="fa-solid fa-arrow-right"></i></a>
    </div>
</div>
