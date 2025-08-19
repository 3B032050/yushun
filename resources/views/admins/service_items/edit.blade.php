@extends('masters.layouts.master')

@section('title', '豫順家居媒合服務平台')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center">
                <nav aria-label="breadcrumb" class="mb-2 mb-md-0">
                    <ol class="breadcrumb breadcrumb-path">
                        <li class="breadcrumb-item">
                            <a href="{{ route('masters.index') }}"><i class="fa fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admins.service_items.index') }}">服務項目管理</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">編輯項目</li>
                    </ol>
                </nav>

                <!-- 字級切換按鈕 -->
                <div class="btn-group btn-group-sm text-size-controls" role="group" aria-label="字級調整">
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
                </div>
            </div>
        </div>

        <!-- 表單區塊 -->
        <div id="content" class="font-medium">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header text-center">{{ __('編輯項目') }}</div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('admins.service_items.update', ['hash_service_item' => \Vinkla\Hashids\Facades\Hashids::encode($service_item->id)]) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')

                                <!-- 名稱 -->
                                <div class="row mb-3">
                                    <label for="name" class="col-md-4 col-form-label text-md-end">
                                        <span class="required">*</span>{{ __('名稱 / Name') }}
                                    </label>
                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                               name="name" value="{{ old('name', $service_item->name) }}" required placeholder="請輸入設備名稱">
                                        @error('name')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- 描述 -->
                                <div class="row mb-3">
                                    <label for="description" class="col-md-4 col-form-label text-md-end">
                                        <span class="required">*</span>{{ __('描述 / Description') }}
                                    </label>
                                    <div class="col-md-6">
                                    <textarea id="description" class="form-control @error('description') is-invalid @enderror"
                                              name="description" required placeholder="請輸入項目內容">{{ old('description', $service_item->description) }}</textarea>
                                        @error('description')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- 價格 -->
                                <div class="row mb-3">
                                    <label for="price" class="col-md-4 col-form-label text-md-end">
                                        <span class="required">*</span>{{ __('價格 / Price') }}
                                    </label>
                                    <div class="col-md-6">
                                        <input id="price" type="text" class="form-control @error('price') is-invalid @enderror"
                                               name="price" value="{{ old('price', $service_item->price) }}" required placeholder="請輸入價格">
                                        @error('price')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- 儲存按鈕 -->
                                <div class="row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('儲存') }}
                                        </button>
                                        <button type="button" class="btn btn-secondary" onclick="history.back();">{{ __('返回') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div> <!-- card-body -->
                    </div> <!-- card -->
                </div>
            </div>
        </div>
    </div>

    <!-- 樣式 -->
    <style>
        .breadcrumb-path {
            font-size: 1.4em;
            white-space: normal;
            word-break: break-word;
        }

        @media (max-width: 768px) {
            .breadcrumb-path {
                font-size: 1.4em;
            }
        }

        @media (max-width: 480px) {
            .breadcrumb-path {
                font-size: 1.2em;
            }
        }

        .required {
            color: red;
            margin-left: 5px;
            font-weight: bold;
        }

        .font-small {
            font-size: 0.85rem;
        }

        .font-medium {
            font-size: 1rem;
        }

        .font-large {
            font-size: 1.15rem;
        }

        .btn-group-sm .btn {
            padding: 2px 6px;
            font-size: 0.75rem;
        }
    </style>

    <!-- 字級切換腳本 -->
    <script>
        function setFontSize(size) {
            const content = document.getElementById('content');
            content.classList.remove('font-small', 'font-medium', 'font-large');
            content.classList.add('font-' + size);
        }
    </script>
@endsection
