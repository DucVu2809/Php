<?php

use App\Helpers\FormatHelper;
?>
<div class="toolbar">
    <h1>Nhập kho</h1>
    <a class="abtn abtn--primary" href="<?= url('admin/kho/them') ?>"><i class="fa-solid fa-plus"></i> Tạo phiếu nhập</a>
</div>

<div class="panel">
    <div class="panel__body panel__body--flush">
        <table class="atable">
            <thead>
                <tr><th>Mã phiếu</th><th>Nhà cung cấp</th><th>Tổng tiền</th><th>Người tạo</th><th>Ngày</th><th></th></tr>
            </thead>
            <tbody>
                <?php if (empty($receipts)): ?>
                    <tr><td colspan="6" class="empty-row">Chưa có phiếu nhập kho nào.</td></tr>
                <?php else: foreach ($receipts as $r): ?>
                    <tr>
                        <td><strong><?= e($r['code']) ?></strong></td>
                        <td><?= e($r['supplier'] ?: '—') ?></td>
                        <td class="price"><?= FormatHelper::price($r['total']) ?></td>
                        <td class="muted"><?= e($r['created_by_name'] ?: '—') ?></td>
                        <td class="muted"><?= FormatHelper::dateTime($r['created_at']) ?></td>
                        <td><a class="abtn abtn--ghost abtn--sm" href="<?= url('admin/kho/' . $r['id']) ?>">Chi tiết</a></td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
