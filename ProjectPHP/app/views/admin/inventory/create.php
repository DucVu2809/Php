<div class="toolbar">
    <h1>Tạo phiếu nhập kho</h1>
    <a class="abtn abtn--ghost" href="<?= url('admin/kho') ?>"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
</div>

<form method="post" action="<?= url('admin/kho') ?>">
    <div class="panel">
        <div class="panel__body">
            <div class="aform aform--2">
                <div class="afield">
                    <label>Nhà cung cấp</label>
                    <input type="text" name="supplier" placeholder="Tên nhà cung cấp">
                </div>
                <div class="afield">
                    <label>Ghi chú</label>
                    <input type="text" name="note" placeholder="Ghi chú phiếu nhập">
                </div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel__head"><h2>Sản phẩm nhập</h2></div>
        <div class="panel__body panel__body--flush">
            <table class="atable" id="importRows">
                <thead>
                    <tr><th style="width:50%;">Sản phẩm</th><th>Số lượng</th><th>Giá nhập (VNĐ)</th><th></th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <select name="product_id[]" class="status-select" style="width:100%;">
                                <option value="">-- Chọn sản phẩm --</option>
                                <?php foreach ($products as $p): ?>
                                    <option value="<?= (int) $p['id'] ?>"><?= e($p['name']) ?> (tồn: <?= (int) $p['stock'] ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><input type="number" name="quantity[]" min="0" value="0" class="status-select" style="width:100px;"></td>
                        <td><input type="number" name="cost_price[]" min="0" value="0" class="status-select" style="width:140px;"></td>
                        <td><button type="button" class="abtn abtn--danger abtn--sm" data-remove-row><i class="fa-solid fa-xmark"></i></button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div style="display:flex; gap:10px;">
        <button type="button" class="abtn abtn--ghost" id="addImportRow"><i class="fa-solid fa-plus"></i> Thêm dòng</button>
        <button type="submit" class="abtn abtn--primary"><i class="fa-solid fa-floppy-disk"></i> Lưu phiếu &amp; cập nhật tồn kho</button>
    </div>
</form>

<script>
(function () {
    var table = document.querySelector('#importRows tbody');
    document.getElementById('addImportRow').addEventListener('click', function () {
        var row = table.querySelector('tr');
        var clone = row.cloneNode(true);
        clone.querySelectorAll('input').forEach(function (i) { i.value = i.type === 'number' ? '0' : ''; });
        clone.querySelector('select').selectedIndex = 0;
        table.appendChild(clone);
    });
    table.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-remove-row]');
        if (!btn) { return; }
        if (table.querySelectorAll('tr').length > 1) { btn.closest('tr').remove(); }
    });
})();
</script>
