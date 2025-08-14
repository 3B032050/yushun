@extends('users.layouts.master')

@section('title', '家居媒合服務平台')

@section('content')
    <div class="content-wrapper">
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
                <nav aria-label="breadcrumb" class="mb-2 mb-md-0 w-100 w-md-auto">
                    <ol class="breadcrumb breadcrumb-path mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('users.schedule.index') }}"> 預約時段</a></li>
                    </ol>
                </nav>
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
                            <a class="dropdown-item" href="{{ route('users.schedule.create') }}">
                                <i class="fas fa-plus-circle me-2"></i> 新增預約時段
                            </a>
                        </li>
                        <li>
                            <!-- 複製時段的觸發按鈕 -->
                            <button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#copyModal">
                                <i class="fas fa-copy me-2"></i> 複製當月時段
                            </button>
                        </li>
                    </ul>
                </div>
            </div>


            <div class="modal fade" id="copyModal" tabindex="-1" aria-labelledby="copyModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="copyModalLabel">選擇目標月份</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- 複製時段的表單 -->
                        <form method="POST" action="{{ route('users.schedule.copy') }}">
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
        <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reviewModalLabel">撰寫評論</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="reviewForm" method="POST" action="{{ route('users.schedule_details.review') }}">
                            @csrf
                            @method('POST')
                            <input type="hidden" name="schedule_id" id="schedule_id">

                            <div class="mb-3">
                                <label for="score" class="form-label">評分 (1-5)</label>
                                <select class="form-control" name="score" id="score" required>
                                    <option value="">請選擇</option>
                                    <option value="1">⭐ 1</option>
                                    <option value="2">⭐⭐ 2</option>
                                    <option value="3">⭐⭐⭐ 3</option>
                                    <option value="4">⭐⭐⭐⭐ 4</option>
                                    <option value="5">⭐⭐⭐⭐⭐ 5</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="comment" class="form-label">評語</label>
                                <textarea class="form-control" name="comment" id="comment" rows="3" required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">提交評論</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <!-- flatpickr CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet" />

    <!-- fullcalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.7/main.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.7/main.min.css" rel="stylesheet" />

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
        function openReviewModal(scheduleId) {
            document.getElementById('schedule_id').value = scheduleId;
            var reviewModal = new bootstrap.Modal(document.getElementById('reviewModal'));
            reviewModal.show();
        }

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
                buttonText: {
                    today: '今日',
                    month: '月',
                    week: '週',
                    day: '日'
                },
                events: [
                        @foreach($schedules as $schedule)
                    {
                        title: `{!! '<b>師傅名稱：</b>' . ($schedule->master ? $schedule->master->name : "未指派") .
                        '<br><b>時段：</b>' . $schedule->appointmenttime->start_time . ' - ' . $schedule->appointmenttime->end_time .
                        '<br><b>狀態：</b>' .
                        ($schedule->status == 1 ? "已確認" :
                        ($schedule->status == 2 ? "已完成" :
                        ($schedule->status == 3 ? "不成立" :
                        ($schedule->status == 4 ? "取消" : "待確認")))) !!}`,
                        start: '{{ $schedule->service_date }}',
                        end: '{{ $schedule->service_date }}',
                        price: '{{ $schedule->price }}',
                        service: '{{ $schedule->service->name }}',
                        appointmentTime: '{{ $schedule->appointment_time }}',
                        service_address: '{{ $schedule->service_address }}',
                        status: '{{ $schedule->status == 0 ? "待確認" :
                       ($schedule->status == 1 ? "已確認" :
                       ($schedule->status == 2 ? "已完成" :
                       ($schedule->status == 3 ? "不成立" :
                       ($schedule->status == 4 ? "已取消" : "未知狀態"))))}}',
                        color: '{{ $schedule->status == 0 ? "#28a745" : "#dc3545" }}',
                        id: '{{ $schedule->id }}',
                        score: '{{ $schedule->scheduledetail->score ?? "" }}',  // 評分
                        comment: '{{ $schedule->scheduledetail->comment ?? "" }}',  // 評論
                    },
                    @endforeach
                ],
                eventContent: function(arg) {
                    return {
                        html: `<div class="fc-event-title">${arg.event.title}</div>`  // 解析 HTML
                    };
                },
                eventClick: function(info) {
                    document.getElementById('modal-title').innerText = "詳細資訊";

                    const eventDate = new Date(info.event.start).toLocaleDateString('zh-TW', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });

                    let reviewButton = ""; // 預設不顯示評論按鈕
                    let reviewContent = ""; // 預設評論內容為空

                    if (info.event.extendedProps.score) {
                        // 如果已經有評分，顯示評分和評論內容，並加上區隔線
                        reviewContent = `
                        <hr>
                        <h6><strong>訂單已評論</strong></h6>
                        <p><strong>評分：</strong> ${"⭐".repeat(info.event.extendedProps.score)}</p>
                        <p><strong>評論：</strong> ${info.event.extendedProps.comment}</p>
                        `;
                    } else if (info.event.extendedProps.status === "已完成") {
                        // 如果沒有評分，但狀態為「已完成」，顯示評論按鈕
                        reviewButton = `<button class="btn btn-warning mt-2" onclick="openReviewModal(${info.event.id})">撰寫評論</button>`;
                    }

                    document.getElementById('modal-body').innerHTML = `
                    <p><strong>預約日期：</strong> ${eventDate}</p>
                    <p><strong>預約時間：</strong> ${info.event.extendedProps.appointmentTime}</p>
                    <p><strong>服務內容：</strong> ${info.event.extendedProps.service}</p>
                    <p><strong>金額：</strong> ${info.event.extendedProps.price}</p>
                    <p><strong>狀態：</strong> ${info.event.extendedProps.status}</p>
                    ${reviewContent} <!-- 顯示評分與評論內容，並加上「訂單已評論」標示 -->
                    ${reviewButton} <!-- 在狀態為已完成且未評論時顯示按鈕 -->
                    `;

                    var myModal = new bootstrap.Modal(document.getElementById('eventDetailModal'));
                    myModal.show();
                }
            });

            calendar.render();
        });
    </script>
@endpush
<style>
    .breadcrumb-path {
        font-size: 1.4em;
        white-space: normal;
        word-break: break-word;
    }

    @media (max-width: 768px) {
        .breadcrumb-path {
            font-size: 1.3em;
        }
        .text-size-controls {
            margin-top: 0.5rem;
        }
    }

    @media (max-width: 480px) {
        .breadcrumb-path {
            font-size: 1.1em;
        }
        .d-flex.flex-column.flex-md-row > .btn-group {
            width: 100%;
            justify-content: center;
        }
    }
    #calendar {
        max-width: 100%;
        margin: 0 auto;
        height: 600px;
    }
</style>







