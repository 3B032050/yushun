@extends('masters.layouts.master')

@section('title', '豫順家居媒合服務平台')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-2 flex-wrap">
                <nav aria-label="breadcrumb" class="mb-2 mb-md-0 w-100 w-md-auto">
                    <ol class="breadcrumb breadcrumb-path mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('masters.index') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admins.masters.index') }}">師傅管理</a></li>
                    </ol>
                </nav>
                <div class="text-size-controls btn-group btn-group-sm" role="group" aria-label="字級調整">
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
                </div>
            </div>
            <h1 class="mt-4 text-center">師傅管理</h1>
        </div>

        <div id="content" class="medium">
            <div class="table-responsive">
                <table class="table table-bordered table-hover w-100" id="sortable-list">
                    <thead class="table-light">
                    <tr>
                        <td colspan="6"></td>
                        <td class="text-center">
                            <a class="btn btn-success btn-sm" href="{{ route('admins.masters.create') }}">新增師傅</a>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-center" style="width:5%;">#</th>
                        <th class="text-center" style="width:15%;">名稱</th>
                        <th class="text-center" style="width:15%;">Email</th>
                        <th class="text-center" style="width:15%;">電話</th>
                        <th class="text-center" style="width:15%;">總服務時數</th>
                        <th class="text-center" style="width:10%;">編輯</th>
                        <th class="text-center" style="width:10%;">刪除</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($masters as $index => $master)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ $master->name }}</td>
                            <td class="text-center">{{ $master->email }}</td>
                            <td class="text-center">{{ $master->phone }}</td>
                            <td class="text-center">{{ $master->total_hours }} 小時</td>
                            <td class="text-center">
                                <a href="{{ route('admins.masters.edit', ['hash_master' => \Vinkla\Hashids\Facades\Hashids::encode($master->id)]) }}" class="btn btn-secondary btn-sm">編輯</a>
                            </td>
                            <td class="text-center">
                                <form id="deleteForm_{{  $master->id }}" action="{{ route('admins.masters.destroy', ['hash_master' => \Vinkla\Hashids\Facades\Hashids::encode($master->id)]) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $master->name }}', {{ $master->id }})">刪除</button>
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

<style>
    /* 麵包屑響應式字級與換行 */
    .breadcrumb-path {
        font-size: 1.4em;
        white-space: normal;
        word-break: break-word;
    }

    /* 表格與字級響應式 */
    #sortable-list th:nth-child(2),
    #sortable-list td:nth-child(2) {
        min-width: 120px;
    }

    #sortable-list th, #sortable-list td {
        vertical-align: middle;
    }

    /* 手機小螢幕字級調整 */
    @media (max-width: 768px) {
        .breadcrumb-path {
            font-size: 1.2em;
        }
        #sortable-list {
            min-width: 600px;
        }
        #sortable-list th, #sortable-list td {
            font-size: 0.9em;
        }
        .text-size-controls .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.85em;
        }
    }

    @media (max-width: 480px) {
        .breadcrumb-path {
            font-size: 1em;
        }
        #sortable-list {
            min-width: 500px;
        }
        #sortable-list th, #sortable-list td {
            font-size: 0.8em;
        }
        /* 手機版字級按鈕組寬度縮小 */
        .text-size-controls .btn {
            padding: 0.2rem 0.4rem;
            font-size: 0.75em;
        }
    }

    /* 按鈕微調 */
    .btn-sm {
        line-height: 1.2;
    }
</style>
