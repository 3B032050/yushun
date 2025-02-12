@extends('masters.layouts.master')

@section('title', '查看師傅排程')

@section('content')
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
                initialView: 'dayGridMonth', // 初始視圖
                locale: 'zh-tw', // 設置語言為繁體中文
                headerToolbar: {
                    left: 'prev,next today', // 顯示上月、下月、今天按鈕
                    center: 'title', // 顯示標題
                    right: 'dayGridMonth,timeGridWeek,timeGridDay' // 月視圖、周視圖、日視圖
                },
                events: function(fetchInfo, successCallback, failureCallback) {
                    let masterId = document.getElementById('master-select').value;

                    if (!masterId) {
                        successCallback([]); // 如果未選擇師傅，顯示空數據
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
                <div class="fc-event-title">${arg.event.title}</div>
                <div class="fc-event-description">${arg.event.extendedProps.description}</div>
            `
                    };
                },
                eventClick: function(info) {
                    console.log("點擊事件資料：", info.event.extendedProps);

                    // 只顯示年月日
                    const formattedDate = info.event.start.toLocaleDateString('zh-TW', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });

                    document.getElementById('modal-title').innerText = info.event.title;
                    document.getElementById('modal-body').innerHTML = `
                    <p><strong>服務日期：</strong> ${formattedDate}</p>
                    <p><strong>服務時間：</strong> ${info.event.extendedProps.time ?? '未設定'}</p>
                    <p><strong>服務內容：</strong> ${info.event.extendedProps.service ?? '未提供'}</p>
                    <p><strong>金額：</strong> ${info.event.extendedProps.price ?? '未提供'}</p>
                    <p><strong>狀態：</strong> ${info.event.extendedProps.description}</p>
                    `;

                    var myModal = new bootstrap.Modal(document.getElementById('eventDetailModal'));
                    myModal.show();
                }


            });

            calendar.render();

            // 當師傅選擇改變時，重新加載日曆事件
            document.getElementById('master-select').addEventListener('change', function() {
                let masterId = this.value;
                console.log('Selected master ID:', masterId); // 調試用
                calendar.refetchEvents(); // 重新加載事件
            });
        });
    </script>
@endpush
