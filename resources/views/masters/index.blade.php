@extends('masters.layouts.master')

@section('title', '豫順家居')

@section('content')
    <div class="content-wrapper">
        <div class="container text-center my-5">
            <div class="container my-5">
                <div class="row justify-content-center"> <!-- 使用 justify-content-center 來居中 -->
                    @if (Auth::guard('master')->check() && Auth::guard('master')->user()->position === '1')
                        <div class="col-6 col-md-3 mb-2">
                            <a href="{{ route('masters.appointmenttime.index') }}" class="button-name w-100 text-decoration-none">
                                <div>
                                    <i class="fas fa-calendar-days fa-3x"></i>
                                </div>
                                <div>
                                    <h3>接案行事曆</h3>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <a href="{{ route('masters.service_areas.index') }}" class="button-name w-100 text-decoration-none">
                                <div>
                                    <i class="fa-solid fa-chart-area fa-3x"></i>
                                </div>
                                <div>
                                    <h3>設定可服務地區</h3>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <a href="{{ route('masters.personal_information.index') }}" class="button-name w-100 text-decoration-none">
                                <div>
                                    <i class="fa-sharp fa-solid fa-address-book fa-3x"></i>
                                </div>
                                <div>
                                    <h3>個人資料(制服登記)</h3>
                                </div>
                            </a>
                        </div>
                    @else
                        <div class="col-6 col-md-3 mb-2">
                            <a href="{{ route('admins.masters.index') }}" class="button-name w-100 text-decoration-none">
                                <div>
                                    <i class="fa-sharp fa-solid fa-clipboard fa-3x"></i>
                                </div>
                                <div>
                                    <h3>師傅管理</h3>
                                </div>
                            </a>
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
                            <a href="{{ route('admins.service_items.index') }}" class="button-name w-100 text-decoration-none">
                                <div>
                                    <i class="fa-solid fa-chart-area fa-3x"></i>
                                </div>
                                <div>
                                    <h3>服務項目管理</h3>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <a href="{{ route('admins.service_areas.index') }}" class="button-name w-100 text-decoration-none">
                                <div>
                                    <i class="fa-sharp fa-solid fa-address-book fa-3x"></i>
                                </div>
                                <div>
                                    <h3>服務地區管理</h3>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <a href="{{ route('admins.uniforms.index') }}" class="button-name w-100 text-decoration-none">
                                <div>
                                    <i class="fa-sharp fa-solid fa-shirt fa-3x"></i>
                                </div>
                                <div>
                                    <h3>制服管理</h3>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <a href="{{ route('admins.schedules.index') }}" class="button-name w-100 text-decoration-none">
                                <div>
                                    <i class="fa-solid fa-file-invoice-dollar fa-3x"></i>
                                </div>
                                <div>
                                    <h3>排程管理</h3>
                                </div>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
