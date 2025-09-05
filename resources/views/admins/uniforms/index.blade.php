@extends('masters.layouts.master')

@section('title', '豫順家居媒合服務平台')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-2 flex-column flex-md-row">
                <nav aria-label="breadcrumb" class="mb-2 mb-md-0 w-100 w-md-auto">
                    <ol class="breadcrumb breadcrumb-path">
                        <li class="breadcrumb-item">
                            <a href="{{ route('masters.index') }}"><i class="fa fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">制服資料管理</li>
                    </ol>
                </nav>
                <div class="btn-group btn-group-sm text-size-controls" role="group" aria-label="字級調整">
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
                </div>
            </div>
            <h1 class="mt-3 text-center">制服資料管理</h1>
        </div>

        <div id="content" class="medium">
            <div class="table-responsive">
                <table class="table table-bordered table-hover w-100" id="sortable-list">
                    <thead class="table-light">
                    <tr>
                        <td colspan="5"></td>
                        <td class="text-center">
                            <a class="btn btn-success btn-sm" href="{{ route('admins.uniforms.create') }}">
                                <i class="fa fa-plus"></i> 新增制服資料
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-center" style="width:5%;">#</th>
                        <th style="width: 15%;">師傅姓名</th>
                        <th style="width: 10%;">尺寸</th>
                        <th style="width: 10%;">數量</th>
                        <th style="width: 15%;">時間</th>
                        <th class="text-center" colspan="2" style="width:10%;">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rent_uniforms as $index => $rent_uniform)
                        @php
                            $masterName = optional($rent_uniform->master)->name ?? '（師傅資料不存在）';
                            $dateStr    = optional($rent_uniform->created_at)->format('Y-m-d') ?? '-';
                        @endphp
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ $masterName }}</td>
                            <td class="text-center">{{ $rent_uniform->size }}</td>
                            <td class="text-center">{{ $rent_uniform->quantity }}</td>
                            <td class="text-center">{{ $dateStr }}</td>
                            <td colspan="2">
                                <div class="d-flex gap-2 justify-content-center">
                                    {{-- 編輯 --}}
                                    <a href="{{ route('admins.uniforms.edit', ['hash_uniform' => \Vinkla\Hashids\Facades\Hashids::encode($rent_uniform->id)]) }}"
                                       class="btn btn-secondary btn-sm btn-icon" title="編輯">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>

                                    {{-- 刪除 --}}
                                    <form action="{{ route('admins.uniforms.destroy', ['hash_uniform' => \Vinkla\Hashids\Facades\Hashids::encode($rent_uniform->id)]) }}" method="POST">
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

