<?php
$coupon = [];
$action = url('admin/ma-giam-gia');
?>
<div class="toolbar">
    <h1>Thêm mã giảm giá</h1>
    <a class="abtn abtn--ghost" href="<?= url('admin/ma-giam-gia') ?>"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
</div>
<div class="panel">
    <div class="panel__body">
        <?php require VIEW_PATH . '/admin/coupons/_form.php'; ?>
    </div>
</div>
