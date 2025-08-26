@extends('masters.layouts.master')

@section('title','豫順家居服務媒合平台')
@section('content')

    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-2">
                <nav aria-label="breadcrumb" class="mb-2 mb-md-0 w-100 w-md-auto">
                    <ol class="breadcrumb breadcrumb-path mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('masters.index') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item">個人資料</li>
                    </ol>
                </nav>

                <div class="text-size-controls btn-group btn-group-sm" role="group" aria-label="字程調整">
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
                </div>
            </div>
        </div>

        <div id="content" class="medium">
            <div class="row justify-content-center mt-3">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header text-center">{{ __('個人資料') }}</div>

                        <div class="card-body">
                            <script>
                                function setFontSize(size) {
                                    const content = document.getElementById('content');
                                    content.className = size;
                                }
                            </script>

                            <!-- Existing content continues here -->
                            <!-- 基本資料 -->
                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end"><span class="required"></span>{{ __('姓名') }}</label>
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" value="{{ $master->name }}" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end"><span class="required"></span>{{ __('信箱') }}</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" value="{{ $master->email }}" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="phone" class="col-md-4 col-form-label text-md-end"><span class="required"></span>{{ __('電話') }}</label>
                                <div class="col-md-6">
                                    <input id="phone" type="text" class="form-control" value="{{ $master->phone }}" readonly>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <a href="{{ route('admins.personal_information.edit') }}" class="btn btn-primary">
                                        <i class="fa fa-edit"></i> 編輯個人資料
                                    </a>
                                </div>
                            </div>

                            <hr>
                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label text-md-end">{{ __('入職時間') }}</label>
                                <div class="col-md-6">
                                    <input type="text" readonly
                                           class="form-control-plaintext"
                                           value="{{ $master->created_at?->format('Y-m-d') }}">
                                </div>
                            </div>

{{--                            <div class="row mb-3">--}}
{{--                                <label class="col-md-4 col-form-label text-md-end">{{ __('累積總工時') }}</label>--}}
{{--                                <div class="col-md-6 pt-2">--}}
{{--                                    <div class="form-control-plaintext">{{ $master->total_hours }}</div>--}}
{{--                                </div>--}}
{{--                            </div>--}}


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
