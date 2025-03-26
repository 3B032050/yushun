@extends('masters.layouts.master')

@section('title', '豫順清潔')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div style="margin-top: 10px;">
                <p style="font-size: 1.8em;">
                    <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                    <a href="{{ route('admins.service_areas.index') }}" class="custom-link">服務地區管理</a> >
                    編輯服務地區
                </p>
            </div>
        </div>

        <div class="container d-flex justify-content-center align-items-center">
            <div class="col-md-6 col-12">
                <h2 class="text-center mb-4">編輯服務地區</h2>

                <form action="{{ route('admins.service_areas.update', ['service_area' => $service_area->id]) }}" method="POST" role="form">
                    @csrf
                    @method('PATCH')

                    <div class="form-group">
                        <label for="major_area">縣市</label>
                        <input type="text" class="form-control" id="major_area" name="major_area" value="{{ old('major_area', $service_area->major_area) }}" required>
                    </div><br>

                    <div class="form-group">
                        <label for="minor_area">鄉鎮</label>
                        <input type="text" class="form-control" id="minor_area" name="minor_area" value="{{ old('minor_area', $service_area->minor_area) }}" required>
                    </div><br>


                    <!-- 蛋黃區 / 蛋白區選項 -->
                    <div class="form-group">
                        <label>區域類別</label>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="egg_yolk_area" name="area_type" value="egg_yolk"
                                   {{ old('area_type', $service_area->status) == 1 ? 'checked' : '' }} required>
                            <label class="form-check-label" for="egg_yolk_area">蛋黃區</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="egg_white_area" name="area_type" value="egg_white"
                                   {{ old('area_type', $service_area->status) == 0 ? 'checked' : '' }} required>
                            <label class="form-check-label" for="egg_white_area">蛋白區</label>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">更新地區</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
