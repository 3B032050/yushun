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

    <!-- 設置標題和路徑 -->
    <div style="margin-top: 10px;">
        <p style="font-size: 1.8em;">
            <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> &gt;
            可預約時段
        </p>
    </div>
    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a class="btn btn-success btn-sm" href="{{ route('masters.appointmenttime.create') }}">新增可預約時段</a>
    </div>

    <!-- 日曆顯示區域 -->
    <div id="calendar"></div>

{{--    <div class="container mt-5">--}}
{{--        <h1 class="text-center">師傅可預約時段</h1>--}}

{{--        <!-- 如果沒有資料 -->--}}
{{--        @if($appointmenttimes->isEmpty())--}}
{{--            <p class="text-center">目前沒有可用的預約時段。</p>--}}
{{--        @else--}}
{{--            <table class="table table-bordered text-center">--}}
{{--                <thead>--}}
{{--                <tr>--}}
{{--                    <th>客戶名稱</th>--}}
{{--                    <th>日期</th>--}}
{{--                    <th>開始時間</th>--}}
{{--                    <th>結束時間</th>--}}
{{--                    <th>狀態</th>--}}
{{--                    <th>操作</th>--}}
{{--                </tr>--}}
{{--                </thead>--}}
{{--                <tbody>--}}
{{--                @foreach($appointmenttimes as $appointmenttime)--}}
{{--                    <tr>--}}
{{--                        <td>{{ $appointmenttime->user ? $appointmenttime->user->name : '暫無客戶' }}</td>--}}
{{--                        <td>{{ $appointmenttime->service_date }}</td>--}}
{{--                        <td>{{ $appointmenttime->start_time }}</td>--}}
{{--                        <td>{{ $appointmenttime->end_time }}</td>--}}
{{--                        @if($appointmenttime->status == '1')--}}
{{--                            <td class="text-success font-weight-bold">(已預約)</td>--}}
{{--                        @elseif($appointmenttime->status == '0')--}}
{{--                            <td class="text-danger font-weight-bold">(待預約)</td>--}}
{{--                        @endif--}}
{{--                        <td>--}}
{{--                            <a href="{{ route('masters.appointmenttime.edit', $appointmenttime->id) }}" class="btn btn-primary">編輯</a>--}}
{{--                            <form action="{{ route('masters.appointmenttime.destroy', $appointmenttime->id) }}" method="POST" style="display:inline;">--}}
{{--                                @csrf--}}
{{--                                @method('DELETE')--}}
{{--                                <button type="submit" class="btn btn-danger" onclick="return confirm('確定要刪除這個時段嗎？')">刪除</button>--}}
{{--                            </form>--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                @endforeach--}}
{{--                </tbody>--}}
{{--            </table>--}}
{{--        @endif--}}
{{--    </div>--}}
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
                        title: '{{ $appointmenttime->user ? $appointmenttime->user->name : "暫無客戶" }}',
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
                    // 這裡自定義事件的顯示方式
                    var startTime = arg.event.start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                    var endTime = arg.event.end.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                    return {
                        html: `
            <div class="fc-event-time">${startTime} - ${endTime}</div>
            <div class="fc-event-title">${arg.event.title}</div>
        `
                    };
                }
            });

            calendar.render(); // 渲染日曆
        });
    </script>
@endpush







