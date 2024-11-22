@extends('masters.layouts.master')

@section('title', '制服管理')

@section('content')
    <div class="container-fluid px-4">
        <div style="margin-top: 10px;">
            <p style="font-size: 1.8em;">
                <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                制服管理
            </p>
        </div>
        <h1 class="mt-4 text-center">制服管理</h1>
    </div>

    <div class="table-responsive d-flex justify-content-center">
        <table class="table" style="width: 80%;" id="sortable-list">
            <thead>
            <tr>
                <td colspan="9" class="align-middle" style="text-align:center"></td>
                <td class="align-middle" style="text-align:center; width: 15%;">
                    <a class="btn btn-success btn-sm" href="{{ route('admins.uniforms.create') }}">新增制服</a>
                </td>
            </tr>
            <tr>
                <th scope="col" style="text-align:center; width: 10%;">名稱</th>
                <th scope="col" style="text-align:center; width: 15%;">圖片</th>
                <th scope="col" style="text-align:center; width: 8%;">S</th>
                <th scope="col" style="text-align:center; width: 8%;">M</th>
                <th scope="col" style="text-align:center; width: 8%;">L</th>
                <th scope="col" style="text-align:center; width: 8%;">XL</th>
                <th scope="col" style="text-align:center; width: 8%;">XXL</th>
                <th scope="col" style="text-align:center; width: 12%;">編輯</th>
                <th scope="col" style="text-align:center; width: 12%;">刪除</th>
                <th scope="col" style="text-align:center; width: 12%;">租借紀錄</th>
            </tr>
            </thead>
            <tbody>
            @foreach($uniforms as $index => $uniform)
                <tr>
                    <td class="align-middle" style="text-align:center">{{ $uniform->name }}</td>
                    <td class="align-middle" style="text-align:center">
                        <img src="{{ asset('storage/uniforms/' . $uniform->photo) }}" height="110px" width="150px">
                    </td>

                    <td class="align-middle" style="text-align:center">{{ $uniform->S }}</td>
                    <td class="align-middle" style="text-align:center">{{ $uniform->M }}</td>
                    <td class="align-middle" style="text-align:center">{{ $uniform->L }}</td>
                    <td class="align-middle" style="text-align:center">{{ $uniform->XL }}</td>
                    <td class="align-middle" style="text-align:center">{{ $uniform->XXL }}</td>

                    <td class="align-middle" style="text-align:center">
                        <a href="{{ route('admins.uniforms.edit', $uniform->id) }}" class="btn btn-secondary btn-sm">編輯</a>
                    </td>
                    <td class="align-middle" style="text-align:center">
                        <form id="deleteForm{{ $index + 1 }}" action="{{ route('admins.uniforms.destroy', $uniform->id) }}" method="POST">
                            @method('DELETE')
                            @csrf
                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $uniform->name }}', {{ $index + 1 }})">刪除</button>
                        </form>
                    </td>
                    <td>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#rentHistoryModal{{ $index }}">租借紀錄</button>
                    </td>
                </tr>

                <div class="modal fade" id="rentHistoryModal{{ $index }}" tabindex="-1" aria-labelledby="rentHistoryModalLabel{{ $index }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 40%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="rentHistoryModalLabel{{ $index }}">租借紀錄 - {{ $uniform->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                @php
                                    $filteredRecords = $rent_uniforms->where('uniform_id', $uniform->id);
                                @endphp

                                @if($filteredRecords->isEmpty())
                                    <p class="text-center">目前無租借紀錄。</p>
                                @else
                                    <ul class="list-group">
                                        @foreach($filteredRecords as $record)
                                            <li class="list-group-item">
                                                師傅：{{ $record->master->name }}&nbsp&nbsp|&nbsp&nbsp
                                                尺寸：{{ $record->size }}&nbsp&nbsp|&nbsp&nbsp
                                                數量：{{ $record->quantity }}&nbsp&nbsp|&nbsp&nbsp
                                                日期：{{ $record->created_at->format('Y-m-d') }}&nbsp;&nbsp;
                                                @if($record->status == 1)
                                                    <span style="color: green;">(使用中)</span>
                                                @else
                                                    <span style="color: red;">(已歸還)</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function confirmDelete(name, id) {
            if (confirm('確定要刪除設備 ' + name + ' 嗎？')) {
                document.getElementById('deleteForm' + id).submit();
            }
        }
    </script>
@endsection
