@extends('masters.layouts.master')

@section('title', '豫順家居媒合服務平台')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-2 flex-wrap">
                <nav aria-label="breadcrumb" class="mb-2 mb-md-0 w-100 w-md-auto">
                    <ol class="breadcrumb breadcrumb-path">
                        <li class="breadcrumb-item"><a href="{{ route('masters.index') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admins.equipment.index') }}">設備管理</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> 新增設備</li>
                    </ol>
                </nav>
                <div class="btn-group btn-group-sm text-size-controls mt-2" role="group" aria-label="字級調整">
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
                        <div class="card-header text-center">{{ __('新增設備') }}</div>

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

                            <form method="POST" action="{{ route('admins.equipment.store') }}" enctype="multipart/form-data">
                                @csrf
                                @method('POST')

                                <!-- 設備名稱 -->
                                <div class="row mb-3">
                                    <label for="storage_location" class="col-md-4 col-form-label text-md-end">{{ __('名稱') }}</label>
                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                               name="name" value="{{ old('name') }}" required autocomplete="name"
                                               placeholder="請輸入設備名稱" autofocus>
                                        @error('name')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- 設備數量 -->
                                <div class="row mb-3">
                                    <label for="storage_location" class="col-md-4 col-form-label text-md-end">{{ __('數量') }}</label>
                                    <div class="col-md-6">
                                        <input id="quantity" type="number" class="form-control @error('quantity') is-invalid @enderror"
                                               name="quantity" value="{{ old('quantity') }}" required placeholder="請輸入設備數量">
                                        @error('quantity')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- 倉儲位置 -->
                                <div class="row mb-3">
                                    <label for="storage_location" class="col-md-4 col-form-label text-md-end">{{ __('倉儲位置') }}</label>
                                    <div class="col-md-6">
                                        <input id="storage_location" type="text" name="storage_location" class="form-control @error('storage_location') is-invalid @enderror" value="{{ old('storage_location') }}" required placeholder="請輸入倉儲位置">
                                        @error('storage_location')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- 設備圖片 -->
                                <div class="row mb-3">
                                    <label for="photo" class="col-md-4 col-form-label text-md-end">{{ __('圖片') }}</label>
                                    <div class="col-md-6">
                                        <input id="image_path" name="image_path" type="file" class="form-control @error('image_path') is-invalid @enderror" onchange="previewImage(this);">
                                        <img id="image-preview" src="#" alt="圖片預覽" class="img-fluid mt-2" style="display: none; max-height: 300px;">
                                        @error('image_path')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- 儲存按鈕 -->
                                <div class="row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary">{{ __('儲存') }}</button>
                                    </div>
                                </div>
                            </form>
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

        <script>
            function previewImage(input) {
                const preview = document.getElementById('image-preview');
                const file = input.files[0];
                const reader = new FileReader();
                reader.onloadend = function () {
                    preview.src = reader.result;
                    preview.style.display = 'block';
                };
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
            document.getElementById('image_path').addEventListener('change', function () {
                const file = this.files[0];
                if (file && file.size > 2 * 1024 * 1024) { // 2MB
                    alert('圖片大小不能超過 2MB');
                    this.value = ''; // 清空選擇
                }
            });
        </script>
@endsection
