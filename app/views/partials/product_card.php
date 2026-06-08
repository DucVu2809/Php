<?php
/**
 * Thẻ sản phẩm dùng lại ở trang chủ và trang danh mục.
 *
 * @var array<string,mixed> $p  Bản ghi sản phẩm
 */

use App\Helpers\FormatHelper;

$price    = (float) $p['price'];
$sale     = $p['sale_price'] !== null ? (float) $p['sale_price'] : null;
$discount = $sale ? FormatHelper::discountPercent($price, $sale) : 0;
$detailUrl = url('san-pham/' . $p['slug']);
?>
<article class="pcard">
    <a class="pcard__thumb" href="<?= $detailUrl ?>">
        <?php if ($discount > 0): ?>
            <span class="pcard__badge">-<?= $discount ?>%</span>
        <?php endif; ?>
        <img src="<?= product_image($p['thumbnail']) ?>" alt="<?= e($p['name']) ?>" loading="lazy">
    </a>
    <div class="pcard__body">
        <?php if (!empty($p['brand_name'])): ?>
            <span class="pcard__brand"><?= e($p['brand_name']) ?></span>
        <?php endif; ?>
        <h3 class="pcard__name">
            <a href="<?= $detailUrl ?>"><?= e($p['name']) ?></a>
        </h3>
        <div class="pcard__price">
            <span class="pcard__price-now"><?= FormatHelper::price($sale ?: $price) ?></span>
            <?php if ($sale): ?>
                <span class="pcard__price-old"><?= FormatHelper::price($price) ?></span>
            <?php endif; ?>
        </div>
        <div class="pcard__foot">
            <?php if ((int) $p['stock'] > 0): ?>
                <span class="pcard__stock in"><i class="fa-solid fa-check"></i> Còn hàng</span>
            <?php else: ?>
                <span class="pcard__stock out"><i class="fa-solid fa-xmark"></i> Liên hệ</span>
            <?php endif; ?>
            <button class="btn-add" type="button"
                    data-add-to-cart data-id="<?= (int) $p['id'] ?>"
                    title="Thêm vào giỏ hàng">
                <i class="fa-solid fa-cart-plus"></i>
            </button>
        </div>
    </div>
</article>
