<div class="container breadcrumb">
    <a href="<?= url('/') ?>">Trang chủ</a>
    <i class="fa-solid fa-angle-right"></i>
    <span>Đăng nhập</span>
</div>

<div class="container section authpage">
    <div class="authbox">
        <div class="authbox__aside">
            <div class="authbox__logo"><i class="fa-solid fa-helmet-safety"></i> XIN<strong>MAI</strong></div>
            <h2>Chào mừng trở lại!</h2>
            <p>Đăng nhập để mua sắm nhanh hơn và theo dõi đơn hàng của bạn.</p>
            <ul class="authbox__perks">
                <li><i class="fa-solid fa-bolt"></i> Đặt hàng nhanh chóng</li>
                <li><i class="fa-solid fa-truck-fast"></i> Theo dõi đơn hàng dễ dàng</li>
                <li><i class="fa-solid fa-tag"></i> Nhận ưu đãi dành cho thành viên</li>
            </ul>
        </div>
        <div class="authbox__form">
            <h1>Đăng nhập</h1>
            <p class="sub">Nhập thông tin tài khoản của bạn</p>
            <form method="post" action="<?= url('dang-nhap') ?>">
                <div class="authfield">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" name="email" value="<?= e(old('email')) ?>" placeholder="Email" required>
                </div>
                <div class="authfield">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" placeholder="Mật khẩu" required>
                </div>
                <button class="btn btn-primary btn-lg authbox__submit" type="submit">
                    <i class="fa-solid fa-right-to-bracket"></i> Đăng nhập
                </button>
            </form>
            <p class="authbox__alt">Chưa có tài khoản? <a href="<?= url('dang-ky') ?>">Đăng ký ngay</a></p>
        </div>
    </div>
</div>
