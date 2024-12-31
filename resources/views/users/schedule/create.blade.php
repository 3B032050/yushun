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

            <!-- 服務地區選擇 -->
            <div class="form-group">
                <label for="service_area">選擇服務地區</label>
                <select id="service_area" name="service_area" class="form-control" required>
                    <option value="">請先選擇師傅</option>
                </select>
            </div>

            <div class="form-group">
                <label for="available_times">可預約時段</label>
                <select id="available_times" class="form-control">
                    <option value="">請選擇可預約時段</option>
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
        <button id="modal-confirm" class="btn btn-primary">確認新增</button>
        <button id="modal-close" class="btn btn-secondary">取消</button>
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
            const modalDate = document.getElementById('modal-date');
            const selectedDateInput = document.getElementById('selected_date');
            const serviceAreaSelect = document.getElementById('service_area');
            const masterSelect = document.getElementById('master_id');
            const availableTimesSelect = document.getElementById('available_times');

            let selectedMasterId = null;  // 新增變數存儲選中的師傅ID
            let selectedDate = null;  // 新增變數存儲選中的日期

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
                        start: '{{ $appointmenttime->service_date }}T{{ $appointmenttime->start_time }}',
                        end: '{{ $appointmenttime->service_date }}T{{ $appointmenttime->end_time }}',
                        color: '{{ $appointmenttime->status == 1 ? "#28a745" : "#dc3545" }}',
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

                    fetch(`{{ url('users/schedule/available') }}?date=${selectedDate}`)
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

                    modal.classList.remove('hidden');
                }
            });

            calendar.render();

            // 當選擇師傅時，更新選中的 masterId
            masterSelect.addEventListener('change', function () {
                selectedMasterId = this.value;
                const selectedDate = selectedDateInput.value;
                serviceAreaSelect.innerHTML = '<option value="">加載中...</option>';

                if (selectedMasterId) {
                    fetch(`{{ url('users/schedule/available') }}/${selectedMasterId}?date=${selectedDate}`)
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
                        });
                } else {
                    serviceAreaSelect.innerHTML = '<option value="">請先選擇師傅</option>';
                }
            });

            // 當選擇服務區域時，更新可預約時段
            serviceAreaSelect.addEventListener('change', function () {
                const serviceAreaId = this.value;
                availableTimesSelect.innerHTML = '<option value="">加載中...</option>';

                if (serviceAreaId && selectedMasterId) {
                    fetch(`{{ url('users/schedule/available') }}/${selectedMasterId}?date=${selectedDate}&service_area=${serviceAreaId}`)
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
                            availableTimesSelect.innerHTML = '<option value="">無法加載可預約時段</option>';
                        });
                } else {
                    availableTimesSelect.innerHTML = '<option value="">請先選擇服務地區</option>';
                }
            });

            document.getElementById('modal-close').addEventListener('click', function () {
                modal.classList.add('hidden');
            });

            document.getElementById('modal-confirm').addEventListener('click', function () {
                document.getElementById('schedule-form').submit();
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
