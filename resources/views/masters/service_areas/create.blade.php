@extends('masters.layouts.master')

@section('title', '豫順清潔')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div style="margin-top: 10px;">
                <p style="font-size: 1.8em;">
                    <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                    <a href="{{ route('masters.service_areas.index') }}" class="custom-link">可服務地區</a> >
                    <a href="{{ route('masters.service_areas.create_item') }}" class="custom-link">選擇服務項目</a> >
                    選擇服務地區
                </p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8 col-12">
                <div class="card">
                    <div class="card-header text-center">{{ __('選擇服務項目') }}</div>

                    <div class="card-body">
                        <form action="{{ route('masters.service_areas.store') }}" method="POST" role="form" enctype="multipart/form-data">
                            @csrf
                            @method('POST')

                            <div class="form-group">
                                <label for="service_area" class="fw-bold">選擇服務地區</label>

                                <div class="border p-3 rounded bg-light">
                                    <div class="row">
                                        @foreach($serviceAreas as $area)
                                            <div class="col-md-4 col-sm-6 col-12">
                                                <div class="form-check">
                                                    <input class="form-check-input custom-checkbox"
                                                           type="checkbox"
                                                           name="service_area[]"
                                                           value="{{ $area->id }}"
                                                           id="area_{{ $area->id }}"
                                                           @if(in_array($area->id, $selectedAreas)) checked @endif> <!-- 判斷是否已選擇 -->
                                                    <label class="form-check-label" for="area_{{ $area->id }}">
                                                        {{ $area->major_area }} - {{ $area->minor_area }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div><br>

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
@endsection

<style>
    .custom-checkbox {
        transform: scale(1.3); /* 放大 checkbox */
        border: 2px solid black !important; /* 黑色邊框 */
    }
</style>
