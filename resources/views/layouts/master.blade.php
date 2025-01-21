<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>@yield('title', '豫順清潔')</title>

    <!-- 引入 CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/homepage-styles.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/IcoFont/1.0.0/icofont.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Ma+Shan+Zheng&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}" />

    <!-- 自訂樣式 -->
    <style>
        .custom-link {
            color: black;
            text-decoration: none;
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
            color: #36395a;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            height: 150px;
            width: 150px;
            justify-content: center;
            padding: 16px;
            text-align: center;
            font-size: 18px;
            transition: box-shadow 0.15s, transform 0.15s;
        }

        .button-name i {
            margin-bottom: 10px;
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
    </style>
</head>
<body>
<!-- 導覽列 -->
@include('layouts.partials.navigation')

<!-- 主內容 -->
<div class="container mt-4">
    @yield('content')
</div>

<!-- 頁腳 -->
@include('layouts.partials.footer')

<!-- 引入 JS -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{ asset('library/dselect.js') }}"></script>
</body>
</html>
