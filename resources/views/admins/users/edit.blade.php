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
                            <a href="{{ route('admins.users.index') }}">客戶資料管理</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">編輯客戶資料</li>
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
                <div class="col-12 col-md-10 col-lg-8">
                    <div class="card">
                        <div class="card-header text-center">{{ __('編輯客戶資料') }}</div>

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

                                <form method="POST" action="{{ route('admins.users.update', ['hash_user' => \Vinkla\Hashids\Facades\Hashids::encode($user->id)]) }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')

                                    {{-- 姓名 --}}
                                    <div class="row mb-3">
                                        <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('姓名') }}</label>
                                        <div class="col-md-6">
                                            <input id="name" type="text"
                                                   class="form-control @error('name') is-invalid @enderror text-content"
                                                   name="name" value="{{ old('name', $user->name) }}" required placeholder="請輸入姓名" autofocus>
                                            @error('name')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Email --}}
                                    <div class="row mb-3">
                                        <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email') }}</label>
                                        <div class="col-md-6">
                                            <input id="email" type="email"
                                                   class="form-control @error('email') is-invalid @enderror text-content"
                                                   name="email" value="{{ old('email', $user->email) }}" required placeholder="請輸入 Email">
                                            @error('email')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- 手機 --}}
                                    <div class="row mb-3">
                                        <label for="mobile" class="col-md-4 col-form-label text-md-end">{{ __('手機號碼') }}</label>
                                        <div class="col-md-6">
                                            <input id="mobile" type="text"
                                                   class="form-control @error('mobile') is-invalid @enderror text-content"
                                                   name="mobile" value="{{ old('mobile', $user->mobile) }}" required placeholder="請輸入手機號碼">
                                            @error('mobile')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- 電話 --}}
                                    <div class="row mb-3">
                                        <label for="phone" class="col-md-4 col-form-label text-md-end">{{ __('電話') }}</label>
                                        <div class="col-md-6">
                                            <input id="phone" type="text"
                                                   class="form-control @error('phone') is-invalid @enderror text-content"
                                                   name="phone" value="{{ old('phone', $user->phone) }}" placeholder="請輸入電話（可留空）">
                                            @error('phone')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- 地址 --}}
                                    <div class="row mb-3">
                                        <label for="address" class="col-md-4 col-form-label text-md-end">{{ __('地址') }}</label>
                                        <div class="col-md-6">
                                            <input id="address" type="text"
                                                   class="form-control @error('address') is-invalid @enderror text-content"
                                                   name="address" value="{{ old('address', $user->address) }}" required placeholder="請輸入地址">
                                            @error('address')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- LINE ID --}}
                                    <div class="row mb-3">
                                        <label for="line_id" class="col-md-4 col-form-label text-md-end">{{ __('LINE ID') }}</label>
                                        <div class="col-md-6">
                                            <input id="line_id" type="text"
                                                   class="form-control @error('line_id') is-invalid @enderror text-content"
                                                   name="line_id" value="{{ old('line_id', $user->line_id) }}" placeholder="請輸入 LINE ID（可留空）">
                                            @error('line_id')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- 客戶類型 (is_recurring) --}}
                                    <div class="row mb-3">
                                        <label for="is_recurring" class="col-md-4 col-form-label text-md-end">客戶類型</label>
                                        <div class="col-md-6">
                                            <select id="is_recurring" name="is_recurring"
                                                    class="form-select @error('is_recurring') is-invalid @enderror">
                                                <option value="">請選擇客戶類型</option>
                                                <option value="0" {{ old('is_recurring', $user->is_recurring) == 0 ? 'selected' : '' }}>定期</option>
                                                <option value="1" {{ old('is_recurring', $user->is_recurring) == 1 ? 'selected' : '' }}>非定期</option>
                                            </select>
                                            @error('is_recurring')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
                                        </div>
                                    </div>

                                {{-- 按鈕列 --}}
                                <div class="row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary">{{ __('儲存') }}</button>
                                        <button type="button" class="btn btn-danger" onclick="window.location.href='{{ route('admins.users.index') }}'">
                                            返回
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div> {{-- card-body --}}
                    </div>
                </div>
            </div>
        </div>
@endsection
