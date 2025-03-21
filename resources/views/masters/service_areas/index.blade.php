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
                @php
                    $colors = ['#46A3FF', '#46A3FF', '#00EC00', '#FF9224', '#46A3FF', '#46A3FF']; // 服務項目顏色
                @endphp

                @foreach ($groupedServiceAreas as $index => $group)
                    @php
                        $colorIndex = $index % count($colors); // 根據服務項目取色
                        $majorGroups = $group->flatMap(fn($serviceArea) => $serviceArea->adminarea)->groupBy('major_area');
                    @endphp

                    <tr>
                        <td class="align-middle text-center">{{ $loop->iteration }}</td>
                        <td class="align-middle text-center"><h4>{{ $group->first()->adminitem->name }}</h4></td>
                        <td class="align-middle">
                            @foreach ($majorGroups as $majorIndex => $minorAreas)
                                @php
                                    $uniqueId = "accordion-{$index}-" . Str::slug($majorIndex) . "-" . uniqid();
                                @endphp
                                <div class="accordion" id="{{ $uniqueId }}">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading-{{ $uniqueId }}">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#collapse-{{ $uniqueId }}" aria-expanded="false"
                                                    aria-controls="collapse-{{ $uniqueId }}"
                                                    style="background-color: {{ $colors[$colorIndex] }}; color: white;">
                                                {{ $majorIndex }}
                                            </button>
                                        </h2>
                                        <div id="collapse-{{ $uniqueId }}" class="accordion-collapse collapse"
                                             aria-labelledby="heading-{{ $uniqueId }}">
                                            <div class="accordion-body">
                                                @foreach ($minorAreas as $adminArea)
                                                    <span class="badge bg-secondary area-badge">
                                        {{ $adminArea->minor_area }}
                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
