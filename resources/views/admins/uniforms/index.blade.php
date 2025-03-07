@extends('masters.layouts.master')

@section('title', '制服管理')

@section('content')
    <div class="content-wrapper">
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
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
