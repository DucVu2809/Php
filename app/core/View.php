<?php

/**
 * Bộ kết xuất giao diện (template engine tối giản dựa trên PHP thuần).
 *
 * Render một view bên trong layout chung: layout nhận sẵn biến $content
 * là phần thân trang đã render, kèm các biến dữ liệu do controller truyền.
 */

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

class View
{
    /**
     * Render view con bọc trong layout.
     *
     * @param string               $view   Đường dẫn view tương đối, vd "products/detail"
     * @param array<string,mixed>  $data   Biến truyền cho view
     * @param string               $layout Layout bao ngoài (mặc định "main")
     */
    public static function render(string $view, array $data = [], string $layout = 'main'): void
    {
        $content = self::capture(VIEW_PATH . '/' . $view . '.php', $data);

        $layoutFile = VIEW_PATH . '/layouts/' . $layout . '.php';
        if (!is_file($layoutFile)) {
            echo $content;
            return;
        }

        echo self::capture($layoutFile, array_merge($data, ['content' => $content]));
    }

    /**
     * Nạp một file PHP và trả về output dưới dạng chuỗi (output buffering).
     *
     * @param array<string,mixed> $data
     */
    private static function capture(string $file, array $data): string
    {
        if (!is_file($file)) {
            throw new RuntimeException("Không tìm thấy view: {$file}");
        }

        extract($data, EXTR_SKIP);
        ob_start();
        require $file;
        return (string) ob_get_clean();
    }
}
