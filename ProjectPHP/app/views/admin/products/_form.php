<?php

$pv = static function (string $key, string $default = '') use ($product): string {
    $old = $_SESSION['_old'][$key] ?? null;
    if ($old !== null) {
        return (string) $old;
    }
    return (string) ($product[$key] ?? $default);
};
$checked = static function (string $key, bool $default = false) use ($product): bool {
    if (!empty($_SESSION['_old'])) {
        return isset($_SESSION['_old'][$key]);
    }
    if (!empty($product)) {
        return (int) ($product[$key] ?? 0) === 1;
    }
    return $default;
};
$hasThumb = !empty($product['thumbnail']);
?>
<form id="productForm" class="aform aform--2" method="post" action="<?= $action ?>" data-store="<?= url('admin/san-pham') ?>" enctype="multipart/form-data">
    <div class="afield afield--full">
        <label>Tên sản phẩm *</label>
        <input type="text" name="name" value="<?= e($pv('name')) ?>" required>
    </div>
    <div class="afield">
        <label>Danh mục</label>
        <select name="category_id">
            <option value="">-- Chọn danh mục --</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?= (int) $c['id'] ?>" <?= (int) $pv('category_id') === (int) $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="afield">
        <label>Hãng sản xuất</label>
        <select name="brand_id">
            <option value="">-- Chọn hãng --</option>
            <?php foreach ($brands as $b): ?>
                <option value="<?= (int) $b['id'] ?>" <?= (int) $pv('brand_id') === (int) $b['id'] ? 'selected' : '' ?>><?= e($b['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="afield">
        <label>Mã SKU</label>
        <input type="text" name="sku" value="<?= e($pv('sku')) ?>">
    </div>
    <div class="afield">
        <label>Tồn kho</label>
        <input type="number" name="stock" value="<?= e($pv('stock', '0')) ?>" min="0">
    </div>
    <div class="afield">
        <label>Giá gốc (VNĐ) *</label>
        <input type="number" name="price" value="<?= e($pv('price', '0')) ?>" min="0" required>
    </div>
    <div class="afield">
        <label>Giá khuyến mãi</label>
        <input type="number" name="sale_price" value="<?= e($pv('sale_price')) ?>" min="0">
    </div>
    <div class="afield afield--full">
        <label>Mô tả ngắn</label>
        <input type="text" name="short_desc" value="<?= e($pv('short_desc')) ?>">
    </div>
    <div class="afield afield--full">
        <label>Mô tả chi tiết</label>
        <div id="descEditor"><?= $pv('description') ?></div>
        <textarea name="description" id="descInput" hidden><?= e($pv('description')) ?></textarea>
    </div>
    <div class="afield afield--full">
        <label>Thông số kỹ thuật (JSON)</label>
        <textarea name="specs" rows="3"><?= e($pv('specs')) ?></textarea>
    </div>
    <div class="afield afield--full">
        <label>Ảnh sản phẩm</label>
        <div class="uploader" data-uploader>
            <input type="file" name="thumbnail" accept="image/*" hidden data-upload-input>
            <img data-current-thumb class="uploader__preview" src="<?= $hasThumb ? product_image($product['thumbnail']) : '' ?>" alt="" style="display:<?= $hasThumb ? 'block' : 'none' ?>;">
            <div class="uploader__hint" data-upload-hint style="display:<?= $hasThumb ? 'none' : 'flex' ?>;">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <span>Kéo thả ảnh vào đây hoặc <b>bấm để chọn</b></span>
                <small>Hỗ trợ JPG, PNG, WEBP, SVG</small>
            </div>
        </div>
    </div>
    <div class="afield" style="justify-content:center; gap:12px;">
        <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
            <input type="checkbox" name="is_active" <?= $checked('is_active', true) ? 'checked' : '' ?>> Đang bán
        </label>
        <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
            <input type="checkbox" name="is_featured" <?= $checked('is_featured') ? 'checked' : '' ?>> Nổi bật
        </label>
    </div>
    <div class="aform__actions">
        <button class="abtn abtn--primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Lưu</button>
        <button type="button" class="abtn abtn--ghost" data-modal-close>Hủy</button>
    </div>
</form>
