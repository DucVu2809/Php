<?php
/**
 * Trang chi tiết sản phẩm: ảnh, giá, thông số kỹ thuật, mô tả, sản phẩm liên quan.
 *
 * @var array<string,mixed> $product
 * @var array<int,array<string,mixed>> $images
 * @var array<int,array<string,mixed>> $related
 */

use App\Helpers\FormatHelper;

$price    = (float) $product['price'];
$sale     = $product['sale_price'] !== null ? (float) $product['sale_price'] : null;
$discount = $sale ? FormatHelper::discountPercent($price, $sale) : 0;

$specs = [];
if (!empty($product['specs'])) {
    $decoded = json_decode((string) $product['specs'], true);
    if (is_array($decoded)) {
        $specs = $decoded;
    }
}

// Ảnh chính + danh sách ảnh phụ (gộp thumbnail vào đầu nếu chưa có ảnh phụ)
$gallery = array_column($images, 'image_path');
if (empty($gallery)) {
    $gallery = [$product['thumbnail']];
}
?>

<div class="container breadcrumb">
    <a href="<?= url('/') ?>">Trang chủ</a>
    <i class="fa-solid fa-angle-right"></i>
    <?php if (!empty($product['category_slug'])): ?>
        <a href="<?= url('danh-muc/' . $product['category_slug']) ?>"><?= e($product['category_name']) ?></a>
        <i class="fa-solid fa-angle-right"></i>
    <?php endif; ?>
    <span><?= e($product['name']) ?></span>
</div>

<div class="container detail">
    <!-- Thư viện ảnh -->
    <div class="detail__gallery">
        <div class="detail__main-img">
            <?php if ($discount > 0): ?><span class="detail__badge">-<?= $discount ?>%</span><?php endif; ?>
            <img id="mainImage" src="<?= product_image($gallery[0]) ?>" alt="<?= e($product['name']) ?>">
        </div>
        <?php if (count($gallery) > 1): ?>
            <div class="detail__thumbs">
                <?php foreach ($gallery as $i => $img): ?>
                    <img class="<?= $i === 0 ? 'active' : '' ?>" src="<?= product_image($img) ?>"
                         alt="Ảnh <?= $i + 1 ?>" data-thumb onclick="
                            document.getElementById('mainImage').src=this.src;
                            document.querySelectorAll('[data-thumb]').forEach(function(t){t.classList.remove('active')});
                            this.classList.add('active');">
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Thông tin mua hàng -->
    <div class="detail__info">
        <h1 class="detail__name"><?= e($product['name']) ?></h1>
        <div class="detail__meta">
            <?php if (!empty($product['brand_name'])): ?>
                <span>Hãng: <strong><?= e($product['brand_name']) ?></strong></span>
            <?php endif; ?>
            <span>Mã: <strong><?= e($product['sku'] ?: 'Đang cập nhật') ?></strong></span>
            <span>Lượt xem: <strong><?= (int) $product['views'] ?></strong></span>
        </div>

        <div class="detail__price">
            <span class="detail__price-now"><?= FormatHelper::price($sale ?: $price) ?></span>
            <?php if ($sale): ?>
                <span class="detail__price-old"><?= FormatHelper::price($price) ?></span>
                <span class="detail__price-save">Tiết kiệm <?= FormatHelper::price($price - $sale) ?></span>
            <?php endif; ?>
        </div>

        <?php if (!empty($product['short_desc'])): ?>
            <p class="detail__short"><?= e($product['short_desc']) ?></p>
        <?php endif; ?>

        <div class="detail__stock">
            <?php if ((int) $product['stock'] > 0): ?>
                <span class="in"><i class="fa-solid fa-circle-check"></i> Còn hàng (<?= (int) $product['stock'] ?> sản phẩm)</span>
            <?php else: ?>
                <span class="out"><i class="fa-solid fa-circle-info"></i> Liên hệ để đặt hàng</span>
            <?php endif; ?>
        </div>

        <div class="detail__buy">
            <div class="qty">
                <button type="button" data-qty="-1">−</button>
                <input id="buyQty" type="number" value="1" min="1" max="<?= max(1, (int) $product['stock']) ?>">
                <button type="button" data-qty="1">+</button>
            </div>
            <button class="btn btn-primary btn-lg" data-add-to-cart
                    data-id="<?= (int) $product['id'] ?>" data-qty-source="#buyQty">
                <i class="fa-solid fa-cart-plus"></i> Thêm vào giỏ
            </button>
            <a class="btn btn-accent btn-lg" href="<?= url('gio-hang') ?>">
                <i class="fa-solid fa-bolt"></i> Mua ngay
            </a>
        </div>

        <div class="detail__policy">
            <div><i class="fa-solid fa-shield-halved"></i> Bảo hành chính hãng</div>
            <div><i class="fa-solid fa-truck-fast"></i> Giao hàng toàn quốc</div>
            <div><i class="fa-solid fa-headset"></i> Hỗ trợ kỹ thuật trọn đời</div>
        </div>
    </div>
</div>

<!-- Thông số + mô tả -->
<div class="container detail__tabs">
    <?php if (!empty($specs)): ?>
        <section class="detail__panel">
            <h2 class="detail__panel-title">Thông số kỹ thuật</h2>
            <table class="spec-table">
                <?php foreach ($specs as $key => $value): ?>
                    <tr><th><?= e((string) $key) ?></th><td><?= e((string) $value) ?></td></tr>
                <?php endforeach; ?>
            </table>
        </section>
    <?php endif; ?>

    <section class="detail__panel">
        <h2 class="detail__panel-title">Mô tả sản phẩm</h2>
        <div class="detail__desc"><?= $product['description'] ?: '<p>Đang cập nhật mô tả.</p>' ?></div>
    </section>
</div>

<div class="container detail__tabs">
    <section class="detail__panel">
        <h2 class="detail__panel-title">Đánh giá sản phẩm</h2>

        <div class="reviews__summary">
            <div class="reviews__avg">
                <strong><?= $reviewSummary['count'] > 0 ? $reviewSummary['avg'] : '0' ?></strong>
                <div>
                    <div class="reviews__stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fa-solid fa-star <?= $i <= round($reviewSummary['avg']) ? 'on' : '' ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <span><?= (int) $reviewSummary['count'] ?> đánh giá đã duyệt</span>
                </div>
            </div>
        </div>

        <?php if (empty($reviews)): ?>
            <p class="reviews__empty">Chưa có đánh giá nào. Hãy là người đầu tiên đánh giá sản phẩm này!</p>
        <?php else: ?>
            <div class="reviews__list">
                <?php foreach ($reviews as $rv): ?>
                    <div class="review">
                        <div class="review__head">
                            <strong><?= e($rv['author']) ?></strong>
                            <span class="review__stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fa-solid fa-star <?= $i <= (int) $rv['rating'] ? 'on' : '' ?>"></i>
                                <?php endfor; ?>
                            </span>
                        </div>
                        <p class="review__content"><?= nl2br(e($rv['content'])) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form class="review-form" method="post" action="<?= url('danh-gia') ?>">
            <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
            <h3>Viết đánh giá của bạn</h3>
            <div class="review-form__rating">
                <span>Chọn số sao:</span>
                <select name="rating">
                    <option value="5">5 sao - Tuyệt vời</option>
                    <option value="4">4 sao - Tốt</option>
                    <option value="3">3 sao - Bình thường</option>
                    <option value="2">2 sao - Tạm</option>
                    <option value="1">1 sao - Kém</option>
                </select>
            </div>
            <div class="form-row">
                <input type="text" name="author" value="<?= e(old('author')) ?>" placeholder="Tên của bạn" required>
            </div>
            <div class="form-row">
                <textarea name="content" rows="3" placeholder="Cảm nhận của bạn về sản phẩm..." required></textarea>
            </div>
            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-paper-plane"></i> Gửi đánh giá</button>
        </form>
    </section>
</div>

<!-- Sản phẩm liên quan -->
<?php if (!empty($related)): ?>
<section class="container section">
    <div class="section__head">
        <h2 class="section__title"><i class="fa-solid fa-thumbs-up"></i> Sản phẩm liên quan</h2>
    </div>
    <div class="product-grid">
        <?php foreach ($related as $p): require VIEW_PATH . '/partials/product_card.php'; endforeach; ?>
    </div>
</section>
<?php endif; ?>
