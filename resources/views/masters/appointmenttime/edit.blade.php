@extends('masters.layouts.master')

@section('title', '豫順家居服務媒合平台')

@section('content')
    @php
        $locked = ($appointmenttime->status == 4); // 已取消 => 鎖死
    @endphp

    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-2">
                <nav aria-label="breadcrumb" class="mb-2 mb-md-0 w-100 w-md-auto">
                    <ol class="breadcrumb breadcrumb-path mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('masters.index') }}"><i class="fa fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('masters.appointmenttime.index') }}">可預約時段</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">編輯可預約時段</li>
                    </ol>
                </nav>
                <div class="text-size-controls btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
                </div>
            </div>
        </div>

        <div id="content" class="medium">
            <div class="d-flex justify-content-center align-items-start" style="min-height: 100vh; width: 100%;">
                <div class="w-100" style="max-width: 800px;">

                    {{-- 已取消提示橫幅 --}}
                    @if($locked)
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i class="fa fa-ban me-2"></i>
                            訂單已取消
                        </div>
                    @endif

                    <!-- 更新時段表單 -->
                    <form method="POST" action="{{ route('masters.appointmenttime.update', ['hash_appointmenttime' => \Vinkla\Hashids\Facades\Hashids::encode($appointmenttime->id)]) }}" class="mb-3">
                        @csrf
                        @method('PATCH')

                        <div class="form-group mb-3">
                            <label for="name">客戶名稱</label>
                            @if(optional($appointmenttime->user)->name)
                                <input type="text" id="name" name="name" value="{{ old('name', $appointmenttime->user->name) }}" class="form-control" disabled required>
                            @else
                                <input type="text" id="name" name="name" value="無客戶" class="form-control" disabled required>
                            @endif
                        </div>

                        <div class="form-group mb-3">
                            <label for="service_date">服務日期</label>
                            <input type="date" id="service_date" name="service_date" value="{{ old('service_date', $appointmenttime->service_date) }}" class="form-control" disabled required>
                        </div>

                        @if($appointmenttime->status == 0 || $appointmenttime->status == 1)
                            <div class="form-group mb-3">
                                <label for="start_time">開始時間</label>
                                <input type="text" id="start_time" name="start_time" value="{{ old('start_time', $appointmenttime->start_time) }}" class="form-control" step="1" required {{ $locked ? 'disabled' : '' }}>
                            </div>

                            <div class="form-group mb-3">
                                <label for="end_time">結束時間</label>
                                <input type="text" id="end_time" name="end_time" value="{{ old('end_time', $appointmenttime->end_time) }}" class="form-control" step="1" required {{ $locked ? 'disabled' : '' }}>
                            </div>
                        @else
                            <div class="form-group mb-3">
                                <label for="start_time">開始時間</label>
                                <input type="time" id="start_time" name="start_time" value="{{ old('start_time', $appointmenttime->start_time) }}" class="form-control" step="1" disabled required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="end_time">結束時間</label>
                                <input type="time" id="end_time" name="end_time" value="{{ old('end_time', $appointmenttime->end_time) }}" class="form-control" step="1" disabled required>
                            </div>
                        @endif

                        <div class="form-group mb-3">
                            <label for="service_item_id">服務項目</label>
                            @if(optional($appointmenttime->user)->name)
                                <select id="service_item_id" name="service_item_id" class="form-select" {{ $locked ? 'disabled' : '' }}>
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}"
                                            {{ (string)$item->id === (string) old('service_item_id', optional($appointmenttime->schedulerecord)->service_id) ? 'selected' : '' }}>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('service_item_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            @else
                                <input type="text" id="name" name="name" value="無項目" class="form-control" disabled required>
                            @endif

                        </div>

                        <div class="form-group mb-3">
                            <label for="service_address">服務地址</label>
                            <input type="text" id="service_address" name="service_address" value="{{ old('service_address', $appointmenttime->service_address) }}" class="form-control" step="1" disabled required>
                        </div>

                        @if($appointmenttime->status == 2 && optional(optional($appointmenttime->schedulerecord)->scheduledetail))
                            <div class="card mt-3">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">客戶評價</h5>
                                </div>
                                <div class="card-body">
                                    @if(optional($appointmenttime->schedulerecord->scheduledetail)->score)
                                        <div class="mb-3">
                                            <label class="form-label">客戶評分</label>
                                            <div class="fs-5 text-warning">
                                                {!! str_repeat('⭐', $appointmenttime->schedulerecord->scheduledetail->score) !!}
                                            </div>
                                        </div>
                                    @endif

                                    @if(optional($appointmenttime->schedulerecord->scheduledetail)->comment)
                                        <div class="mb-3">
                                            <label class="form-label">客戶評論</label>
                                            <div class="border rounded p-2 bg-light">
                                                {{ $appointmenttime->schedulerecord->scheduledetail->comment }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div><br>
                        @endif

                        {{-- 進行中 --}}
                        @if(!$locked && $appointmenttime->status == 1)
                            @php
                                $borrowRecords = \App\Models\BorrowingRecord::where('appointment_time_id', $appointmenttime->id)->get();
                            @endphp

                            @if($borrowRecords->isNotEmpty())
                                <div class="card mt-3">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="mb-0">已借用設備</h5>
                                    </div>
                                    <div class="card-body">
                                        @foreach($borrowRecords as $record)
                                            <div class="border rounded p-2 mb-2 bg-light">
                                                <p><strong>設備名稱：</strong> {{ optional($record->equipment)->name ?? '未知設備' }}</p>
                                                <p><strong>數量：</strong> {{ $record->quantity }}</p>
                                                <p><strong>狀態：</strong> {{ $record->status == 1 ? '已歸還' : '使用中' }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div><br>
                            @else
                                <a href="{{ route('masters.borrow_equipments.create', ['hash_appointmenttime' => \Vinkla\Hashids\Facades\Hashids::encode($appointmenttime->id)]) }}" class="btn btn-primary w-100 mb-2">借用設備</a><br><br>
                            @endif

                            {{-- 完成訂單按鈕 --}}
                            <a href="{{ route('masters.schedule_details.create', ['hash_appointmenttime' => \Vinkla\Hashids\Facades\Hashids::encode($appointmenttime->id)]) }}" class="btn btn-success w-100">完成訂單</a><br><br>
                        @endif


                            {{-- 按鈕區塊 --}}
                            @if($locked)
                                <button type="button" class="btn btn-secondary w-100" onclick="window.location.href='{{ route('masters.appointmenttime.index') }}'">
                                    返回
                                </button>
                            @else
                                @if($appointmenttime->status == 0 || $appointmenttime->status == 1)
                                    <div class="d-flex flex-column gap-2">
                                        {{-- 修改（SweetAlert -> 點隱藏 submit） --}}
                                        <button type="button" class="btn-modify btn btn-danger w-100">修改排程</button>
                                        <button type="submit" class="btn-submit-alter" name="action" value="alter" style="display:none;"></button>


                                        {{-- 只有進行中(status==1) 顯示取消 --}}
                                        @if($appointmenttime->status == 1)
                                            <button type="button" class="btn-cancel btn btn-warning w-100">取消排程</button>
                                            <button type="submit" class="btn-submit-cancel" name="action" value="cancel" style="display:none;"></button>
                                        @endif

                                        <button type="button" class="btn btn-secondary w-100" onclick="window.location.href='{{ route('masters.appointmenttime.index') }}'">
                                            返回
                                        </button>
                                    </div>
                                @elseif($appointmenttime->status == 0 && $appointmenttime->user_id!=null)
                                    <div class="d-flex justify-content-between">
                                        <button type="submit" name="action" value="accept" class="btn btn-success w-100" onclick="return confirm('確定要接受這筆訂單？')">接受</button>
                                        <button type="submit" name="action" value="reject" class="btn btn-secondary w-100" onclick="return confirm('確定不接受這筆訂單？')">不接受</button>
                                    </div><br>
                                @else
                                    <button type="button" class="btn btn-secondary w-100" onclick="window.location.href='{{ route('masters.appointmenttime.index') }}'">
                                        返回
                                    </button>

                                @endif
                            @endif
                        </form>

                        {{-- 刪除（只有未鎖且 status==0 才顯示） --}}
                        @if(!$locked && $appointmenttime->status == 0)
                            <form id="delete-form" action="{{ route('masters.appointmenttime.destroy',['hash_appointmenttime' => \Vinkla\Hashids\Facades\Hashids::encode($appointmenttime->id)]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-delete btn btn-secondary w-100">刪除排程</button>
                            </form>
                        @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // 僅在未鎖時啟用時間選擇器
            @if(!$locked)
            flatpickr("#start_time", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                minuteIncrement: 30,
                disableMobile: true
            });

            flatpickr("#end_time", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                minuteIncrement: 30,
                disableMobile: true
            });
            @endif
        </script>
    @endpush
            @endsection

            <style>
            .breadcrumb-path {
                font-size: 1.4em;
                white-space: normal;
                word-break: break-word;
            }
            @media (max-width: 768px) {
                .breadcrumb-path { font-size: 1.3em; }
                .text-size-controls { margin-top: 0.5rem; }
            }
            @media (max-width: 480px) {
                .breadcrumb-path { font-size: 1.1em; }
                .text-size-controls { width: 100%; justify-content: center; }
            }
            </style>
