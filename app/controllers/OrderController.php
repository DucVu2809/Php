<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Cart;
use App\Core\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Requests\CheckoutRequest;
use App\Helpers\MailHelper;

class OrderController extends Controller
{
    public function checkout(): void
    {
        if (!Cart::items()) {
            $this->redirect('/gio-hang');
        }

        $this->view('orders/checkout', [
            'pageTitle' => 'Thanh toán',
            'items'     => Cart::items(),
            'subtotal'  => Cart::subtotal(),
            'coupon'    => $_SESSION['coupon'] ?? null,
            'user'      => Auth::user(),
        ]);
        unset($_SESSION['_old']);
    }

    public function applyCoupon(): void
    {
        $code   = strtoupper(trim($_POST['code'] ?? ''));
        $coupon = (new Coupon())->findValid($code);

        if ($coupon === null) {
            $this->json(['ok' => false, 'message' => 'Mã giảm giá không tồn tại hoặc đã hết hạn.'], 404);
        }

        $subtotal = Cart::subtotal();
        $discount = (new Coupon())->discountFor($coupon, $subtotal);
        if ($discount <= 0) {
            $this->json([
                'ok'      => false,
                'message' => 'Đơn hàng chưa đạt giá trị tối thiểu để dùng mã này.',
            ], 422);
        }

        $_SESSION['coupon'] = [
            'id'       => (int) $coupon['id'],
            'code'     => $coupon['code'],
            'discount' => $discount,
        ];

        $this->json([
            'ok'       => true,
            'message'  => 'Áp dụng mã "' . $coupon['code'] . '" thành công.',
            'code'     => $coupon['code'],
            'discount' => $discount,
            'total'    => max(0, $subtotal - $discount),
        ]);
    }

    public function removeCoupon(): void
    {
        unset($_SESSION['coupon']);
        $this->json(['ok' => true, 'total' => Cart::subtotal()]);
    }

    public function place(): void
    {
        $items = Cart::items();
        if (!$items) {
            $this->redirect('/gio-hang');
        }

        $errors = CheckoutRequest::validate($_POST);
        if ($errors) {
            $_SESSION['_old'] = $_POST;
            flash('error', reset($errors));
            $this->redirect('/dat-hang');
        }

        $subtotal = Cart::subtotal();
        $coupon   = $_SESSION['coupon'] ?? null;
        $discount = $coupon ? (float) $coupon['discount'] : 0.0;
        $total    = max(0, $subtotal - $discount);

        $payment = in_array($_POST['payment_method'] ?? 'cod', ['cod', 'bank'], true)
            ? $_POST['payment_method']
            : 'cod';

        $orderModel = new Order();
        $code = $orderModel->generateCode();

        $orderModel->createWithItems([
            'code'             => $code,
            'user_id'          => Auth::id(),
            'coupon_id'        => $coupon['id'] ?? null,
            'customer_name'    => trim($_POST['customer_name']),
            'customer_phone'   => trim($_POST['customer_phone']),
            'customer_address' => trim($_POST['customer_address']),
            'customer_email'   => trim($_POST['customer_email'] ?? '') ?: null,
            'note'             => trim($_POST['note'] ?? '') ?: null,
            'subtotal'         => $subtotal,
            'discount'         => $discount,
            'total'            => $total,
            'payment_method'   => $payment,
            'status'           => 'pending',
        ], $items);

        $emailNhan = trim($_POST['customer_email'] ?? '');
        $tenKhach = trim($_POST['customer_name'] ?? 'Quý khách');

        // Chỉ gửi mail nếu khách hàng có nhập email hợp lệ
        if ($emailNhan !== '' && filter_var($emailNhan, FILTER_VALIDATE_EMAIL)) {
            // Gọi hàm từ file MailHelper bạn vừa tạo ở Bước 3
            MailHelper::sendOrderConfirmation(
                $emailNhan, 
                $tenKhach, 
                $code, 
                $total,
                trim($_POST['customer_phone'] ?? ''),  // SỬA THÀNH: customer_phone thay vì phone
                trim($_POST['customer_address'] ?? ''),    // Truyền thêm Địa chỉ
                $payment
            );
        }
        $sqlNotification = "INSERT INTO `notifications` (title, message) VALUES (?, ?)";
        $formattedTotal = number_format($total, 0, ',', '.') . ' VNĐ';
        
        $msg = "Khách hàng <b>{$tenKhach}</b> vừa đặt đơn hàng mới <b>#{$code}</b>. Tổng giá trị: <b>{$formattedTotal}</b>.";
        
        \App\Core\Database::run($sqlNotification, ['Đơn hàng mới', $msg]);

        Cart::clear();
        unset($_SESSION['coupon'], $_SESSION['_old']);
        flash('success', 'Đặt hàng thành công! Mã đơn: ' . $code);
        $this->redirect('/dat-hang/thanh-cong/' . $code);
    }

    public function success(string $code): void
    {
        $order = (new Order())->findByCode($code);
        if ($order === null) {
            $this->redirect('/');
        }

        $this->view('orders/success', [
            'pageTitle' => 'Đặt hàng thành công',
            'order'     => $order,
            'items'     => (new Order())->items((int) $order['id']),
        ]);
    }
}
