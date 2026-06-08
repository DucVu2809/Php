<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AdminMiddleware;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function __construct()
    {
        AdminMiddleware::handle();
    }

    public function index(): void
    {
        $this->view('admin/coupons/index', [
            'pageTitle' => 'Mã giảm giá',
            'coupons'   => (new Coupon())->active(),
        ], 'admin');
        unset($_SESSION['_old']);
    }

    public function show(string $id): void
    {
        $coupon = (new Coupon())->find((int) $id);
        if ($coupon === null) {
            $this->json(['error' => 'not_found'], 404);
        }
        $this->json($coupon);
    }

    public function create(): void
    {
        $this->view('admin/coupons/create', ['pageTitle' => 'Thêm mã giảm giá'], 'admin');
        unset($_SESSION['_old']);
    }

    public function store(): void
    {
        $data = $this->data($_POST);
        if ($data === null) {
            $_SESSION['_old'] = $_POST;
            $this->redirect('/admin/ma-giam-gia');
        }
        (new Coupon())->create($data);
        flash('success', 'Đã thêm mã giảm giá.');
        $this->redirect('/admin/ma-giam-gia');
    }

    public function edit(string $id): void
    {
        $coupon = (new Coupon())->find((int) $id);
        if ($coupon === null) {
            $this->redirect('/admin/ma-giam-gia');
        }
        $this->view('admin/coupons/edit', [
            'pageTitle' => 'Sửa mã giảm giá',
            'coupon'    => $coupon,
        ], 'admin');
        unset($_SESSION['_old']);
    }

    public function update(string $id): void
    {
        if ((new Coupon())->find((int) $id) === null) {
            $this->redirect('/admin/ma-giam-gia');
        }
        $data = $this->data($_POST);
        if ($data === null) {
            $_SESSION['_old'] = $_POST;
            $this->redirect('/admin/ma-giam-gia/' . (int) $id . '/sua');
        }
        (new Coupon())->update((int) $id, $data);
        flash('success', 'Đã cập nhật mã giảm giá.');
        $this->redirect('/admin/ma-giam-gia');
    }

    public function delete(string $id): void
    {
        (new Coupon())->delete((int) $id);
        flash('success', 'Đã xóa mã giảm giá.');
        $this->redirect('/admin/ma-giam-gia');
    }

    private function data(array $post): ?array
    {
        $code = strtoupper(trim($post['code'] ?? ''));
        $value = $post['value'] ?? '';
        if ($code === '' || !is_numeric($value)) {
            flash('error', 'Vui lòng nhập mã và giá trị hợp lệ.');
            return null;
        }
        return [
            'code'        => $code,
            'type'        => in_array($post['type'] ?? 'percent', ['percent', 'fixed'], true) ? $post['type'] : 'percent',
            'value'       => (float) $value,
            'min_order'   => (float) ($post['min_order'] ?? 0),
            'usage_limit' => trim((string) ($post['usage_limit'] ?? '')) !== '' ? (int) $post['usage_limit'] : null,
            'expires_at'  => trim((string) ($post['expires_at'] ?? '')) !== '' ? $post['expires_at'] : null,
            'is_active'   => isset($post['is_active']) ? 1 : 0,
        ];
    }
}
