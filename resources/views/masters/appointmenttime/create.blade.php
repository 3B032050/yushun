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
            <div class="d-flex justify-content-between align-items-center mt-2">
                <p class="fs-4 mb-0">
                    <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> &gt;
                    <a href="{{ route('masters.appointmenttime.index') }}" class="custom-link">可預約時段</a> &gt;
                    新增可預約時段
                </p>

                <div class="text-size-controls btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
                </div>
            </div>
        </div>

        <div id="content" class="medium">
            <div class="container mt-3">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <h2 class="text-center">新增可預約時段</h2>

                        <form action="{{ route('masters.appointmenttime.store') }}" method="POST" role="form" enctype="multipart/form-data">
                            @csrf
                            @method('POST')

                            <div class="mb-3">
                                <label for="service_date" class="form-label">選擇服務日期:</label>
                                <input type="date" id="service_date" name="service_date" min="{{ date('Y-m-d') }}" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="start_time" class="form-label">選擇開始時間:</label>
                                <input type="time" id="start_time" name="start_time" value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Taipei')->format('H:i') }}" required class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="end_time" class="form-label">選擇結束時間:</label>
                                <input type="time" id="end_time" name="end_time" value="{{  \Carbon\Carbon::now()->setTimezone('Asia/Taipei')->addMinutes(180)->format('H:i') }}" required class="form-control">
                            </div>

                            <button type="submit" class="btn btn-primary w-100">提交</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#date').flatpickr({
                    dateFormat: "Y-m-d H:i",
                    minDate: "today",
                });
            });
        </script>
    @endpush
@endsection
