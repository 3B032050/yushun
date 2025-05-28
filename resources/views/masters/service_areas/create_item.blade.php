@extends('masters.layouts.master')

@section('title', '豫順家居媒合服務平台')

@section('content')
    <div class="content-wrapper">
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="container-fluid px-4">
            <div style="margin-top: 10px;">
                <p style="font-size: 1.8em;">
                    <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                    <a href="{{ route('masters.service_areas.index') }}" class="custom-link">可服務地區</a> >
                    選擇服務項目
                </p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8 col-12">
                <div class="card">
                    <div class="card-header text-center">{{ __('選擇服務項目') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('masters.service_areas.storeServiceSelection') }}" enctype="multipart/form-data">
                            @csrf

                            @foreach ($serviceItems as $item)
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded bg-light"> <!-- 新增背景、邊框、內距 -->
                                            <input id="item_{{ $item->id }}" type="radio" name="service_item_id" value="{{ $item->id }}" class="form-check-input custom-radio">
                                            <label for="item_{{ $item->id }}" class="form-check-label fw-bold"><h4>{{ $item->name }}</h4></label>
                                            <br>
                                            <label for="item_{{ $item->id }}" class="text-muted small">({{ $item->description }})</label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="row mb-0">
                                <div class="col-12 text-center"> <!-- 讓按鈕在 col-12 並置中 -->
                                    <button type="submit" class="btn btn-primary w-50"> <!-- 調整按鈕寬度 -->
                                        {{ __('確認') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<style>
    .custom-radio {
        transform: scale(1.5);
        border: 2px solid black !important;
    }
</style>
