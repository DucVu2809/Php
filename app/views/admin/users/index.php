<?php

use App\Helpers\FormatHelper;
?>
<div class="toolbar"><h1>Người dùng</h1></div>

<div class="panel">
    <div class="panel__body panel__body--flush">
        <table class="atable">
            <thead>
                <tr><th>#</th><th>Họ tên</th><th>Email</th><th>Điện thoại</th><th>Vai trò</th><th>Ngày tạo</th></tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr><td colspan="6" class="empty-row">Chưa có người dùng nào.</td></tr>
                <?php else: foreach ($users as $u): ?>
                    <tr>
                        <td><?= (int) $u['id'] ?></td>
                        <td><strong><?= e($u['name']) ?></strong></td>
                        <td><?= e($u['email']) ?></td>
                        <td><?= e($u['phone'] ?: '—') ?></td>
                        <td>
                            <?php if ($u['role'] === 'admin'): ?>
                                <span class="badge badge--orange">Quản trị</span>
                            <?php else: ?>
                                <span class="badge badge--gray">Khách hàng</span>
                            <?php endif; ?>
                        </td>
                        <td class="muted"><?= FormatHelper::dateTime($u['created_at']) ?></td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
