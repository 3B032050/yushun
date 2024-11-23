@extends('masters.layouts.master')

@section('title', '豫順清潔')

@section('content')
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">  <!-- 修正這裡 -->
            {{ session('success') }}
        </div>
    @endif
    <div class="container-fluid px-4">
        <div style="margin-top: 10px;">
            <p style="font-size: 1.8em;">
                <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> &gt;
                可服務地區
            </p>
        </div>
        <h1 class="mt-4 text-center">可服務地區</h1>
    </div>

    <div class="table-responsive d-flex justify-content-center">
        <table class="table" style="width: 100%;" id="sortable-list">  <!-- 這裡設置寬度為100% -->
            <thead>
            <tr>
                <td colspan="4" class="align-middle" style="text-align:center" ></td>
                <td class="align-middle" style="text-align:center" >
                    <a class="btn btn-success btn-sm" href="{{ route('masters.service_areas.create_item') }}">新增可服務地區</a>
                </td>
            </tr>
            <tr>
                <th scope="col" style="text-align:center; width: 5%;">#</th>
                <th scope="col" style="text-align:center; width: 20%;">項目名稱</th>
                <th scope="col" style="text-align:center; width: 10%;">縣市</th>
                <th scope="col" style="text-align:center; width: 15%;">地區</th>
                <th scope="col" style="text-align:center; width: 15%;">刪除</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($serviceAreas as $index=>$serviceArea)
                @foreach ($serviceArea->adminarea as $adminArea)
                    <tr>
                        <td class="align-middle" style="text-align:center">{{ $index+1}}</td>
                        <td class="align-middle" style="text-align:center">{{ $serviceArea->adminitem->name}}</td>
                        <td class="align-middle" style="text-align:center">{{ $adminArea->major_area ?? '無資料' }}</td>
                        <td class="align-middle" style="text-align:center">{{ $adminArea->minor_area  ?? '無資料' }}</td>
                        <td class="align-middle" style="text-align:center">
                            <form action="{{ route('masters.service_areas.destroy', $serviceArea->id) }}" method="POST" style="display:inline;">
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

    <style>
        .table-responsive {
            max-width: 85%; /* 限制表格最大寬度 */
            margin: auto;   /* 讓表格置中 */
        }

        .table th, .table td {
            vertical-align: middle; /* 垂直置中 */
            text-align: center;     /* 文字水平置中 */
        }
    </style>
@endsection
