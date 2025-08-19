@extends('masters.layouts.master')

@section('title', '豫順家居媒合服務平台')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-2 gap-2">
                <nav aria-label="breadcrumb" class="mb-2 mb-md-0 w-100 w-md-auto">
                    <ol class="breadcrumb breadcrumb-path mb-0">
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

        <div id="content" class="font-medium">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8">
                    <div class="card">
                        <div class="card-header text-center">{{ __('編輯服務地區') }}</div>

                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('admins.service_areas.update', ['hash_service_area' => \Vinkla\Hashids\Facades\Hashids::encode($service_area->id)]) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <!-- 縣市 -->
                                <div class="row mb-3">
                                    <label for="major_area" class="col-md-4 col-form-label text-md-end">{{ __('縣市') }}</label>
                                    <div class="col-md-6">
                                        <input id="major_area" type="text"
                                               class="form-control @error('major_area') is-invalid @enderror"
                                               name="major_area"
                                               value="{{ old('major_area', $service_area->major_area) }}"
                                               required placeholder="請輸入縣市" autofocus>
                                        @error('major_area')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- 鄉鎮 -->
                                <div class="row mb-3">
                                    <label for="minor_area" class="col-md-4 col-form-label text-md-end">{{ __('鄉鎮') }}</label>
                                    <div class="col-md-6">
                                        <input id="minor_area" type="text"
                                               class="form-control @error('minor_area') is-invalid @enderror"
                                               name="minor_area"
                                               value="{{ old('minor_area', $service_area->minor_area) }}"
                                               required placeholder="請輸入鄉鎮地區">
                                        @error('minor_area')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- 區域類別 -->
                                <div class="row mb-4">
                                    <label class="col-md-4 col-form-label text-md-end">{{ __('區域類別') }}</label>
                                    <div class="col-md-6 d-flex align-items-center gap-3">
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
                                </div>

                                <!-- 按鈕 -->
                                <div class="row mb-0">
                                    <div class="col-md-8 offset-md-4 d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">{{ __('更新地區') }}</button>
                                        <a href="{{ route('admins.service_areas.index') }}" class="btn btn-secondary">{{ __('返回') }}</a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
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
