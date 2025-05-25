<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>@yield('title')</title>
    @stack('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <link rel="icon" type="image/x-icon" href="{{asset('assets/favicon.ico')}}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="{{asset('css/homepage-styles.css')}}" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ma+Shan+Zheng&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.7/main.min.css" rel="stylesheet">
    <script src="{{ asset('js/index.global.min.js') }}"></script>
    <script src="{{ asset('js/index.global.js') }}"></script>


    <link href="library/bootstrap-5/bootstrap.min.css" rel="stylesheet" />
    <script src="library/bootstrap-5/bootstrap.bundle.min.js"></script>
    <script src="library/dselect.js"></script>

    <!-- Google Fonts字型 -->
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/IcoFont/1.0.0/icofont.min.css">

</head>
<body>
@include('users.layouts.partials.navigation')
{{--        <div style="padding-left: 150px; background-color: #EEEDEC;" class="py-1">--}}
{{--            @yield('page-path')--}}
{{--        </div>--}}
<div class="content-wrapper">
    @yield('content')
</div>
@include('users.layouts.partials.footer')
{{--        @include('layouts.partials.footer')--}}
<!-- 引入 Flatpickr 样式 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- 引入 Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- 引入 jQuery UI 库 -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
    <style>
     #calendar {
         max-width: 100%;
         margin: 0 auto;
         height: 600px;
         /*min-height: 300px;*/
         /* 設置明確的高度 */
     }
    .fc-event-delete-container {
        margin-top: 10px; /* 上方間隔 */
        display: block;   /* 確保容器占滿整行 */
        text-align: center; /* 可選，讓刪除按鈕居中 */
    }

    .fc-event-delete {
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 5px 10px;
        font-size: 12px;
        cursor: pointer;
        display: block; /* 確保按鈕是塊級元素 */
        margin: 0 auto; /* 居中對齊按鈕 */
    }

    .fc-event-delete:hover {
        background-color: #c82333;
    }
</style>
</style>

@stack('scripts')
</body>
</html>
