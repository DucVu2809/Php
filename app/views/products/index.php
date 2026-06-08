<?php
/**
 * Trang danh sách sản phẩm: cột bộ lọc (hãng, giá) + lưới sản phẩm + phân trang.
 *
 * @var array<string,mixed>|null $category
 * @var array<int,array<string,mixed>> $brands
 * @var array<int,array<string,mixed>> $categories
 * @var array{items:array,total:int,page:int,pages:int} $result
 * @var array<string,mixed> $filters
 */

use App\Helpers\FormatHelper;

/**
 * Dựng URL giữ nguyên các tham số lọc hiện tại nhưng ghi đè vài tham số.
 *
 * @param array<string,mixed> $overrides
 */
$buildUrl = static function (array $overrides) use ($category): string {
    $base  = $category ? url('danh-muc/' . $category['slug']) : url('san-pham');
    $query = array_filter(array_merge($_GET, $overrides), static fn ($v) => $v !== '' && $v !== null);
    return $query ? $base . '?' . http_build_query($query) : $base;
};

$currentSort = $filters['sort'] ?? '';
$priceRanges = [
    ['label' => 'Dưới 3 triệu',     'min' => 0,        'max' => 3000000],
    ['label' => '3 - 10 triệu',     'min' => 3000000,  'max' => 10000000],
    ['label' => '10 - 20 triệu',    'min' => 10000000, 'max' => 20000000],
    ['label' => 'Trên 20 triệu',    'min' => 20000000, 'max' => 0],
];
?>

<div class="container breadcrumb">
    <a href="<?= url('/') ?>">Trang chủ</a>
    <i class="fa-solid fa-angle-right"></i>
    <?php if ($category): ?>
        <a href="<?= url('san-pham') ?>">Sản phẩm</a>
        <i class="fa-solid fa-angle-right"></i>
        <span><?= e($category['name']) ?></span>
    <?php else: ?>
        <span>Sản phẩm</span>
    <?php endif; ?>
</div>

<div class="container listing">
    <!-- Cột bộ lọc -->
    <aside class="filters">
        <div class="filters__box">
            <h4 class="filters__title">Danh mục</h4>
            <ul class="filters__list">
                <li class="<?= !$category ? 'active' : '' ?>"><a href="<?= url('san-pham') ?>">Tất cả sản phẩm</a></li>
                <?php foreach ($categories as $c): ?>
                    <li class="<?= ($category['id'] ?? 0) === (int) $c['id'] ? 'active' : '' ?>">
                        <a href="<?= url('danh-muc/' . $c['slug']) ?>">
                            <?= e($c['name']) ?> <em>(<?= (int) $c['product_count'] ?>)</em>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="filters__box">
            <h4 class="filters__title">Hãng sản xuất</h4>
            <ul class="filters__list">
                <?php foreach ($brands as $b): ?>
                    <li class="<?= (int) ($filters['brand_id'] ?? 0) === (int) $b['id'] ? 'active' : '' ?>">
                        <a href="<?= $buildUrl(['brand' => $b['id'], 'page' => null]) ?>"><?= e($b['name']) ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="filters__box">
            <h4 class="filters__title">Khoảng giá</h4>
            <ul class="filters__list">
                <?php foreach ($priceRanges as $r): ?>
                    <li>
                        <a href="<?= $buildUrl(['min_price' => $r['min'] ?: null, 'max_price' => $r['max'] ?: null, 'page' => null]) ?>">
                            <?= e($r['label']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </aside>

    <!-- Khu vực sản phẩm -->
    <section class="listing__main">
        <div class="listing__bar">
            <h1 class="listing__title"><?= e($category['name'] ?? ($filters['keyword'] ? 'Kết quả: "' . $filters['keyword'] . '"' : 'Tất cả sản phẩm')) ?></h1>
            <div class="listing__sort">
                <span>Sắp xếp:</span>
                <?php
                $sorts = [
                    ''           => 'Nổi bật',
                    'price_asc'  => 'Giá thấp → cao',
                    'price_desc' => 'Giá cao → thấp',
                    'name_asc'   => 'Tên A → Z',
                    'popular'    => 'Xem nhiều',
                    'sale'       => 'For sale',
                ];
                foreach ($sorts as $key => $label): ?>
                    <a class="sort-chip <?= $currentSort === $key ? 'active' : '' ?>"
                       href="<?= $buildUrl(['sort' => $key ?: null, 'page' => null]) ?>"><?= e($label) ?></a>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if (empty($result['items'])): ?>
            <div class="empty">
                <i class="fa-solid fa-box-open"></i>
                <p>Không tìm thấy sản phẩm phù hợp.</p>
                <a class="btn btn-primary" href="<?= url('san-pham') ?>">Xem tất cả sản phẩm</a>
            </div>
        <?php else: ?>
            <p class="listing__count">Tìm thấy <strong><?= (int) $result['total'] ?></strong> sản phẩm</p>
            <div class="product-grid">
                <?php foreach ($result['items'] as $p): require VIEW_PATH . '/partials/product_card.php'; endforeach; ?>
            </div>

            <?php if ($result['pages'] > 1): ?>
                <nav class="pagination">
                    <?php for ($i = 1; $i <= $result['pages']; $i++): ?>
                        <a class="<?= $i === $result['page'] ? 'active' : '' ?>"
                           href="<?= $buildUrl(['page' => $i]) ?>"><?= $i ?></a>
                    <?php endfor; ?>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </section>
</div>
