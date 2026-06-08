<?php

use App\Helpers\FormatHelper;
?>
<div class="toolbar"><h1>Đánh giá sản phẩm</h1></div>

<div class="panel">
    <div class="panel__body panel__body--flush">
        <table class="atable">
            <thead>
                <tr><th>Sản phẩm</th><th>Người đánh giá</th><th>Số sao</th><th>Nội dung</th><th>Trạng thái</th><th>Thao tác</th></tr>
            </thead>
            <tbody>
                <?php if (empty($reviews)): ?>
                    <tr><td colspan="6" class="empty-row">Chưa có đánh giá nào.</td></tr>
                <?php else: foreach ($reviews as $r): ?>
                    <tr>
                        <td><?= e($r['product_name'] ?: '—') ?></td>
                        <td><strong><?= e($r['author']) ?></strong><br><span class="muted"><?= FormatHelper::dateTime($r['created_at']) ?></span></td>
                        <td>
                            <?php for ($i = 0; $i < 5; $i++): ?>
                                <i class="fa-solid fa-star" style="color:<?= $i < (int) $r['rating'] ? '#f5821f' : '#d8dee6' ?>;"></i>
                            <?php endfor; ?>
                        </td>
                        <td class="muted" style="max-width:280px;"><?= e($r['content'] ?: '—') ?></td>
                        <td>
                            <?php if ((int) $r['is_approved'] === 1): ?>
                                <span class="badge badge--green">Đã duyệt</span>
                            <?php else: ?>
                                <span class="badge badge--orange">Chờ duyệt</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="act-group">
                                <?php if ((int) $r['is_approved'] === 0): ?>
                                    <form method="post" action="<?= url('admin/danh-gia/' . $r['id'] . '/duyet') ?>">
                                        <button class="abtn abtn--primary abtn--sm" type="submit"><i class="fa-solid fa-check"></i></button>
                                    </form>
                                <?php endif; ?>
                                <form method="post" action="<?= url('admin/danh-gia/' . $r['id'] . '/xoa') ?>" style="display:inline;">
                                    <button type="button" class="abtn abtn--danger abtn--sm" data-confirm="Xóa đánh giá này?"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
