<?php

/**
 * Dịch vụ Giỏ hàng lưu trong session.
 *
 * Mỗi mục giỏ hàng là một dòng: id, name, price, image, slug, quantity.
 * Toàn bộ thao tác thêm/sửa/xoá/tính tổng được gom về một chỗ để
 * controller và view dùng lại nhất quán.
 */

declare(strict_types=1);

namespace App\Core;

class Cart
{
    /** Khoá lưu giỏ hàng trong $_SESSION. */
    private const KEY = 'cart';

    /**
     * Lấy toàn bộ giỏ hàng.
     *
     * @return array<int,array<string,mixed>>  Mảng item, key là product id
     */
    public static function items(): array
    {
        return $_SESSION[self::KEY] ?? [];
    }

    /**
     * Thêm sản phẩm vào giỏ (cộng dồn nếu đã có).
     *
     * @param array<string,mixed> $product Bản ghi sản phẩm từ DB
     */
    public static function add(array $product, int $quantity = 1): void
    {
        $quantity = max(1, $quantity);
        $id       = (int) $product['id'];
        $cart     = self::items();

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                'id'       => $id,
                'name'     => $product['name'],
                'slug'     => $product['slug'],
                'price'    => (float) ($product['sale_price'] ?: $product['price']),
                'image'    => $product['thumbnail'],
                'quantity' => $quantity,
            ];
        }

        $_SESSION[self::KEY] = $cart;
    }

    /**
     * Cập nhật số lượng một mục; số lượng <= 0 sẽ xoá mục đó.
     */
    public static function update(int $productId, int $quantity): void
    {
        $cart = self::items();
        if (!isset($cart[$productId])) {
            return;
        }

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId]['quantity'] = $quantity;
        }

        $_SESSION[self::KEY] = $cart;
    }

    /**
     * Xoá một sản phẩm khỏi giỏ.
     */
    public static function remove(int $productId): void
    {
        $cart = self::items();
        unset($cart[$productId]);
        $_SESSION[self::KEY] = $cart;
    }

    /**
     * Xoá sạch giỏ hàng.
     */
    public static function clear(): void
    {
        unset($_SESSION[self::KEY]);
    }

    /**
     * Tổng số lượng sản phẩm trong giỏ (dùng cho badge ở header).
     */
    public static function totalQuantity(): int
    {
        return array_sum(array_column(self::items(), 'quantity'));
    }

    /**
     * Tổng tiền tạm tính của giỏ hàng.
     */
    public static function subtotal(): float
    {
        $total = 0.0;
        foreach (self::items() as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}
