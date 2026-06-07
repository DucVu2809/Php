<?php

$cv = static function (string $key, string $default = '') use ($coupon): string {
    $old = $_SESSION['_old'][$key] ?? null;
    if ($old !== null) {
        return (string) $old;
    }
    return (string) ($coupon[$key] ?? $default);
};
$ckActive = !empty($_SESSION['_old'])
    ? isset($_SESSION['_old']['is_active'])
    : (empty($coupon) ? true : (int) ($coupon['is_active'] ?? 0) === 1);
?>
<form id="couponForm" class="aform aform--2" method="post" action="<?= $action ?>" data-store="<?= url('admin/ma-giam-gia') ?>">
    <div class="afield">
        <label>Mã giảm giá *</label>
        <input type="text" name="code" value="<?= e($cv('code')) ?>" placeholder="VD: XINMAI10" required>
    </div>
    <div class="afield">
        <label>Loại giảm</label>
        <select name="type">
            <option value="percent" <?= $cv('type') === 'percent' ? 'selected' : '' ?>>Phần trăm (%)</option>
            <option value="fixed" <?= $cv('type') === 'fixed' ? 'selected' : '' ?>>Số tiền (VNĐ)</option>
        </select>
    </div>
    <div class="afield">
        <label>Giá trị *</label>
        <input type="number" name="value" value="<?= e($cv('value', '0')) ?>" min="0" required>
    </div>
    <div class="afield">
        <label>Đơn tối thiểu (VNĐ)</label>
        <input type="number" name="min_order" value="<?= e($cv('min_order', '0')) ?>" min="0">
    </div>
    <div class="afield">
        <label>Giới hạn lượt dùng</label>
        <input type="number" name="usage_limit" value="<?= e($cv('usage_limit')) ?>" min="0">
    </div>
    <div class="afield">
        <label>Ngày hết hạn</label>
        <input type="date" name="expires_at" value="<?= e($cv('expires_at')) ?>">
    </div>
    <div class="afield afield--full">
        <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
            <input type="checkbox" name="is_active" <?= $ckActive ? 'checked' : '' ?>> Kích hoạt mã
        </label>
    </div>
    <div class="aform__actions">
        <button class="abtn abtn--primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Lưu</button>
        <button type="button" class="abtn abtn--ghost" data-modal-close>Hủy</button>
    </div>
</form>
