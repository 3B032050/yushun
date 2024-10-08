@extends('masters.layouts.master')

@section('title', '豫順清潔')

@section('content')
    <div class="container">
        <h2>編輯服務地區</h2>

        <form action="{{ route('admins.service_areas.update', ['service_area' => $service_area->id]) }}" method="POST" role="form" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label for="major_area">縣市</label>
                <input type="text" class="form-control" id="major_area" name="major_area"value="{{ old('major_area',$service_area->major_area) }}" required>
            </div>

            <div class="form-group">
                <label for="minor_area">鄉鎮</label>
                <input type="text" class="form-control" id="minor_area" name="minor_area"value="{{ old('minor_area',$service_area->minor_area) }}" required>
            </div>

            <button type="submit" class="btn btn-primary">更新地區</button>
        </form>
    </div>
@endsection
