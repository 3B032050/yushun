@extends('users.layouts.master')

@section('title','個人資料')

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
                    個人資料
                </p>
            </div>
        </div>

        <section id="location"><br>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">{{ __('個人資料') }}</div>

                            <div class="card-body">
                                <form method="POST" action="{{ route('users.personal_information.update',$user->id) }}">
                                    @csrf
                                    @method('PATCH')

                                    <div class="row mb-3">
                                        <label for="name" class="col-md-4 col-form-label text-md-end"><span class="required"></span>{{ __('姓名：') }}</label>

                                        <div class="col-md-6">
                                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $user->name }}" required autocomplete="name" placeholder="必填">

                                            @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="email" class="col-md-4 col-form-label text-md-end"><span class="required"></span>{{ __('信箱：') }}</label>

                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" required autocomplete="email" placeholder="必填">

                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="mobile" class="col-md-4 col-form-label text-md-end">{{ __('手機號碼：') }}</label>

                                        <div class="col-md-6">
                                            <input id="mobile" type="text" class="form-control @error('mobile') is-invalid @enderror"
                                                   name="mobile" value="{{ $user->mobile }}" required placeholder="必填">

                                            @error('mobile')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="phone" class="col-md-4 col-form-label text-md-end">{{ __('市話：') }}</label>

                                        <div class="col-md-6">
                                            <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror"
                                                   name="phone" value="{{ $user->phone }}" placeholder="選填">

                                            @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="address" class="col-md-4 col-form-label text-md-end">{{ __('地址：') }}</label>

                                        <div class="col-md-6">
                                            <input id="address" type="text" class="form-control @error('address') is-invalid @enderror"
                                                   name="address" value="{{ $user->address }}" required placeholder="必填">

                                            @error('address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="line_id" class="col-md-4 col-form-label text-md-end">{{ __('LINE ID：') }}</label>

                                        <div class="col-md-6">
                                            <input id="line_id" type="text" class="form-control @error('line_id') is-invalid @enderror"
                                                   name="line_id" value="{{ $user->line_id }}" placeholder="選填">

                                            @error('line_id')
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
        </section>
    </div>
    <style>
        .required {
            color: red;
            margin-left: 5px;
            font-weight: bold;
        }
    </style>
@endsection
