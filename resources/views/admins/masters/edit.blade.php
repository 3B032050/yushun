@extends('masters.layouts.master')

@section('title', '編輯師傅')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-2">
                <p class="fs-4 mb-0">
                    <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                    <a href="{{ route('admins.masters.index') }}" class="custom-link">師傅管理</a> >
                    編輯師傅
                </p>
                <div class="text-size-controls btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
                </div>
            </div>
        </div>

        <div id="content" class="medium">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header text-center">{{ __('編輯師傅') }}</div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('admins.masters.update', ['hash_master' => \Vinkla\Hashids\Facades\Hashids::encode($master->id)]) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')

                                <!-- 名稱 -->
                                <div class="row mb-3">
                                    <label for="name" class="col-md-3 col-form-label text-md-end">
                                        <span class="required">*</span>{{ __('名稱') }}
                                    </label>
                                    <div class="col-md-9">
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                               name="name" value="{{ old('name', $master->name) }}" required autocomplete="name"
                                               placeholder="請輸入師傅名稱" autofocus>
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="row mb-3">
                                    <label for="email" class="col-md-3 col-form-label text-md-end">
                                        <span class="required">*</span>{{ __('Email') }}
                                    </label>
                                    <div class="col-md-9">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                               name="email" value="{{ old('email', $master->email) }}" required placeholder="請輸入師傅Email">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- 電話 -->
                                <div class="row mb-3">
                                    <label for="phone" class="col-md-3 col-form-label text-md-end">
                                        <span class="required">*</span>{{ __('電話') }}
                                    </label>
                                    <div class="col-md-9">
                                        <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror"
                                               name="phone" value="{{ old('phone', $master->phone) }}" required placeholder="請輸入師傅電話">
                                        @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
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
        </div>
    </div>

    <style>
        .required {
            color: red;
            margin-left: 4px;
            font-weight: bold;
        }
    </style>
@endsection
