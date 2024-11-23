@extends('masters.layouts.master')

@section('title', '豫順清潔')

@section('content')
    <div class="container">
        <div style="margin-top: 10px;">
            <p style="font-size: 1.8em;">
                <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                <a href="{{ route('masters.service_areas.index') }}" class="custom-link">可服務地區</a> >
                <a href="{{ route('masters.service_areas.create_item') }}" class="custom-link">選擇服務項目</a> >
                選擇服務地區
            </p>
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
                                <label for="service_area">選擇服務地區</label>
                                <select class="form-control" id="service_area" name="service_area[]" multiple style="height: 300px">
                                    @foreach($serviceAreas as $area)
                                        <option value="{{ $area->id }}">{{ $area->major_area }} - {{ $area->minor_area }}</option>
                                    @endforeach
                                </select>
                            </div>

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
