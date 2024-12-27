@extends('masters.layouts.master')

@section('title', '豫順清潔')

@section('content')
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
                <a href="{{ route('masters.appointmenttime.index') }}" class="custom-link">預約時段</a> &gt;
                新增預約時段
            </p>
        </div>
    </div>

{{--    <div class="container">--}}
{{--        <div class="row justify-content-center">--}}
{{--            <div class="col-md-6">--}}
{{--                <h2 class="text-center">新增可預約時段</h2>--}}

{{--                <form action="{{ route('masters.appointmenttime.store') }}" method="POST" role="form" enctype="multipart/form-data">--}}
{{--                    @csrf--}}
{{--                    @method('POST')--}}

{{--                    <!-- 選擇服務日期 -->--}}
{{--                    <div class="mb-3">--}}
{{--                        <label for="service_date" class="form-label">選擇服務日期:</label>--}}
{{--                        <input type="date" id="service_date" name="service_date" min="{{ date('Y-m-d') }}" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required class="form-control">--}}
{{--                    </div>--}}

{{--                    <!-- 選擇開始時間 -->--}}
{{--                    <div class="mb-3">--}}
{{--                        <label for="start_time" class="form-label">選擇開始時間:</label>--}}
{{--                        <input type="time" id="start_time" name="start_time" value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Taipei')->format('H:i') }}" required class="form-control">--}}
{{--                    </div>--}}

{{--                    <!-- 選擇結束時間 -->--}}
{{--                    <div class="mb-3">--}}
{{--                        <label for="end_time" class="form-label">選擇結束時間:</label>--}}
{{--                        <input type="time" id="end_time" name="end_time" value="{{  \Carbon\Carbon::now()->setTimezone('Asia/Taipei')->addMinutes(180)->format('H:i') }}" required class="form-control">--}}
{{--                    </div>--}}

{{--                    <button type="submit" class="btn btn-primary w-100">提交</button>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>--}}
{{--    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>--}}
{{--    <script>--}}
{{--        // 使用 jQuery 初始化 flatpickr 用於選擇日期--}}
{{--        $(document).ready(function() {--}}
{{--            // 初始化 flatpickr 日期選擇器--}}
{{--            $('#date').flatpickr({--}}
{{--                dateFormat: "Y-m-d H:i",--}}
{{--                minDate: "today",     // 只允許選擇今天以後的日期--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}
    <!-- 彈出視窗 -->
    <div id="schedule-modal" style="display: none;">
        <h4>新增時段預約</h4>
        <p>選擇的日期：<span id="modal-date"></span></p>
        <form id="schedule-form" action="{{ route('masters.appointmenttime.store') }}" method="POST">
            @csrf
            <input type="hidden" id="selected_date" name="service_date">
            <div class="form-group">
                <label for="master_id">選擇師傅</label>
                <select id="master_id" name="master_id" class="form-control" required>
                    <option value="">請選擇師傅</option>
                    @foreach($masters as $master)
                        <option value="{{ $master->id }}">{{ $master->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="service_area">選擇服務地區</label>
                <select id="service_area" name="service_area" class="form-control" required>
                    <option value="">請先選擇師傅</option>
                </select>
            </div>
            <div class="form-group">
                <label for="start_time">開始時間</label>
                <input type="time" id="start_time" name="start_time" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="end_time">結束時間</label>
                <input type="time" id="end_time" name="end_time" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="price">價格</label>
                <input type="number" id="price" name="price" class="form-control" required>
            </div>
        </form>
        <button id="modal-confirm" class="btn btn-primary">確認新增</button>
        <button id="modal-close" class="btn btn-secondary">取消</button>
    </div>


    @push('scripts')
        <!-- 引入 FullCalendar 中文语言包 -->
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.7/locales/zh-tw.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: 'zh-tw',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    events: [
                            @foreach($appointmenttimes as $appointmenttime)
                        {
                            title: '{{ $appointmenttime->master ? $appointmenttime->master->name : "暫無師傅" }}',
                            start: '{{ $appointmenttime->service_date }}T{{ $appointmenttime->start_time }}',
                            end: '{{ $appointmenttime->service_date }}T{{ $appointmenttime->end_time }}',
                            color: '{{ $appointmenttime->status == 1 ? "#28a745" : "#dc3545" }}',
                            id: '{{ $appointmenttime->id }}',
                        },
                        @endforeach
                    ],
                    dateClick: function(info) {
                        var modal = document.getElementById('schedule-modal');
                        var modalDate = document.getElementById('modal-date');
                        var selectedDateInput = document.getElementById('selected_date');
                        var serviceAreaSelect = document.getElementById('service_area');
                        var masterSelect = document.getElementById('master_id');

                        // 設置選中的日期
                        modalDate.textContent = info.dateStr;
                        selectedDateInput.value = info.dateStr;

                        // 當選擇師傅時動態加載服務地區
                        masterSelect.addEventListener('change', function() {
                            var masterId = this.value;
                            serviceAreaSelect.innerHTML = '<option value="">加載中...</option>';

                            if (masterId) {
                                fetch(`/api/masters/${masterId}/service-areas`)
                                    .then(response => response.json())
                                    .then(data => {
                                        serviceAreaSelect.innerHTML = '<option value="">請選擇服務地區</option>';
                                        data.forEach(area => {
                                            serviceAreaSelect.innerHTML += `<option value="${area.id}">${area.name}</option>`;
                                        });
                                    })
                                    .catch(error => {
                                        console.error('Error fetching service areas:', error);
                                        serviceAreaSelect.innerHTML = '<option value="">無法加載服務地區</option>';
                                    });
                            } else {
                                serviceAreaSelect.innerHTML = '<option value="">請先選擇師傅</option>';
                            }
                        });

                        // 顯示模態框
                        modal.style.display = 'block';
                    }
                });

                calendar.render();

                // 關閉彈窗
                document.getElementById('modal-close').addEventListener('click', function() {
                    document.getElementById('schedule-modal').style.display = 'none';
                });

                // 提交表單
                document.getElementById('modal-confirm').addEventListener('click', function() {
                    document.getElementById('schedule-form').submit();
                });
            });

        </script>
    @endpush
    @push('styles')
        <style>
            #schedule-modal {
                display: none;
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: white;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                z-index: 1000;
                width: 300px;
            }
            #schedule-modal h4 {
                margin-bottom: 20px;
            }
            .form-group {
                margin-bottom: 15px;
            }
            .btn {
                margin-top: 10px;
            }
        </style>
    @endpush

@endsection
