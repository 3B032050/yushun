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
            <div class="d-flex justify-content-between align-items-center mt-2">
                <p class="fs-4 mb-0">
                    <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                    <a href="{{ route('masters.service_areas.index') }}" class="custom-link">可服務地區</a> >
                    選擇服務項目
                </p>

                <div class="text-size-controls btn-group btn-group-sm" role="group" aria-label="字級調整">
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
                </div>
            </div>
        </div>

        <div id="content" class="medium">
            <div class="row justify-content-center mt-3">
                <div class="col-md-8 col-12">
                    <div class="card">
                        <div class="card-header text-center">{{ __('選擇服務項目') }}</div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('masters.service_areas.storeServiceSelection') }}" enctype="multipart/form-data">
                                @csrf

                                @foreach ($serviceItems as $item)
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="p-3 border rounded bg-light">
                                                <input id="item_{{ $item->id }}" type="radio" name="service_item_id" value="{{ $item->id }}" class="form-check-input custom-radio">
                                                <label for="item_{{ $item->id }}" class="form-check-label fw-bold"><h4>{{ $item->name }}</h4></label>
                                                <br>
                                                <label for="item_{{ $item->id }}" class="text-muted small">({{ $item->description }})</label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="row mb-0">
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-primary w-50">
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

