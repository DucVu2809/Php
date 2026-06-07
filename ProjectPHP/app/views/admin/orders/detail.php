<?php

use App\Helpers\FormatHelper;

$statuses = [
    'pending'   => 'Chờ xử lý',
    'confirmed' => 'Đã xác nhận',
    'shipping'  => 'Đang giao',
    'completed' => 'Hoàn thành',
    'cancelled' => 'Đã hủy',
];
$statusClass = [
    'pending'   => 'badge--orange',
    'confirmed' => 'badge--blue',
    'shipping'  => 'badge--blue',
    'completed' => 'badge--green',
    'cancelled' => 'badge--red',
];
$payments = ['cod' => 'Thanh toán khi nhận hàng (COD)', 'bank' => 'Chuyển khoản ngân hàng'];
?>
<div class="toolbar">
    <h1>Đơn hàng <?= e($order['code']) ?>
        <span class="badge <?= $statusClass[$order['status']] ?? 'badge--gray' ?>" style="vertical-align:middle; margin-left:6px;"><?= e($statuses[$order['status']] ?? $order['status']) ?></span>
    </h1>
    <a class="abtn abtn--ghost" href="<?= url('admin/don-hang') ?>"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
</div>

<div class="order-grid">
    <div class="panel">
        <div class="panel__head"><h2>Thông tin khách hàng</h2></div>
        <div class="panel__body">
            <div class="info-list">
                <div class="info-list__row"><i class="fa-solid fa-user"></i><span class="k">Người nhận</span><span class="v"><?= e($order['customer_name']) ?></span></div>
                <div class="info-list__row"><i class="fa-solid fa-phone"></i><span class="k">Điện thoại</span><span class="v"><?= e($order['customer_phone']) ?></span></div>
                <div class="info-list__row"><i class="fa-solid fa-location-dot"></i><span class="k">Địa chỉ</span><span class="v"><?= e($order['customer_address']) ?></span></div>
                <?php if (!empty($order['customer_email'])): ?>
                    <div class="info-list__row"><i class="fa-solid fa-envelope"></i><span class="k">Email</span><span class="v"><?= e($order['customer_email']) ?></span></div>
                <?php endif; ?>
                <div class="info-list__row"><i class="fa-solid fa-credit-card"></i><span class="k">Thanh toán</span><span class="v"><?= e($payments[$order['payment_method']] ?? $order['payment_method']) ?></span></div>
                <?php if (!empty($order['note'])): ?>
                    <div class="info-list__row"><i class="fa-solid fa-note-sticky"></i><span class="k">Ghi chú</span><span class="v"><?= e($order['note']) ?></span></div>
                <?php endif; ?>
                <div class="info-list__row"><i class="fa-regular fa-clock"></i><span class="k">Ngày đặt</span><span class="v"><?= FormatHelper::dateTime($order['created_at']) ?></span></div>
            </div>
        </div>
        <form class="status-bar" method="post" action="<?= url('admin/don-hang/' . $order['id'] . '/trang-thai') ?>">
            <span style="font-weight:600;">Trạng thái:</span>
            <select class="status-select" name="status">
                <?php foreach ($statuses as $key => $label): ?>
                    <option value="<?= $key ?>" <?= $order['status'] === $key ? 'selected' : '' ?>><?= e($label) ?></option>
                <?php endforeach; ?>
            </select>
            <button class="abtn abtn--primary abtn--sm" type="submit"><i class="fa-solid fa-floppy-disk"></i> Cập nhật</button>
        </form>
    </div>

    <div class="panel">
        <div class="panel__head"><h2>Sản phẩm trong đơn</h2></div>
        <div class="panel__body panel__body--flush">
            <table class="atable">
                <thead><tr><th>Sản phẩm</th><th>Đơn giá</th><th>SL</th><th>Thành tiền</th></tr></thead>
                <tbody>
                    <?php foreach ($items as $it): ?>
                        <tr>
                            <td><?= e($it['product_name']) ?></td>
                            <td><?= FormatHelper::price($it['price']) ?></td>
                            <td><?= (int) $it['quantity'] ?></td>
                            <td class="price"><?= FormatHelper::price($it['price'] * $it['quantity']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="sumbox">
                <div class="sumrow"><span>Tạm tính</span><span><?= FormatHelper::price($order['subtotal']) ?></span></div>
                <?php if ((float) $order['discount'] > 0): ?>
                    <div class="sumrow"><span>Giảm giá</span><span>- <?= FormatHelper::price($order['discount']) ?></span></div>
                <?php endif; ?>
                <div class="sumrow sumrow--total"><span>Tổng cộng</span><span><?= FormatHelper::price($order['total']) ?></span></div>
            </div>
        </div>
    </div>
</div>
