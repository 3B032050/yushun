@extends('masters.layouts.master')

@section('title', '豫順家居媒合服務平台')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center">
                <nav aria-label="breadcrumb" class="mb-2 mb-md-0">
                    <ol class="breadcrumb breadcrumb-path">
                        <li class="breadcrumb-item"><a href="{{ route('masters.index') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admins.masters.index') }}">師傅管理</a></li>
                        <li class="breadcrumb-item active" aria-current="page">新增師傅</li>
                    </ol>
                </nav>
                <div class="text-size-controls btn-group btn-group-sm" role="group" aria-label="字級調整">
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-3">
            <div class="col-md-8">
                <div class="card" id="content">
                    <div class="card-header text-center">{{ __('師傅管理') }}</div>

                    <div class="card-body text-content">
                        <form method="POST" action="{{ route('admins.masters.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row mb-3">
                                <label for="name" class="col-md-3 col-form-label text-md-end">
                                    <span class="required">*</span>{{ __('名稱') }}
                                </label>
                                <div class="col-md-9">
                                    <input id="name" type="text"
                                           class="form-control @error('name') is-invalid @enderror text-content"
                                           name="name" value="{{ old('name') }}" required autocomplete="name"
                                           placeholder="請輸入師傅名稱" autofocus>
                                    @error('name')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-3 col-form-label text-md-end">
                                    <span class="required">*</span>{{ __('Email') }}
                                </label>
                                <div class="col-md-9">
                                    <input id="email" type="email"
                                           class="form-control @error('email') is-invalid @enderror text-content"
                                           name="email" value="{{ old('email') }}" required
                                           placeholder="請輸入師傅Email">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="phone" class="col-md-3 col-form-label text-md-end">
                                    <span class="required">*</span>{{ __('電話') }}
                                </label>
                                <div class="col-md-9">
                                    <input id="phone" type="text"
                                           class="form-control @error('phone') is-invalid @enderror text-content"
                                           name="phone" value="{{ old('phone') }}" required
                                           placeholder="請輸入師傅電話">
                                    @error('phone')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-12 d-flex justify-content-center gap-2">
                                    <button type="submit" class="btn btn-primary">{{ __('儲存') }}</button>
                                    <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                                        {{ __('取消') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            function setFontSize(size) {
                const content = document.getElementById('content');
                content.className = size;
                localStorage.setItem('preferredFontSize', size);
            }

            document.addEventListener('DOMContentLoaded', () => {
                const savedSize = localStorage.getItem('preferredFontSize') || 'medium';
                document.getElementById('content').className = savedSize;
            });
        </script>
    @endpush
@endsection
