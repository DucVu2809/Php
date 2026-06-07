<?php
$s = static fn (string $k): string => (string) ($settings[$k] ?? '');
?>
<div class="toolbar"><h1>Cấu hình website</h1></div>

<div class="panel">
    <div class="panel__body">
        <form class="aform aform--2" method="post" action="<?= url('admin/cau-hinh') ?>">
            <div class="afield afield--full">
                <label>Tên website</label>
                <input type="text" name="site_name" value="<?= e($s('site_name')) ?>">
            </div>
            <div class="afield">
                <label>Hotline chính</label>
                <input type="text" name="hotline" value="<?= e($s('hotline')) ?>">
            </div>
            <div class="afield">
                <label>Email</label>
                <input type="text" name="email" value="<?= e($s('email')) ?>">
            </div>
            <div class="afield">
                <label>Hotline Miền Bắc</label>
                <input type="text" name="hotline_north" value="<?= e($s('hotline_north')) ?>">
            </div>
            <div class="afield">
                <label>Hotline Miền Nam</label>
                <input type="text" name="hotline_south" value="<?= e($s('hotline_south')) ?>">
            </div>
            <div class="afield afield--full">
                <label>Địa chỉ</label>
                <input type="text" name="address" value="<?= e($s('address')) ?>">
            </div>
            <div class="afield">
                <label>Giờ làm việc</label>
                <input type="text" name="working_hours" value="<?= e($s('working_hours')) ?>">
            </div>
            <div class="afield">
                <label>Ngưỡng miễn phí ship (VNĐ)</label>
                <input type="number" name="free_ship_threshold" value="<?= e($s('free_ship_threshold')) ?>">
            </div>
            <div class="aform__actions">
                <button class="abtn abtn--primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Lưu cấu hình</button>
            </div>
        </form>
    </div>
</div>
