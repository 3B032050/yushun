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
@endsection
@push('scripts')
    <!-- 引入 FullCalendar 中文语言包 -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.7/locales/zh-tw.js"></script>
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
                        price: '{{ $schedule->service->price }}',
                        service: '{{ $schedule->service->name }}',
                        appointmentTime: '{{ $schedule->appointment_time }}',
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
    #calendar {
        max-width: 100%;
        margin: 0 auto;
        height: 600px;
    }
</style>







