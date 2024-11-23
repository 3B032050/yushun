@extends('masters.layouts.master')

@section('title', '豫順清潔')

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-6 col-12">
            <h2 class="text-center mb-4">新增服務地區</h2>

            <form action="{{ route('admins.service_areas.store') }}" method="POST" role="form" enctype="multipart/form-data">
                @csrf
                @method('POST')

                <div class="form-group">
                    <label for="major_area">縣市</label>
                    <input type="text" class="form-control" id="major_area" name="major_area" required>
                </div>

                <div class="form-group">
                    <label for="minor_area">鄉鎮</label>
                    <input type="text" class="form-control" id="minor_area" name="minor_area" required>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">新增地區</button>
                </div>
            </form>
        </div>
    </div>
@endsection

