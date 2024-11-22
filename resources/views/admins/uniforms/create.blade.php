@extends('masters.layouts.master')

@section('title', '新增制服')

@section('content')
    <div class="container">
        <div style="margin-top: 10px;">
            <p style="font-size: 1.8em;">
                <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                <a href="{{ route('admins.uniforms.index') }}" class="custom-link">制服管理</a> >
                新增制服
            </p>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('新增制服') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('admins.uniforms.store') }}" enctype="multipart/form-data">
                            @csrf
                            @method('POST')

                            <!-- 制服名稱 -->
                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">
                                    <span class="required">*</span>{{ __('名稱 / Name') }}
                                </label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="請輸入制服名稱" autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- 制服圖片 -->
                            <div class="row mb-3">
                                <label for="photo" class="col-md-4 col-form-label text-md-end">
                                    {{ __('圖片 / Photo') }}
                                </label>

                                <div class="col-md-6">
                                    <input id="image_path" name="image_path" type="file" class="form-control" placeholder="請選擇圖片" onchange="previewImage(this);">
                                    <img id="image-preview" src="#" alt="圖片預覽" style="display: none; width:200px; height:200px;" >
                                    @error('photo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- 尺寸數量 -->
                            <div class="row mb-3">
                                <label for="sizes" class="col-md-4 col-form-label text-md-end">
                                    <span class="required">*</span>{{ __('尺寸數量 / Sizes Quantity') }}
                                </label>

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-4 mb-2">
                                            <label for="size_s" class="form-label">S</label>
                                            <input id="size_s" type="number" min="0" class="form-control" name="size_s" value="{{ old('size_s', 0) }}" required>
                                        </div>
                                        <div class="col-4 mb-2">
                                            <label for="size_m" class="form-label">M</label>
                                            <input id="size_m" type="number" min="0" class="form-control" name="size_m" value="{{ old('size_m', 0) }}" required>
                                        </div>
                                        <div class="col-4 mb-2">
                                            <label for="size_l" class="form-label">L</label>
                                            <input id="size_l" type="number" min="0" class="form-control" name="size_l" value="{{ old('size_l', 0) }}" required>
                                        </div>
                                        <div class="col-4 mb-2">
                                            <label for="size_xl" class="form-label">XL</label>
                                            <input id="size_xl" type="number" min="0" class="form-control" name="size_xl" value="{{ old('size_xl', 0) }}" required>
                                        </div>
                                        <div class="col-4 mb-2">
                                            <label for="size_xxl" class="form-label">XXL</label>
                                            <input id="size_xxl" type="number" min="0" class="form-control" name="size_xxl" value="{{ old('size_xxl', 0) }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

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
    </div><br><br><br>

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
