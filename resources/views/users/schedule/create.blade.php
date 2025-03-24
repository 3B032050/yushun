@extends('users.layouts.master')

@section('title', '豫順清潔')

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
                    <a href="{{ route('users.index') }}" class="custom-link"><i class="fa fa-home"></i></a> &gt;
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
                <div class="form-group" id="recurring_options" style="display: none;">
                    <div>
                        <label for="recurring_interval">每次預約間隔（週）：</label>
                        <input type="number" id="recurring_interval" name="recurring_interval" min="1" max="4" value="1">
                    </div>
                    <div>
                        <label for="recurring_times">請選擇您希望的預約次數：</label>
                        <select id="recurring_times" name="recurring_times">
{{--                            <option value="1">1 次</option>--}}
{{--                            <option value="2">2 次</option>--}}
{{--                            <option value="3">3 次</option>--}}
{{--                            <option value="4">4 次</option>--}}
                        </select>
                    </div>
                    <div id="recurring_dates"></div>
                </div>
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

                <div class="form-group">
                    <label for="total_price">總金額</label>
                    <p id="total_price" class="form-control-static">請選擇服務項目與時段</p>
                </div>
                <button type="submit" class="btn btn-primary">確認</button>
                <button class="btn btn-secondary close-modal">取消</button>
            </form>


        </div>

        <div id="calendar"></div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

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
            const servicePriceElement = document.getElementById('service_price');
            const TotalPriceElement = document.getElementById('total_price');
            let isRecurring = document.getElementById('is_recurring').checked;
            // 設定預設地址
            let serviceAddress = '{{ Auth::user()->address }}';
            let selectedDate = null;
            //定期客戶選擇

            document.getElementById('is_recurring').addEventListener('change', function () {
                let recurringOptions = document.getElementById('recurring_options');
                recurringOptions.style.display = this.checked ? 'block' : 'none';

                // 當選擇定期客戶時，重新計算預約次數
                if (this.checked) {
                    updateRecurringTimes();
                } else {
                    // 如果取消選擇定期客戶，清除選項
                    document.getElementById('recurring_times').innerHTML = '<option value="1">1 次</option>';
                }
            });

            document.getElementById('recurring_interval').addEventListener('change', updateRecurringTimes);
            document.getElementById('recurring_times').addEventListener('change', calculateRecurringDates);

            function updateRecurringTimes() {
                let selectedDate = document.getElementById('selected_date').value; // 選擇的日期
                let recurringInterval = parseInt(document.getElementById('recurring_interval').value); // 間隔週數

                let recurringTimesContainer = document.getElementById('recurring_times');
                recurringTimesContainer.innerHTML = ''; // 清空現有選項

                if (!selectedDate || isNaN(recurringInterval)) {
                    return;
                }

                let startDate = new Date(selectedDate);
                let year = startDate.getFullYear();
                let month = startDate.getMonth(); // 0-11 表示月份

                // 計算當月的最後一天
                let monthEndDate = new Date(year, month + 1, 0); // 計算當月最後一天
                let daysInMonth = monthEndDate.getDate(); // 當月的天數

                // 計算距離月底的天數
                let daysToEndOfMonth = daysInMonth - startDate.getDate();

                // 計算當月最多有多少周（每週7天）
                let maxWeeksInMonth = Math.floor(daysToEndOfMonth / 7);

                // 根據間隔週數計算最大預約次數
                let maxRecurringTimes = Math.floor(maxWeeksInMonth / recurringInterval);

                // 確認最大預約次數
                console.log(`Max recurring times calculated: ${maxRecurringTimes}`);
                console.log(`Days to end of month: ${daysToEndOfMonth}`);

                // 動態生成預約次數選項
                // 如果 maxRecurringTimes 是 0，顯示提示文字
                if (maxRecurringTimes === 0) {
                    let option = document.createElement('option');
                    option.value = 0;
                    option.textContent = "當月不滿足間隔天數，請重新選擇日期";
                    recurringTimesContainer.appendChild(option);
                } else {
                    // 根據最大預約次數生成選項
                    for (let i = 1; i <= maxRecurringTimes; i++) {
                        let option = document.createElement('option');
                        option.value = i;
                        option.textContent = `${i} 次`;
                        recurringTimesContainer.appendChild(option);
                    }
                }
                // 如果已選的次數大於最大次數，則設為最大次數
                let currentSelectedTimes = document.getElementById('recurring_times').value;
                if (currentSelectedTimes > maxRecurringTimes) {
                    document.getElementById('recurring_times').value = maxRecurringTimes;
                }

                // 更新預約日期顯示
                calculateRecurringDates();
            }

            function calculateRecurringDates() {
                let selectedDate = document.getElementById('selected_date').value; // 選擇的日期
                let recurringInterval = parseInt(document.getElementById('recurring_interval').value); // 間隔週數
                let recurringTimes = parseInt(document.getElementById('recurring_times').value); // 預約次數

                let recurringContainer = document.getElementById('recurring_dates');
                recurringContainer.innerHTML = ''; // 清空舊的紀錄

                if (!selectedDate || isNaN(recurringInterval) || isNaN(recurringTimes)) {
                    return;
                }

                let startDate = new Date(selectedDate);
                let year = startDate.getFullYear();
                let month = startDate.getMonth(); // 0-11 表示月份

                let weeksAdded = 0;
                let addedDates = [];
                let monthEndDate = new Date(year, month + 1, 0); // 取得當月的最後一天

                // 計算當月最多有多少週
                let maxPossibleTimes = Math.floor((monthEndDate.getDate() + (7 - monthEndDate.getDay())) / 7);

                // 限制最大預約次數不超過當月的最大週數
                while (weeksAdded < recurringTimes && weeksAdded < maxPossibleTimes) {
                    let nextDate = new Date(startDate);
                    nextDate.setDate(startDate.getDate() + (weeksAdded * recurringInterval * 7)); // 計算下一次的日期

                    // 如果 nextDate 超過了當月的最後一天，則不再計算
                    if (nextDate > monthEndDate) {
                        break;
                    }

                    addedDates.push(nextDate.toISOString().split('T')[0]); // 存入 YYYY-MM-DD 格式
                    weeksAdded++;
                }

                // 更新預約次數
                if (addedDates.length === 0) {
                    document.getElementById('recurring_times').value = 0;
                } else {
                    // 更新預約次數
                    document.getElementById('recurring_times').value = addedDates.length;
                }

                console.log("預約產生的日期:", addedDates); // 可在 console 查看產生的日期
            }

            //取消
            document.querySelector('.close-modal').addEventListener('click', function(event) {
                event.preventDefault();  // 防止表單提交

                // 清除選擇的資料或返回到初始狀態
                clearSelectedData();

                // 關閉模態視窗
                $('#modalId').modal('hide');  // 假設這是用來關閉模態視窗的代碼
            });

            // 用來清除資料的函式
            function clearSelectedData() {
                const formElement = document.getElementById('schedule-form');// 假設你要重置的是整個表單
                const recurringOptions = document.getElementById('recurring_options');
                if (formElement) {
                    formElement.reset();  // 重置表單的所有資料
                } else {
                    console.error('Form element not found');
                }
                // 隱藏定期選項
                if (recurringOptions) {
                    recurringOptions.style.display = 'none';
                }
            }

            // 如果有多個取消按鈕，使用下面的程式碼來遍歷並關閉模態框
            document.querySelectorAll('.close-modal').forEach(button => {
                button.addEventListener('click', function() {
                    modal.classList.add('hidden');  // 假設這是用來隱藏模態框的代碼
                });
            });
            const addressField = document.getElementById('address'); // 隱藏的地址欄位
            const addressOptionProfile = document.getElementById('use_profile_address'); // 預設地址選項
            const customRadio = document.getElementById('enter_new_address'); // 自訂地址選項
            const customAddressInput = document.getElementById('custom_address_input'); // 自訂地址輸入區塊
            const customAddress = document.getElementById('custom_address'); // 自訂地址輸入框




            // 切換地址輸入方式
            function toggleAddressInput() {
                if (customRadio.checked) {
                    customAddressInput.style.display = 'block';
                    serviceAddress = customAddress.value.trim();  // 如果有輸入，使用輸入的地址
                } else {
                    customAddressInput.style.display = 'none';
                    customAddress.value = '';  // 清空輸入框，避免錯誤保留
                    serviceAddress = '{{ Auth::user()->address }}'; // 重新設定為預設地址
                }
                addressField.value = serviceAddress;  // 更新隱藏欄位
                //console.log('目前選擇的服務地址:', serviceAddress);
            }

            // 監聽 radio 切換事件
            addressOptionProfile.addEventListener('change', toggleAddressInput);
            customRadio.addEventListener('change', toggleAddressInput);

            // 監聽手動輸入變化
            customAddress.addEventListener('input', function() {
                if (customRadio.checked) {
                    serviceAddress = this.value.trim();
                    addressField.value = serviceAddress;
                   // console.log('目前手動輸入的服務地址:', serviceAddress);
                }
            });

                // 當用戶選擇服務項目時，檢查是否設置了地址
            serviceSelect.addEventListener('change', function() {
                if (!serviceAddress || (customRadio.checked && !customAddress.value.trim())) {
                    alert('請先選擇或輸入地址');
                    window.location.href = '/users/personal_information/edit';  // 重定向到個人資訊頁面
                    return; // 如果地址未設定，阻止繼續選擇服務項目
                }
                //console.log('服務項目選擇了:', this.value);
                //console.log('選擇的服務地址:', serviceAddress);
            });

            // 預設執行一次，確保畫面初始狀態
            toggleAddressInput();

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
                    @php
                        $availableDates = $appointmenttimes->pluck('service_date')->unique();
                    @endphp

                    @foreach($availableDates as $date)
                    events.push({
                        title: '點選',
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
                eventContent: function(arg) {
                    // 設置事件內容，包含標題並置中
                    return { html: `<div style="text-align: center;">${arg.event.title}</div>` };
                },
                dateClick: function(info) {
                   // console.log('Date clicked:', info.dateStr);
                    selectedDate = new Date(info.dateStr)
                    const today = new Date();
                    today.setHours(0, 0, 0, 0); // 設置今天的零點時間，便於比較

                    // 比較所選日期是否是今天或之後的日期
                    if (selectedDate < today) {
                        alert('選擇的日期不能是過去的日期，請選擇今天或之後的日期');
                        return; // 阻止顯示彈出視窗
                    }

                    // 如果日期合法，顯示彈出視窗
                    modalDate.textContent = selectedDate.toLocaleDateString('zh-TW', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                    selectedDate = info.dateStr;
                    document.getElementById('selected_date').value = info.dateStr;
                    modal.classList.remove('hidden'); // 顯示彈出視窗
                }
            });

            calendar.render();


            serviceSelect.addEventListener('change', function () {
                const serviceId = this.value;

                // console.log('Service ID:', serviceId);
                //console.log('selected_date:', document.getElementById('selected_date').value);
                if (!selectedDate) {
                    alert('請先選擇日期');
                    return;
                }

                masterSelect.innerHTML = '<option value="">加載中...</option>';
                masterSelect.disabled = true;

                availableTimesSelect.innerHTML = '<option value="">請先選擇師傅</option>';
                availableTimesSelect.disabled = true;

                // 透過 AJAX 獲取對應的價格
                fetch(`{{ url('users/schedule/getServicePrice') }}?service_id=${serviceId}&date=${selectedDate}&address=${serviceAddress}`)
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
                fetch(`{{ url('users/schedule/available_masters') }}?service_id=${serviceId}&date=${selectedDate}&address=${serviceAddress}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Data received:', data);
                        //alert(JSON.stringify(data));
                        if (data.status === 'empty') {
                            masterSelect.innerHTML = '<option value="">該師傅當日無可預約時段</option>';
                            masterSelect.disabled = true;
                        }
                        else if (data.status === 'error') {
                            masterSelect.innerHTML = `<option value="">${data.message}</option>`;
                            masterSelect.disabled = true;
                        }else if (data.status === 'success' && data.data.length > 0) {
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
                const time = availableTimesSelect.value;
                availableTimesSelect.innerHTML = '<option value="">加載中...</option>';
                availableTimesSelect.disabled = true;  // 每次變更時先鎖定

                if (!masterId) return;

                fetch(`{{ url('users/schedule/available_times') }}?date=${selectedDate}&master_id=${masterId}&address=${serviceAddress}`)
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

                // ⭐⭐ 當使用者選擇時段後，再去請求價格 ⭐⭐
                availableTimesSelect.addEventListener('change', function () {
                    const appointment_time = this.value; // 取得選擇的時段
                    const serviceId = this.value;
                    isRecurring = document.getElementById('is_recurring').checked;
                    console.log('選擇的時段:', appointment_time);
                    console.log('定期:', isRecurring);
                    console.log('服務地點:',serviceAddress);
                    if (!appointment_time) {
                        TotalPriceElement.textContent = '請選擇時段';
                        return;
                    }
                    fetch(`{{ url('users/schedule/getTotalPrice') }}?service_id=${serviceId}&appointment_time=${appointment_time}&is_recurring=${isRecurring}&address=${serviceAddress}`)

                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                TotalPriceElement.textContent = `NT$ ${data.price}`;
                            } else {
                                TotalPriceElement.textContent = '無法獲取價格';
                            }
                        })
                        .catch(error => {
                            TotalPriceElement.textContent = '錯誤: 無法獲取價格';
                            console.error('Error:', error);
                        });
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
