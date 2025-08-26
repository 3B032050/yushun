@extends('masters.layouts.master')

@section('title', '豫順家居服務媒合平台')

@section('content')
    <div class="content-wrapper">
        <div class="row justify-content-center mb-4">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="row justify-content-center"> <!-- 使用 justify-content-center 來居中 -->
                    @if (Auth::guard('master')->check() && Auth::guard('master')->user()->position === '1')
                        {{-- 接案行事曆 --}}
                        <div class="col-6 mb-3">
                            <a href="{{ route('masters.appointmenttime.index') }}" class="button-name w-100 text-decoration-none">
                                <div>
                                    <i class="fa-solid fa-calendar-check fa-3x"></i>
                                </div>
                                <div>
                                    <h3>接案行事曆</h3>
                                </div>
                            </a>
                        </div>

                        {{-- 設定可服務地區 --}}
                        <div class="col-6 mb-3">
                            <a href="{{ route('masters.service_areas.index') }}" class="button-name w-100 text-decoration-none">
                                <div>
                                    <i class="fa-solid fa-map-marked-alt fa-3x"></i>
                                </div>
                                <div>
                                    <h3>設定可服務地區</h3>
                                </div>
                            </a>
                        </div>

                        {{-- 個人資料(制服登記) --}}
                        <div class="col-6 mb-3">
                            <a href="{{ route('masters.personal_information.index') }}" class="button-name w-100 text-decoration-none">
                                <div>
                                    <i class="fa-solid fa-id-card fa-3x"></i>
                                </div>
                                <div>
                                    <h3>個人資料(制服登記)</h3>
                                </div>
                            </a>
                        </div>
                    @else
                        {{-- 個人資料 --}}
                        <div class="col-6 mb-3">
                            <a href="{{ route('admins.personal_information.index') }}" class="button-name w-100 text-decoration-none">
                                <div>
                                    <i class="fa-solid fa-id-card fa-3x"></i>
                                </div>
                                <div>
                                    <h3>個人資料</h3>
                                </div>
                            </a>
                        </div>
                        {{-- 師傅資料管理 --}}
                        <div class="col-6 mb-3">
                            <a href="{{ route('admins.masters.index') }}" class="button-name w-100 text-decoration-none">
                                <div>
                                    <i class="fa-solid fa-user-tie fa-3x"></i>
                                </div>
                                <div>
                                    <h3>師傅資料管理</h3>
                                </div>
                            </a>
                        </div>

                        {{-- 客戶資料管理 --}}
                        <div class="col-6 mb-3">
                            <a href="{{ route('admins.users.index') }}" class="button-name w-100 text-decoration-none">
                                <div>
                                    <i class="fa-solid fa-users fa-3x"></i>
                                </div>
                                <div>
                                    <h3>客戶資料管理</h3>
                                </div>
                            </a>
                        </div>

                        {{-- 設備狀態資訊管理 --}}
                        <div class="col-6 mb-3">
                            <a href="{{ route('admins.equipment.index') }}" class="button-name w-100 text-decoration-none">
                                <div>
                                    <i class="fa-solid fa-cogs fa-3x"></i>
                                </div>
                                <div>
                                    <h3>設備狀態資訊管理</h3>
                                </div>
                            </a>
                        </div>

                        {{-- 服務項目資料管理 --}}
                        <div class="col-6 mb-3">
                            <a href="{{ route('admins.service_items.index') }}" class="button-name w-100 text-decoration-none">
                                <div>
                                    <i class="fa-solid fa-list-check fa-3x"></i>
                                </div>
                                <div>
                                    <h3>服務項目資料管理</h3>
                                </div>
                            </a>
                        </div>

                        {{-- 服務地區資料管理 --}}
                        <div class="col-6 mb-3">
                            <a href="{{ route('admins.service_areas.index') }}" class="button-name w-100 text-decoration-none">
                                <div>
                                    <i class="fa-solid fa-map-location-dot fa-3x"></i>
                                </div>
                                <div>
                                    <h3>服務地區資料管理</h3>
                                </div>
                            </a>
                        </div>

                        {{-- 制服資料管理 --}}
                        <div class="col-6 mb-3">
                            <a href="{{ route('admins.uniforms.index') }}" class="button-name w-100 text-decoration-none">
                                <div>
                                    <i class="fa-solid fa-shirt fa-3x"></i>
                                </div>
                                <div>
                                    <h3>制服資料管理</h3>
                                </div>
                            </a>
                        </div>

                        {{-- 排程管理 --}}
                        <div class="col-6 mb-3">
                            <a href="{{ route('admins.schedules.index') }}" class="button-name w-100 text-decoration-none">
                                <div>
                                    <i class="fa-solid fa-business-time fa-3x"></i>
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
