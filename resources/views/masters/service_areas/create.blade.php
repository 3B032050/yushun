@extends('masters.layouts.master')

@section('title', '豫順家居媒合服務平台')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-2">
                <p class="fs-4 mb-0">
                    <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                    <a href="{{ route('masters.service_areas.index') }}" class="custom-link">可服務地區</a> >
                    <a href="{{ route('masters.service_areas.create_item') }}" class="custom-link">選擇服務項目</a> >
                    選擇服務地區
                </p>

                <div class="text-size-controls btn-group btn-group-sm">
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
                        <div class="card-header text-center"><strong>{{ __('選擇服務地區') }}</strong></div>

                        <div class="card-body">
                            <form action="{{ route('masters.service_areas.store') }}" method="POST" role="form" enctype="multipart/form-data">
                                @csrf
                                @method('POST')

                                <div class="form-group">
                                    <div class="border p-3 rounded bg-light">
                                        <div class="row">
                                            @php
                                                $currentMajorArea = null;
                                            @endphp

                                            @foreach($serviceAreas as $area)
                                                @if ($currentMajorArea !== $area->major_area)
                                                    @if ($currentMajorArea !== null)
                                                        <div class="col-12">
                                                            <hr class="my-2">
                                                        </div>
                                                    @endif
                                                    @php
                                                        $currentMajorArea = $area->major_area;
                                                    @endphp
                                                    <div class="col-12">
                                                        <h5 class="fw-bold text-primary">{{ $currentMajorArea }}</h5>
                                                    </div>
                                                @endif

                                                <div class="col-md-4 col-sm-6 col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input custom-checkbox"
                                                               type="checkbox"
                                                               name="service_area[]"
                                                               value="{{ $area->id }}"
                                                               id="area_{{ $area->id }}"
                                                               @if(in_array($area->id, $selectedAreas)) checked @endif>
                                                        <label class="form-check-label" for="area_{{ $area->id }}">
                                                            {{ $area->minor_area }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <br>

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
@endsection

<style>
    .custom-checkbox {
        transform: scale(1.3);
        border: 2px solid black !important;
    }
</style>

