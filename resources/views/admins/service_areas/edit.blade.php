@extends('masters.layouts.master')

@section('title', '豫順家居媒合服務平台')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center">
                <nav aria-label="breadcrumb" class="mb-2 mb-md-0">
                    <ol class="breadcrumb breadcrumb-path">
                        <li class="breadcrumb-item"><a href="{{ route('masters.index') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admins.service_areas.index') }}">服務地區管理</a></li>
                        <li class="breadcrumb-item active" aria-current="page">編輯服務地區</li>
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
                    <h2 class="text-center mb-4">編輯服務地區</h2>

                    <form action="{{ route('admins.service_areas.update', ['hash_service_area' => \Vinkla\Hashids\Facades\Hashids::encode($service_area->id)]) }}" method="POST" role="form">
                        @csrf
                        @method('PATCH')

                        <div class="form-group mb-3">
                            <label for="major_area">縣市</label>
                            <input type="text" class="form-control" id="major_area" name="major_area" value="{{ old('major_area', $service_area->major_area) }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="minor_area">鄉鎮</label>
                            <input type="text" class="form-control" id="minor_area" name="minor_area" value="{{ old('minor_area', $service_area->minor_area) }}" required>
                        </div>

                        <div class="form-group mb-4">
                            <label>區域類別</label>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="egg_yolk_area" name="area_type" value="1"
                                       {{ old('area_type', $service_area->status) == 1 ? 'checked' : '' }} required>
                                <label class="form-check-label" for="egg_yolk_area">蛋黃區</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="egg_white_area" name="area_type" value="0"
                                       {{ old('area_type', $service_area->status) == 0 ? 'checked' : '' }} required>
                                <label class="form-check-label" for="egg_white_area">蛋白區</label>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary px-4">更新地區</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .breadcrumb-path {
            font-size: 1.4em;
            white-space: normal;
            word-break: break-word;
        }

        @media (max-width: 768px) {
            .breadcrumb-path {
                font-size: 1.3em;
            }
            .text-size-controls {
                margin-top: 0.5rem;
            }
        }

        @media (max-width: 480px) {
            .breadcrumb-path {
                font-size: 1.1em;
            }
            .d-flex.flex-column.flex-md-row > .btn-group {
                width: 100%;
                justify-content: center;
            }
        }

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
