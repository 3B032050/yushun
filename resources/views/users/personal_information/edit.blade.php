@extends('users.layouts.master')

@section('title','編輯個人資料')

@section('content')
    <div class="content-wrapper">
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="container-fluid px-4">
            <div style="margin-top: 10px;">
                <p style="font-size: 1.8em;">
                    <a href="{{ route('users.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                    <a href="{{ route('users.personal_information.personal_index') }}" class="custom-link">個人資料</a> >
                    編輯
                </p>
            </div>
        </div>
            <div style="display: flex; justify-content: flex-end; margin-top: 10px;">
                <div class="text-size-controls">
                    <button onclick="setFontSize('small')">小</button>
                    <button onclick="setFontSize('medium')">中</button>
                    <button onclick="setFontSize('large')">大</button>
                </div>
            </div>
            <div id="content" class="medium">
            <section id="location"><br>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-10 col-lg-8">
                            <div class="card">
                                <div class="card-header">{{ __('編輯個人資料') }}</div>

                                <div class="card-body">
                                    <form method="POST" action="{{ route('users.personal_information.update', ['hash_user' => \Vinkla\Hashids\Facades\Hashids::encode($user->id)]) }}">
                                        @csrf
                                        @method('PATCH')

                                        <div class="row mb-3">
                                            <label for="name" class="col-md-4 col-form-label text-md-end">姓名：</label>
                                            <div class="col-md-6">
                                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                                       name="name" value="{{ old('name', $user->name) }}" required>
                                                @error('name')
                                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="email" class="col-md-4 col-form-label text-md-end">信箱：</label>
                                            <div class="col-md-6">
                                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                                       name="email" value="{{ old('email', $user->email) }}" required>
                                                @error('email')
                                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="mobile" class="col-md-4 col-form-label text-md-end">手機號碼：</label>
                                            <div class="col-md-6">
                                                <input id="mobile" type="text" class="form-control @error('mobile') is-invalid @enderror"
                                                       name="mobile" value="{{ old('mobile', $user->mobile) }}" required>
                                                @error('mobile')
                                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="phone" class="col-md-4 col-form-label text-md-end">市話：</label>
                                            <div class="col-md-6">
                                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror"
                                                       name="phone" value="{{ old('phone', $user->phone) }}">
                                                @error('phone')
                                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="address" class="col-md-4 col-form-label text-md-end">地址：</label>
                                            <div class="col-md-6">
                                                <input id="address" type="text" class="form-control @error('address') is-invalid @enderror"
                                                       name="address" value="{{ old('address', $user->address) }}" required>
                                                @error('address')
                                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="line_id" class="col-md-4 col-form-label text-md-end">LINE ID：</label>
                                            <div class="col-md-6">
                                                <input id="line_id" type="text" class="form-control @error('line_id') is-invalid @enderror"
                                                       name="line_id" value="{{ old('line_id', $user->line_id) }}">
                                                @error('line_id')
                                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-0">
                                            <div class="col-md-8 offset-md-4">
                                                <button type="submit" class="btn btn-success">儲存</button>
                                                <a href="{{ route('users.personal_information.personal_index') }}" class="btn btn-secondary">取消</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        #content.small { font-size: 14px; }
        #content.medium { font-size: 22px; }
        #content.large { font-size: 30px; }

        .text-size-controls {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 999;
        }

        .text-size-controls button {
            margin-left: 5px;
            padding: 5px 10px;
            font-size: 14px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function setFontSize(size) {
            const content = document.getElementById('content');
            content.className = size;
        }
    </script>
@endpush
