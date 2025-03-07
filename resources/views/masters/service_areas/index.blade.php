@extends('masters.layouts.master')

@section('title', '豫順清潔')

@section('content')
    <div class="content-wrapper">
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="container-fluid px-4">
            <div style="margin-top: 10px;">
                <p style="font-size: 1.8em;">
                    <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> &gt;
                    管理可服務地區
                </p>
            </div>
            <h1 class="mt-4 text-center">管理可服務地區</h1>
        </div>

        <div class="table-responsive d-flex justify-content-center">
            <table class="table" style="width: 100%;" id="sortable-list">
                <thead>
                <tr>
                    <td colspan="3" class="align-middle text-end">
                        <a class="btn btn-success btn-sm" href="{{ route('masters.service_areas.create_item') }}">新增可服務地區</a>
                    </td>
                </tr>
                <tr>
                    <th scope="col" style="text-align:center; width: 5%;">#</th>
                    <th scope="col" style="text-align:center; width: 25%;">項目名稱</th>
                    <th scope="col" style="text-align:center; width: 50%;">可服務地區</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $groupedServiceAreas = $serviceAreas->groupBy('admin_service_item_id');
                @endphp
                @foreach ($groupedServiceAreas as $index => $group)
                    <tr>
                        <td class="align-middle text-center">{{ $loop->iteration }}</td>
                        <td class="align-middle text-center"><h4>{{ $group->first()->adminitem->name }}</h4></td>
                        <td class="align-middle">
                            @foreach ($group as $serviceArea)
                                @foreach ($serviceArea->adminarea as $adminArea)
                                    <span class="badge bg-primary area-badge">
                                        {{ $adminArea->major_area }} - {{ $adminArea->minor_area }}
                                    </span>
                                @endforeach
                            @endforeach
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .area-badge {
            font-size: 1.2rem;
            padding: 10px 15px;
            margin: 5px;
            font-weight: bold;
            border-radius: 8px;
        }
    </style>
@endsection
