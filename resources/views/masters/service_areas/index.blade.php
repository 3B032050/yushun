@extends('masters.layouts.master')

@section('title', '豫順清潔')

@section('content')
    <div style="margin-top: 10px;">
        <p style="font-size: 1.8em;">
            <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> &gt;
            可服務地區
        </p>
    </div>

    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a class="btn btn-success btn-sm" href="{{ route('masters.service_areas.create') }}">新增地區</a>
    </div>
    <div class="container">
        <h1>服務區域列表</h1>
        <table class="table">
            <thead>
            <tr>
                <th>縣市</th>
                <th>地區</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @if ($serviceAreas->isEmpty())
                <tr>
                    <td colspan="4">沒有找到符合條件的服務地區。</td>
                </tr>
            @else
                @foreach ($serviceAreas as $area)
                    @foreach($area->adminServiceAreas as $adminArea)
                        <tr>
                            <td>{{ $adminArea->major_area }}</td>
                            <td>{{ $adminArea->minor_area }}</td>

                            <td>
                                <!-- 編輯和刪除按鈕 -->
                                <form action="{{ route('admins.service_areas.destroy', $area->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('確定要刪除這個項目嗎？')">刪除</button>
                                </form>
                            </td>
                        </tr>
                  @endforeach
                @endforeach
            @endif
            </tbody>
        </table>
    </div>

@endsection
