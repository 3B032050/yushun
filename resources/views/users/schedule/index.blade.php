@extends('users.layouts.master')

@section('title', '豫順清潔')

@section('content')
    @if(session('info'))
        <div class="alert alert-info">
            {{ session('info') }}
        </div>
    @endif
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
                <a href="{{ route('users.index') }}" class="custom-link"><i class="fa fa-home"></i></a> &gt;
                預約時段
            </p>
        </div>
    </div>

    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a class="btn btn-success btn-sm" href="{{ route('users.schedule.create') }}">新增預約時段</a>
    </div>

    <!-- 日曆顯示區域 -->
    <div id="calendar"></div>

    <div class="modal fade" id="eventDetailModal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-body">
                    <!-- 內容將透過 JS 動態填入 -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        #calendar {
            max-width: 100%;
            margin: 0 auto;
            height: 600px;
           /*min-height: 300px;*/
            /* 設置明確的高度 */
        }
        .fc-event-delete-container {
            margin-top: 10px; /* 上方間隔 */
            display: block;   /* 確保容器占滿整行 */
            text-align: center; /* 可選，讓刪除按鈕居中 */
        }

        .fc-event-delete {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            font-size: 12px;
            cursor: pointer;
            display: block; /* 確保按鈕是塊級元素 */
            margin: 0 auto; /* 居中對齊按鈕 */
        }

        .fc-event-delete:hover {
            background-color: #c82333;
        }
    </style>
@endpush

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
                        @foreach($schedules as $schedule)
                    {

                        title: '師傅名稱：{{ $schedule->master->name }}',
                        start: '{{ $schedule->service_date }}',
                        end: '{{ $schedule->service_date }}',
                        price: '{{ $schedule->service->price }}',
                        appointmentTime: '{{ $schedule->appointment_time }}',
                        status: '{{ $schedule->status == 0 ? "已確認" : "待確認" }}',
                        color: '{{ $schedule->status == 0 ? "#28a745" : "#dc3545" }}',
                        id: '{{ $schedule->id }}',
                    },
                    @endforeach
                ],
                eventClick: function(info) {
                    // 設置 Modal 內的內容
                    document.getElementById('modal-title').innerText = info.event.title;
                    document.getElementById('modal-body').innerHTML = `
                <p><strong>預約日期：</strong> ${info.event.start.toLocaleString()}</p>
                <p><strong>預約時間：</strong> ${info.event.extendedProps.appointmentTime}</p>
                <p><strong>金額：</strong> ${info.event.extendedProps.price}</p>
                <p><strong>狀態：</strong> ${info.event.extendedProps.status}</p>
            `;

                    // 顯示 Modal
                    var myModal = new bootstrap.Modal(document.getElementById('eventDetailModal'));
                    myModal.show();
                }
            });

            calendar.render();
        });
    </script>
@endpush







