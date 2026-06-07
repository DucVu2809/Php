<?php

/**
 * Controller Sản phẩm: danh sách, lọc theo danh mục, chi tiết, tìm kiếm.
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\View;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;

class ProductController extends Controller
{
    /**
     * Danh sách toàn bộ sản phẩm + tìm kiếm + bộ lọc (trang /san-pham).
     */
    public function index(): void
    {
        $this->renderListing(null, 'Tất cả sản phẩm');
    }

    /**
     * Danh sách sản phẩm theo danh mục (trang /danh-muc/{slug}).
     */
    public function category(string $slug): void
    {
        $category = (new Category())->findBySlug($slug);
        if ($category === null) {
            $this->notFound();
            return;
        }
        $this->renderListing($category, $category['name']);
    }

    /**
     * Chi tiết một sản phẩm (trang /san-pham/{slug}).
     */
    public function show(string $slug): void
    {
        $productModel = new Product();
        $product      = $productModel->findBySlug($slug);

        if ($product === null) {
            $this->notFound();
            return;
        }

        $productModel->incrementViews((int) $product['id']);

        $reviewModel = new Review();

        $this->view('products/detail', [
            'pageTitle'     => $product['name'],
            'product'       => $product,
            'images'        => $productModel->images((int) $product['id']),
            'related'       => $productModel->related(
                (int) $product['category_id'],
                (int) $product['id'],
                4
            ),
            'reviews'       => $reviewModel->approvedFor((int) $product['id']),
            'reviewSummary' => $reviewModel->summaryFor((int) $product['id']),
        ]);
        unset($_SESSION['_old']);
    }

    public function review(): void
    {
        $productId = (int) ($_POST['product_id'] ?? 0);
        $product   = (new Product())->find($productId);
        if ($product === null) {
            $this->redirect('/san-pham');
        }

        $author  = trim($_POST['author'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $rating  = (int) ($_POST['rating'] ?? 5);
        if ($rating < 1 || $rating > 5) {
            $rating = 5;
        }

        if ($author === '' && Auth::check()) {
            $author = Auth::user()['name'];
        }

        if ($author === '' || $content === '') {
            flash('error', 'Vui lòng nhập tên và nội dung đánh giá.');
            $this->redirect('/san-pham/' . $product['slug']);
        }

        (new Review())->create([
            'product_id'  => $productId,
            'user_id'     => Auth::id(),
            'author'      => $author,
            'rating'      => $rating,
            'content'     => $content,
            'is_approved' => 0,
        ]);

        flash('success', 'Cảm ơn bạn! Đánh giá sẽ hiển thị sau khi được duyệt.');
        $this->redirect('/san-pham/' . $product['slug']);
    }

    /**
     * Dựng trang danh sách dùng chung cho cả "tất cả" và "theo danh mục".
     *
     * @param array<string,mixed>|null $category
     */
    private function renderListing(?array $category, string $title): void
    {
        $productModel = new Product();

        $filters = [
            'category_id' => $category['id'] ?? null,
            'brand_id'    => (int) $this->query('brand', 0) ?: null,
            'keyword'     => trim((string) $this->query('q', '')) ?: null,
            'min_price'   => (int) $this->query('min_price', 0) ?: null,
            'max_price'   => (int) $this->query('max_price', 0) ?: null,
            'sort'        => $this->query('sort', null),
            'page'        => (int) $this->query('page', 1),
        ];

        $result = $productModel->paginate($filters);

        $this->view('products/index', [
            'pageTitle'  => $title,
            'category'   => $category,
            'brands'     => (new Brand())->active(),
            'categories' => (new Category())->withProductCount(),
            'result'     => $result,
            'filters'    => $filters,
        ]);
    }

    /**
     * Trả về trang 404 cho sản phẩm/danh mục không tồn tại.
     */
    private function notFound(): void
    {
        http_response_code(404);
        View::render('errors/404', ['pageTitle' => 'Không tìm thấy']);
    }
}
