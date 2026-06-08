<?php

use App\Helpers\FormatHelper;
?>
<div class="toolbar">
    <h1>Sản phẩm</h1>
    <div style="display:flex; gap:10px; align-items:center;">
        <form class="toolbar__search" method="get" action="<?= url('admin/san-pham') ?>">
            <input type="text" name="q" value="<?= e($keyword) ?>" placeholder="Tìm theo tên / SKU...">
            <button class="abtn abtn--ghost" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
        <button type="button" class="abtn abtn--primary" data-product-add><i class="fa-solid fa-plus"></i> Thêm sản phẩm</button>
    </div>
</div>

<div class="panel">
    <div class="panel__body panel__body--flush">
        <table class="atable">
            <thead>
                <tr><th>Ảnh</th><th>Tên sản phẩm</th><th>Danh mục</th><th>Giá</th><th>Tồn</th><th>Trạng thái</th><th>Thao tác</th></tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr><td colspan="7" class="empty-row">Chưa có sản phẩm nào.</td></tr>
                <?php else: foreach ($products as $p): ?>
                    <tr>
                        <td><img class="thumb" src="<?= product_image($p['thumbnail']) ?>" alt=""></td>
                        <td>
                            <strong><?= e($p['name']) ?></strong>
                            <?php if (!empty($p['brand_name'])): ?><br><span class="muted"><?= e($p['brand_name']) ?></span><?php endif; ?>
                        </td>
                        <td class="muted"><?= e($p['category_name'] ?? '—') ?></td>
                        <td>
                            <span class="price"><?= FormatHelper::price($p['sale_price'] ?: $p['price']) ?></span>
                            <?php if ($p['sale_price']): ?><br><span class="muted" style="text-decoration:line-through;"><?= FormatHelper::price($p['price']) ?></span><?php endif; ?>
                        </td>
                        <td><span class="badge <?= (int) $p['stock'] <= 5 ? 'badge--red' : 'badge--gray' ?>"><?= (int) $p['stock'] ?></span></td>
                        <td>
                            <?php if ((int) $p['is_active'] === 1): ?><span class="badge badge--green">Đang bán</span><?php else: ?><span class="badge badge--gray">Ẩn</span><?php endif; ?>
                            <?php if ((int) $p['is_featured'] === 1): ?><br><span class="badge badge--orange">Nổi bật</span><?php endif; ?>
                        </td>
                        <td>
                            <div class="act-group">
                                <button type="button" class="abtn abtn--ghost abtn--sm" data-product-edit="<?= (int) $p['id'] ?>"><i class="fa-solid fa-pen"></i></button>
                                <form method="post" action="<?= url('admin/san-pham/' . $p['id'] . '/xoa') ?>" style="display:inline;">
                                    <button type="button" class="abtn abtn--danger abtn--sm" data-confirm="Xóa sản phẩm &quot;<?= e($p['name']) ?>&quot;?"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal" id="productModal">
    <div class="modal__box">
        <div class="modal__head">
            <h3 data-modal-title>Thêm sản phẩm</h3>
            <button type="button" class="modal__x" data-modal-close>&times;</button>
        </div>
        <div class="modal__body">
            <?php
            $product = [];
            $action = url('admin/san-pham');
            require VIEW_PATH . '/admin/products/_form.php';
            ?>
        </div>
    </div>
</div>

<?php if (!empty($_SESSION['_old'])): ?>
<script>
    document.getElementById('productModal').classList.add('open');
    document.body.classList.add('modal-open');
</script>
<?php endif; ?>
