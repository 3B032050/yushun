@extends('users.layouts.master')

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
                <a href="{{ route('users.schedule.index') }}" class="custom-link">預約時段</a> &gt;
                新增預約時段
            </p>
        </div>
    </div>

    <!-- 彈出視窗 -->
    <div id="schedule-modal" class="hidden">
        <h4>新增時段預約</h4>
        <p>選擇的日期：<span id="modal-date"></span></p>
        <form id="schedule-form" action="{{ route('users.schedule.store') }}" method="POST">
            @csrf
            <input type="hidden" id="selected_date" name="service_date">
            <label>
                <input type="checkbox" name="is_recurring" id="is_recurring">
                是否為定期客戶
            </label>
            <!-- 顯示錯誤訊息 -->
            @if ($errors->has('recurring_interval'))
                <p class="text-danger">{{ $errors->first('recurring_interval') }}</p>
            @endif
            <div class="form-group">
            <div id="recurring_options" style="display: none;">
                    <label>每隔幾天預約一次：</label>
                    <input type="number" name="recurring_interval" min="1">
                </div>
            </div>
            <!-- 服務項目選擇 -->
            <div class="form-group">
                <label for="service_id">選擇服務項目</label>
                <select id="service_id" name="service_id" class="form-control" required>
                    <option value="">請選擇服務項目</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="service_price">價格</label>
                <p id="service_price" class="form-control-static">請選擇服務項目</p>
            </div>

            <!-- 師傅選擇 -->
            <div class="form-group">
                <label for="master_id">選擇師傅</label>
                <select id="master_id" name="master_id" class="form-control" disabled required>
                    <option value="">請先選擇服務項目</option>
                </select>
            </div>

            <div class="form-group">
                <label for="available_times">可預約時段</label>
                <select id="available_times" name="appointment_time_id" class="form-control">
                    <option value="">請先選擇師傅</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">確認</button>
            <button class="btn btn-secondary close-modal">取消</button>
        </form>


    </div>

    <div id="calendar"></div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.7/locales/zh-tw.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');
            const modal = document.getElementById('schedule-modal');
            const closeModalButtons = document.querySelectorAll('.close-modal');
            const modalDate = document.getElementById('modal-date');
            const selectedDateInput = document.getElementById('selected_date');
            const serviceSelect = document.getElementById('service_id');
            const masterSelect = document.getElementById('master_id');
            const availableTimesSelect = document.getElementById('available_times');
            const confirmButton = document.getElementById('confirm-schedule');
            const scheduleForm = document.getElementById('schedule-form');
            let selectedDate = null;

            closeModalButtons.forEach(button => {
                button.addEventListener('click', () => {
                    modal.classList.add('hidden');
                });
            });
            document.getElementById('is_recurring').addEventListener('change', function () {
                document.getElementById('recurring_options').style.display = this.checked ? 'block' : 'none';
            });
            // confirmButton.addEventListener('click', function (event) {
            //     event.preventDefault();  // 阻止表單的預設提交
            //
            //     const selectedDate = document.getElementById('selected_date').value;
            //     const masterId = document.getElementById('master_id').value;
            //     const serviceId = document.getElementById('service_id').value;
            //     const availableTime = document.getElementById('available_times').value;  // 修正抓取值
            //
            //     if (!selectedDate || !masterId || !serviceId || !availableTime) {
            //         alert('請確保所有選項都已填寫完整');
            //         return;
            //     }
            //
            //     scheduleForm.submit();  // 提交表單
            // });

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'zh-tw',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: function(info, successCallback, failureCallback) {
                    const events = [];

                    @if($appointmenttimes && $appointmenttimes->count() > 0)
                        {{-- 先收集所有有可預約時段的日期 --}}
                        @php
                            $availableDates = $appointmenttimes->pluck('service_date')->unique();
                        @endphp

                        {{-- 為每個日期添加一個 "可預約" 事件 --}}
                        @foreach($availableDates as $date)
                        events.push({
                            title: '可預約',
                            start: '{{ $date }}', // 只使用日期
                            end: '{{ $date }}',
                            color: '#28a745', // 綠色表示可預約
                            textColor: '#ffffff', // 白色文字
                        });
                        @endforeach
                    @else
                    // 無可預約師傅，添加無法預約事件
                        events.push({
                            title: '無可預約師傅',
                            color: '#dc3545', // 紅色表示無法預約
                            textColor: '#ffffff', // 白色文字
                        });
                    @endif


                    // 使用 successCallback 返回事件
                    successCallback(events);
                },
                eventRender: function(info) {
                    // 使用 CSS 居中標題
                    const titleElement = info.el.querySelector('.fc-event-title');
                    if (titleElement) {
                        titleElement.style.textAlign = 'center'; // 居中標題
                    }
                },
                dateClick: function(info) {
                    selectedDate = info.dateStr;
                    modalDate.textContent = new Date(selectedDate).toLocaleDateString('zh-TW', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                    selectedDateInput.value = selectedDate;
                    modal.classList.remove('hidden');
                }
            });

            calendar.render();

            serviceSelect.addEventListener('change', function () {
                const serviceId = this.value;
                const servicePriceElement = document.getElementById('service_price');

                console.log('Service ID:', serviceId);

                // 確認是否已選擇日期
                if (!selectedDate) {
                    alert('請先選擇日期');
                    return;
                }

                masterSelect.innerHTML = '<option value="">加載中...</option>';
                masterSelect.disabled = true;

                availableTimesSelect.innerHTML = '<option value="">請先選擇師傅</option>';
                availableTimesSelect.disabled = true;

                // 透過 AJAX 獲取對應的價格
                fetch(`{{ url('users/schedule/getServicePrice') }}?service_id=${serviceId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            servicePriceElement.textContent = `NT$ ${data.price}`;
                        } else {
                            servicePriceElement.textContent = '無法獲取價格';
                        }
                    })
                    .catch(error => {
                        servicePriceElement.textContent = '錯誤: 無法獲取價格';
                        console.error('Error:', error);
                    });

                // 取得可預約的師傅
                fetch(`{{ url('users/schedule/available_masters') }}?service_id=${serviceId}&date=${selectedDate}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Data received:', data);

                        if (data.status === 'empty') {
                            masterSelect.innerHTML = '<option value="">該師傅當日無可預約時段</option>';
                            masterSelect.disabled = true;
                        } else if (data.status === 'success' && data.data.length > 0) {
                            masterSelect.innerHTML = '<option value="">請選擇師傅</option>';
                            data.data.forEach(master => {
                                const option = document.createElement('option');
                                option.value = master.id;
                                option.textContent = master.name;
                                masterSelect.appendChild(option);
                            });

                            masterSelect.disabled = false;
                        } else {
                            masterSelect.innerHTML = '<option value="">無法加載師傅</option>';
                            masterSelect.disabled = true;
                        }
                    })
                    .catch(error => {
                        masterSelect.innerHTML = '<option value="">無法加載師傅</option>';
                        masterSelect.disabled = true;
                        console.error('Error:', error);
                    });
            });
            masterSelect.addEventListener('change', function () {
                const masterId = this.value;
                availableTimesSelect.innerHTML = '<option value="">加載中...</option>';
                availableTimesSelect.disabled = true;  // 每次變更時先鎖定

                if (!masterId) return;

                fetch(`{{ url('users/schedule/available_times') }}?date=${selectedDate}&master_id=${masterId}`)
                    .then(response => response.json())
                    .then(data => {
                        availableTimesSelect.innerHTML = '<option value="">請選擇可預約時段</option>';

                        if (data.length === 0) {
                            // 如果沒有可預約時段，顯示「無可預約時段」
                            const option = document.createElement('option');
                            option.value = "";
                            option.textContent = "無可預約時段";
                            availableTimesSelect.appendChild(option);
                            availableTimesSelect.disabled = true;  // 無資料時保持禁用
                        } else {
                            // 有可預約時段時，顯示時段選項
                            data.forEach(time => {
                                const option = document.createElement('option');
                                option.value = time.id;
                                option.textContent = `${time.start_time} - ${time.end_time}`;
                                availableTimesSelect.appendChild(option);
                            });
                            availableTimesSelect.disabled = false;  // 有資料時啟用
                        }
                    })
                    .catch(error => {
                        availableTimesSelect.innerHTML = '<option value="">無法加載時段</option>';
                        availableTimesSelect.disabled = true;  // 發生錯誤時禁用
                        console.error('Error:', error);
                    });
            });
        });


    </script>


    @push('styles')
    <style>
        #schedule-modal.hidden {
            display: none;
        }
        .fc-event-title {
            text-align: center !important; /* 強制居中 */
            width: 100%; /* 確保標題區域寬度填滿 */
            display: block; /* 使標題區域成為區塊元素 */
        }
        #schedule-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            width: 90%;
            max-width: 300px;
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

        #calendar {
            max-width: 100%;
            margin: 0 auto;
            height: 600px;
        }
    </style>
@endpush
