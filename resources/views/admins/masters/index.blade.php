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
                        <li class="breadcrumb-item active" aria-current="page">師傅資料管理</li>
                    </ol>
                </nav>
                <div class="btn-group btn-group-sm text-size-controls" role="group" aria-label="字級調整">
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
                </div>
            </div>
            <h1 class="mt-4 text-center">師傅資料管理</h1>
        </div>

        <div id="content" class="medium">
            <div class="table-responsive">
                <table class="table table-bordered table-hover w-100" id="sortable-list">
                    <thead class="table-light">
                    <tr>
                        <td colspan="6"></td>
                        <td class="text-center">
                            <a class="btn btn-success btn-sm" href="{{ route('admins.masters.create') }}">
                                <i class="fa fa-plus"></i> 新增師傅資料
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-center" style="width:5%;">#</th>
                        <th class="text-center" style="width:15%;">名稱</th>
                        <th class="text-center" style="width:15%;">Email</th>
                        <th class="text-center" style="width:15%;">電話</th>
                        <th class="text-center" style="width:15%;">總服務時數</th>
                        <th class="text-center" colspan="2" style="width:20%;">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($masters as $index => $master)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ $master->name }}</td>
                            <td class="text-center">{{ $master->email }}</td>
                            <td class="text-center">{{ $master->phone }}</td>
                            <td class="text-center">{{ $master->total_hours }}</td>
                            <td colspan="2" class="text-center">
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    <a href="{{ route('admins.masters.edit', ['hash_master' => \Vinkla\Hashids\Facades\Hashids::encode($master->id)]) }}"
                                       class="btn btn-secondary btn-sm" title="編輯">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                    <form id="deleteForm_{{ $master->id }}" action="{{ route('admins.masters.destroy', ['hash_master' => \Vinkla\Hashids\Facades\Hashids::encode($master->id)]) }}" method="POST" style="margin:0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm" title="刪除" onclick="confirmDelete('{{ $master->name }}', {{ $master->id }})">
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
            if (confirm('確定要刪除師傅 ' + name + ' 嗎？')) {
                document.getElementById('deleteForm_' + id).submit();
            }
        }

        function setFontSize(size) {
            const content = document.getElementById('content');
            content.classList.remove('small', 'medium', 'large');
            content.classList.add(size);
        }
    </script>
@endsection

