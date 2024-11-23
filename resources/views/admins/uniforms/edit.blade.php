@extends('masters.layouts.master')

@section('title', '編輯制服')

@section('content')
    <div class="container-fluid px-4">
        <div style="margin-top: 10px;">
            <p style="font-size: 1.8em;">
                <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                <a href="{{ route('admins.uniforms.index') }}" class="custom-link">制服管理</a> >
                編輯制服
            </p>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('編輯制服') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admins.uniforms.update', $uniform->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <!-- 制服名稱 -->
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">
                                <span class="required">*</span>{{ __('名稱 / Name') }}
                            </label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                       name="name" value="{{ old('name', $uniform->name) }}" required autofocus>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="photo" class="col-md-4 col-form-label text-md-end">
                                {{ __('圖片 / Photo') }}
                            </label>

                            <div class="col-md-6">
                                <input id="image_path" name="image_path" type="file" class="form-control" value="{{ old('image_url',$uniform->photo ) }}" onchange="previewImage(this);">
                                <img id="image-preview" src="{{ $uniform->photo ? asset('storage/uniforms/' . $uniform->photo) : '#' }}" alt="圖片預覽" style="display: {{ $uniform->photo ? 'block' : 'none' }}; width:200px; height:200px;">
                                @error('photo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <!-- 尺寸與數量 -->
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">
                                {{ __('尺寸數量 / Sizes Quantity') }}
                            </label>
                            <div class="col-md-6">
                                @php
                                    $sizes = ['S', 'M', 'L', 'XL', 'XXL'];
                                @endphp
                                @foreach($sizes as $size)
                                    <div class="mb-2">
                                        <label>{{ $size }} 尺寸</label>
                                        <input type="number" name="size_{{ strtolower($size) }}" class="form-control @error('size_' . strtolower($size)) is-invalid @enderror"
                                               value="{{ old('size_' . strtolower($size), $uniform->$size) }}" min="0">
                                        @error('size_' . strtolower($size))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- 提交按鈕 -->
                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('儲存') }}
                                </button>
                                <a href="{{ route('admins.uniforms.index') }}" class="btn btn-secondary">
                                    {{ __('取消') }}
                                </a>
                            </div>
                        </div>
                    </form>
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
