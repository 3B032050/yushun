@extends('masters.layouts.master')

@section('title', '豫順清潔')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div style="margin-top: 10px;">
                <p style="font-size: 1.8em;">
                    <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                    <a href="{{ route('admins.service_areas.index') }}" class="custom-link">服務地區管理</a> >
                    新增服務地區
                </p>
            </div>
        </div>

        <div class="container d-flex justify-content-center align-items-center">
            <div class="col-md-6 col-12">
                <h2 class="text-center mb-4">新增服務地區</h2>

                <form action="{{ route('admins.service_areas.store') }}" method="POST" role="form" enctype="multipart/form-data">
                    @csrf
                    @method('POST')

                    <div class="form-group">
                        <label for="major_area">縣市</label>
                        <input type="text" class="form-control" id="major_area" name="major_area" required>
                    </div><br>

                    <div class="form-group">
                        <label for="minor_area">鄉鎮</label>
                        <input type="text" class="form-control" id="minor_area" name="minor_area" required>
                    </div><br>

                    <div class="form-group">
                        <label>區域類別</label>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="egg_yolk_area" name="area_type" value="egg_yolk" required>
                            <label class="form-check-label" for="egg_yolk_area">蛋黃區</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="egg_white_area" name="area_type" value="egg_white" required>
                            <label class="form-check-label" for="egg_white_area">蛋白區</label>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">新增地區</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
