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
                可預約時段
            </p>
        </div>
    </div>

    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a class="btn btn-success btn-sm" href="{{ route('masters.appointmenttime.create') }}">新增可預約時段</a>
    </div>

    <!-- 日曆顯示區域 -->
    <div id="calendar"></div>
@endsection
    @push('styles')
        <style>
            #calendar {
                max-width: 100%;
                margin: 0 auto;
                height: 600px;  /* 設置明確的高度 */
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

            // 初始化 FullCalendar
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth', // 初始視圖是月份視圖
                locale: 'zh-tw',  // 設置語言為繁體中文
                headerToolbar: {
                    left: 'prev,next today', // 顯示上月、下月、今天
                    center: 'title',         // 顯示標題
                    right: 'dayGridMonth,timeGridWeek,timeGridDay' // 月視圖、周視圖、日視圖
                },
                events: [
                        @foreach($appointmenttimes as $appointmenttime)
                    {
                        title: '<b>客戶名稱：</b>{{ $appointmenttime->user ? $appointmenttime->user->name : "暫無客戶" }}<br><b>時段：</b>{{ $appointmenttime->start_time }} - {{ $appointmenttime->end_time }}',
                        start: '{{ $appointmenttime->service_date }}T{{ $appointmenttime->start_time }}',
                        end: '{{ $appointmenttime->service_date }}T{{ $appointmenttime->end_time }}',
                        url: '{{ route('masters.appointmenttime.edit', $appointmenttime->id) }}',
                        color: '{{ $appointmenttime->status == 1 ? "#28a745" : "#dc3545" }}', // 根據狀態設置顏色
                        id: '{{ $appointmenttime->id }}', // 添加事件ID
                    },
                    @endforeach

                ],
                editable: true, // 可以拖拽修改
                droppable: true, // 可以拖拽
                eventContent: function(arg) {
                    return {
                        html: `<div class="fc-event-title">${arg.event.title}</div>`  // 只顯示 title
                    };
                    // 提取开始时间和结束时间
                    var startTime = arg.event.start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                    var endTime = arg.event.end ? arg.event.end.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '';

                    return {
                        html: `
            <div class="fc-event-title">${arg.event.title.replace(/\n/g, '<br>')}</div>
            <div class="fc-event-time">${startTime} - ${endTime}</div>
        `
                    };
                }

            });

            calendar.render(); // 渲染日曆
        });
    </script>
@endpush







