@extends('masters.layouts.master')

@section('title', '租借紀錄')

@section('content')
    <div class="container">
        <div style="margin-top: 10px;">
            <p style="font-size: 1.8em;">
                <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                租借紀錄
            </p>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">租借紀錄</div>
                    <div class="card-body">
                        @if($rentals->isEmpty())
                            <p class="text-center">目前沒有租借紀錄。</p>
                        @else
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col" style="text-align:center;">制服名稱</th>
                                    <th scope="col" style="text-align:center;">尺寸</th>
                                    <th scope="col" style="text-align:center;">數量</th>
                                    <th scope="col" style="text-align:center;">狀態</th>
                                    <th scope="col" style="text-align:center;">租借時間</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($rentals as $rental)
                                    <tr>
                                        <td class="align-middle" style="text-align:center">{{ $rental->uniform->name }}</td>
                                        <td class="align-middle" style="text-align:center">{{ $rental->size }}</td>
                                        <td class="align-middle" style="text-align:center">{{ $rental->quantity }}</td>
                                        <td class="align-middle" style="text-align:center">
                                            @if($rental->status === 1)
                                                <div style="color:#21b70a; font-weight:bold;">
                                                    (租用中)
                                                </div>
                                            @elseif($rental->status === 2)
                                                <div style="color:#000000; font-weight:bold;">
                                                    (已歸還)
                                                </div>
                                            @endif
                                        </td>
                                        <td class="align-middle" style="text-align:center">{{ $rental->created_at->format('Y-m-d H:i') }}</td>
                                        <td class="align-middle" style="text-align:center">
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
    </div>
@endsection
