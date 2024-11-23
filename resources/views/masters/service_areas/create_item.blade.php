@extends('masters.layouts.master')

@section('title', '豫順清潔')

@section('content')
    <div class="container">
        <div style="margin-top: 10px;">
            <p style="font-size: 1.8em;">
                <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                <a href="{{ route('masters.service_areas.index') }}" class="custom-link">可服務地區</a> >
                選擇服務項目
            </p>
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
                                        <input id="item_{{ $item->id }}" type="radio" name="service_item_id" value="{{ $item->id }}" class="form-check-input">
                                        <label for="item_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label><br>
                                        <label for="item_{{ $item->id }}">({{ $item->description }})</label>
                                    </div>
                                </div>
                            @endforeach

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
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
@endsection
