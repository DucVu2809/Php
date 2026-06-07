<?php

use App\Helpers\FormatHelper;
?>
<div class="toolbar">
    <h1>Phiếu nhập <?= e($receipt['code']) ?></h1>
    <a class="abtn abtn--ghost" href="<?= url('admin/kho') ?>"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
</div>

<div class="panel">
    <div class="panel__body">
        <p><strong>Nhà cung cấp:</strong> <?= e($receipt['supplier'] ?: '—') ?></p>
        <p><strong>Ghi chú:</strong> <?= e($receipt['note'] ?: '—') ?></p>
        <p><strong>Ngày tạo:</strong> <?= FormatHelper::dateTime($receipt['created_at']) ?></p>
    </div>
</div>

<div class="panel">
    <div class="panel__head"><h2>Chi tiết nhập</h2></div>
    <div class="panel__body panel__body--flush">
        <table class="atable">
            <thead><tr><th>Sản phẩm</th><th>Số lượng</th><th>Giá nhập</th><th>Thành tiền</th></tr></thead>
            <tbody>
                <?php foreach ($details as $d): ?>
                    <tr>
                        <td><?= e($d['product_name'] ?: 'Sản phẩm đã xóa') ?></td>
                        <td><?= (int) $d['quantity'] ?></td>
                        <td><?= FormatHelper::price($d['cost_price']) ?></td>
                        <td class="price"><?= FormatHelper::price($d['quantity'] * $d['cost_price']) ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3" style="text-align:right;"><strong>Tổng cộng</strong></td>
                    <td class="price"><?= FormatHelper::price($receipt['total']) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
