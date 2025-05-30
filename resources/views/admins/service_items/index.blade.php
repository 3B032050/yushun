@extends('masters.layouts.master')

@section('title', '服務項目管理')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-2">
                <p style="font-size: 1.8em;">
                    <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                    服務項目管理
                </p>
                <div class="btn-group btn-group-sm" role="group" aria-label="字級調整">
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
                </div>
            </div>
            <h1 class="mt-4 text-center">服務項目管理</h1>
        </div>

        <div class="table-responsive d-flex justify-content-center">
            <div id="content" class="font-medium" style="width: 80%;">
                <div class="d-flex justify-content-end mb-2">
                    <a class="btn btn-success btn-sm" href="{{ route('admins.service_items.create') }}">新增服務項目</a>
                </div>
                <table class="table" id="sortable-list">
                    <thead>
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
                            <td class="align-middle">{{ $index + 1 }}</td>
                            <td class="align-middle">{{ $item->name }}</td>
                            <td class="align-middle text-truncate" style="max-width: 200px;">{{ $item->description }}</td>
                            <td class="align-middle">{{ $item->price }}</td>
                            <td class="align-middle">
                                <a href="{{ route('admins.service_items.edit', ['hash_service_item' => \Vinkla\Hashids\Facades\Hashids::encode($item->id)]) }}" class="btn btn-secondary btn-sm">編輯</a>
                            </td>
                            <td class="align-middle">
                                <form id="deleteForm{{ $index + 1 }}" action="{{ route('admins.service_items.destroy',['hash_service_item' => \Vinkla\Hashids\Facades\Hashids::encode($item->id)]) }}" method="POST">
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
    </div>

    <script>
        function confirmDelete(name, id) {
            if (confirm('確定要刪除項目 ' + name + ' 嗎？')) {
                document.getElementById('deleteForm' + id).submit();
            }
        }

        function setFontSize(size) {
            const content = document.getElementById('content');
            content.classList.remove('font-small', 'font-medium', 'font-large');
            content.classList.add('font-' + size);
        }
    </script>

    <style>
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }
        .table img {
            display: block;
            margin: auto;
        }
        .text-truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .font-small {
            font-size: 0.85rem;
        }

        .font-medium {
            font-size: 1rem;
        }

        .font-large {
            font-size: 1.15rem;
        }

        .btn-group-sm .btn {
            padding: 2px 6px;
            font-size: 0.75rem;
        }
    </style>
@endsection
