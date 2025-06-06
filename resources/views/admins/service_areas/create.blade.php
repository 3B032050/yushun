@extends('masters.layouts.master')

@section('title', '新增服務地區')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-2">
                <nav aria-label="breadcrumb" class="mb-2 mb-md-0 w-100 w-md-auto">
                    <ol class="breadcrumb breadcrumb-path mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('masters.index') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admins.service_areas.index') }}">服務地區管理</a></li>
                        <li class="breadcrumb-item active" aria-current="page">新增服務地區</li>
                    </ol>
                </nav>
                <div class="text-size-controls btn-group btn-group-sm" role="group" aria-label="字級調整">
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
                </div>
            </div>
        </div>

        <div id="content" class="medium">
            <div class="container d-flex justify-content-center align-items-center">
                <div class="col-md-6 col-12">
                    <h2 class="text-center mb-4">新增服務地區</h2>

                    <form action="{{ route('admins.service_areas.store') }}" method="POST" role="form" enctype="multipart/form-data">
                        @csrf
                        @method('POST')

                        <div class="form-group">
                            <label for="major_area">縣市</label>
                            <input type="text" class="form-control" id="major_area" name="major_area" required>
                        </div><br>

                        <div class="form-group">
                            <label for="minor_area">鄉鎮</label>
                            <input type="text" class="form-control" id="minor_area" name="minor_area" required>
                        </div><br>

                        <div class="form-group">
                            <label>區域類別</label>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="egg_yolk_area" name="area_type" value="egg_yolk" required>
                                <label class="form-check-label" for="egg_yolk_area">蛋黃區</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="egg_white_area" name="area_type" value="egg_white" required>
                                <label class="form-check-label" for="egg_white_area">蛋白區</label>
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary">新增地區</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        #content.font-small {
            font-size: 14px;
        }

        #content.font-medium {
            font-size: 16px;
        }

        #content.font-large {
            font-size: 18px;
        }

        #content.font-small .form-control,
        #content.font-small .form-check-label {
            font-size: 0.85rem;
        }

        #content.font-medium .form-control,
        #content.font-medium .form-check-label {
            font-size: 1rem;
        }

        #content.font-large .form-control,
        #content.font-large .form-check-label {
            font-size: 1.15rem;
        }
    </style>

    <script>
        function setFontSize(size) {
            const content = document.getElementById('content');
            content.classList.remove('font-small', 'font-medium', 'font-large');
            content.classList.add(`font-${size}`);
        }
    </script>
@endsection
