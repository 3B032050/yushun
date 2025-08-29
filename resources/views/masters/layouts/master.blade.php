<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome, IcoFont, Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/IcoFont/1.0.0/icofont.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Ma+Shan+Zheng&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- ViewerJS 圖片縮放 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.5/viewer.min.css" />

    <!-- 其他樣式 -->
{{--    <link rel="stylesheet" href="{{ asset('css/style.css') }}">--}}
{{--    <link rel="stylesheet" href="{{ asset('css/homepage-styles.css') }}">--}}

    <!-- 預留插入更多 CSS -->
    @stack('styles')
</head>
<body>
@include('masters.layouts.partials.navigation')

<div class="content-wrapper">
    @yield('content')
</div>



@include('masters.layouts.partials.footer')

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- jQuery UI -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- ViewerJS 圖片縮放 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.5/viewer.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- 預留插入更多 JS -->

@stack('scripts')
<style>
    html {
        font-size: 16px;
    }
    /* -------------------- 全站文字與連結 -------------------- */
    body, p, span, div, label, th, td {
        color: #000; /* 預設黑色文字 */
    }

    a, a:visited, .breadcrumb a {
        color: black !important;  /* 預設黑色連結 */
        text-decoration: none;
    }

    a:hover, .breadcrumb a:hover, .custom-link:hover {
        color: #3498db !important; /* 滑鼠懸停藍色 */
        text-decoration: none;
    }

    /* -------------------- 按鈕樣式 -------------------- */
    .button-name {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 150px;
        width: 150px;
        padding: 16px;
        text-align: center;
        font-size: 18px;
        background-color: #fcfcfd;
        border-radius: 25px;
        border: none;
        box-shadow: rgba(45, 35, 66, 0.2) 0 2px 4px,
        rgba(45, 35, 66, 0.15) 0 7px 13px -3px,
        #d6d6e7 0 -3px 0 inset;
        cursor: pointer;
        transition: box-shadow 0.15s, transform 0.15s;
        user-select: none;
        white-space: normal;
    }
    .button-name i { margin-bottom: 10px; }
    .button-name:hover {
        box-shadow: rgba(45, 35, 66, 0.3) 0 4px 8px,
        rgba(45, 35, 66, 0.2) 0 7px 13px -3px,
        #d6d6e7 0 -3px 0 inset;
        transform: translateY(-2px);
    }
    .button-name:active {
        box-shadow: #d6d6e7 0 3px 7px inset;
        transform: translateY(2px);
    }

    /* 單個按鈕與圖片 */
    .uniform-btn {
        background-color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .uniform-btn:hover { transform: scale(1.05); }
    .uniform-btn:active { transform: translateY(2px); box-shadow: 2px 2px 4px rgba(0,0,0,0.2); }
    .uniform-image { max-width: 100%; max-height: 200px; border-radius: 5px; }

    /* -------------------- 表格樣式 -------------------- */
    .table-responsive { max-width: 85%; margin: auto; }
    .table th, .table td { vertical-align: middle; text-align: center; }
    .table img { display: block; margin: auto; }

    /* -------------------- 選項框樣式 -------------------- */
    .service-item {
        padding: 10px 15px;
        border-radius: 8px;
        border: 2px solid black;
        transition: all 0.3s ease-in-out;
    }
    .service-item:hover { background-color: #f8f9fa; }
    .form-check-input:checked + label { font-weight: bold; color: black; }

    /* -------------------- 字級控制 -------------------- */
    #content.small { font-size: 0.8rem; }
    #content.medium { font-size: 1rem; }
    #content.large { font-size: 1.25rem; }
    #content.small, #content.small * { font-size: 0.8rem !important; }
    #content.medium, #content.medium * { font-size: 1rem !important; }
    #content.large, #content.large * { font-size: 1.2rem !important; }

    /* -------------------- breadcrumb 固定字級 -------------------- */
    .breadcrumb-path {
        font-size: 16px !important;
        white-space: normal;
        word-break: break-word;
    }

    /* -------------------- 表格欄位調整 -------------------- */
    #sortable-list th:nth-child(2),
    #sortable-list td:nth-child(2) { min-width: 120px; }

    /* -------------------- 響應式 -------------------- */
    @media (max-width: 768px) {
        #sortable-list { min-width: 600px; }
        #sortable-list th, #sortable-list td { font-size: 0.9em; }
        .text-size-controls .btn { padding: 0.25rem 0.5rem; font-size: 0.85em; }
    }
    @media (max-width: 480px) {
        #sortable-list { min-width: 500px; }
        #sortable-list th, #sortable-list td { font-size: 0.8em; }
        .text-size-controls .btn { padding: 0.2rem 0.4rem; font-size: 0.75em; }
    }

    /* -------------------- 微調 -------------------- */
    .btn-sm { line-height: 1.2; }
    html, body { height: 100%; margin: 0; }
</style>

<script>
                function setFontSize(size) {
                    const content = document.getElementById('content');
                    content.className = size;
                    localStorage.setItem('preferredFontSize', size);
                }

                // document.addEventListener('DOMContentLoaded', () => {
                //     const savedSize = localStorage.getItem('preferredFontSize') || 'medium';
                //     document.getElementById('content').className = savedSize;
                // });
                document.addEventListener('DOMContentLoaded', () => {
                    const savedSize = localStorage.getItem('preferredFontSize') || 'medium';
                    const content = document.getElementById('content');
                    if (content) {  // 有這個元素才操作
                        content.classList.remove('small', 'medium', 'large');
                        content.classList.add(savedSize);
                    }
                });
            </script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const showAlert = (icon, title, text, callback) => {
            // if (!document.body) return; // 安全檢查
            Swal.fire({
                icon: icon,
                title: title,
                html: text, // 用 html 可以支援 <br>
                confirmButtonText: '確定',
                // target: document.body // 明確指定 body
            }).then(() => {
                if (callback) callback();
            });
        };

        @if(session('success'))
        showAlert('success', '成功', @json(session('success')));
        @endif

        @if(session()->has('validation_errors') && is_array(session('validation_errors')) && count(session('validation_errors')) > 0)
        let errors = @json(session('validation_errors')).map(e => e).join('<br>');
        showAlert('error', '驗證失敗', errors);
        @endif

        @if(session('error'))
        showAlert('error', '錯誤', @json(session('error')));
        @endif

        @if(session('warning'))
        showAlert('warning', '提醒', @json(session('warning')), () => {
            window.location.href = "{{ route('login') }}";
        });
        @endif
    });

        // 修改按鈕
        document.querySelectorAll('.btn-modify').forEach(btn => {
            btn.addEventListener('click', function () {
                Swal.fire({
                    title: '確定要修改這筆時段嗎？',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '確定',
                    cancelButtonText: '取消',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // 如果有隱藏的 submit 按鈕就用它（帶 action="alter"）
                        const submitAlterBtn = btn.closest('form').querySelector('button[name="action"][value="alter"]');
                        if (submitAlterBtn) {
                            submitAlterBtn.click();
                        } else {
                            // 沒有就直接送出表單
                            btn.closest('form').submit();
                        }
                    }
                });
            });
        });

// 刪除按鈕
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function () {
                Swal.fire({
                    title: '確定要刪除這筆資料嗎？',
                    text: "刪除後將無法復原！",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '是，刪除！',
                    cancelButtonText: '取消',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        btn.closest('form').submit();
                    }
                });
            });
        });

</script>

    </body>
</html>
