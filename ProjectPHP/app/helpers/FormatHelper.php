<?php

/**
 * Helper định dạng dữ liệu hiển thị (tiền tệ, phần trăm, ngày tháng, slug).
 */

declare(strict_types=1);

namespace App\Helpers;

class FormatHelper
{
    /**
     * Định dạng số tiền VND: 12200000 => "12.200.000 ₫".
     */
    public static function price(float|int|string|null $amount): string
    {
        $value = (float) ($amount ?? 0);
        return number_format($value, 0, ',', '.') . ' ' . CURRENCY_SUFFIX;
    }

    /**
     * Tính phần trăm giảm giá giữa giá gốc và giá khuyến mãi.
     */
    public static function discountPercent(float|int|null $price, float|int|null $salePrice): int
    {
        $price     = (float) $price;
        $salePrice = (float) $salePrice;
        if ($price <= 0 || $salePrice <= 0 || $salePrice >= $price) {
            return 0;
        }
        return (int) round(($price - $salePrice) / $price * 100);
    }

    /**
     * Định dạng ngày giờ kiểu Việt Nam: "14:30 07/06/2026".
     */
    public static function dateTime(?string $datetime): string
    {
        if (!$datetime) {
            return '';
        }
        $timestamp = strtotime($datetime);
        return $timestamp ? date('H:i d/m/Y', $timestamp) : '';
    }

    /**
     * Tạo slug thân thiện URL từ chuỗi tiếng Việt có dấu.
     */
    public static function slug(string $text): string
    {
        $map = [
            'a' => 'áàảãạăắằẳẵặâấầẩẫậ', 'e' => 'éèẻẽẹêếềểễệ',
            'i' => 'íìỉĩị', 'o' => 'óòỏõọôốồổỗộơớờởỡợ',
            'u' => 'úùủũụưứừửữự', 'y' => 'ýỳỷỹỵ', 'd' => 'đ',
        ];
        $text = mb_strtolower($text, 'UTF-8');
        foreach ($map as $ascii => $accents) {
            $text = preg_replace('/[' . $accents . ']/u', $ascii, $text);
        }
        $text = preg_replace('/[^a-z0-9]+/u', '-', $text);
        return trim((string) $text, '-');
    }

    /**
     * Rút gọn văn bản dài, thêm dấu "…" nếu vượt quá độ dài cho phép.
     */
    public static function excerpt(?string $text, int $length = 120): string
    {
        $text = trim((string) $text);
        if (mb_strlen($text, 'UTF-8') <= $length) {
            return $text;
        }
        return mb_substr($text, 0, $length, 'UTF-8') . '…';
    }
}
