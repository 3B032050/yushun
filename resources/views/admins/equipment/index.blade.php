@extends('masters.layouts.master')

@section('title', '設備管理')

@section('content')
    <div id="content" class="medium">
        <div class="content-wrapper">
            <div class="container-fluid px-4">
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <p style="font-size: 1.8em;">
                        <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                        設備管理
                    </p>
                    <div class="text-size-controls btn-group btn-group-sm" role="group" aria-label="字級調整">
                        <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                        <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                        <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
                    </div>
                </div>
                <h1 class="mt-4 text-center">設備管理</h1>
            </div>

            <div class="table-responsive d-flex justify-content-center">
                <table class="table" style="width: 80%;" id="sortable-list">
                    <thead>
                    <tr>
                        <td colspan="5" class="align-middle" style="text-align:center" ></td>
                        <td class="align-middle" style="text-align:center" >
                            <a class="btn btn-success btn-sm" href="{{ route('admins.equipment.create') }}">新增設備</a>
                        </td>
                    </tr>
                    <tr>
                        <th scope="col" style="text-align:center; width: 5%;">#</th>
                        <th scope="col" style="text-align:center; width: 20%;">名稱</th>
                        <th scope="col" style="text-align:center; width: 10%;">數量</th>
                        <th scope="col" style="text-align:center; width: 30%;">圖片</th>
                        <th scope="col" style="text-align:center; width: 15%;">編輯</th>
                        <th scope="col" style="text-align:center; width: 15%;">刪除</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($equipments as $index => $equipment)
                        <tr>
                            <td class="align-middle" style="text-align:center">{{ $index + 1 }}</td>
                            <td class="align-middle" style="text-align:center">{{ $equipment->name}}</td>
                            <td class="align-middle" style="text-align:center">{{ $equipment->quantity}}</td>
                            <td class="align-middle" style="text-align:center">
                                <img src="{{ asset( 'storage/equipments/' . $equipment->photo) }}" height="90px" width="150px">
                            </td>
                            <td class="align-middle" style="text-align:center">
                                <a href="{{ route('admins.equipment.edit',['hash_equipment' => \Vinkla\Hashids\Facades\Hashids::encode($equipment->id)]) }}" class="btn btn-secondary btn-sm">編輯</a>
                            </td>
                            <td class="align-middle" style="text-align:center">
                                <form id="deleteForm{{ $index + 1 }}" action="{{ route('admins.equipment.destroy', ['hash_equipment' => \Vinkla\Hashids\Facades\Hashids::encode($equipment->id)]) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
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
    <script>
        function confirmDelete(name, id) {
            if (confirm('確定要刪除設備 ' + name + ' 嗎？')) {
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

        .table img {
            display: block;
            margin: auto; /* 讓圖片在單元格內置中 */
        }
    </style>
@endsection
