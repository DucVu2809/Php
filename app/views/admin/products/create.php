<?php
$product = [];
$action = url('admin/san-pham');
?>
<div class="toolbar">
    <h1>Thêm sản phẩm</h1>
    <a class="abtn abtn--ghost" href="<?= url('admin/san-pham') ?>"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
</div>
<div class="panel">
    <div class="panel__body">
        <?php require VIEW_PATH . '/admin/products/_form.php'; ?>
    </div>
</div>
