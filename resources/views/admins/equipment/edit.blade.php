@extends('masters.layouts.master')

@section('title', '編輯設備')

@section('content')
    <div class="container-fluid px-3 py-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <nav aria-label="breadcrumb" class="mb-2 mb-md-0 w-100 w-md-auto">
                <ol class="breadcrumb breadcrumb-path">
                    <li class="breadcrumb-item"><a href="{{ route('masters.index') }}"><i class="fa fa-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admins.equipment.index') }}">設備管理</a></li>
                    <li class="breadcrumb-item active" aria-current="page"> 編輯設備</li>
                </ol>
            </nav>
            <div class="btn-group btn-group-sm text-size-controls" role="group" aria-label="字級調整">
                <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
            </div>
        </div>

        <div id="content" class="font-medium">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header text-center h5">{{ __('編輯設備') }}</div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admins.equipment.update', ['hash_equipment' => \Vinkla\Hashids\Facades\Hashids::encode($equipment->id)]) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')

                                <!-- 設備名稱 -->
                                <div class="mb-3 row">
                                    <label for="name" class="col-md-4 col-form-label text-md-end">
                                        <span class="required">*</span>{{ __('名稱 / Name') }}
                                    </label>
                                    <div class="col-md-8">
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                               name="name" value="{{ old('name', $equipment->name) }}" required
                                               placeholder="請輸入設備名稱" autocomplete="name" autofocus>
                                        @error('name')
                                        <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- 設備數量 -->
                                <div class="mb-3 row">
                                    <label for="quantity" class="col-md-4 col-form-label text-md-end">
                                        <span class="required">*</span>{{ __('數量 / Quantity') }}
                                    </label>
                                    <div class="col-md-8">
                                        <input id="quantity" type="number" class="form-control @error('quantity') is-invalid @enderror"
                                               name="quantity" value="{{ old('quantity', $equipment->quantity) }}" required
                                               placeholder="請輸入設備數量">
                                        @error('quantity')
                                        <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- 設備圖片 -->
                                <div class="mb-3 row">
                                    <label for="image_path" class="col-md-4 col-form-label text-md-end">
                                        {{ __('圖片 / Photo') }}
                                    </label>
                                    <div class="col-md-8">
                                        <input id="image_path" name="image_path" type="file" class="form-control"
                                               onchange="previewImage(this);">
                                        <img id="image-preview"
                                             src="{{ $equipment->photo ? asset('storage/equipments/' . $equipment->photo) : '#' }}"
                                             alt="圖片預覽"
                                             class="img-thumbnail mt-2"
                                             style="display: {{ $equipment->photo ? 'block' : 'none' }}; max-width: 100%; height: auto;">
                                        @error('photo')
                                        <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- 儲存按鈕 -->
                                <div class="row">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary w-100">
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
            font-weight: bold;
            margin-left: 5px;
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

        .text-size-controls .btn {
            padding: 0.25rem 0.5rem;
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
