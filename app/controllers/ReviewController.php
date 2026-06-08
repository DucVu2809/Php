<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AdminMiddleware;
use App\Models\Review;

class ReviewController extends Controller
{
    public function __construct()
    {
        AdminMiddleware::handle();
    }

    public function index(): void
    {
        $this->view('admin/reviews/index', [
            'pageTitle' => 'Quản lý đánh giá',
            'reviews'   => (new Review())->adminAll(),
        ], 'admin');
    }

    public function approve(string $id): void
    {
        (new Review())->approve((int) $id);
        flash('success', 'Đã duyệt đánh giá.');
        $this->redirect('/admin/danh-gia');
    }

    public function delete(string $id): void
    {
        (new Review())->delete((int) $id);
        flash('success', 'Đã xóa đánh giá.');
        $this->redirect('/admin/danh-gia');
    }
}
