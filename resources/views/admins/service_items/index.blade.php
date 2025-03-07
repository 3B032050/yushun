@extends('masters.layouts.master')

@section('title', '服務項目管理')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div style="margin-top: 10px;">
                <p style="font-size: 1.8em;">
                    <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                    服務項目管理
                </p>
            </div>
            <h1 class="mt-4 text-center">服務項目管理</h1>
        </div>

        <div class="table-responsive d-flex justify-content-center">
            <table class="table" style="width: 80%;" id="sortable-list">
                <thead>
                <tr>
                    <td colspan="5" class="align-middle" style="text-align:center" ></td>
                    <td class="align-middle" style="text-align:center" >
                        <a class="btn btn-success btn-sm" href="{{ route('admins.service_items.create') }}">新增服務項目</a>
                    </td>
                </tr>
                <tr>
                    <th scope="col" style="text-align:center; width: 5%;">#</th>
                    <th scope="col" style="text-align:center; width: 20%;">名稱</th>
                    <th scope="col" style="text-align:center; width: 10%;">描述</th>
                    <th scope="col" style="text-align:center; width: 10%;">價格</th>
                    <th scope="col" style="text-align:center; width: 15%;">編輯</th>
                    <th scope="col" style="text-align:center; width: 15%;">刪除</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $index => $item)
                    <tr>
                        <td class="align-middle" style="text-align:center">{{ $index + 1 }}</td>
                        <td class="align-middle" style="text-align:center">{{ $item->name}}</td>
                        <td class="align-middle" style="text-align:center">{{ $item->description}}</td>
                        <td class="align-middle" style="text-align:center">{{ $item->price}}</td>
                        <td class="align-middle" style="text-align:center">
                            <a href="{{ route('admins.service_items.edit', $item->id) }}" class="btn btn-secondary btn-sm">編輯</a>
                        </td>
                        <td class="align-middle" style="text-align:center">
                            <form id="deleteForm{{ $index + 1 }}" action="{{ route('admins.service_items.destroy', $item->id) }}" method="POST">
                                @method('DELETE')
                                @csrf
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $item->name }}', {{ $index + 1 }})">刪除</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
        function confirmDelete(name, id) {
            if (confirm('確定要刪除項目 ' + name + ' 嗎？')) {
                document.getElementById('deleteForm' + id).submit();
            }
        }
    </script>

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
