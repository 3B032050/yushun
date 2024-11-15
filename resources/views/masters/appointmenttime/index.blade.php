@extends('masters.layouts.master')

@section('title', '豫順清潔')

@section('content')
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div style="margin-top: 10px;">
        <p style="font-size: 1.8em;">
            <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> &gt;
            可服務地區
        </p>
    </div>

    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a class="btn btn-success btn-sm" href="{{ route('masters.service_areas.create') }}">新增地區</a>
    </div>
    @if($serviceAreas->isEmpty())
        <p>沒有可用的服務地區資料</p>
    @else
    <div class="container">
        <h1>服務區域列表</h1>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th>縣市</th>
                <th>地區</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($serviceAreas as $index=>$servicearea)
                @foreach ($servicearea->adminarea as $adminArea)
                    <tr>
                        <td>{{ $servicearea->id}}</td>
                        <td>{{ $adminArea->major_area ?? '無資料' }}</td>
                        <td>{{ $adminArea->minor_area  ?? '無資料' }}</td>
                        <td>
                            <form action="{{ route('masters.service_areas.destroy', $servicearea->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('確定要刪除這個項目嗎？')">刪除</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
    </div>
    @endif
@endsection
