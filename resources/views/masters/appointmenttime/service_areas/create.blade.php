@extends('masters.layouts.master')

@section('title', '豫順清潔')

@section('content')
    <div class="container">
        <h2>新增可服務地區</h2>

        <form action="{{ route('masters.service_areas.store') }}" method="POST" role="form" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="form-group">
                <label for="service_area">選擇服務地區</label>
                <select class="form-control" id="service_area" name="service_area[]" multiple>
                    @foreach($serviceAreas as $area)
                        <option value="{{ $area->id }}">{{ $area->major_area }} - {{ $area->minor_area }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">新增可服務地區</button>
        </form>
    </div>
@endsection
