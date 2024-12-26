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
{{--            <tr>--}}
{{--                <td colspan="9" class="align-middle" style="text-align:center"></td>--}}
{{--                <td class="align-middle" style="text-align:center; width: 15%;">--}}
{{--                    <a class="btn btn-success btn-sm" href="{{ route('admins.uniforms.create') }}">新增制服</a>--}}
{{--                </td>--}}
{{--            </tr>--}}
            <tr>
                <th scope="col" style="text-align:center; width: 15%;">師傅姓名</th>
                <th scope="col" style="text-align:center; width: 10%;">尺寸</th>
                <th scope="col" style="text-align:center; width: 10%;">數量</th>
                <th scope="col" style="text-align:center; width: 15%;">時間</th>
            </tr>
            </thead>
            <tbody>
            @foreach($rent_uniforms as $index => $rent_uniform)
                <tr>
                    <td class="align-middle" style="text-align:center">{{ $rent_uniform->master->name }}</td>
                    <td class="align-middle" style="text-align:center">{{ $rent_uniform->size }}</td>
                    <td class="align-middle" style="text-align:center">{{ $rent_uniform->quantity }}</td>
                    <td class="align-middle" style="text-align:center">{{ $rent_uniform->created_at->format('Y-m-d') }}</td>

{{--                    <td class="align-middle" style="text-align:center">--}}
{{--                        <a href="{{ route('admins.uniforms.edit', $uniform->id) }}" class="btn btn-secondary btn-sm">編輯</a>--}}
{{--                    </td>--}}
{{--                    <td class="align-middle" style="text-align:center">--}}
{{--                        <form id="deleteForm{{ $index + 1 }}" action="{{ route('admins.uniforms.destroy', $uniform->id) }}" method="POST">--}}
{{--                            @method('DELETE')--}}
{{--                            @csrf--}}
{{--                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $uniform->name }}', {{ $index + 1 }})">刪除</button>--}}
{{--                        </form>--}}
{{--                    </td>--}}
{{--                    <td>--}}
{{--                        <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#rentHistoryModal{{ $index }}">租借紀錄</button>--}}
{{--                    </td>--}}
                </tr>

{{--                <div class="modal fade" id="rentHistoryModal{{ $index }}" tabindex="-1" aria-labelledby="rentHistoryModalLabel{{ $index }}" aria-hidden="true">--}}
{{--                    <div class="modal-dialog modal-dialog-centered" style="max-width: 40%;">--}}
{{--                        <div class="modal-content">--}}
{{--                            <div class="modal-header">--}}
{{--                                <h5 class="modal-title" id="rentHistoryModalLabel{{ $index }}">租借紀錄 - {{ $uniform->name }}</h5>--}}
{{--                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>--}}
{{--                            </div>--}}
{{--                            <div class="modal-body">--}}
{{--                                @php--}}
{{--                                    $filteredRecords = $rent_uniforms->where('uniform_id', $uniform->id);--}}
{{--                                @endphp--}}

{{--                                @if($filteredRecords->isEmpty())--}}
{{--                                    <p class="text-center">目前無租借紀錄。</p>--}}
{{--                                @else--}}
{{--                                    <ul class="list-group">--}}
{{--                                        @foreach($filteredRecords as $record)--}}
{{--                                            <li class="list-group-item">--}}
{{--                                                師傅：{{ $record->master->name }}&nbsp&nbsp|&nbsp&nbsp--}}
{{--                                                尺寸：{{ $record->size }}&nbsp&nbsp|&nbsp&nbsp--}}
{{--                                                數量：{{ $record->quantity }}&nbsp&nbsp|&nbsp&nbsp--}}
{{--                                                日期：{{ $record->created_at->format('Y-m-d') }}&nbsp;&nbsp;--}}
{{--                                                @if($record->status == 1)--}}
{{--                                                    <span style="color: green;">(使用中)</span>--}}
{{--                                                @else--}}
{{--                                                    <span style="color: red;">(已歸還)</span>--}}
{{--                                                @endif--}}
{{--                                            </li>--}}
{{--                                        @endforeach--}}
{{--                                    </ul>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
            @endforeach
            </tbody>
        </table>
    </div>

{{--    <script>--}}
{{--        function confirmDelete(name, id) {--}}
{{--            if (confirm('確定要刪除設備 ' + name + ' 嗎？')) {--}}
{{--                document.getElementById('deleteForm' + id).submit();--}}
{{--            }--}}
{{--        }--}}
{{--    </script>--}}
@endsection
