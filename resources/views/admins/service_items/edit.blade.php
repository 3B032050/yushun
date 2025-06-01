@extends('masters.layouts.master')

@section('title', '編輯服務項目')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-2">
                <p style="font-size: 1.8em;">
                    <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                    <a href="{{ route('admins.service_items.index') }}" class="custom-link">項目管理</a> >
                    編輯項目
                </p>
                <div class="btn-group btn-group-sm text-size-controls" role="group" aria-label="字級調整">
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
                                               name="name" value="{{ old('name', $service_item->name) }}" required
                                               placeholder="請輸入設備名稱">
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
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
                                              name="description" required
                                              placeholder="請輸入項目內容">{{ old('description', $service_item->description) }}</textarea>
                                        @error('description')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
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
                                               name="price" value="{{ old('price', $service_item->price) }}" required
                                               placeholder="請輸入價格">
                                        @error('price')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
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

        <!-- 樣式區塊 -->
        <style>
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
