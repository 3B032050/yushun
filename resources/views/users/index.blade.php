@extends('users.layouts.master')
@section('title', '豫順家居媒合服務平台')
@section('content')
    <div class="content-wrapper">
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

        <div class="container text-center my-5">
    </div>
@endsection

