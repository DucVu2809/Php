<?php

/**
 * Controller Giỏ hàng: trang giỏ + các thao tác thêm/sửa/xoá qua AJAX.
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Cart;
use App\Core\Controller;
use App\Models\Product;

class CartController extends Controller
{
    /**
     * Trang giỏ hàng.
     */
    public function index(): void
    {
        $this->view('cart/index', [
            'pageTitle' => 'Giỏ hàng',
            'items'     => Cart::items(),
            'subtotal'  => Cart::subtotal(),
        ]);
    }

    /**
     * Thêm sản phẩm vào giỏ (AJAX). Trả JSON gồm số lượng & tổng tiền mới.
     */
    public function add(): void
    {
        $productId = (int) ($_POST['product_id'] ?? 0);
        $quantity  = max(1, (int) ($_POST['quantity'] ?? 1));

        $product = (new Product())->find($productId);
        if ($product === null || (int) $product['is_active'] !== 1) {
            $this->json(['ok' => false, 'message' => 'Sản phẩm không tồn tại.'], 404);
        }

        Cart::add($product, $quantity);

        $this->json([
            'ok'           => true,
            'message'      => 'Đã thêm "' . $product['name'] . '" vào giỏ hàng.',
            'cartCount'    => Cart::totalQuantity(),
            'cartSubtotal' => Cart::subtotal(),
        ]);
    }

    /**
     * Cập nhật số lượng một mục trong giỏ (AJAX).
     */
    public function update(): void
    {
        $productId = (int) ($_POST['product_id'] ?? 0);
        $quantity  = (int) ($_POST['quantity'] ?? 1);

        Cart::update($productId, $quantity);

        $this->json([
            'ok'           => true,
            'cartCount'    => Cart::totalQuantity(),
            'cartSubtotal' => Cart::subtotal(),
        ]);
    }

    /**
     * Xoá một sản phẩm khỏi giỏ (AJAX).
     */
    public function remove(): void
    {
        $productId = (int) ($_POST['product_id'] ?? 0);
        Cart::remove($productId);

        $this->json([
            'ok'           => true,
            'cartCount'    => Cart::totalQuantity(),
            'cartSubtotal' => Cart::subtotal(),
        ]);
    }
}
