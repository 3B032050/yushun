@extends('masters.layouts.master')

@section('title', '租借紀錄')

@section('content')
    <div class="container-fluid px-4">
        <div style="margin-top: 10px;">
            <p style="font-size: 1.8em;">
                <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                已選擇尺寸
            </p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header text-center">已選擇尺寸</div>
                <div class="card-body">
                        <table class="table table-striped text-center">
                            <thead>
                            <tr>
                                <th scope="col">尺寸</th>
                                <th scope="col">數量</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="align-middle">{{ $rental->size }}</td>
                                    <td class="align-middle">{{ $rental->quantity }}</td>
                                </tr>
                            </tbody>
                        </table>
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
