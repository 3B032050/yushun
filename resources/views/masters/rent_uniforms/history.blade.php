@extends('masters.layouts.master')

@section('title', '租借紀錄')

@section('content')
    <div class="container-fluid px-4">
        <div style="margin-top: 10px;">
            <p style="font-size: 1.8em;">
                <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                租借紀錄
            </p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header text-center">租借紀錄</div>
                <div class="card-body">
                    @if($rentals->isEmpty())
                        <p class="text-center">目前沒有租借紀錄。</p>
                    @else
                        <table class="table table-striped text-center">
                            <thead>
                            <tr>
                                <th scope="col">制服名稱</th>
                                <th scope="col">尺寸</th>
                                <th scope="col">數量</th>
                                <th scope="col">狀態</th>
                                <th scope="col">租借時間</th>
                                <th scope="col">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rentals as $rental)
                                <tr>
                                    <td class="align-middle">{{ $rental->uniform->name }}</td>
                                    <td class="align-middle">{{ $rental->size }}</td>
                                    <td class="align-middle">{{ $rental->quantity }}</td>
                                    <td class="align-middle">
                                        @if($rental->status === 1)
                                            <div style="color:#21b70a; font-weight:bold;">(租用中)</div>
                                        @elseif($rental->status === 2)
                                            <div style="color:#000000; font-weight:bold;">(已歸還)</div>
                                        @endif
                                    </td>
                                    <td class="align-middle">{{ $rental->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="align-middle">
                                        @if($rental->status === 1)
                                            <form action="{{ route('masters.rent_uniforms.return', $rental->id) }}" method="POST">
                                                @csrf
                                                @method("POST")
                                                <button type="submit" class="btn btn-success btn-sm">歸還</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    /* 表格居中 */
    .table {
        margin: 0 auto;
    }

    /* 讓表格內容自動調整 */
    .table th, .table td {
        text-align: center;
    }
</style>
