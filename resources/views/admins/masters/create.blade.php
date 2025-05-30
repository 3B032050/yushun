@extends('masters.layouts.master')

@section('title', '新增師傅')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center" style="margin-top: 10px;">
                <p style="font-size: 1.8em;">
                    <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                    <a href="{{ route('admins.masters.index') }}" class="custom-link">師傅管理</a> >
                    新增師傅
                </p>
                <div class="text-size-controls">
                    <button onclick="setFontSize('small')" class="btn btn-outline-secondary btn-sm">小</button>
                    <button onclick="setFontSize('medium')" class="btn btn-outline-secondary btn-sm">中</button>
                    <button onclick="setFontSize('large')" class="btn btn-outline-secondary btn-sm">大</button>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" id="content">
                    <div class="card-header text-center">{{ __('師傅管理') }}</div>

                    <div class="card-body text-content">
                        <form method="POST" action="{{ route('admins.masters.store') }}" enctype="multipart/form-data">
                            @csrf
                            @method('POST')

                            <div class="row mb-3">
                                <label for="name" class="col-md-3 col-form-label text-md-end">
                                    <span class="required">*</span>{{ __('名稱') }}
                                </label>
                                <div class="col-md-9">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror text-content"
                                           name="name" value="{{ old('name') }}" required autocomplete="name"
                                           placeholder="請輸入師傅名稱" autofocus>
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-3 col-form-label text-md-end">
                                    <span class="required">*</span>{{ __('Email') }}
                                </label>
                                <div class="col-md-9">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror text-content"
                                           name="email" value="{{ old('email') }}" required
                                           placeholder="請輸入師傅Email">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="phone" class="col-md-3 col-form-label text-md-end">
                                    <span class="required">*</span>{{ __('電話') }}
                                </label>
                                <div class="col-md-9">
                                    <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror text-content"
                                           name="phone" value="{{ old('phone') }}" required
                                           placeholder="請輸入師傅電話">
                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-9 offset-md-3">
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

    <style>
        .required {
            color: red;
            margin-left: 5px;
            font-weight: bold;
        }

        .text-size-controls {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* 字級樣式 */
        #content.small .text-content {
            font-size: 14px;
        }

        #content.medium .text-content {
            font-size: 18px;
        }

        #content.large .text-content {
            font-size: 24px;
        }
    </style>

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
