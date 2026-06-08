<?php

/**
 * Controller Trang chủ và các trang tĩnh (giới thiệu, liên hệ).
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    /**
     * Trang chủ: banner, lưới danh mục, sản phẩm nổi bật, sản phẩm mới.
     */
    public function index(): void
    {
        $categoryModel = new Category();
        $productModel  = new Product();

        $this->view('home/index', [
            'pageTitle'  => 'Trang chủ',
            'categories' => $categoryModel->withProductCount(),
            'featured'   => $productModel->featured(8),
            'latest'     => $productModel->latest(8),
        ]);
    }

    /**
     * Trang giới thiệu (nội dung thuần tuý).
     */
    public function about(): void
    {
        $this->view('pages/about', ['pageTitle' => 'Giới thiệu']);
    }

    /**
     * Trang liên hệ.
     */
    public function contact(): void
    {
        $this->view('pages/contact', ['pageTitle' => 'Liên hệ']);
    }
    public function warranty()
    {
    $this->view('pages/bao-hanh', ['pageTitle' => 'Chính sách bảo hành']);
    }

    public function returns()
    {
    $this->view('pages/doi-tra', ['pageTitle' => 'Chính sách đổi trả']);
    }

    public function guide()
    {
    $this->view('pages/huong-dan', ['pageTitle' => 'Hướng dẫn mua hàng']);
    }
}
