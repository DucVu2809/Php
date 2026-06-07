<?php
/**
 * Trang chủ: banner, lưới danh mục, sản phẩm nổi bật và sản phẩm mới.
 *
 * @var array<int,array<string,mixed>> $categories
 * @var array<int,array<string,mixed>> $featured
 * @var array<int,array<string,mixed>> $latest
 */
?>

<!-- Banner + danh mục cố định (trang chủ) -->
<section class="hero">
    <div class="container hero__inner">
        <!-- Sidebar danh mục gắn liền dưới nút "Danh mục sản phẩm" -->
        <aside class="hero__cats">
            <ul>
                <?php foreach ($categories as $cat): ?>
                    <li>
                        <a href="<?= url('danh-muc/' . $cat['slug']) ?>">
                            <i class="fa-solid <?= e($cat['icon'] ?: 'fa-cube') ?> hero__cats-ic"></i>
                            <span class="hero__cats-info">
                                <strong><?= e($cat['name']) ?></strong>
                                <?php if (!empty($cat['description'])): ?>
                                    <small><?= e($cat['description']) ?></small>
                                <?php endif; ?>
                            </span>
                            <i class="fa-solid fa-angle-right hero__cats-arrow"></i>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </aside>

        <!-- Banner slider khuyến mãi (tự chạy) -->
        <?php
        $slides = [
            ['tag' => 'SALE ĐẶC BIỆT 2026', 'title' => 'GIẢM ĐẾN', 'big' => '500.000đ',
             'desc' => 'Cho đơn hàng máy xây dựng trên 10.000.000đ trong tuần này',
             'img' => 'may-phat-dien.svg', 'g1' => '#1657b8', 'g2' => '#38c6df'],
            ['tag' => 'MUA SẮM KHÔNG LO', 'title' => 'TRẢ GÓP', 'big' => '0% LÃI SUẤT',
             'desc' => 'Áp dụng cho máy phát điện, máy hàn, máy nén khí — duyệt nhanh trong ngày',
             'img' => 'may-han.svg', 'g1' => '#0e2a47', 'g2' => '#f5821f'],
            ['tag' => 'GIAO HÀNG TOÀN QUỐC', 'title' => 'MIỄN PHÍ', 'big' => 'VẬN CHUYỂN',
             'desc' => 'Freeship cho mọi đơn hàng máy móc từ 5.000.000đ trên toàn quốc',
             'img' => 'may-tron-be-tong.svg', 'g1' => '#0b6e4f', 'g2' => '#22c1a4'],
        ];
        ?>
        <div class="hero__banner">
            <div class="slider" data-slider>
                <?php foreach ($slides as $i => $s): ?>
                    <div class="slide <?= $i === 0 ? 'active' : '' ?>" style="--g1: <?= e($s['g1']) ?>; --g2: <?= e($s['g2']) ?>;">
                        <div class="slide__text">
                            <span class="hero__tag"><i class="fa-solid fa-bolt"></i> <?= e($s['tag']) ?></span>
                            <h2><?= e($s['title']) ?> <em><?= e($s['big']) ?></em></h2>
                            <p><?= e($s['desc']) ?></p>
                            <a class="btn btn-accent btn-lg" href="<?= url('san-pham') ?>">Mua ngay <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                        <div class="slide__img">
                            <img src="<?= asset('uploads/products/' . $s['img']) ?>" alt="<?= e($s['title'] . ' ' . $s['big']) ?>">
                        </div>
                    </div>
                <?php endforeach; ?>

                <button class="slider__nav slider__prev" type="button" data-slide-prev aria-label="Slide trước">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button class="slider__nav slider__next" type="button" data-slide-next aria-label="Slide sau">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
                <div class="slider__dots">
                    <?php foreach ($slides as $i => $s): ?>
                        <span class="<?= $i === 0 ? 'active' : '' ?>" data-slide-to="<?= $i ?>"></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Dải cam kết dịch vụ -->
<section class="container">
    <div class="features">
        <div class="features__item"><i class="fa-solid fa-truck-fast"></i><div><strong>Giao hàng toàn quốc</strong><span>Nhanh chóng, an toàn</span></div></div>
        <div class="features__item"><i class="fa-solid fa-shield-halved"></i><div><strong>Bảo hành chính hãng</strong><span>Lên đến 24 tháng</span></div></div>
        <div class="features__item"><i class="fa-solid fa-rotate-left"></i><div><strong>Đổi trả 7 ngày</strong><span>Nếu lỗi nhà sản xuất</span></div></div>
        <div class="features__item"><i class="fa-solid fa-headset"></i><div><strong>Hỗ trợ kỹ thuật</strong><span>Tư vấn trọn đời</span></div></div>
    </div>
</section>

<!-- Dải khuyến mãi nổi bật -->
<section class="container">
    <a class="promo-strip" href="<?= url('san-pham') ?>">
        <span class="promo-strip__hot"><i class="fa-solid fa-fire"></i> HOT SALE</span>
        <span class="promo-strip__title">XINMAI KHAI TRƯƠNG — GIẢM ĐẾN <strong>30%</strong> TOÀN BỘ MÁY MÓC</span>
        <span class="promo-strip__cta">Săn deal ngay <i class="fa-solid fa-arrow-right"></i></span>
    </a>
</section>

<!-- Sản phẩm nổi bật -->
<?php if (!empty($featured)): ?>
<section class="container section">
    <div class="section__head">
        <h2 class="section__title"><i class="fa-solid fa-fire"></i> Sản phẩm nổi bật</h2>
        <a class="section__more" href="<?= url('san-pham') ?>">Xem tất cả <i class="fa-solid fa-angle-right"></i></a>
    </div>
    <div class="product-grid">
        <?php foreach ($featured as $p): require VIEW_PATH . '/partials/product_card.php'; endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Danh mục nổi bật dạng ô -->
<section class="container section">
    <div class="section__head">
        <h2 class="section__title"><i class="fa-solid fa-layer-group"></i> Danh mục nổi bật</h2>
    </div>
    <div class="cat-grid">
        <?php foreach ($categories as $cat): ?>
            <a class="cat-grid__item" href="<?= url('danh-muc/' . $cat['slug']) ?>">
                <i class="fa-solid <?= e($cat['icon'] ?: 'fa-cube') ?>"></i>
                <strong><?= e($cat['name']) ?></strong>
                <span><?= (int) $cat['product_count'] ?> sản phẩm</span>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- Sản phẩm mới -->
<?php if (!empty($latest)): ?>
<section class="container section">
    <div class="section__head">
        <h2 class="section__title"><i class="fa-solid fa-bolt"></i> Sản phẩm mới về</h2>
        <a class="section__more" href="<?= url('san-pham') ?>">Xem tất cả <i class="fa-solid fa-angle-right"></i></a>
    </div>
    <div class="product-grid">
        <?php foreach ($latest as $p): require VIEW_PATH . '/partials/product_card.php'; endforeach; ?>
    </div>
</section>
<?php endif; ?>
