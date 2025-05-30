@extends('masters.layouts.master')

@section('title', '編輯設備')

@section('content')
    <div id="content" class="medium">
        <div class="content-wrapper">
            <div class="container-fluid px-4">
                <div style="margin-top: 10px;">
                    <p style="font-size: 1.8em;">
                        <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                        <a href="{{ route('admins.equipment.index') }}" class="custom-link">設備管理</a> >
                        編輯設備
                    </p>
                    <div class="text-size-controls btn-group btn-group-sm" role="group" aria-label="字級調整">
                        <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                        <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                        <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header text-center">{{ __('編輯設備') }}</div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('admins.equipment.update',['hash_equipment' => \Vinkla\Hashids\Facades\Hashids::encode($equipment->id)]) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')

                                <!-- 設備名稱 -->
                                <div class="row mb-3">
                                    <label for="name" class="col-md-4 col-form-label text-md-end">
                                        <span class="required">*</span>{{ __('名稱 / Name') }}
                                    </label>

                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $equipment->name) }}" required autocomplete="name" placeholder="請輸入設備名稱" autofocus>

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
                                        <input id="quantity" type="number" class="form-control @error('quantity') is-invalid @enderror" name="quantity" value="{{ old('quantity', $equipment->quantity) }}" required placeholder="請輸入設備數量">

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
                                        <input id="image_path" name="image_path" type="file" class="form-control" value="{{ old('image_url', $equipment->photo) }}" onchange="previewImage(this);">
                                        <img id="image-preview" src="{{ $equipment->photo ? asset('storage/equipments/' . $equipment->photo) : '#' }}" alt="圖片預覽" style="display: {{ $equipment->photo ? 'block' : 'none' }}; width:200px; height:200px;">
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
    </div>
    <style>
        .required {
            color: red;
            margin-left: 5px;
            font-weight: bold;
        }
    </style>

    <script>
        function previewImage(input) {
            var preview = document.getElementById('image-preview');
            var file = input.files[0];
            var reader = new FileReader();
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
    </script>
@endsection
