@extends('masters.layouts.master')

@section('title', '豫順家居媒合服務平台')

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
                    可預約時段
                </p>
            </div>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <!-- 下拉選單 -->
            <div class="dropdown">
                <button class="btn btn-success btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    操作選單
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li>
                        <a class="dropdown-item" href="{{ route('masters.appointmenttime.create') }}">新增可預約時段</a>
                    </li>
                    <li>
                        <!-- 複製時段的觸發按鈕 -->
                        <button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#copyModal">
                            複製當月時段
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <!-- 複製時段的彈出視窗 -->
        <div class="modal fade" id="copyModal" tabindex="-1" aria-labelledby="copyModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="copyModalLabel">選擇目標月份</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- 複製時段的表單 -->
                        <form method="POST" action="{{ route('masters.appointmenttime.copy') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="target_month" class="form-label">選擇目標月份：</label>
                                <select id="target_month" name="target_month" class="form-select">
                                    <option value="1">複製到一個月後</option>
                                    <option value="2">複製到兩個月後</option>
                                    <option value="3">複製到三個月後</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success">複製時段</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="calendar"></div>
    </div>
@endsection
    @push('styles')
        <style>
            #calendar {
                max-width: 100%;
                margin: 0 auto;
                height: 600px;  /* 設置明確的高度 */
            }
        </style>
    @endpush

@push('styles')
    <!-- flatpickr CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.7/main.min.css" rel="stylesheet" />

    <!-- fullcalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.7/main.min.css" rel="stylesheet" />

    <!-- select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- jquery-ui CSS -->
    <link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet" />
@endpush
@push('scripts')
    <!-- flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- fullcalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/index.global.min.js"></script>


    <!-- select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- jquery-ui JS -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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
                buttonText: {
                    today: '今日',
                    month: '月',
                    week: '週',
                    day: '日'
                },
                events: [
                        @foreach($appointmenttimes as $index => $appointmenttime)
                    {
                        title: {!! json_encode('<b>客戶名稱：</b>' . ($appointmenttime->user ? $appointmenttime->user->name : "暫無客戶") .
                            '<br><b>時段：</b>' . $appointmenttime->start_time . ' - ' . $appointmenttime->end_time .
                            '<br><b>狀態：</b>' .
                               ($appointmenttime->status == 1 ? "已確認" :
                                ($appointmenttime->status == 2 ? "已完成" :
                                ($appointmenttime->status == 3 ? "不成立" :
                                ($appointmenttime->status == 4 ? "取消" :
                                ($appointmenttime->status == 0
                                    ? ($appointmenttime->user_id === null ? "無預約" : "待確認")
                                    : "未知狀態")))))
                        ) !!},

                        start: '{{ $appointmenttime->service_date }}T{{ $appointmenttime->start_time }}',
                        end: '{{ $appointmenttime->service_date }}T{{ $appointmenttime->end_time }}',
                        url: '{{ route('masters.appointmenttime.edit',['hash_appointmenttime' => \Vinkla\Hashids\Facades\Hashids::encode($appointmenttime->id)]) }}',

                        color: {!! json_encode(
                            $appointmenttime->status == 1 ? "#28a745" :  // 綠色：已確認
                            ($appointmenttime->status == 2 ? "#007bff" : // 藍色：已完成
                            ($appointmenttime->status == 3 ? "#6c757d" : // 灰色：不成立
                            ($appointmenttime->status == 4 ? "#ffc107" : // 黃色：取消
                            "#dc3545"))) // 紅色：待確認
                        ) !!},
                        id: '{{ $appointmenttime->id }}'
                    }
                    @if (!$loop->last), @endif
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







