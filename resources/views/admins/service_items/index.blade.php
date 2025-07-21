@extends('masters.layouts.master')

@section('title', '服務項目管理')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-2 flex-column flex-md-row">
                <nav aria-label="breadcrumb" class="mb-2 mb-md-0 w-100 w-md-auto">
                    <ol class="breadcrumb breadcrumb-path">
                        <li class="breadcrumb-item">
                            <a href="{{ route('masters.index') }}"><i class="fa fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">服務項目管理</li>
                    </ol>
                </nav>
                <div class="btn-group btn-group-sm text-size-controls" role="group" aria-label="字級調整">
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
                </div>
            </div>
            <h1 class="mt-3 text-center">服務項目管理</h1>
        </div>

        <div class="table-responsive d-flex justify-content-center">
            <div id="content" class="font-medium" style="width: 80%;">
                <div class="d-flex justify-content-end mb-2">
                    <a class="btn btn-success btn-sm" href="{{ route('admins.service_items.create') }}">新增服務項目</a>
                </div>
                <table class="table" id="sortable-list">
                    <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 20%;">名稱</th>
                        <th style="width: 10%;">說明</th>
                        <th style="width: 10%;">價格</th>
                        <th style="width: 20%;">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->name }}</td>
                            <td class="text-truncate" style="max-width: 200px;">{{ $item->description }}</td>
                            <td>{{ $item->price }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    <a href="{{ route('admins.service_items.edit', ['hash_service_item' => \Vinkla\Hashids\Facades\Hashids::encode($item->id)]) }}"
                                       class="btn btn-secondary btn-sm" title="編輯">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                    <form id="deleteForm{{ $index + 1 }}" action="{{ route('admins.service_items.destroy',['hash_service_item' => \Vinkla\Hashids\Facades\Hashids::encode($item->id)]) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm" title="刪除"
                                                onclick="confirmDelete('{{ $item->name }}', {{ $index + 1 }})">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
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
            if (confirm('確定要刪除項目 "' + name + '" 嗎？')) {
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
        .breadcrumb-path {
            font-size: 1.4em;
            white-space: normal;
            word-break: break-word;
        }

        @media (max-width: 768px) {
            .breadcrumb-path {
                font-size: 1.3em;
            }

            .text-size-controls {
                margin-top: 0.5rem;
            }
        }

        @media (max-width: 480px) {
            .breadcrumb-path {
                font-size: 1.1em;
            }

            .text-size-controls {
                width: 100%;
                justify-content: center;
            }
        }

        .table th, .table td {
            vertical-align: middle;
            text-align: center;
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
