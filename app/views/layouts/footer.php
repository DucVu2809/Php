<?php
/**
 * Phần chân trang: thông tin cửa hàng, danh mục, chính sách và liên hệ.
 */

use App\Models\Category;
use App\Models\Setting;

$setting    = new Setting();
$footerCats = (new Category())->active();
?>
<footer class="footer">
    <div class="container footer__grid">
        <div class="footer__col">
            <div class="footer__brand">
                <i class="fa-solid fa-helmet-safety"></i> XINMAI
            </div>
            <p class="footer__desc">
                Tổng kho phân phối máy móc, thiết bị xây dựng chính hãng với giá tốt nhất thị trường.
                Cam kết hàng thật, bảo hành uy tín, giao hàng toàn quốc.
            </p>
            <p><i class="fa-solid fa-location-dot"></i> <?= e($setting->get('address', 'Hà Nội')) ?></p>
            <p><i class="fa-solid fa-phone"></i> <?= e($setting->get('hotline', '0901 234 567')) ?></p>
            <p><i class="fa-solid fa-envelope"></i> <?= e($setting->get('email', 'lienhe@xinmai.vn')) ?></p>
        </div>

        <div class="footer__col">
            <h4>Danh mục sản phẩm</h4>
            <ul>
                <?php foreach (array_slice($footerCats, 0, 6) as $cat): ?>
                    <li><a href="<?= url('danh-muc/' . $cat['slug']) ?>"><?= e($cat['name']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="footer__col">
            <h4>Hỗ trợ khách hàng</h4>
            <ul>
                <li><a href="<?= url('gioi-thieu') ?>">Giới thiệu</a></li>
                <li><a href="<?= url('lien-he') ?>">Liên hệ</a></li>
                <li><a href="<?= url('bao-hanh') ?>">Chính sách bảo hành</a></li>
                <li><a href="<?= url('doi-tra') ?>">Chính sách đổi trả</a></li>
                <li><a href="<?= url('huong-dan') ?>">Hướng dẫn mua hàng</a></li>
            </ul>
        </div>

        <div class="footer__col">
            <h4>Cam kết của XINMAI</h4>
            <ul class="footer__commit">
                <li><i class="fa-solid fa-circle-check"></i> 100% hàng chính hãng</li>
                <li><i class="fa-solid fa-circle-check"></i> Bảo hành tận nơi</li>
                <li><i class="fa-solid fa-circle-check"></i> Giao hàng toàn quốc</li>
                <li><i class="fa-solid fa-circle-check"></i> Hỗ trợ kỹ thuật trọn đời</li>
            </ul>
        </div>
    </div>

    <div class="footer__bottom">
        <div class="container">
            © <?= date('Y') ?> XINMAI - Tổng kho máy xây dựng. 
        </div>
    </div>
</footer>
