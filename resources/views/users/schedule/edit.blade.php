@extends('users.layouts.master')

@section('title', '編輯預約時段')

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
                    <a href="{{ route('masters.appointmenttime.index') }}" class="custom-link">可預約時段</a> &gt;
                    編輯可預約時段
                </p>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            <form method="POST" style="max-width: 600px; width: 100%;" action="{{ route('masters.appointmenttime.update', $appointmenttime->id) }}">
                @csrf
                @method('PATCH')

                <div class="form-group mb-3">
                    <label for="service_date">服務日期</label>
                    <input type="date" id="service_date" name="service_date" value="{{ old('service_date', $appointmenttime->service_date) }}" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label for="start_time">開始時間</label>
                    <input type="time" id="start_time" name="start_time" value="{{ old('start_time', $appointmenttime->start_time) }}" class="form-control" step="1" required>
                </div>

                <div class="form-group mb-3">
                    <label for="end_time">結束時間</label>
                    <input type="time" id="end_time" name="end_time" value="{{ old('end_time', $appointmenttime->end_time) }}" class="form-control" step="1" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">更新時段</button>
            </form>
            <div class="d-flex justify-content-center">
                <div class="form-group mb-3">
                    <form action="{{ route('masters.appointmenttime.destroy',  ['appointmenttime' => $appointmenttime->id]) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('確定要刪除這個時段嗎？')">刪除</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
