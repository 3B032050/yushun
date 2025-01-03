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

            <!-- 師傅選擇 -->
            <div class="form-group">
                <label for="master_id">選擇師傅</label>
                <select id="master_id" name="master_id" class="form-control" required>
                    <option value="">請選擇師傅</option>
                </select>
            </div>

            <div class="form-group">
                <label for="available_times">可預約時段</label>
                <select id="available_times" class="form-control">
                    <option value="">請選擇可預約時段</option>
                </select>
            </div>
            <!-- 服務地區選擇 -->
            <div class="form-group">
                <label for="service_area">選擇服務地區</label>
                <select id="service_area" name="service_area" class="form-control" required>
                    <option value="">請先選擇師傅</option>
                </select>
            </div>

            <!-- 開始與結束時間 -->
            <div class="form-group">
                <label for="start_time">開始時間</label>
                <input type="time" id="start_time" name="start_time" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="end_time">結束時間</label>
                <input type="time" id="end_time" name="end_time" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="price">價格</label>
                <input type="number" id="price" name="price" class="form-control" required>
            </div>
        </form>
        <button id="confirm-schedule" class="btn btn-primary">確認</button>
        <button class="btn btn-secondary close-modal">取消</button>

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
            const serviceAreaSelect = document.getElementById('service_area');
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
            confirmButton.addEventListener('click', function () {
                const selectedDate = document.getElementById('selected_date').value;
                const masterId = document.getElementById('master_id').value;
                const serviceAreaId = document.getElementById('service_area').value;
                const startTime = document.getElementById('start_time').value;
                const endTime = document.getElementById('end_time').value;
                const price = document.getElementById('price').value;

                // 確保所有必填字段都有值
                if (!selectedDate || !masterId || !serviceAreaId || !startTime || !endTime || !price) {
                    alert('請確保所有選項都已填寫完整');
                    return;
                }

                // 提交表單
                scheduleForm.submit();
            });

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'zh-tw',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [
                        @foreach($appointmenttimes as $appointmenttime)
                        {
                            title: '{{ $appointmenttime->master ? $appointmenttime->master->name : "暫無師傅" }}',
                            color: '{{ $appointmenttime->master ? ($appointmenttime->status == 1 ? "#28a745" : "#dc3545") : "#dc3545" }}',
                            textColor: '{{ $appointmenttime->master ? "#ffffff" : "#dc3545" }}',  // 如果沒有師傅，顯示紅色文字
                            id: '{{ $appointmenttime->id }}',
                        },
                        @endforeach
                ],
                dateClick: function (info) {
                    selectedDate = info.dateStr;
                    modalDate.textContent = new Date(selectedDate).toLocaleDateString('zh-TW', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                    selectedDateInput.value = selectedDate;

                    masterSelect.innerHTML = '<option value="">加載中...</option>';
                    masterSelect.disabled = true;

                    // 加載可用師傅
                    fetch(`{{ url('users/schedule/available_masters') }}?date=${selectedDate}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.message) {
                                masterSelect.innerHTML = `<option value="">${data.message}</option>`;
                            } else {
                                masterSelect.innerHTML = '<option value="">請選擇師傅</option>';
                                data.forEach(master => {
                                    const option = document.createElement('option');
                                    option.value = master.id;
                                    option.textContent = master.name;
                                    masterSelect.appendChild(option);
                                });
                                masterSelect.disabled = false;
                            }
                        })
                        .catch(error => {
                            masterSelect.innerHTML = '<option value="">無法加載師傅</option>';
                            console.error('Error:', error);
                        });

                    modal.classList.remove('hidden'); // 顯示模態框
                }
            });

            calendar.render();

            // 當選擇師傅後加載服務地區
            masterSelect.addEventListener('change', function () {
                const masterId = this.value;
                availableTimesSelect.innerHTML = '<option value="">請先選擇服務地區</option>';
                if (!masterId) return;

                serviceAreaSelect.innerHTML = '<option value="">加載中...</option>';
                fetch(`{{ url('users/schedule/service_areas') }}?master_id=${masterId}`)
                    .then(response => response.json())
                    .then(data => {
                        serviceAreaSelect.innerHTML = '<option value="">請選擇服務地區</option>';
                        data.forEach(area => {
                            const option = document.createElement('option');
                            option.value = area.id;
                            option.textContent = area.name;
                            serviceAreaSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        serviceAreaSelect.innerHTML = '<option value="">無法加載服務地區</option>';
                        console.error('Error:', error);
                    });
            });

            // 當選擇服務地區後加載可用時段
            serviceAreaSelect.addEventListener('change', function () {
                const serviceAreaId = this.value;
                const masterId = masterSelect.value;

                if (!serviceAreaId || !masterId || !selectedDate) return;

                availableTimesSelect.innerHTML = '<option value="">加載中...</option>';
                fetch(`{{ url('users/schedule/available_times') }}?date=${selectedDate}&master_id=${masterId}&service_area=${serviceAreaId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.message) {
                            availableTimesSelect.innerHTML = `<option value="">${data.message}</option>`;
                        } else {
                            availableTimesSelect.innerHTML = '<option value="">請選擇可預約時段</option>';
                            data.forEach(time => {
                                const option = document.createElement('option');
                                option.value = time.id;
                                option.textContent = `${time.start_time} - ${time.end_time}`;
                                availableTimesSelect.appendChild(option);
                            });
                        }
                    })
                    .catch(error => {
                        availableTimesSelect.innerHTML = '<option value="">無法加載可預約時段</option>';
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
