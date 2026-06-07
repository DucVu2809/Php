<?php
$action = url('admin/san-pham/' . $product['id']);
?>
<div class="toolbar">
    <h1>Sửa sản phẩm</h1>
    <a class="abtn abtn--ghost" href="<?= url('admin/san-pham') ?>"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
</div>
<div class="panel">
    <div class="panel__body">
        <?php require VIEW_PATH . '/admin/products/_form.php'; ?>
    </div>
</div>
