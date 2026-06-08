<?php
declare(strict_types=1);

namespace App\Helpers;
require_once __DIR__ . '/../../vendor/phpmailer/Exception.php';
require_once __DIR__ . '/../../vendor/phpmailer/PHPMailer.php';
require_once __DIR__ . '/../../vendor/phpmailer/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailHelper 
{
    public static function sendOrderConfirmation(
        string $toEmail, 
        string $customerName, 
        string $orderCode, 
        float $totalPrice,
        string $phone = '',     
        string $address = '',    
        string $paymentMethod = ''
    ) {
        $mail = new PHPMailer(true);
        
        try {
            // Cấu hình máy chủ SMTP của Google
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            
            // THAY BẰNG EMAIL VÀ MẬT KHẨU ỨNG DỤNG CỦA BẠN
            $mail->Username   = 'phptester14@gmail.com'; 
            $mail->Password   = 'trxo rxxd sktv irui';
            
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $mail->CharSet    = 'UTF-8';

            // Cấu hình người gửi & nhận
            $mail->setFrom('email-cua-ban@gmail.com', 'Xinmai Shop - Tổng kho máy xây dựng');
            $mail->addAddress($toEmail, $customerName);

            // Nội dung Email
            $mail->isHTML(true);
            $mail->Subject = "Xác nhận đơn hàng #{$orderCode} đặt thành công";
            
            $formattedPrice = number_format($totalPrice, 0, ',', '.') . ' VNĐ';
            $pttt = $paymentMethod === 'cod' ? 'Thanh toán khi nhận hàng (COD)' : 'Chuyển khoản ngân hàng';

            $mail->Body = "
                <h3>Xin chào <b>{$customerName}</b>,</h3>
                <p>Cảm ơn bạn đã tin tưởng và đặt hàng tại Xinmai Shop.</p>
                <p>Mã đơn hàng: <b>{$orderCode}</b></p>
                <p>Số điện thoại: {$phone}</p>
                <p>Địa chỉ nhận hàng: {$address}</p>
                <p>Phương thức thanh toán: <b>{$pttt}</b></p>
                <p>Tổng tiền: <b>" . number_format($totalPrice, 0, ',', '.') . " VNĐ</b></p>
            ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            // Bạn có thể dùng error_log để xem lý do lỗi nếu không gửi được
            error_log("Lỗi gửi mail: {$mail->ErrorInfo}");
            return false;
        }
    }
}