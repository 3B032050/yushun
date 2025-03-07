@extends('masters.layouts.master')

@section('title', '查看師傅排程')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div style="margin-top: 10px;">
                <p style="font-size: 1.8em;">
                    <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                    查看師傅排程
                </p>
            </div>
            <h1 class="mt-4 text-center">查看師傅排程</h1>
        </div>

        <div class="container">
            <!-- 師傅選擇 -->
            <div class="form-group">
                <select id="master-select" class="form-control">
                    <option value="" selected disabled>請選擇師傅</option>
                    @foreach ($masters as $master)
                        <option value="{{ $master->id }}">{{ $master->name }}</option>
                    @endforeach
                </select>
            </div><br><br>

            <!-- 行事曆 -->
            <div id="calendar"></div>
        </div>
        <div class="modal fade" id="eventDetailModal" tabindex="-1" aria-labelledby="eventDetailModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-title">事件詳情</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modal-body">
                        <!-- 這裡會透過 JavaScript 動態填充內容 -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
                    </div>
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
                events: function(fetchInfo, successCallback, failureCallback) {
                    let masterId = document.getElementById('master-select').value;

                    if (!masterId) {
                        successCallback([]);
                        return;
                    }

                    fetch(`/admins/schedules/getScheduleData?master_id=${masterId}`)
                        .then(response => response.json())
                        .then(data => successCallback(data))
                        .catch(error => {
                            console.error('Error fetching schedule data:', error);
                            failureCallback(error);
                        });
                },
                eventContent: function(arg) {
                    return {
                        html: `
                            <div class="fc-event-content">
                                <div><strong>客戶：</strong> ${arg.event.extendedProps.customer}</div>
                                <div><strong>時段：</strong> ${arg.event.extendedProps.time ?? '未設定'}</div>
                                <div><strong>狀態：</strong>${arg.event.extendedProps.description}</div>
                            </div>
        `
                    };
                },
                eventClick: function(info) {
                    document.getElementById('modal-title').innerText = "詳細資訊";

                    const eventDate = new Date(info.event.start).toLocaleDateString('zh-TW', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });

                    let reviewContent = "";
                    if (info.event.extendedProps.score) {
                        reviewContent = `
                        <hr>
                        <h6><strong>訂單已評論</strong></h6>
                        <p><strong>評分：</strong> ${"⭐".repeat(info.event.extendedProps.score)}</p>
                        <p><strong>評論：</strong> ${info.event.extendedProps.comment}</p>
                        `;
                    }

                    document.getElementById('modal-body').innerHTML = `
                    <p><strong>預約日期：</strong> ${eventDate}</p>
                    <p><strong>預約時間：</strong> ${info.event.extendedProps.time}</p>
                    <p><strong>服務內容：</strong> ${info.event.extendedProps.service}</p>
                    <p><strong>金額：</strong> ${info.event.extendedProps.price}</p>
                    <p><strong>狀態：</strong> ${info.event.extendedProps.description}</p>
                    ${reviewContent}
                    `;

                    var myModal = new bootstrap.Modal(document.getElementById('eventDetailModal'));
                    myModal.show();
                }
            });

            calendar.render();

            document.getElementById('master-select').addEventListener('change', function() {
                calendar.refetchEvents();
            });
        });
    </script>
@endpush
