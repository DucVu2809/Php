<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Middleware\AdminMiddleware;
use App\Models\ImportReceipt;
use App\Models\Product;

class InventoryController extends Controller
{
    public function __construct()
    {
        AdminMiddleware::handle();
    }

    public function index(): void
    {
        $this->view('admin/inventory/index', [
            'pageTitle' => 'Nhập kho',
            'receipts'  => (new ImportReceipt())->allWithUser(),
        ], 'admin');
    }

    public function create(): void
    {
        $this->view('admin/inventory/create', [
            'pageTitle' => 'Tạo phiếu nhập kho',
            'products'  => (new Product())->adminAll(),
        ], 'admin');
    }

    public function store(): void
    {
        $productIds = $_POST['product_id'] ?? [];
        $quantities = $_POST['quantity'] ?? [];
        $costs      = $_POST['cost_price'] ?? [];

        $details = [];
        $total = 0.0;
        foreach ($productIds as $i => $pid) {
            $pid = (int) $pid;
            $qty = (int) ($quantities[$i] ?? 0);
            $cost = (float) ($costs[$i] ?? 0);
            if ($pid > 0 && $qty > 0) {
                $details[] = ['product_id' => $pid, 'quantity' => $qty, 'cost_price' => $cost];
                $total += $qty * $cost;
            }
        }

        if (!$details) {
            flash('error', 'Vui lòng chọn ít nhất một sản phẩm và số lượng hợp lệ.');
            $this->redirect('/admin/kho/them');
        }

        $model = new ImportReceipt();
        $model->createWithDetails([
            'code'       => $model->generateCode(),
            'supplier'   => trim($_POST['supplier'] ?? '') ?: null,
            'note'       => trim($_POST['note'] ?? '') ?: null,
            'total'      => $total,
            'created_by' => Auth::id(),
        ], $details);

        flash('success', 'Đã tạo phiếu nhập kho và cập nhật tồn kho.');
        $this->redirect('/admin/kho');
    }

    public function detail(string $id): void
    {
        $receipt = (new ImportReceipt())->find((int) $id);
        if ($receipt === null) {
            $this->redirect('/admin/kho');
        }
        $this->view('admin/inventory/detail', [
            'pageTitle' => 'Chi tiết phiếu nhập',
            'receipt'   => $receipt,
            'details'   => (new ImportReceipt())->details((int) $id),
        ], 'admin');
    }
}
