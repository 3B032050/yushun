<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
{{--        <meta viewport" content="width=device-width, initial-scale=1.0">--}}
{{--        <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">--}}
{{--        <meta http-equiv="Pragma" content="no-cache">--}}
{{--        <meta http-equiv="Expires" content="0">--}}
        <title>@yield('title')</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
        <link rel="icon" type="image/x-icon" href="{{asset('assets/favicon.ico')}}" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <link href="{{asset('css/homepage-styles.css')}}" rel="stylesheet" />
{{--        @vite(['resources/sass/app.scss', 'resources/js/app.js'])--}}

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Ma+Shan+Zheng&display=swap" rel="stylesheet">
{{--        <style>--}}
{{--            .ma-shan-zheng-regular {--}}
{{--                font-family: "Ma Shan Zheng", cursive;--}}
{{--                font-weight: 200;--}}
{{--                font-style: normal;--}}
{{--            }--}}
{{--        </style>--}}

{{--        <!-- Bootstrap JS（包含 Popper.js） -->--}}

        <link href="library/bootstrap-5/bootstrap.min.css" rel="stylesheet" />
        <script src="library/bootstrap-5/bootstrap.bundle.min.js"></script>
        <script src="library/dselect.js"></script>
        <style>
            .custom-link {
                color: black; /* 設置字體顏色為黑色 */
                text-decoration: none; /* 移除下劃線 */
            }
        </style>
        <!-- Google Fonts字型 -->
        <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/IcoFont/1.0.0/icofont.min.css">

    </head>
    <body>
        @include('masters.layouts.partials.navigation')
{{--        <div style="padding-left: 150px; background-color: #EEEDEC;" class="py-1">--}}
{{--            @yield('page-path')--}}
{{--        </div>--}}
        @yield('content')
{{--        @include('layouts.partials.footer')--}}

        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

        <!-- 引入 jQuery UI 库 -->
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <style>
            a {
                text-decoration:none;
            }
        </style>
        <style>
            .custom-link {
                color: black; /* 預設顏色 */
                text-decoration: none; /* 移除下劃線 */
            }
            .custom-link:hover {
                color: #3498db; /* 滑鼠懸停時的顏色，這裡使用藍色 */
            }
        </style>
        <style>
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

        </style>
    </body>
</html>
