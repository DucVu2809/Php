<?php

use App\Helpers\FormatHelper;

$statusBadge = [
    'pending'   => ['Chờ xử lý', 'badge--orange'],
    'confirmed' => ['Đã xác nhận', 'badge--blue'],
    'shipping'  => ['Đang giao', 'badge--blue'],
    'completed' => ['Hoàn thành', 'badge--green'],
    'cancelled' => ['Đã hủy', 'badge--red'],
];
?>
<div class="stats">
    <div class="stat">
        <div class="stat__icon stat__icon--orange"><i class="fa-solid fa-box"></i></div>
        <div><div class="stat__value"><?= (int) $totalProducts ?></div><div class="stat__label">Sản phẩm</div></div>
    </div>
    <div class="stat">
        <div class="stat__icon stat__icon--blue"><i class="fa-solid fa-receipt"></i></div>
        <div><div class="stat__value"><?= (int) $totalOrders ?></div><div class="stat__label">Đơn hàng (<?= (int) $pendingOrders ?> chờ xử lý)</div></div>
    </div>
    <div class="stat">
        <div class="stat__icon stat__icon--green"><i class="fa-solid fa-sack-dollar"></i></div>
        <div><div class="stat__value"><?= FormatHelper::price($revenue) ?></div><div class="stat__label">Doanh thu</div></div>
    </div>
    <div class="stat">
        <div class="stat__icon stat__icon--red"><i class="fa-solid fa-users"></i></div>
        <div><div class="stat__value"><?= (int) $totalUsers ?></div><div class="stat__label">Khách hàng</div></div>
    </div>
</div>

<div class="panel">
    <div class="panel__head">
        <h2>Đơn hàng gần đây</h2>
        <a class="abtn abtn--ghost abtn--sm" href="<?= url('admin/don-hang') ?>">Xem tất cả</a>
    </div>
    <div class="panel__body panel__body--flush">
        <table class="atable">
            <thead>
                <tr><th>Mã đơn</th><th>Khách hàng</th><th>Tổng tiền</th><th>Trạng thái</th><th>Ngày</th></tr>
            </thead>
            <tbody>
                <?php if (empty($recentOrders)): ?>
                    <tr><td colspan="5" class="empty-row">Chưa có đơn hàng nào.</td></tr>
                <?php else: foreach ($recentOrders as $o):
                    [$label, $cls] = $statusBadge[$o['status']] ?? [$o['status'], 'badge--gray']; ?>
                    <tr>
                        <td><strong><?= e($o['code']) ?></strong></td>
                        <td><?= e($o['customer_name']) ?><br><span class="muted"><?= e($o['customer_phone']) ?></span></td>
                        <td class="price"><?= FormatHelper::price($o['total']) ?></td>
                        <td><span class="badge <?= $cls ?>"><?= e($label) ?></span></td>
                        <td class="muted"><?= FormatHelper::dateTime($o['created_at']) ?></td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="panel">
    <div class="panel__head"><h2>Sản phẩm sắp hết hàng (tồn ≤ 10)</h2></div>
    <div class="panel__body panel__body--flush">
        <table class="atable">
            <thead><tr><th>Sản phẩm</th><th>Tồn kho</th><th></th></tr></thead>
            <tbody>
                <?php if (empty($lowStock)): ?>
                    <tr><td colspan="3" class="empty-row">Không có sản phẩm nào sắp hết.</td></tr>
                <?php else: foreach ($lowStock as $p): ?>
                    <tr>
                        <td><?= e($p['name']) ?></td>
                        <td><span class="badge <?= (int) $p['stock'] <= 5 ? 'badge--red' : 'badge--orange' ?>"><?= (int) $p['stock'] ?></span></td>
                        <td><a class="abtn abtn--ghost abtn--sm" href="<?= url('admin/kho/them') ?>">Nhập thêm</a></td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
