@extends('users.layouts.master')

@section('title', '')

@section('content')
    <div class="content-wrapper">
        {{-- 字級按鈕：固定在右上角 --}}
        <div class="text-size-controls">
            <button onclick="setFontSize('small')">小</button>
            <button onclick="setFontSize('medium')">中</button>
            <button onclick="setFontSize('large')">大</button>
        </div>

        <div class="container text-center my-5">
            <div id="content" class="medium">
                <div class="row justify-content-center mb-4">
                    <div class="col-12 col-md-10 col-lg-8">
                        <a href="{{ route('users.personal_information.personal_index') }}" class="button-name w-100 text-decoration-none">
                            <div><i class="fa-sharp fa-solid fa-address-book fa-3x"></i></div>
                            <div><h3>個人資料</h3></div>
                        </a>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10 col-lg-8">
                        <a href="{{ route('users.schedule.index') }}" class="button-name w-100 text-decoration-none">
                            <div><i class="fa-sharp fa-solid fa-calendar-check fa-3x"></i></div>
                            <div><h3>預約排程</h3></div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        #content.small { font-size: 14px; }
        #content.medium { font-size: 22px; }
        #content.large { font-size: 30px; }

        .text-size-controls {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 999;
        }

        .text-size-controls button {
            margin-left: 5px;
            padding: 5px 10px;
            font-size: 14px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function setFontSize(size) {
            const content = document.getElementById('content');
            content.className = size;
        }
    </script>
@endpush
