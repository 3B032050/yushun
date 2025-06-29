@extends('masters.layouts.master')

@section('title', '豫順家居媒合服務平台')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-2">
                <nav aria-label="breadcrumb" class="mb-2 mb-md-0 w-100 w-md-auto">
                    <ol class="breadcrumb breadcrumb-path mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('masters.index') }}"><i class="fa fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('masters.service_areas.index') }}">可服務地區</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('masters.service_areas.create_item') }}">選擇服務項目</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">選擇服務地區</li>
                    </ol>
                </nav>
                <div class="text-size-controls btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
                </div>
            </div>
        </div>

        <div id="content" class="medium">
            <div class="row justify-content-center mt-3">
                <div class="col-md-8 col-12">
                    <div class="card">
                        <div class="card-header text-center d-flex justify-content-between align-items-center">
                            <strong>{{ __('選擇服務地區') }}</strong>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="toggle-all">全部展開</button>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('masters.service_areas.store') }}" method="POST" role="form" enctype="multipart/form-data">
                                @csrf
                                @method('POST')

                                <div class="form-group">
                                    <div class="border p-3 rounded bg-light">
                                        <div class="accordion" id="areaAccordion">
                                            @php
                                                $currentMajorArea = null;
                                                $areaIndex = 0;
                                            @endphp

                                            @foreach($serviceAreas as $area)
                                                @if ($currentMajorArea !== $area->major_area)
                                                    @if ($currentMajorArea !== null)
                                        </div></div></div>
                                @endif
                                @php
                                    $currentMajorArea = $area->major_area;
                                    $areaIndex++;
                                @endphp
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{ $areaIndex }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $areaIndex }}" aria-expanded="false" aria-controls="collapse{{ $areaIndex }}">
                                            {{ $currentMajorArea }}
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $areaIndex }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $areaIndex }}" data-bs-parent="#areaAccordion">
                                        <div class="accordion-body row">
                                            <div class="col-12">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input custom-checkbox select-all-checkbox" type="checkbox" id="select_all_{{ $areaIndex }}" data-group="group_{{ $areaIndex }}">
                                                    <label class="form-check-label text-danger" for="select_all_{{ $areaIndex }}">全區</label>
                                                </div>
                                            </div>
                                            @endif

                                            <div class="col-md-4 col-sm-6 col-12">
                                                <div class="form-check">
                                                    <input class="form-check-input custom-checkbox group_{{ $areaIndex }}"
                                                           type="checkbox"
                                                           name="service_area[]"
                                                           value="{{ $area->id }}"
                                                           id="area_{{ $area->id }}"
                                                           @if(in_array($area->id, $selectedAreas)) checked @endif>
                                                    <label class="form-check-label" for="area_{{ $area->id }}">
                                                        {{ $area->minor_area }}
                                                    </label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-0">
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-primary w-50">
                                            {{ __('確認') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <br>

            </div>
        </div>
    </div>
@endsection

<style>
    .custom-checkbox {
        transform: scale(1.3);
        border: 2px solid black !important;
    }
</style>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleButton = document.getElementById('toggle-all');
            let isExpanded = false;

            toggleButton.addEventListener('click', function () {
                const collapses = document.querySelectorAll('.accordion-collapse');
                collapses.forEach(collapse => {
                    const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapse);
                    if (!isExpanded) {
                        bsCollapse.show();
                    } else {
                        bsCollapse.hide();
                    }
                });
                toggleButton.textContent = isExpanded ? '全部展開' : '全部收合';
                isExpanded = !isExpanded;
            });
        });
        document.querySelectorAll('.select-all-checkbox').forEach(function(selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function () {
                const groupClass = this.getAttribute('data-group');
                const checkboxes = document.querySelectorAll('.' + groupClass);
                checkboxes.forEach(function (checkbox) {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            });
        });
    </script>
@endpush
