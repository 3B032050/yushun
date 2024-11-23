@extends('masters.layouts.master')

@section('title', '編輯項目')

@section('content')
    <div class="container-fluid px-4">
        <div style="margin-top: 10px;">
            <p style="font-size: 1.8em;">
                <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                <a href="{{ route('admins.service_items.index') }}" class="custom-link">項目管理</a> >
                編輯項目
            </p>
        </div>
    </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center">{{ __('編輯項目') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('admins.service_items.update', $service_item->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <!-- 名稱欄位 -->
                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">
                                    <span class="required">*</span>{{ __('名稱 / Name') }}
                                </label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $service_item->name) }}" required autocomplete="name" placeholder="請輸入設備名稱" autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- 描述欄位 -->
                            <div class="row mb-3">
                                <label for="description" class="col-md-4 col-form-label text-md-end">
                                    <span class="required">*</span>{{ __('描述 / Description') }}
                                </label>

                                <div class="col-md-6">
                                    <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" required placeholder="請輸入項目內容">{{ old('description', $service_item->description) }}</textarea>

                                    @error('description')
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

    <!-- 樣式設定 -->
    <style>
        .required {
            color: red;
            margin-left: 5px;
            font-weight: bold;
        }
    </style>
@endsection
