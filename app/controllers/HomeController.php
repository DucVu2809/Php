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

    /**
 * Trang tin tức.
 */
public function news(): void
{
    $this->view('pages/news', [
        'pageTitle' => 'Tin tức'
    ]);
}

/**
 * Trang khuyến mãi.
 */
public function promotion(): void
{
    $productModel = new Product();

    $this->view('pages/promotion', [
        'pageTitle' => 'Khuyến mãi',
        'products'  => $productModel->promotions(50)
    ]);
}
}
