<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Helpers\FormatHelper;
use App\Helpers\UploadHelper;
use App\Middleware\AdminMiddleware;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\Setting;
use App\Models\User;

class AdminController extends Controller
{
    public function __construct()
    {
        AdminMiddleware::handle();
    }

    public function dashboard(): void
    {
        \App\Models\Notification::markAllAsRead();
        $orderModel = new Order();
        $productModel = new Product();

        $this->view('admin/dashboard/index', [
            'pageTitle'    => 'Tổng quan',
            'totalProducts' => (new Product())->count(),
            'totalOrders'  => $orderModel->countByStatus(),
            'pendingOrders' => $orderModel->countByStatus('pending'),
            'totalUsers'   => (new User())->count("role = 'customer'"),
            'revenue'      => $orderModel->totalRevenue(),
            'pendingReviews' => (new Review())->countPending(),
            'recentOrders' => $orderModel->recent(8),
            'lowStock'     => $productModel->lowStock(10),
        ], 'admin');
    }

    public function products(): void
    {
        $keyword = trim((string) $this->query('q', ''));
        $this->view('admin/products/index', [
            'pageTitle'  => 'Quản lý sản phẩm',
            'products'   => (new Product())->adminAll($keyword),
            'keyword'    => $keyword,
            'categories' => (new Category())->all(),
            'brands'     => (new Brand())->all(),
        ], 'admin');
        unset($_SESSION['_old']);
    }

    public function productJson(string $id): void
    {
        $product = (new Product())->find((int) $id);
        if ($product === null) {
            $this->json(['error' => 'not_found'], 404);
        }
        $this->json($product);
    }

    public function productCreate(): void
    {
        $this->view('admin/products/create', [
            'pageTitle'  => 'Thêm sản phẩm',
            'categories' => (new Category())->all(),
            'brands'     => (new Brand())->all(),
        ], 'admin');
        unset($_SESSION['_old']);
    }

    public function productStore(): void
    {
        $data = $this->productData($_POST, $_FILES);
        if ($data === null) {
            $_SESSION['_old'] = $_POST;
            $this->redirect('/admin/san-pham/them');
        }

        $productModel = new Product();
        $existingProduct = null;

        // 1. Kiểm tra xem sản phẩm đã tồn tại dựa vào SKU hoặc Tên
        if (!empty($data['sku'])) {
            $existingProduct = $productModel->findBy('sku', $data['sku']);
        } else {
            $existingProduct = $productModel->findBy('name', $data['name']);
        }

        // 2. Nếu sản phẩm ĐÃ TỒN TẠI -> Tiến hành cộng dồn số lượng tồn kho (stock)
        if ($existingProduct !== null) {
            $oldStock = (int) $existingProduct['stock'];
            $addedStock = (int) $data['stock'];
            $newStock = $oldStock + $addedStock;

            // Cập nhật lại số lượng tồn kho mới cho sản phẩm cũ
            $productModel->update((int) $existingProduct['id'], [
                'stock' => $newStock
            ]);

            flash('success', 'Sản phẩm đã tồn tại. Hệ thống tự động cộng dồn thêm ' . $addedStock . ' vào kho hàng (Tổng tồn: ' . $newStock . ').');
        } 
        // 3. Nếu sản phẩm CHƯA TỒN TẠI -> Tạo mới hoàn toàn như cũ
        else {
            $productModel->create($data);
            flash('success', 'Đã thêm sản phẩm mới.');
        }

        $this->redirect('/admin/san-pham');
    }

    public function productEdit(string $id): void
    {
        $product = (new Product())->find((int) $id);
        if ($product === null) {
            flash('error', 'Không tìm thấy sản phẩm.');
            $this->redirect('/admin/san-pham');
        }
        $this->view('admin/products/edit', [
            'pageTitle'  => 'Sửa sản phẩm',
            'product'    => $product,
            'categories' => (new Category())->all(),
            'brands'     => (new Brand())->all(),
        ], 'admin');
        unset($_SESSION['_old']);
    }

    public function productUpdate(string $id): void
    {
        $product = (new Product())->find((int) $id);
        if ($product === null) {
            $this->redirect('/admin/san-pham');
        }
        $data = $this->productData($_POST, $_FILES, $product);
        if ($data === null) {
            $_SESSION['_old'] = $_POST;
            $this->redirect('/admin/san-pham/' . (int) $id . '/sua');
        }
        (new Product())->update((int) $id, $data);
        flash('success', 'Đã cập nhật sản phẩm.');
        $this->redirect('/admin/san-pham');
    }

    public function productDelete(string $id): void
    {
        (new Product())->delete((int) $id);
        flash('success', 'Đã xóa sản phẩm.');
        $this->redirect('/admin/san-pham');
    }

    public function orders(): void
    {
        $this->view('admin/orders/index', [
            'pageTitle' => 'Quản lý đơn hàng',
            'orders'    => (new Order())->recent(200),
        ], 'admin');
    }

    public function orderDetail(string $id): void
    {
        $order = (new Order())->find((int) $id);
        if ($order === null) {
            flash('error', 'Không tìm thấy đơn hàng.');
            $this->redirect('/admin/don-hang');
        }
        $this->view('admin/orders/detail', [
            'pageTitle' => 'Đơn hàng ' . $order['code'],
            'order'     => $order,
            'items'     => (new Order())->items((int) $id),
        ], 'admin');
    }

    public function orderStatus(string $id): void
    {
        $allowed = ['pending', 'confirmed', 'shipping', 'completed', 'cancelled'];
        $status  = $_POST['status'] ?? 'pending'; // Trạng thái mới Admin vừa click chọn

        if (in_array($status, $allowed, true)) {
            $orderModel = new Order();
            
            // 1. Tìm đơn hàng hiện tại để lấy ra trạng thái CŨ trước khi lưu
            $order = $orderModel->find((int) $id);

            if ($order !== null) {
                $oldStatus = $order['status'] ?? ''; // Trạng thái cũ trong DB

                // Chỉ xử lý kho nếu Admin thực sự đổi sang trạng thái khác
                if ($oldStatus !== $status) {
                    
                    // NẾU CHUYỂN SANG HOÀN THÀNH -> TRỪ KHO
                    if ($status === 'completed') {
                        $orderModel->updateInventory((int) $id, 'decrease');
                    } 
                    
                    // NẾU ĐƠN ĐÃ HOÀN THÀNH MÀ BỊ HỦY -> CỘNG TRẢ KHO
                    elseif ($status === 'cancelled' && $oldStatus === 'completed') {
                        $orderModel->updateInventory((int) $id, 'increase');
                    }
                }
            }

            // 2. Lưu trạng thái mới vào Database như cũ
            $orderModel->updateStatus((int) $id, $status);
            flash('success', 'Đã cập nhật trạng thái đơn hàng và biến động kho.');
        }
        
        $this->redirect('/admin/don-hang');
    }

    public function users(): void
    {
        $this->view('admin/users/index', [
            'pageTitle' => 'Quản lý người dùng',
            'users'     => (new User())->allCustomers(),
        ], 'admin');
    }

    public function settings(): void
    {
        $this->view('admin/settings/index', [
            'pageTitle' => 'Cấu hình website',
            'settings'  => (new Setting())->load(),
        ], 'admin');
    }

    public function settingsUpdate(): void
    {
        $keys = ['site_name', 'hotline', 'hotline_north', 'hotline_south', 'email', 'address', 'working_hours', 'free_ship_threshold'];
        $setting = new Setting();
        foreach ($keys as $key) {
            if (array_key_exists($key, $_POST)) {
                $setting->set($key, trim((string) $_POST[$key]));
            }
        }
        flash('success', 'Đã lưu cấu hình.');
        $this->redirect('/admin/cau-hinh');
    }

    private function productData(array $post, array $files, ?array $current = null): ?array
    {
        $name = trim($post['name'] ?? '');
        $price = $post['price'] ?? '';
        if ($name === '' || !is_numeric($price)) {
            flash('error', 'Vui lòng nhập tên và giá hợp lệ.');
            return null;
        }

        $thumbnail = $current['thumbnail'] ?? null;
        if (!empty($files['thumbnail']['name'])) {
            $uploaded = UploadHelper::image($files['thumbnail']);
            if ($uploaded !== null) {
                $thumbnail = $uploaded;
            }
        }

        $slug = $current['slug'] ?? '';
        if ($slug === '' || ($current && $current['name'] !== $name)) {
            $slug = $this->uniqueSlug(FormatHelper::slug($name), (int) ($current['id'] ?? 0));
        }

        $sale = trim((string) ($post['sale_price'] ?? ''));

        return [
            'name'        => $name,
            'slug'        => $slug,
            'sku'         => trim($post['sku'] ?? '') ?: null,
            'category_id' => (int) ($post['category_id'] ?? 0) ?: null,
            'brand_id'    => (int) ($post['brand_id'] ?? 0) ?: null,
            'price'       => (float) $price,
            'sale_price'  => $sale !== '' ? (float) $sale : null,
            'stock'       => (int) ($post['stock'] ?? 0),
            'thumbnail'   => $thumbnail,
            'short_desc'  => trim($post['short_desc'] ?? '') ?: null,
            'description' => trim($post['description'] ?? '') ?: null,
            'specs'       => trim($post['specs'] ?? '') ?: null,
            'is_featured' => isset($post['is_featured']) ? 1 : 0,
            'is_active'   => isset($post['is_active']) ? 1 : 0,
        ];
    }

    private function uniqueSlug(string $slug, int $ignoreId = 0): string
    {
        $base = $slug !== '' ? $slug : 'san-pham';
        $candidate = $base;
        $model = new Product();
        $i = 2;
        while (true) {
            $found = $model->findBy('slug', $candidate);
            if ($found === null || (int) $found['id'] === $ignoreId) {
                return $candidate;
            }
            $candidate = $base . '-' . $i;
            $i++;
        }
    }
}
