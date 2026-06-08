<?php
/**
 * Trang giỏ hàng: bảng sản phẩm, cập nhật số lượng, xoá, tổng tiền.
 *
 * @var array<int,array<string,mixed>> $items
 * @var float $subtotal
 */

use App\Helpers\FormatHelper;
?>

<div class="container breadcrumb">
    <a href="<?= url('/') ?>">Trang chủ</a>
    <i class="fa-solid fa-angle-right"></i>
    <span>Giỏ hàng</span>
</div>

<div class="container section">
    <h1 class="page-title"><i class="fa-solid fa-cart-shopping"></i> Giỏ hàng của bạn</h1>

    <?php if (empty($items)): ?>
        <div class="empty">
            <i class="fa-solid fa-cart-arrow-down"></i>
            <p>Giỏ hàng đang trống.</p>
            <a class="btn btn-primary" href="<?= url('san-pham') ?>">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <div class="cart">
            <div class="cart__list">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Đơn giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr data-cart-row data-id="<?= (int) $item['id'] ?>">
                                <td class="cart-table__prod">
                                    <img src="<?= product_image($item['image']) ?>" alt="<?= e($item['name']) ?>">
                                    <a href="<?= url('san-pham/' . $item['slug']) ?>"><?= e($item['name']) ?></a>
                                </td>
                                <td data-label="Đơn giá"><?= FormatHelper::price($item['price']) ?></td>
                                <td data-label="Số lượng">
                                    <div class="qty qty--sm">
                                        <button type="button" data-cart-qty="-1">−</button>
                                        <input type="number" value="<?= (int) $item['quantity'] ?>" min="1" data-cart-input>
                                        <button type="button" data-cart-qty="1">+</button>
                                    </div>
                                </td>
                                <td data-label="Thành tiền" class="cart-table__total">
                                    <?= FormatHelper::price($item['price'] * $item['quantity']) ?>
                                </td>
                                <td>
                                    <button class="cart-remove" type="button" data-cart-remove title="Xoá">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a class="btn btn-outline" href="<?= url('san-pham') ?>"><i class="fa-solid fa-arrow-left"></i> Tiếp tục mua sắm</a>
            </div>

            <aside class="cart__summary">
                <h3>Tổng giỏ hàng</h3>
                <div class="cart__summary-row">
                    <span>Tạm tính</span>
                    <strong id="cartSubtotal"><?= FormatHelper::price($subtotal) ?></strong>
                </div>
                <div class="cart__summary-row">
                    <span>Phí vận chuyển</span>
                    <span>Liên hệ</span>
                </div>
                <div class="cart__summary-total">
                    <span>Tổng cộng</span>
                    <strong id="cartTotal"><?= FormatHelper::price($subtotal) ?></strong>
                </div>
                <a class="btn btn-accent btn-block" href="<?= url('dat-hang') ?>"><i class="fa-solid fa-credit-card"></i> Tiến hành đặt hàng</a>
                <p class="cart__note">Bạn có thể đặt hàng không cần đăng nhập.</p>
            </aside>
        </div>
    <?php endif; ?>
</div>
