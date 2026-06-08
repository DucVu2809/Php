<?php

/**
 * Bảng định tuyến của website (Frontend).
 *
 * Khai báo tất cả URL công khai và ánh xạ tới controller::action tương ứng.
 * File trả về một đối tượng Router đã đăng ký đầy đủ route.
 */

declare(strict_types=1);

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\ProductController;
use App\Controllers\CartController;
use App\Controllers\AuthController;
use App\Controllers\OrderController;
use App\Controllers\AdminController;
use App\Controllers\CouponController;
use App\Controllers\InventoryController;
use App\Controllers\ReviewController;

$router = new Router();

$router->get('/',            [HomeController::class, 'index']);
$router->get('/gioi-thieu',  [HomeController::class, 'about']);
$router->get('/lien-he',     [HomeController::class, 'contact']);
$router->get('/tin-tuc',     [HomeController::class, 'news']);
$router->get('/khuyen-mai',  [HomeController::class, 'promotion']);

$router->get('/san-pham',        [ProductController::class, 'index']);
$router->post('/danh-gia',       [ProductController::class, 'review']);
$router->get('/danh-muc/{slug}', [ProductController::class, 'category']);
$router->get('/san-pham/{slug}', [ProductController::class, 'show']);

$router->get('/gio-hang',           [CartController::class, 'index']);
$router->post('/gio-hang/them',     [CartController::class, 'add']);
$router->post('/gio-hang/cap-nhat', [CartController::class, 'update']);
$router->post('/gio-hang/xoa',      [CartController::class, 'remove']);

$router->get('/dang-nhap',  [AuthController::class, 'loginForm']);
$router->post('/dang-nhap', [AuthController::class, 'login']);
$router->get('/dang-ky',    [AuthController::class, 'registerForm']);
$router->post('/dang-ky',   [AuthController::class, 'register']);
$router->get('/dang-xuat',  [AuthController::class, 'logout']);

$router->get('/dat-hang',                  [OrderController::class, 'checkout']);
$router->post('/dat-hang',                 [OrderController::class, 'place']);
$router->post('/dat-hang/ma-giam-gia',     [OrderController::class, 'applyCoupon']);
$router->post('/dat-hang/xoa-ma',          [OrderController::class, 'removeCoupon']);
$router->get('/dat-hang/thanh-cong/{code}', [OrderController::class, 'success']);

$router->get('/admin', [AdminController::class, 'dashboard']);

$router->get('/admin/san-pham',            [AdminController::class, 'products']);
$router->get('/admin/san-pham/them',       [AdminController::class, 'productCreate']);
$router->post('/admin/san-pham',           [AdminController::class, 'productStore']);
$router->get('/admin/san-pham/{id}/json',  [AdminController::class, 'productJson']);
$router->get('/admin/san-pham/{id}/sua',   [AdminController::class, 'productEdit']);
$router->post('/admin/san-pham/{id}/xoa',  [AdminController::class, 'productDelete']);
$router->post('/admin/san-pham/{id}',      [AdminController::class, 'productUpdate']);

$router->get('/admin/don-hang',                  [AdminController::class, 'orders']);
$router->post('/admin/don-hang/{id}/trang-thai', [AdminController::class, 'orderStatus']);
$router->get('/admin/don-hang/{id}',             [AdminController::class, 'orderDetail']);

$router->get('/admin/nguoi-dung', [AdminController::class, 'users']);

$router->get('/admin/cau-hinh',  [AdminController::class, 'settings']);
$router->post('/admin/cau-hinh', [AdminController::class, 'settingsUpdate']);

$router->get('/admin/ma-giam-gia',           [CouponController::class, 'index']);
$router->get('/admin/ma-giam-gia/them',      [CouponController::class, 'create']);
$router->post('/admin/ma-giam-gia',          [CouponController::class, 'store']);
$router->get('/admin/ma-giam-gia/{id}/json', [CouponController::class, 'show']);
$router->get('/admin/ma-giam-gia/{id}/sua',  [CouponController::class, 'edit']);
$router->post('/admin/ma-giam-gia/{id}/xoa', [CouponController::class, 'delete']);
$router->post('/admin/ma-giam-gia/{id}',     [CouponController::class, 'update']);

$router->get('/admin/kho',       [InventoryController::class, 'index']);
$router->get('/admin/kho/them',  [InventoryController::class, 'create']);
$router->post('/admin/kho',      [InventoryController::class, 'store']);
$router->get('/admin/kho/{id}',  [InventoryController::class, 'detail']);

$router->get('/admin/danh-gia',           [ReviewController::class, 'index']);
$router->post('/admin/danh-gia/{id}/duyet', [ReviewController::class, 'approve']);
$router->post('/admin/danh-gia/{id}/xoa',   [ReviewController::class, 'delete']);

return $router;
