@extends('masters.layouts.master')

@section('title', '豫順清潔')

@section('content')
    <div class="container text-center my-5">
    <div class="container my-5">
        <div class="row justify-content-start">
            @if (Auth::guard('master')->check() && Auth::guard('master')->user()->position === '1')
                <div class="col-6 col-md-3 mb-2">
                    <button class="button-name w-100" role="button">
                        <div>
                            <i class="fa-sharp fa-solid fa-clipboard fa-3x"></i>
                        </div>
                        <div>
                            <h3>我的訂單</h3>
                        </div>
                    </button>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <button class="button-name w-100" role="button">
                        <div>
                            <i class="fas fa-calendar-days fa-3x"></i>
                        </div>
                        <div>
                            <h3>接案行事曆</h3>
                        </div>
                    </button>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <button class="button-name w-100" role="button">
                        <div>
                            <i class="fa-regular fa-calendar-plus fa-3x"></i>
                        </div>
                        <div>
                            <h3>設定接案時段</h3>
                        </div>
                    </button>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <button class="button-name w-100" role="button">
                        <div>
                            <i class="fa-solid fa-chart-area fa-3x"></i>
                        </div>
                        <div>
                            <h3>設定服務地區</h3>
                        </div>
                    </button>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <a href="{{ route('masters.personal_information.edit') }}" class="button-name w-100 text-decoration-none">
                        <div>
                            <i class="fa-sharp fa-solid fa-address-book fa-3x"></i>
                        </div>
                        <div>
                            <h3>個人資料</h3>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <button class="button-name w-100" role="button">
                        <div>
                            <i class="fa-solid fa-file-invoice-dollar fa-3x"></i>
                        </div>
                        <div>
                            <h3>對帳單</h3>
                        </div>
                    </button>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <button class="button-name w-100" role="button">
                        <div>
                            <i class="fa-solid fa-phone-volume fa-3x"></i>
                        </div>
                        <div>
                            <h3>聯繫客服</h3>
                        </div>
                    </button>
                </div>
            @else
                <div class="col-6 col-md-3 mb-2">
                    <button class="button-name w-100" role="button">
                        <div>
                            <i class="fa-sharp fa-solid fa-clipboard fa-3x"></i>
                        </div>
                        <div>
                            <h3>師傅管理</h3>
                        </div>
                    </button>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <button class="button-name w-100" role="button">
                        <div>
                            <i class="fas fa-calendar-days fa-3x"></i>
                        </div>
                        <div>
                            <h3>客戶管理</h3>
                        </div>
                    </button>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <a href="{{ route('admins.equipment.index') }}" class="button-name w-100 text-decoration-none">
                        <div>
                            <i class="fa-regular fa-calendar-plus fa-3x"></i>
                        </div>
                        <div>
                            <h3>設備管理</h3>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <button class="button-name w-100" role="button">
                        <div>
                            <i class="fa-solid fa-chart-area fa-3x"></i>
                        </div>
                        <div>
                            <h3>服務項目管理</h3>
                        </div>
                    </button>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <a href="{{ route('admins.service_areas.create') }}" class="w-100">
                        <div>
                            <i class="fa-sharp fa-solid fa-address-book fa-3x"></i>
                        </div>
                        <div>
                            <h3>服務地區管理</h3>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <button class="button-name w-100" role="button">
                        <div>
                            <i class="fa-solid fa-file-invoice-dollar fa-3x"></i>
                        </div>
                        <div>
                            <h3>金流管理</h3>
                        </div>
                    </button>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <button class="button-name w-100" role="button">
                        <div>
                            <i class="fa-solid fa-file-invoice-dollar fa-3x"></i>
                        </div>
                        <div>
                            <h3>排程管理</h3>
                        </div>
                    </button>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <button class="button-name w-100" role="button">
                        <div>
                            <i class="fa-solid fa-phone-volume fa-3x"></i>
                        </div>
                        <div>
                            <h3>聯繫客服</h3>
                        </div>
                    </button>
                </div>
            @endif

        </div>
    </div>
@endsection
