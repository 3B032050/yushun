@extends('masters.layouts.master')

@section('title', '豫順家居媒合服務平台')

@section('content')
    <div id="content" class="font-medium">
        <div class="content-wrapper">
            <div class="container-fluid px-4">
                    <div class="d-flex justify-content-between align-items-center mt-2 flex-wrap">
                    <nav aria-label="breadcrumb" class="mb-2 mb-md-0 w-100 w-md-auto">
                        <ol class="breadcrumb breadcrumb-path">
                            <li class="breadcrumb-item"><a href="{{ route('masters.index') }}"><i class="fa fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admins.equipment.index') }}">設備管理</a></li>
                        </ol>
                    </nav>
                        <div class="btn-group btn-group-sm text-size-controls mt-2" role="group" aria-label="字級調整">
                            <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                            <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                            <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
                        </div>
                </div>

                <h1 class="mt-4 text-center">設備管理</h1>

                <div class="table-responsive d-flex justify-content-center">
                    <table class="table" style="width: 80%;" id="sortable-list">
                        <thead>
                        <tr>
                            <th colspan="6" class="text-end">
                                <a class="btn btn-success btn-sm" href="{{ route('admins.equipment.create') }}">新增設備</a>
                            </th>
                        </tr>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 20%;">名稱</th>
                            <th style="width: 10%;">數量</th>
                            <th style="width: 30%;">圖片</th>
                            <th style="width: 15%;">編輯</th>
                            <th style="width: 15%;">刪除</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($equipments as $index => $equipment)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $equipment->name }}</td>
                                <td>{{ $equipment->quantity }}</td>
                                <td>
                                    @if($equipment->photo)
                                        <img src="{{ asset('storage/equipments/' . $equipment->photo) }}" alt="設備圖片" height="90" width="150">
                                    @else
                                        <span class="text-muted">無圖片</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admins.equipment.edit', ['hash_equipment' => \Vinkla\Hashids\Facades\Hashids::encode($equipment->id)]) }}" class="btn btn-secondary btn-sm">編輯</a>
                                </td>
                                <td>
                                    <form id="deleteForm{{ $index + 1 }}" action="{{ route('admins.equipment.destroy', ['hash_equipment' => \Vinkla\Hashids\Facades\Hashids::encode($equipment->id)]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $equipment->name }}', {{ $index + 1 }})">刪除</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- 刪除確認 --}}
    <script>
        function confirmDelete(name, id) {
            if (confirm('確定要刪除設備「' + name + '」嗎？')) {
                document.getElementById('deleteForm' + id).submit();
            }
        }
    </script>

    {{-- 樣式 --}}
    <style>
        .breadcrumb-path {
            font-size: 1.4em;
            white-space: normal;
            word-break: break-word;
        }

        @media (max-width: 768px) {
            .breadcrumb-path {
                font-size: 1.4em;
            }
        }

        @media (max-width: 480px) {
            .breadcrumb-path {
                font-size: 1.2em;
            }
        }
        .required {
            color: red;
            margin-left: 5px;
            font-weight: bold;
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
