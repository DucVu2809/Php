<div class="container">

    <div class="listing__bar">
        <h1 class="listing__title">
            Sản phẩm khuyến mãi
        </h1>
    </div>

    <?php if (empty($products)): ?>

        <div class="empty">
            <p>Hiện chưa có sản phẩm khuyến mãi.</p>
        </div>

    <?php else: ?>

        <div class="product-grid">

            <?php foreach ($products as $p): ?>
                <?php require VIEW_PATH . '/partials/product_card.php'; ?>
            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>