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
                            <a href="{{ route('admins.service_items.index') }}">服務項目資料管理</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">新增項目資料</li>
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
                        <div class="card-header">{{ __('新增服務項目資料') }}</div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('admins.service_items.store') }}" enctype="multipart/form-data">
                                @csrf
                                @method('POST')

                                <!-- 名稱 -->
                                <div class="row mb-3">
                                    <label for="name" class="col-md-4 col-form-label text-md-end">
                                        {{ __('名稱') }}
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
                                        {{ __('服務項目說明') }}
                                    </label>
                                    <div class="col-md-6">
                                        <textarea id="description" class="form-control @error('description') is-invalid @enderror"
                                                  name="description" required placeholder="請輸入服務項目說明">{{ old('description') }}</textarea>
                                        @error('description')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- 價格 -->
                                <div class="row mb-3">
                                    <label for="price" class="col-md-4 col-form-label text-md-end">
                                        {{ __('價格') }}
                                    </label>
                                    <div class="col-md-6">
                                        <input id="price" type="number" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}"
                                               required placeholder="請輸入價格(上限19999)" min="0" max="19999" step="1" inputmode="numeric"
                                               oninput="this.value = this.value.replace(/[^0-9]/g,''); if (this.value !== '' && +this.value > 19999) this.value = 19999;">
                                        @error('price')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- 儲存按鈕 -->
                                <div class="row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary">{{ __('儲存') }}</button>
                                         <button type="button" class="btn btn-danger" onclick="window.location.href='{{ route('admins.service_items.index') }}'">
                                            返回
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

@endsection
