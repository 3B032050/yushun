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
            a {
                text-decoration:none;
            }

            .custom-link {
                color: black; /* 預設顏色 */
                text-decoration: none; /* 移除下劃線 */
            }
            .custom-link:hover {
                color: #3498db; /* 滑鼠懸停時的顏色，這裡使用藍色 */
            }

            .button-name {
                align-items: center;
                appearance: none;
                background-color: #fcfcfd;
                border-radius: 25px;
                margin: 10px;
                border-width: 0;
                box-shadow:
                    rgba(45, 35, 66, 0.2) 0 2px 4px,
                    rgba(45, 35, 66, 0.15) 0 7px 13px -3px,
                    #d6d6e7 0 -3px 0 inset;
                box-sizing: border-box;
                color: #36395a;
                cursor: pointer;
                display: flex;
                flex-direction: column;
                height: 150px;
                width: 150px;
                justify-content: center;
                padding: 16px;
                position: relative;
                text-align: center;
                text-decoration: none;
                transition:
                    box-shadow 0.15s,
                    transform 0.15s;
                user-select: none;
                -webkit-user-select: none;
                touch-action: manipulation;
                white-space: normal;
                will-change: box-shadow, transform;
                font-size: 18px;
            }

            .button-name i {
                margin-bottom: 10px;
            }

            .button-name:focus {
                box-shadow:
                    #d6d6e7 0 0 0 1.5px inset,
                    rgba(45, 35, 66, 0.4) 0 2px 4px,
                    rgba(45, 35, 66, 0.3) 0 7px 13px -3px,
                    #d6d6e7 0 -3px 0 inset;
            }

            .button-name:hover {
                box-shadow:
                    rgba(45, 35, 66, 0.3) 0 4px 8px,
                    rgba(45, 35, 66, 0.2) 0 7px 13px -3px,
                    #d6d6e7 0 -3px 0 inset;
                transform: translateY(-2px);
            }

            .button-name:active {
                box-shadow: #d6d6e7 0 3px 7px inset;
                transform: translateY(2px);
            }

            /* 單個按鈕 */
            .uniform-btn {
                background: #f8f9fa;
                border: 2px solid #ddd;
                border-radius: 10px;
                box-shadow: 4px 4px 8px rgba(0, 0, 0, 0.2), -4px -4px 8px rgba(255, 255, 255, 0.8);
                padding: 10px;
                /*width: 320px; !* 調小按鈕寬度 *!*/
                /*height: 350px; !* 調小按鈕高度 *!*/
                transition: transform 0.2s, box-shadow 0.2s;
            }

            .uniform-btn:hover {
                transform: translateY(-5px);
                box-shadow: 6px 6px 12px rgba(0, 0, 0, 0.3), -6px -6px 12px rgba(255, 255, 255, 0.9);
            }

            .uniform-btn:active {
                transform: translateY(2px);
                box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2), -2px -2px 4px rgba(255, 255, 255, 0.7);
            }

            /* 圖片樣式 */
            .uniform-image {
                max-width: 100%;
                max-height: 100%;
                border-radius: 5px;
            }

            /* 統一文字顏色與樣式 */
            .uniform-btn h5 {
                color: #333;
                margin-top: 10px;
                font-weight: bold;
            }

            .table-responsive {
                max-width: 85%; /* 限制表格最大寬度 */
                margin: auto;   /* 讓表格置中 */
            }

            .table th, .table td {
                vertical-align: middle; /* 垂直置中 */
                text-align: center;     /* 文字水平置中 */
            }

            .table img {
                display: block;
                margin: auto; /* 讓圖片在單元格內置中 */
            }

            /* 自訂按鈕樣式 */
            .uniform-btn {
                background-color: #fff;
                border: none;
                padding: 0;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
                transition: transform 0.2s ease-in-out;
            }

            /* 按鈕懸停效果 */
            .uniform-btn:hover {
                transform: scale(1.05);
            }

            /* 控制制服圖片大小 */
            .uniform-image {
                max-height: 200px;
                width: auto;
            }

            .table-responsive {
                max-width: 85%; /* 限制表格最大寬度 */
                margin: auto;   /* 讓表格置中 */
            }

            .table th, .table td {
                vertical-align: middle; /* 垂直置中 */
                text-align: center;     /* 文字水平置中 */
            }
            /* 讓選項框變成黑色 */
            .service-item {
                padding: 10px 15px;
                border-radius: 8px;
                border: 2px solid black; /* 設定黑色邊框 */
                transition: all 0.3s ease-in-out;
            }

            /* 當 radio 被選中時，讓邊框變黑色並加粗 */
            .form-check-input:checked + label {
                font-weight: bold;
                color: black; /* 文字變黑色 */
            }

            .service-item:hover {
                background-color: #f8f9fa; /* 可選，讓 hover 有明顯感 */
            }

            html, body {
                height: 100%;
                margin: 0;
            }

            /* 控制字級的 class */
            #content.small input,
            #content.small select,
            #content.small textarea,
            #content.small label {
                font-size: 0.8rem;
            }
            #content.medium input,
            #content.medium select,
            #content.medium textarea,
            #content.medium label {
                font-size: 1rem;
            }
            #content.large input,
            #content.large select,
            #content.large textarea,
            #content.large label {
                font-size: 1.2rem;

            /* 字級按鈕排版 */
            .text-size-controls {
                display: flex;
                align-items: center;
                gap: 5px;
            }

            .text-size-controls button {
                padding: 2px 8px;
                font-size: 14px;
            }
        </style>
        <style>
            #content.small {
                font-size: 0.8rem;
            }

            #content.medium {
                font-size: 1rem;
            }

            #content.large {
                font-size: 1.25rem;
            }

            #content .table th,
            #content .table td {
                font-size: inherit;
            }
        </style>
        @push('scripts')
            <script>
                function setFontSize(size) {
                    const content = document.getElementById('content');
                    content.className = size;
                    localStorage.setItem('preferredFontSize', size);
                }

                document.addEventListener('DOMContentLoaded', () => {
                    const savedSize = localStorage.getItem('preferredFontSize') || 'medium';
                    document.getElementById('content').className = savedSize;
                });
            </script>
        @endpush

        @stack('scripts')
    </body>
</html>
