@extends('users.layouts.master')

@section('title', '豫順家居服務媒合平台')

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
                <nav aria-label="breadcrumb" class="mb-2 mb-md-0 w-100 w-md-auto">
                    <ol class="breadcrumb breadcrumb-path mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('users.schedule.index') }}"> 預約時段</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> 新增時段</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- 彈出視窗 -->
        <div id="schedule-modal" class="hidden">
            <h4>新增時段預約</h4>
            <p>選擇的日期：<span id="modal-date"></span></p>
            <form id="schedule-form" action="{{ route('users.schedule.store') }}" method="POST">
                @csrf
                <input type="hidden" id="selected_date" name="service_date">
{{--                <label>--}}
{{--                    <input type="checkbox" name="is_recurring" id="is_recurring">--}}
{{--                    是否為定期客戶--}}
{{--                </label>--}}
                <!-- 顯示錯誤訊息 -->
                @if ($errors->has('recurring_interval'))
                    <p class="text-danger">{{ $errors->first('recurring_interval') }}</p>
                @endif
                @if(Auth::user()->is_recurring == 1)
                    <div class="form-group" id="recurring_options">
                        <div>
                            <label for="recurring_interval">每次預約間隔（週）：</label>
                            <input type="number" id="recurring_interval" name="recurring_interval" min="1" max="4" value="1">
                        </div>
                        <div>
                            <label for="recurring_times">請選擇您希望的預約次數：</label>
                            <select id="recurring_times" name="recurring_times">
                                {{-- 動態生成 --}}
                            </select>
                        </div>
                        <div id="recurring_dates"></div>
                    </div>
                @endif
                <!-- 隱藏欄位，用來傳遞服務地址 -->
                <input type="hidden" id="address" name="address">
                <div class="address-option">
                    <label>選擇服務地址：</label>
                    <div class="address-option-group">
                        <div class="address-item">
                            <input type="radio" name="address_option" id="use_profile_address" value="profile" checked>
                            <label for="use_profile_address">使用個人地址（{{ Auth::user()->address }}）</label>
                        </div>
                        <div class="address-item">
                            <!-- 選擇手動輸入地址 -->
                            <input type="radio" name="address_option" id="enter_new_address" value="custom">
                            <label for="enter_new_address">輸入欲服務地址</label>
                        </div>
                    </div>

                    <!-- 手動輸入地址的欄位，預設隱藏 -->
                    <div id="custom_address_input" style="display: none;">
                        <input type="text" class="form-control" id="custom_address" name="custom_address" placeholder="請輸入地址">
                    </div><br>
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

                <div class="form-group">
                    <label for="total_price">總金額</label>
                    <p id="total_price" class="form-control-static">請選擇服務項目與時段</p>
                    <input type="hidden" id="total_price_input" name="total_price" value="">
                </div>
                <button type="submit" class="btn btn-primary">確認</button>
                <button class="btn btn-secondary close-modal">取消</button>
            </form>


        </div>

        <div id="calendar"></div>
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

    <!-- jQuery UI CSS -->
    <link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet" />
@endpush

@push('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- FullCalendar 必須在初始化 script 之前 -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/index.global.min.js"></script>
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 先檢查必須的元素是否存在
            const calendarEl = document.getElementById('calendar');
            const modal = document.getElementById('schedule-modal');
            const modalDate = document.getElementById('modal-date');
            const selectedDateInput = document.getElementById('selected_date');
            const serviceSelect = document.getElementById('service_id');
            const masterSelect = document.getElementById('master_id');
            const availableTimesSelect = document.getElementById('available_times');
            const scheduleForm = document.getElementById('schedule-form');
            const servicePriceElement = document.getElementById('service_price');
            const totalPriceElement = document.getElementById('total_price');
            const totalPriceInput = document.getElementById('total_price_input'); // 隱藏輸入欄位

            const recurringIntervalEl = document.getElementById('recurring_interval');
            const recurringTimesEl = document.getElementById('recurring_times');
            const recurringDatesContainer = document.getElementById('recurring_dates');

            const addressField = document.getElementById('address');
            const addressOptionProfile = document.getElementById('use_profile_address');
            const customRadio = document.getElementById('enter_new_address');
            const customAddressInput = document.getElementById('custom_address_input');
            const customAddress = document.getElementById('custom_address');

            let serviceAddress = '{{ Auth::user()->address }}';
            let selectedDate = null;

            // ------------------- 防呆：定期預約 -------------------
            if (recurringIntervalEl) {
                recurringIntervalEl.addEventListener('change', updateRecurringTimes);
            }
            if (recurringTimesEl) {
                recurringTimesEl.addEventListener('change', calculateRecurringDates);
            }

            function updateRecurringTimes() {
                if (!recurringIntervalEl || !recurringTimesEl || !selectedDateInput) return;

                let selectedDateVal = selectedDateInput.value;
                let recurringInterval = parseInt(recurringIntervalEl.value);
                recurringTimesEl.innerHTML = '';

                if (!selectedDateVal || isNaN(recurringInterval)) return;

                let startDate = new Date(selectedDateVal);
                let monthEndDate = new Date(startDate.getFullYear(), startDate.getMonth() + 1, 0);
                let daysToEndOfMonth = monthEndDate.getDate() - startDate.getDate();
                let maxWeeksInMonth = Math.floor(daysToEndOfMonth / 7);
                let maxRecurringTimes = Math.floor(maxWeeksInMonth / recurringInterval);

                if (maxRecurringTimes <= 0) {
                    let option = document.createElement('option');
                    option.value = 0;
                    option.textContent = "當月不滿足間隔天數，請重新選擇日期";
                    recurringTimesEl.appendChild(option);
                } else {
                    for (let i = 1; i <= maxRecurringTimes; i++) {
                        let option = document.createElement('option');
                        option.value = i;
                        option.textContent = `${i} 次`;
                        recurringTimesEl.appendChild(option);
                    }
                }
                calculateRecurringDates();
            }

            function calculateRecurringDates() {
                if (!recurringIntervalEl || !recurringTimesEl || !recurringDatesContainer || !selectedDateInput) return;

                let selectedDateVal = selectedDateInput.value;
                let recurringInterval = parseInt(recurringIntervalEl.value);
                let recurringTimes = parseInt(recurringTimesEl.value);
                recurringDatesContainer.innerHTML = '';

                if (!selectedDateVal || isNaN(recurringInterval) || isNaN(recurringTimes)) return;

                let startDate = new Date(selectedDateVal);
                let monthEndDate = new Date(startDate.getFullYear(), startDate.getMonth() + 1, 0);
                let addedDates = [];

                for (let i = 0; i < recurringTimes; i++) {
                    let nextDate = new Date(startDate);
                    nextDate.setDate(startDate.getDate() + i * recurringInterval * 7);
                    if (nextDate > monthEndDate) break;
                    addedDates.push(nextDate.toISOString().split('T')[0]);
                }

                // 更新實際次數
                recurringTimesEl.value = addedDates.length;
                console.log("產生的日期:", addedDates);
            }

            // ------------------- 地址切換 -------------------
            function toggleAddressInput() {
                if (!addressField) return;
                if (customRadio && customRadio.checked) {
                    if (customAddressInput) customAddressInput.style.display = 'block';
                    serviceAddress = customAddress ? customAddress.value.trim() : serviceAddress;
                } else {
                    if (customAddressInput) customAddressInput.style.display = 'none';
                    if (customAddress) customAddress.value = '';
                    serviceAddress = '{{ Auth::user()->address }}';
                }
                addressField.value = serviceAddress;
            }
            if (addressOptionProfile) addressOptionProfile.addEventListener('change', toggleAddressInput);
            if (customRadio) customRadio.addEventListener('change', toggleAddressInput);
            if (customAddress) customAddress.addEventListener('input', function () {
                if (customRadio && customRadio.checked) {
                    serviceAddress = this.value.trim();
                    if (addressField) addressField.value = serviceAddress;
                }
            });
            toggleAddressInput();

            // ------------------- 日曆 -------------------
            if (calendarEl) {
                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: 'zh-tw',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    buttonText: { today: '今日', month: '月', week: '週', day: '日' },
                    events: function(info, successCallback) {
                        const events = [];
                        @if($appointmenttimes && $appointmenttimes->count() > 0)
                        @php $availableDates = $appointmenttimes->pluck('service_date')->unique(); @endphp
                        @foreach($availableDates as $date)
                        events.push({ title: '點選', start: '{{ $date }}', end: '{{ $date }}', color: '#28a745', textColor: '#ffffff' });
                        @endforeach
                        @else
                        events.push({ title: '無可預約師傅', color: '#dc3545', textColor: '#ffffff' });
                        @endif
                        successCallback(events);
                    },
                    eventContent: function(arg) {
                        return { html: `<div style="text-align: center;">${arg.event.title}</div>` };
                    },
                    dateClick: function(info) {
                        selectedDate = info.dateStr;
                        if (!modal || !modalDate || !selectedDateInput) return;

                        const today = new Date();
                        today.setHours(0, 0, 0, 0);
                        const clickedDate = new Date(info.dateStr);
                        clickedDate.setHours(0, 0, 0, 0);

                        if (clickedDate <= today) { alert('選擇的日期不能是過去日期'); return; }

                        modalDate.textContent = clickedDate.toLocaleDateString('zh-TW', { year:'numeric', month:'long', day:'numeric' });
                        selectedDateInput.value = info.dateStr;
                        modal.classList.remove('hidden');
                    }
                });
                calendar.render();
            }

            // ------------------- 服務項目選擇 -------------------
            if (serviceSelect) {
                serviceSelect.addEventListener('change', function () {
                    if (!selectedDate) { alert('請先選擇日期'); return; }

                    if (masterSelect) {
                        masterSelect.innerHTML = '<option value="">加載中...</option>';
                        masterSelect.disabled = true;
                    }
                    if (availableTimesSelect) {
                        availableTimesSelect.innerHTML = '<option value="">請先選擇師傅</option>';
                        availableTimesSelect.disabled = true;
                    }

                    // 獲取服務價格
                    fetch(`{{ url('users/schedule/getServicePrice') }}?service_id=${this.value}&date=${selectedDate}&address=${serviceAddress}`)
                        .then(res => res.json())
                        .then(data => {
                            if (servicePriceElement) servicePriceElement.textContent = data.status === 'success' ? `NT$ ${data.price}` : '無法獲取價格';
                        }).catch(err => console.error(err));

                    // 獲取可預約師傅
                    fetch(`{{ url('users/schedule/available_masters') }}?service_id=${this.value}&date=${selectedDate}&address=${serviceAddress}`)
                        .then(res => res.json())
                        .then(data => {
                            if (!masterSelect) return;
                            masterSelect.innerHTML = '';
                            if (data.status === 'success' && data.data.length > 0) {
                                masterSelect.innerHTML = '<option value="">請選擇師傅</option>';
                                data.data.forEach(master => {
                                    const option = document.createElement('option');
                                    option.value = master.id;
                                    option.textContent = master.name;
                                    masterSelect.appendChild(option);
                                });
                                masterSelect.disabled = false;
                            } else {
                                masterSelect.innerHTML = '<option value="">無可預約師傅</option>';
                                masterSelect.disabled = true;
                            }
                        }).catch(err => console.error(err));
                });
            }

            // ------------------- 師傅選擇 -------------------
            if (masterSelect && availableTimesSelect) {
                masterSelect.addEventListener('change', function () {
                    const masterId = this.value;
                    if (!masterId) return;

                    availableTimesSelect.innerHTML = '<option value="">加載中...</option>';
                    availableTimesSelect.disabled = true;

                    fetch(`{{ url('users/schedule/available_times') }}?date=${selectedDate}&master_id=${masterId}&address=${serviceAddress}`)
                        .then(res => res.json())
                        .then(data => {
                            availableTimesSelect.innerHTML = '<option value="">請選擇可預約時段</option>';
                            if (data.length === 0) {
                                const option = document.createElement('option');
                                option.value = "";
                                option.textContent = "無可預約時段";
                                availableTimesSelect.appendChild(option);
                                availableTimesSelect.disabled = true;
                            } else {
                                data.forEach(time => {
                                    const option = document.createElement('option');
                                    option.value = time.id;
                                    option.textContent = `${time.start_time} - ${time.end_time}`;
                                    availableTimesSelect.appendChild(option);
                                });
                                availableTimesSelect.disabled = false;
                            }
                        }).catch(err => console.error(err));

                    // 監聽時段選擇
                    availableTimesSelect.addEventListener('change', function () {
                        if (!this.value || !serviceSelect.value) {
                            if (totalPriceElement) totalPriceElement.textContent = '請選擇服務項目與時段';
                            if (totalPriceInput) totalPriceInput.value = '';
                            return;
                        }
                        fetch(`{{ url('users/schedule/getTotalPrice') }}?service_id=${serviceSelect.value}&appointment_time=${this.value}&address=${serviceAddress}`)
                            .then(res => res.json())
                            .then(data => {
                                if (totalPriceElement) totalPriceElement.textContent = data.status === 'success' ? `NT$ ${data.price}` : '無法獲取價格';
                                if (totalPriceInput) totalPriceInput.value = data.status === 'success' ? data.price : '';
                            }).catch(err => console.error(err));
                    });
                });
            }

            // ------------------- 關閉模態視窗 -------------------
            document.querySelectorAll('.close-modal').forEach(btn => {
                btn.addEventListener('click', function () {
                    if (modal) modal.classList.add('hidden');
                    if (scheduleForm) scheduleForm.reset();
                    if (totalPriceElement) totalPriceElement.textContent = '請選擇服務項目與時段';
                });
            });
        });
    </script>
@endpush


    @push('styles')
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
        .address-option {
            display: flex;
            flex-direction: column; /* 垂直排列整個地址選項 */
            gap: 10px; /* 選項間的間距 */
        }

        .address-option-group {
            display: flex; /* 讓選項群組在同一行 */
            gap: 20px; /* 選項之間的間距 */
            flex-wrap: wrap; /* 如果內容太長，允許換行 */
        }

        .address-item {
            display: inline-flex; /* 讓 input 和 label 在同一行顯示 */
            align-items: center; /* 垂直置中對齊 */
            gap: 10px; /* radio button 和文字之間的間距 */
        }

        .address-option input[type="radio"] {
            margin-right: 5px; /* 讓 radio button 和文字有些間距 */
        }

        .address-option label {
            white-space: normal; /* 允許文字換行 */
            word-wrap: break-word; /* 文字過長會換行 */
            overflow-wrap: break-word; /* 防止長字串擠壓 */
        }

    </style>
@endpush
