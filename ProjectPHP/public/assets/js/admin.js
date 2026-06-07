(function () {
    'use strict';

    var BASE = document.body.getAttribute('data-base') || '';

    function openModal(el) {
        if (el) { el.classList.add('open'); document.body.classList.add('modal-open'); }
    }
    function closeModal(el) {
        if (el) { el.classList.remove('open'); document.body.classList.remove('modal-open'); }
    }

    document.addEventListener('click', function (e) {
        if (e.target.closest('[data-modal-close]') || e.target.classList.contains('modal')) {
            var m = e.target.closest('.modal');
            if (m) { closeModal(m); }
        }
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            var open = document.querySelector('.modal.open');
            if (open) { closeModal(open); }
        }
    });

    var confirmModal = document.getElementById('confirmModal');
    var pendingForm = null;
    if (confirmModal) {
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-confirm]');
            if (!btn) { return; }
            e.preventDefault();
            pendingForm = btn.closest('form');
            var msg = confirmModal.querySelector('[data-confirm-msg]');
            if (msg) { msg.textContent = btn.getAttribute('data-confirm') || 'Bạn có chắc chắn muốn thực hiện?'; }
            openModal(confirmModal);
        });
        confirmModal.querySelector('[data-confirm-ok]').addEventListener('click', function () {
            if (pendingForm) { pendingForm.submit(); }
        });
    }

    var pModal = document.getElementById('productModal');
    if (pModal) {
        var pForm = document.getElementById('productForm');
        var pStore = pForm.getAttribute('data-store');
        var pTitle = pModal.querySelector('[data-modal-title]');

        var descQuill = null;
        if (window.Quill && document.getElementById('descEditor')) {
            descQuill = new Quill('#descEditor', {
                theme: 'snow',
                placeholder: 'Nhập mô tả chi tiết sản phẩm...',
                modules: {
                    toolbar: [
                        [{ header: [2, 3, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['link', 'clean']
                    ]
                }
            });
        }
        function syncDesc() {
            var input = document.getElementById('descInput');
            if (descQuill && input) {
                var html = descQuill.root.innerHTML;
                input.value = (html === '<p><br></p>') ? '' : html;
            }
        }
        pForm.addEventListener('submit', syncDesc);

        var upInput = pForm.querySelector('[data-upload-input]');
        var upZone = pForm.querySelector('[data-uploader]');
        var upHint = pForm.querySelector('[data-upload-hint]');
        var upPrev = pForm.querySelector('[data-current-thumb]');

        function showPreview(src) {
            if (!upPrev) { return; }
            if (src) {
                upPrev.src = src; upPrev.style.display = 'block';
                if (upHint) { upHint.style.display = 'none'; }
            } else {
                upPrev.removeAttribute('src'); upPrev.style.display = 'none';
                if (upHint) { upHint.style.display = 'flex'; }
            }
        }
        if (upZone) {
            upZone.addEventListener('click', function () { upInput.click(); });
            upInput.addEventListener('change', function () {
                if (upInput.files && upInput.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (ev) { showPreview(ev.target.result); };
                    reader.readAsDataURL(upInput.files[0]);
                }
            });
            ['dragover', 'dragenter'].forEach(function (ev) {
                upZone.addEventListener(ev, function (e) { e.preventDefault(); upZone.classList.add('dragover'); });
            });
            ['dragleave', 'drop'].forEach(function (ev) {
                upZone.addEventListener(ev, function (e) { e.preventDefault(); upZone.classList.remove('dragover'); });
            });
            upZone.addEventListener('drop', function (e) {
                if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                    upInput.files = e.dataTransfer.files;
                    upInput.dispatchEvent(new Event('change'));
                }
            });
        }

        document.addEventListener('click', function (e) {
            if (e.target.closest('[data-product-add]')) {
                pForm.reset();
                pForm.setAttribute('action', pStore);
                pForm.elements['is_active'].checked = true;
                pForm.elements['is_featured'].checked = false;
                if (descQuill) { descQuill.root.innerHTML = ''; }
                showPreview('');
                pTitle.textContent = 'Thêm sản phẩm';
                openModal(pModal);
                return;
            }
            var edit = e.target.closest('[data-product-edit]');
            if (edit) {
                var id = edit.getAttribute('data-product-edit');
                fetch(BASE + '/admin/san-pham/' + id + '/json')
                    .then(function (r) { return r.json(); })
                    .then(function (p) {
                        pForm.setAttribute('action', pStore + '/' + id);
                        ['name', 'sku', 'stock', 'price', 'sale_price', 'short_desc', 'specs', 'category_id', 'brand_id'].forEach(function (k) {
                            var f = pForm.elements[k];
                            if (f) { f.value = (p[k] === null || p[k] === undefined) ? '' : p[k]; }
                        });
                        pForm.elements['is_active'].checked = parseInt(p.is_active, 10) === 1;
                        pForm.elements['is_featured'].checked = parseInt(p.is_featured, 10) === 1;
                        if (descQuill) { descQuill.root.innerHTML = p.description || ''; }
                        showPreview(p.thumbnail ? BASE + '/assets/uploads/products/' + p.thumbnail : '');
                        pTitle.textContent = 'Sửa sản phẩm';
                        openModal(pModal);
                    });
            }
        });
    }

    var cModal = document.getElementById('couponModal');
    if (cModal) {
        var cForm = document.getElementById('couponForm');
        var cStore = cForm.getAttribute('data-store');
        var cTitle = cModal.querySelector('[data-modal-title]');

        document.addEventListener('click', function (e) {
            if (e.target.closest('[data-coupon-add]')) {
                cForm.reset();
                cForm.setAttribute('action', cStore);
                cForm.elements['is_active'].checked = true;
                cTitle.textContent = 'Thêm mã giảm giá';
                openModal(cModal);
                return;
            }
            var edit = e.target.closest('[data-coupon-edit]');
            if (edit) {
                var id = edit.getAttribute('data-coupon-edit');
                fetch(BASE + '/admin/ma-giam-gia/' + id + '/json')
                    .then(function (r) { return r.json(); })
                    .then(function (c) {
                        cForm.setAttribute('action', cStore + '/' + id);
                        ['code', 'type', 'value', 'min_order', 'usage_limit', 'expires_at'].forEach(function (k) {
                            var f = cForm.elements[k];
                            if (f) { f.value = (c[k] === null || c[k] === undefined) ? '' : c[k]; }
                        });
                        cForm.elements['is_active'].checked = parseInt(c.is_active, 10) === 1;
                        cTitle.textContent = 'Sửa mã giảm giá';
                        openModal(cModal);
                    });
            }
        });
    }

    window.adminOpenModal = openModal;
})();
