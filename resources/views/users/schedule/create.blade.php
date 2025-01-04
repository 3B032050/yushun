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

            <!-- 師傅選擇 -->
            <div class="form-group">
                <label for="master_id">選擇師傅</label>
                <select id="master_id" name="master_id" class="form-control" disabled required>
                    <option value="">請先選擇服務項目</option>
                </select>
            </div>

            <div class="form-group">
                <label for="available_times">可預約時段</label>
                <select id="available_times" class="form-control">
                    <option value="">請選擇可預約時段</option>
                </select>
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

            confirmButton.addEventListener('click', function () {
                const selectedDate = document.getElementById('selected_date').value;
                const masterId = document.getElementById('master_id').value;
                const serviceId = document.getElementById('service_id').value;
                const startTime = document.getElementById('start_time').value;
                const endTime = document.getElementById('end_time').value;
                const price = document.getElementById('price').value;

                if (!selectedDate || !masterId || !serviceId || !startTime || !endTime || !price) {
                    alert('請確保所有選項都已填寫完整');
                    return;
                }
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
                        textColor: '{{ $appointmenttime->master ? "#ffffff" : "#dc3545" }}',
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
                    modal.classList.remove('hidden');
                }
            });

            calendar.render();

            serviceSelect.addEventListener('change', function () {
                const serviceId = this.value;
                masterSelect.innerHTML = '<option value="">加載中...</option>';
                masterSelect.disabled = true;

                fetch(`{{ url('users/schedule/available_masters') }}?service_id=${serviceId}`)
                    .then(response => response.json())
                    .then(data => {
                        masterSelect.innerHTML = '<option value="">請選擇師傅</option>';
                        data.forEach(master => {
                            const option = document.createElement('option');
                            option.value = master.id;
                            option.textContent = master.name;
                            masterSelect.appendChild(option);
                        });
                        masterSelect.disabled = false;
                    })
                    .catch(error => {
                        masterSelect.innerHTML = '<option value="">無法加載師傅</option>';
                        console.error('Error:', error);
                    });
            });

            masterSelect.addEventListener('change', function () {
                const masterId = this.value;
                availableTimesSelect.innerHTML = '<option value="">加載中...</option>';
                if (!masterId) return;

                fetch(`{{ url('users/schedule/available_times') }}?date=${selectedDate}&master_id=${masterId}`)
                    .then(response => response.json())
                    .then(data => {
                        availableTimesSelect.innerHTML = '<option value="">請選擇可預約時段</option>';
                        data.forEach(time => {
                            const option = document.createElement('option');
                            option.value = time.id;
                            option.textContent = `${time.start_time} - ${time.end_time}`;
                            availableTimesSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        availableTimesSelect.innerHTML = '<option value="">無法加載時段</option>';
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
