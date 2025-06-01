@extends('masters.layouts.master')

@section('title', '師傅管理')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-2">
                <p class="fs-4 mb-0">
                    <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> &gt;
                    師傅管理
                </p>
                <div class="text-size-controls btn-group btn-group-sm" role="group" aria-label="字級調整">
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
                </div>
            </div>
            <h1 class="mt-4 text-center">師傅管理</h1>
        </div>

        <div id="content" class="medium">
            <div class="table-responsive d-flex justify-content-center">
                <table class="table" style="width: 80%;" id="sortable-list">
                    <thead>
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
                        <th class="text-center" style="width:15%;">總工時</th>
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
                                <form id="deleteForm{{ $index + 1 }}" action="{{ route('admins.masters.destroy', ['hash_master' => \Vinkla\Hashids\Facades\Hashids::encode($master->id)]) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $master->name }}', {{ $index + 1 }})">刪除</button>
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
                document.getElementById('deleteForm' + id).submit();
            }
        }

        function setFontSize(size) {
            const content = document.getElementById('content');
            content.classList.remove('small', 'medium', 'large');
            content.classList.add(size);
        }
    </script>
@endsection
