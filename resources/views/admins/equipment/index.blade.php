@extends('masters.layouts.master')

@section('title', '豫順家居媒合服務平台')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-2 flex-column flex-md-row">
                <nav aria-label="breadcrumb" class="mb-2 mb-md-0 w-100 w-md-auto">
                    <ol class="breadcrumb breadcrumb-path">
                        <li class="breadcrumb-item"><a href="{{ route('masters.index') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item">設備狀態資訊管理</li>
                    </ol>
                </nav>
                <div class="text-size-controls btn-group btn-group-sm" role="group" aria-label="字級調整">
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
                </div>
            </div>
            <h1 class="mt-4 text-center">設備狀態資訊管理</h1>
        </div>

        <div id="content" class="medium">
            <div class="table-responsive">
                <table class="table table-bordered table-hover w-100" id="sortable-list">
                    <thead class="table-light">
                    <tr>
                        <td colspan="6"></td>
                        <td class="text-center">
                            <a class="btn btn-success btn-sm" href="{{ route('admins.equipment.create') }}">
                                <i class="fa fa-plus"></i> 新增設備狀態資料
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 20%;">名稱</th>
                        <th style="width: 10%;">數量</th>
                        <th style="width: 25%;">倉儲位置</th>
                        <th style="width: 25%;">圖片</th>
                        <th colspan="2" style="width: 15%;">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($equipments as $index => $equipment)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $equipment->name }}</td>
                            <td>{{ $equipment->quantity }}</td>
                            <td>{{ $equipment->storage_location }}</td>
                            <td>
                                @if($equipment->photo)
                                    <img src="{{ asset('storage/equipments/' . $equipment->photo) }}" alt="設備圖片" height="90" width="150">
                                @else
                                    <span class="text-muted">無圖片</span>
                                @endif
                            </td>
                            <td colspan="2" class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    {{-- 編輯 --}}
                                    <a href="{{ route('admins.equipment.edit', ['hash_equipment' => \Vinkla\Hashids\Facades\Hashids::encode($equipment->id)]) }}"
                                       class="btn btn-secondary btn-sm btn-icon" title="編輯">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>

                                    {{-- 刪除 --}}
                                    <form action="{{ route('admins.equipment.destroy', ['hash_equipment' => \Vinkla\Hashids\Facades\Hashids::encode($equipment->id)]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-delete btn btn-danger btn-sm btn-icon" title="刪除">
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
@endsection
