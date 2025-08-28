@extends('masters.layouts.master')

@section('title', '豫順家居服務媒合平台')

@section('content')
    <div class="content-wrapper">

        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-2">
                <nav aria-label="breadcrumb" class="mb-2 mb-md-0 w-100 w-md-auto">
                    <ol class="breadcrumb breadcrumb-path mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('masters.service_areas.index') }}" class="custom-link">可服務地區</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">選擇服務項目</li>
                    </ol>
                </nav>

                <div class="text-size-controls btn-group btn-group-sm" role="group" aria-label="字級調整">
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
                </div>
            </div>
        </div>

        <div id="content" class="medium">
            <div class="row justify-content-center mt-3">
                <div class="col-md-6 col-12"> <!-- 調整卡片寬度：從 col-md-8 改為 col-md-6 -->
                    <div class="card">
                        <div class="card-header text-center d-flex justify-content-between align-items-center">
                            <strong>{{ __('選擇服務項目') }}</strong>
                        </div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('masters.service_areas.storeServiceSelection') }}" enctype="multipart/form-data">
                                @csrf

                                @foreach ($serviceItems as $item)
                                    <div class="mb-3">
                                        <div class="p-3 border rounded bg-light d-flex align-items-start">
                                            <input id="item_{{ $item->id }}" type="radio" name="service_item_id" value="{{ $item->id }}" class="form-check-input custom-radio mt-1 me-2">
                                            <div>
                                                <label for="item_{{ $item->id }}" class="form-check-label fw-bold d-block">{{ $item->name }}</label>
                                                <small class="text-muted">({{ $item->description }})</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="row mb-0">
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-primary w-100">
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
    </div>

    <style>
        .custom-radio {
            transform: scale(1.5);
            border: 2px solid black !important;
        }
@endsection

