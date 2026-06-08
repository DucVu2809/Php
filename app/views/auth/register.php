<div class="container breadcrumb">
    <a href="<?= url('/') ?>">Trang chủ</a>
    <i class="fa-solid fa-angle-right"></i>
    <span>Đăng ký</span>
</div>

<div class="container section authpage">
    <div class="authbox">
        <div class="authbox__aside">
            <div class="authbox__logo"><i class="fa-solid fa-helmet-safety"></i> XIN<strong>MAI</strong></div>
            <h2>Tạo tài khoản mới</h2>
            <p>Tham gia XINMAI để mua máy móc, thiết bị xây dựng chính hãng giá tốt.</p>
            <ul class="authbox__perks">
                <li><i class="fa-solid fa-shield-halved"></i> Bảo hành chính hãng</li>
                <li><i class="fa-solid fa-gift"></i> Ưu đãi & mã giảm giá riêng</li>
                <li><i class="fa-solid fa-headset"></i> Hỗ trợ kỹ thuật trọn đời</li>
            </ul>
        </div>
        <div class="authbox__form">
            <h1>Đăng ký</h1>
            <p class="sub">Chỉ mất một phút để tạo tài khoản</p>
            <form method="post" action="<?= url('dang-ky') ?>">
                <div class="authfield">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" name="name" value="<?= e(old('name')) ?>" placeholder="Họ và tên" required>
                </div>
                <div class="authfield">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" name="email" value="<?= e(old('email')) ?>" placeholder="Email" required>
                </div>
                <div class="authfield">
                    <i class="fa-solid fa-phone"></i>
                    <input type="tel" name="phone" value="<?= e(old('phone')) ?>" placeholder="Số điện thoại">
                </div>
                <div class="authfield">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" placeholder="Mật khẩu (tối thiểu 6 ký tự)" required>
                </div>
                <div class="authfield">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password_confirm" placeholder="Nhập lại mật khẩu" required>
                </div>
                <button class="btn btn-primary btn-lg authbox__submit" type="submit">
                    <i class="fa-solid fa-user-plus"></i> Đăng ký
                </button>
            </form>
            <p class="authbox__alt">Đã có tài khoản? <a href="<?= url('dang-nhap') ?>">Đăng nhập</a></p>
        </div>
    </div>
</div>
