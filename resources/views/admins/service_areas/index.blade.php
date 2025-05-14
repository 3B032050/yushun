@extends('masters.layouts.master')

@section('title', '豫順清潔')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div style="margin-top: 10px;">
                <p style="font-size: 1.8em;">
                    <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                    服務地區
                </p>
            </div>
            <h1 class="mt-4 text-center">服務地區</h1>
        </div>

        <div class="container px-4 px-lg-5 mt-2 mb-4">
            <form method="GET" action="{{ route('admins.service_areas.index') }}" class="d-flex align-items-center">
                <input type="text" class="form-control me-2" name="search" placeholder="搜尋服務地區" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary btn-sm">搜尋</button>
                <a href="{{ route('admins.service_areas.index') }}" class="btn btn-secondary btn-sm ms-2">取消搜尋</a>
            </form>
        </div>

        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="text-center mb-0">服務區域列表</h1>
                <a class="btn btn-success btn-sm" href="{{ route('admins.service_areas.create') }}">新增地區</a>
            </div>

            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>主要區域</th>
                    <th>次要區域</th>
                    <th>狀態</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @if ($serviceAreas->isEmpty())
                    <tr>
                        <td colspan="4" class="text-center">沒有找到符合條件的服務地區。</td>
                    </tr>
                @else
                    @foreach ($serviceAreas as $area)
                        <tr>
                            <td>{{ $area->major_area }}</td>
                            <td>{{ $area->minor_area }}</td>
                            <td>
                                @if($area->status == 1)
                                    <span style="color: #fd7e14">蛋黃區</span>
                                @elseif($area->status == 0)
                                    <span style="color: #343a40">蛋白區</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admins.service_areas.edit', ['{hash_service_area' => \Vinkla\Hashids\Facades\Hashids::encode($area->id)]) }}" class="btn btn-warning btn-sm">編輯</a>
                                <form action="{{ route('admins.service_areas.destroy', ['{hash_service_area' => \Vinkla\Hashids\Facades\Hashids::encode($area->id)]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('確定要刪除這個項目嗎？')">刪除</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>

            <div class="d-flex justify-content-center align-items-center mt-3">
                <span>每頁顯示 <strong>{{ $serviceAreas->perPage() }}</strong> 筆資料，當前在第 <strong>{{ $serviceAreas->currentPage() }}</strong> 頁，共有 <strong>{{ $serviceAreas->total() }}</strong> 筆資料。</span>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $serviceAreas->links() }} <!-- 分頁連結 -->
            </div>
        </div>
    </div>
@endsection
