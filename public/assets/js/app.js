/* =============================================================
   XINMAI - app.js
   Tương tác phía client: thêm/sửa/xoá giỏ hàng (AJAX), bộ chọn số lượng,
   thông báo toast. Viết bằng JavaScript thuần, không phụ thuộc thư viện.
   ============================================================= */
(function () {
    'use strict';

    var BASE = document.body.getAttribute('data-base') || '';

    /** Định dạng số tiền VND giống phía server. */
    function formatPrice(value) {
        return new Intl.NumberFormat('vi-VN').format(Math.round(value)) + ' ₫';
    }

    /** Hiện thông báo dạng toast trong vài giây. */
    function toast(message, isError) {
        var el = document.getElementById('toast');
        if (!el) { return; }
        el.textContent = message;
        el.style.borderLeftColor = isError ? '#e63946' : '#f5821f';
        el.classList.add('show');
        clearTimeout(toast._timer);
        toast._timer = setTimeout(function () { el.classList.remove('show'); }, 2800);
    }

    /** Gửi POST dạng form-urlencoded và trả về Promise<JSON>. */
    function postForm(path, data) {
        var body = Object.keys(data)
            .map(function (k) { return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]); })
            .join('&');
        return fetch(BASE + path, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body
        }).then(function (r) { return r.json(); });
    }

    /** Cập nhật badge số lượng trên header. */
    function setCartCount(count) {
        var badge = document.getElementById('cart-count');
        if (badge) { badge.textContent = count; }
    }

// ----- Thêm vào giỏ hàng & Mua ngay ---------------------------------------
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-add-to-cart]');
        if (!btn) { return; }
        e.preventDefault();

        var id = btn.getAttribute('data-id');
        var qty = 1;
        var src = btn.getAttribute('data-qty-source');
        if (src) {
            var input = document.querySelector(src);
            if (input) { qty = parseInt(input.value, 10) || 1; }
        }

        btn.disabled = true;
        postForm('/gio-hang/them', { product_id: id, quantity: qty })
            .then(function (res) {
                if (res.ok) {
                    setCartCount(res.cartCount);
                    
                    // KIỂM TRA: Nếu là nút "Mua ngay" thì chuyển hướng thẳng, không cần hiện thông báo Toast
                    if (btn.classList.contains('btn-buy-now')) {
                        var baseUrl = document.body.getAttribute('data-base') || '';
                        window.location.href = baseUrl + '/gio-hang';
                    } else {
                        // Nếu là nút "Thêm vào giỏ" thông thường thì vẫn hiện thông báo như cũ
                        toast(res.message || 'Đã thêm vào giỏ hàng.');
                    }
                } else {
                    toast(res.message || 'Có lỗi xảy ra.', true);
                }
            })
            .catch(function () { toast('Không kết nối được máy chủ.', true); })
            .finally(function () { btn.disabled = false; });
    });

    // ----- Bộ chọn số lượng ở trang chi tiết --------------------------------
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-qty]');
        if (!btn) { return; }
        var input = btn.parentElement.querySelector('input');
        if (!input) { return; }
        var step = parseInt(btn.getAttribute('data-qty'), 10);
        var min = parseInt(input.getAttribute('min'), 10) || 1;
        var next = (parseInt(input.value, 10) || min) + step;
        input.value = next < min ? min : next;
    });

    // ----- Cập nhật & xoá trong trang giỏ hàng ------------------------------
    function refreshSummary(res) {
        setCartCount(res.cartCount);
        var price = formatPrice(res.cartSubtotal);
        var sub = document.getElementById('cartSubtotal');
        var tot = document.getElementById('cartTotal');
        if (sub) { sub.textContent = price; }
        if (tot) { tot.textContent = price; }
        if (res.cartCount === 0) { window.location.reload(); }
    }

    // Tăng/giảm số lượng trong giỏ
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-cart-qty]');
        if (!btn) { return; }
        var row = btn.closest('[data-cart-row]');
        var input = row.querySelector('[data-cart-input]');
        var step = parseInt(btn.getAttribute('data-cart-qty'), 10);
        var qty = Math.max(1, (parseInt(input.value, 10) || 1) + step);
        input.value = qty;
        updateRow(row, qty);
    });

    // Nhập tay số lượng
    document.addEventListener('change', function (e) {
        var input = e.target.closest('[data-cart-input]');
        if (!input) { return; }
        var row = input.closest('[data-cart-row]');
        var qty = Math.max(1, parseInt(input.value, 10) || 1);
        input.value = qty;
        updateRow(row, qty);
    });

    function updateRow(row, qty) {
        var id = row.getAttribute('data-id');
        postForm('/gio-hang/cap-nhat', { product_id: id, quantity: qty })
            .then(refreshSummary)
            .catch(function () { toast('Không cập nhật được giỏ hàng.', true); });
    }

    // Xoá khỏi giỏ
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-cart-remove]');
        if (!btn) { return; }
        var row = btn.closest('[data-cart-row]');
        var id = row.getAttribute('data-id');
        postForm('/gio-hang/xoa', { product_id: id })
            .then(function (res) {
                row.remove();
                refreshSummary(res);
                toast('Đã xoá sản phẩm khỏi giỏ hàng.');
            })
            .catch(function () { toast('Không xoá được sản phẩm.', true); });
    });

    // ----- Áp mã giảm giá ở trang thanh toán --------------------------------
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-apply-coupon]');
        if (!btn) { return; }
        var input = document.getElementById('couponCode');
        var code = input ? input.value.trim() : '';
        if (!code) { toast('Vui lòng nhập mã giảm giá.', true); return; }

        btn.disabled = true;
        postForm('/dat-hang/ma-giam-gia', { code: code })
            .then(function (res) {
                if (res.ok) {
                    var d = document.getElementById('ckDiscount');
                    var t = document.getElementById('ckTotal');
                    if (d) { d.textContent = '- ' + formatPrice(res.discount); }
                    if (t) { t.textContent = formatPrice(res.total); }
                    toast(res.message || 'Áp dụng mã thành công.');
                } else {
                    toast(res.message || 'Mã không hợp lệ.', true);
                }
            })
            .catch(function () { toast('Không áp dụng được mã.', true); })
            .finally(function () { btn.disabled = false; });
    });

    // ----- Banner slider (tự chạy + prev/next + chấm) -----------------------
    (function initSlider() {
        var slider = document.querySelector('[data-slider]');
        if (!slider) { return; }

        var slides = slider.querySelectorAll('.slide');
        var dots = slider.querySelectorAll('[data-slide-to]');
        if (slides.length < 2) { return; }

        var index = 0;
        var timer = null;

        function show(n) {
            index = (n + slides.length) % slides.length;
            for (var i = 0; i < slides.length; i++) {
                slides[i].classList.toggle('active', i === index);
                if (dots[i]) { dots[i].classList.toggle('active', i === index); }
            }
        }
        function next() { show(index + 1); }
        function prev() { show(index - 1); }
        function play() { timer = setInterval(next, 4500); }
        function restart() { clearInterval(timer); play(); }

        var btnNext = slider.querySelector('[data-slide-next]');
        var btnPrev = slider.querySelector('[data-slide-prev]');
        if (btnNext) { btnNext.addEventListener('click', function () { next(); restart(); }); }
        if (btnPrev) { btnPrev.addEventListener('click', function () { prev(); restart(); }); }
        for (var d = 0; d < dots.length; d++) {
            dots[d].addEventListener('click', function () {
                show(parseInt(this.getAttribute('data-slide-to'), 10));
                restart();
            });
        }

        // Tạm dừng khi rê chuột vào banner
        slider.addEventListener('mouseenter', function () { clearInterval(timer); });
        slider.addEventListener('mouseleave', play);

        play();
    })();
})();
