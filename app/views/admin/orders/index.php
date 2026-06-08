<?php

use App\Helpers\FormatHelper;

$statuses = [
    'pending'   => 'Chờ xử lý',
    'confirmed' => 'Đã xác nhận',
    'shipping'  => 'Đang giao',
    'completed' => 'Hoàn thành',
    'cancelled' => 'Đã hủy',
];
?>
<div class="toolbar"><h1>Đơn hàng</h1></div>

<div class="panel">
    <div class="panel__body panel__body--flush">
        <table class="atable">
            <thead>
                <tr><th>Mã đơn</th><th>Khách hàng</th><th>Địa chỉ</th><th>Tổng tiền</th><th>Thanh toán</th><th>Trạng thái</th><th>Ngày</th></tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr><td colspan="7" class="empty-row">Chưa có đơn hàng nào.</td></tr>
                <?php else: foreach ($orders as $o): ?>
                    <tr>
                        <td><a href="<?= url('admin/don-hang/' . $o['id']) ?>" style="color:var(--orange);font-weight:700;"><?= e($o['code']) ?></a></td>
                        <td><?= e($o['customer_name']) ?><br><span class="muted"><?= e($o['customer_phone']) ?></span></td>
                        <td class="muted" style="max-width:220px;"><?= e($o['customer_address']) ?></td>
                        <td class="price"><?= FormatHelper::price($o['total']) ?></td>
                        <td><?= $o['payment_method'] === 'bank' ? 'Chuyển khoản' : 'COD' ?></td>
                        <td>
                            <form method="post" action="<?= url('admin/don-hang/' . $o['id'] . '/trang-thai') ?>">
                                <select class="status-select" name="status" onchange="this.form.submit()">
                                    <?php foreach ($statuses as $key => $label): ?>
                                        <option value="<?= $key ?>" <?= $o['status'] === $key ? 'selected' : '' ?>><?= e($label) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </td>
                        <td class="muted"><?= FormatHelper::dateTime($o['created_at']) ?></td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
