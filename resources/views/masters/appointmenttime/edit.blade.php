@extends('masters.layouts.master')

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
            <div class="d-flex justify-content-between align-items-center mt-2">
                <p class="fs-4 mb-0">
                    <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> &gt;
                    <a href="{{ route('masters.appointmenttime.index') }}" class="custom-link">可預約時段</a> &gt;
                    編輯可預約時段
                </p>
                <div class="text-size-controls btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
                </div>
            </div>
        </div>

        <div id="content" class="medium">
            <div class="d-flex justify-content-center align-items-start" style="min-height: 100vh; width: 100%;">
                <div class="w-100" style="max-width: 800px;">
                <!-- 更新時段表單 -->
                <form method="POST" action="{{ route('masters.appointmenttime.update', ['hash_appointmenttime' => \Vinkla\Hashids\Facades\Hashids::encode($appointmenttime->id)]) }}" class="mb-3">
                    @csrf
                    @method('PATCH')

                    <div class="form-group mb-3">
                        <label for="service_date">服務日期</label>
                        <input type="date" id="service_date" name="service_date" value="{{ old('service_date', $appointmenttime->service_date) }}" class="form-control"  disabled required>
                    </div>
                    @if($appointmenttime->status == 0 && $appointmenttime->user_id==null)
                    <div class="form-group mb-3">
                        <label for="start_time">開始時間</label>
                        <input type="time" id="start_time" name="start_time" value="{{ old('start_time', $appointmenttime->start_time) }}" class="form-control" step="1"  required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="end_time">結束時間</label>
                        <input type="time" id="end_time" name="end_time" value="{{ old('end_time', $appointmenttime->end_time) }}" class="form-control" step="1"  required>
                    </div>
                    @else
                        <div class="form-group mb-3">
                            <label for="start_time">開始時間</label>
                            <input type="time" id="start_time" name="start_time" value="{{ old('start_time', $appointmenttime->start_time) }}" class="form-control" step="1" disabled required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="end_time">結束時間</label>
                            <input type="time" id="end_time" name="end_time" value="{{ old('end_time', $appointmenttime->end_time) }}" class="form-control" step="1" disabled required>
                        </div>
                    @endif
                    <div class="form-group mb-3">
                        <label for="service">服務項目</label>
                        @if(optional($appointmenttime->schedulerecord)->service)
                            <input type="text" id="service" name="service" value="{{ old('service', $appointmenttime->schedulerecord->service->name) }}" class="form-control" disabled required>
                        @else
                            <input type="text" id="service" name="service" value="無服務項目" class="form-control" disabled required>
                        @endif
                    </div>

                    <div class="form-group mb-3">
                        <label for="service">服務項目</label>
                        @if(optional(optional($appointmenttime->schedulerecord)->service)->name)
                            <input type="text" id="service" name="service" value="{{ old('service', optional(optional($appointmenttime->schedulerecord)->service)->name) }}" class="form-control" disabled required>
                        @else
                            <input type="text" id="service" name="service" value="無客戶" class="form-control" disabled required>
                        @endif
                    </div>
                    <div class="form-group mb-3">
                        <label for="service_address">服務地址</label>
                        <input type="text" id="service_address" name="service_address" value="{{ old('service_address', $appointmenttime->service_address) }}" class="form-control" step="1" disabled required>
                    </div>
                    @if($appointmenttime->status == 2 && optional(optional($appointmenttime->schedulerecord)->scheduledetail))
                        <div class="card mt-3">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">客戶評價</h5>
                            </div>
                            <div class="card-body">
                                @if(optional($appointmenttime->schedulerecord->scheduledetail)->score)
                                    <div class="mb-3">
                                        <label for="service" class="form-label">客戶評分</label>
                                        <div class="fs-5 text-warning">
                                            {!! str_repeat('⭐', $appointmenttime->schedulerecord->scheduledetail->score) !!}
                                        </div>
                                    </div>
                                @endif

                                @if(optional($appointmenttime->schedulerecord->scheduledetail)->comment)
                                    <div class="mb-3">
                                        <label for="service" class="form-label">客戶評論</label>
                                        <div class="border rounded p-2 bg-light">
                                            {{ $appointmenttime->schedulerecord->scheduledetail->comment }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div><br>
                    @endif


                    @if($appointmenttime->status == 1)  {{-- 進行中 --}}
                    @php
                        $borrowRecords = \App\Models\BorrowingRecord::where('appointment_time_id', $appointmenttime->id)->get();
                    @endphp

                    @if($borrowRecords->isNotEmpty())
                        <div class="card mt-3">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0">已借用設備</h5>
                            </div>
                            <div class="card-body">
                                @foreach($borrowRecords as $record)
                                    <div class="border rounded p-2 mb-2 bg-light">
                                        <p><strong>設備名稱：</strong> {{ optional($record->equipment)->name ?? '未知設備' }}</p>
                                        <p><strong>數量：</strong> {{ $record->quantity }}</p>
                                        <p><strong>狀態：</strong> {{ $record->status == 1 ? '已歸還' : '使用中' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div><br>
                    @else
                        <a href="{{ route('masters.borrow_equipments.create', ['hash_appointmenttime' => \Vinkla\Hashids\Facades\Hashids::encode($appointmenttime->id)]) }}" class="btn btn-primary w-100 mb-2">借用設備</a><br><br>
                    @endif

                    {{-- 只有 "進行中" (status = 1) 時才顯示完成訂單按鈕 --}}
                    <a href="{{ route('masters.schedule_details.create', ['hash_appointmenttime' => \Vinkla\Hashids\Facades\Hashids::encode($appointmenttime->id)]) }}" class="btn btn-success w-100">完成訂單</a><br><br>
                    @endif

                    @if($appointmenttime->status == 0 && $appointmenttime->user_id==null)
                        <div class="d-flex justify-content-between">
                            <button type="submit" name="action" value="alter" class="btn btn-success w-100" >修改</button>
                        </div><br>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary w-100">返回</a><br><br>
                    @elseif($appointmenttime->status == 0 && $appointmenttime->user_id!=null)
                        <div class="d-flex justify-content-between">
                            <button type="submit" name="action" value="accept" class="btn btn-success w-100" onclick="return confirm('確定要接受這筆訂單？')">接受</button>
                            <button type="submit" name="action" value="reject" class="btn btn-secondary w-100" onclick="return confirm('確定不接受這筆訂單？')">不接受</button>\
                        </div><br>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary w-100">返回</a><br><br>
                    @else
                        <a href="{{ url()->previous() }}" class="btn btn-secondary w-100">返回</a><br><br>
                    @endif
                </form>
                    @if($appointmenttime->status == 0)  {{-- 只有在未接受的時段 (status = 0) 時才顯示刪除按鈕 --}}
                    <form action="{{ route('masters.appointmenttime.destroy',['hash_appointmenttime' => \Vinkla\Hashids\Facades\Hashids::encode($appointmenttime->id)]) }}" method="POST"><br>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
