@extends('masters.layouts.master')

@section('title', '豫順家居媒合服務平台')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-2">
                <nav aria-label="breadcrumb" class="mb-2 mb-md-0 w-100 w-md-auto">
                    <ol class="breadcrumb breadcrumb-path mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('masters.index') }}"><i class="fa fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admins.service_items.index') }}">服務項目管理</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">新增項目</li>
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
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('新增設備') }}</div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('admins.service_items.store') }}" enctype="multipart/form-data">
                                @csrf
                                @method('POST')

                                <!-- 名稱 -->
                                <div class="row mb-3">
                                    <label for="name" class="col-md-4 col-form-label text-md-end">
                                        <span class="required">*</span>{{ __('名稱 / Name') }}
                                    </label>
                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                               name="name" value="{{ old('name') }}" required placeholder="請輸入項目名稱" autofocus>
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
                                                  name="description" required placeholder="請輸入項目內容">{{ old('description') }}</textarea>
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
                                               name="price" value="{{ old('price') }}" required placeholder="請輸入價格">
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

        .required {
            color: red;
            margin-left: 5px;
            font-weight: bold;
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

        #content.font-small .form-control {
            font-size: 0.85rem;
        }

        #content.font-medium .form-control {
            font-size: 1rem;
        }

        #content.font-large .form-control {
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
