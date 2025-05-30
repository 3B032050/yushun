@extends('masters.layouts.master')

@section('title', '新增設備')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-2">
                <p style="font-size: 1.8em;">
                    <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                    <a href="{{ route('admins.equipment.index') }}" class="custom-link">設備管理</a> >
                    新增設備
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
                        <div class="card-header text-center">{{ __('新增設備') }}</div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('admins.equipment.store') }}" enctype="multipart/form-data">
                                @csrf
                                @method('POST')

                                <!-- 設備名稱 -->
                                <div class="row mb-3">
                                    <label for="name" class="col-md-4 col-form-label text-md-end">
                                        <span class="required">*</span>{{ __('名稱 / Name') }}
                                    </label>
                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                               name="name" value="{{ old('name') }}" required autocomplete="name"
                                               placeholder="請輸入設備名稱" autofocus>
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- 設備數量 -->
                                <div class="row mb-3">
                                    <label for="quantity" class="col-md-4 col-form-label text-md-end">
                                        <span class="required">*</span>{{ __('數量 / Quantity') }}
                                    </label>
                                    <div class="col-md-6">
                                        <input id="quantity" type="number" class="form-control @error('quantity') is-invalid @enderror"
                                               name="quantity" value="{{ old('quantity') }}" required placeholder="請輸入設備數量">
                                        @error('quantity')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- 設備圖片 -->
                                <div class="row mb-3">
                                    <label for="photo" class="col-md-4 col-form-label text-md-end">
                                        {{ __('圖片 / Photo') }}
                                    </label>
                                    <div class="col-md-6">
                                        <input id="image_path" name="image_path" type="file" class="form-control"
                                               placeholder="請選擇圖片" onchange="previewImage(this);">
                                        <img id="image-preview" src="#" alt="圖片預覽"
                                             style="display: none; width:200px; height:200px;">
                                        @error('photo')
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

        <script>
            function previewImage(input) {
                const preview = document.getElementById('image-preview');
                const file = input.files[0];
                const reader = new FileReader();
                reader.onloadend = function () {
                    preview.src = reader.result;
                    preview.style.display = 'block';
                }
                if (file) {
                    reader.readAsDataURL(file);
                } else {
                    preview.src = '#';
                    preview.style.display = 'none';
                }
            }

            function setFontSize(size) {
                const content = document.getElementById('content');
                content.classList.remove('font-small', 'font-medium', 'font-large');
                content.classList.add('font-' + size);
            }
        </script>
@endsection
