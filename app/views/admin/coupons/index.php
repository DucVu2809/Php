<?php

use App\Helpers\FormatHelper;
?>
<div class="toolbar">
    <h1>Mã giảm giá</h1>
    <button type="button" class="abtn abtn--primary" data-coupon-add><i class="fa-solid fa-plus"></i> Thêm mã</button>
</div>

<div class="panel">
    <div class="panel__body panel__body--flush">
        <table class="atable">
            <thead>
                <tr><th>Mã</th><th>Loại</th><th>Giá trị</th><th>Đơn tối thiểu</th><th>Hết hạn</th><th>Trạng thái</th><th>Thao tác</th></tr>
            </thead>
            <tbody>
                <?php if (empty($coupons)): ?>
                    <tr><td colspan="7" class="empty-row">Chưa có mã giảm giá nào.</td></tr>
                <?php else: foreach ($coupons as $c): ?>
                    <tr>
                        <td><strong><?= e($c['code']) ?></strong></td>
                        <td><?= $c['type'] === 'percent' ? 'Phần trăm' : 'Số tiền' ?></td>
                        <td class="price"><?= $c['type'] === 'percent' ? (int) $c['value'] . '%' : FormatHelper::price($c['value']) ?></td>
                        <td><?= FormatHelper::price($c['min_order']) ?></td>
                        <td class="muted"><?= e($c['expires_at'] ?: 'Không giới hạn') ?></td>
                        <td>
                            <?php if ((int) $c['is_active'] === 1): ?><span class="badge badge--green">Đang chạy</span><?php else: ?><span class="badge badge--gray">Tắt</span><?php endif; ?>
                        </td>
                        <td>
                            <div class="act-group">
                                <button type="button" class="abtn abtn--ghost abtn--sm" data-coupon-edit="<?= (int) $c['id'] ?>"><i class="fa-solid fa-pen"></i></button>
                                <form method="post" action="<?= url('admin/ma-giam-gia/' . $c['id'] . '/xoa') ?>" style="display:inline;">
                                    <button type="button" class="abtn abtn--danger abtn--sm" data-confirm="Xóa mã &quot;<?= e($c['code']) ?>&quot;?"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal" id="couponModal">
    <div class="modal__box">
        <div class="modal__head">
            <h3 data-modal-title>Thêm mã giảm giá</h3>
            <button type="button" class="modal__x" data-modal-close>&times;</button>
        </div>
        <div class="modal__body">
            <?php
            $coupon = [];
            $action = url('admin/ma-giam-gia');
            require VIEW_PATH . '/admin/coupons/_form.php';
            ?>
        </div>
    </div>
</div>

<?php if (!empty($_SESSION['_old'])): ?>
<script>
    document.getElementById('couponModal').classList.add('open');
    document.body.classList.add('modal-open');
</script>
<?php endif; ?>
