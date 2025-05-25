@extends('users.layouts.master')

@section('title', '豫順家居')

@section('content')
    <div class="content-wrapper">
        <div class="container text-center my-5">
            <div class="row justify-content-center">
                <div class="col-4 col-md-3 mb-2">
                    <a href="{{ route('users.personal_information.personal_index') }}" class="button-name w-100 text-decoration-none">
                        <div>
                            <i class="fa-sharp fa-solid fa-address-book fa-3x"></i>
                        </div>
                        <div>
                            <h3>個人資料</h3>
                        </div>
                    </a>
                </div>
                <div class="col-4 col-md-3 mb-2">
                    <a href="{{ route('users.schedule.index') }}" class="button-name w-100 text-decoration-none">
                        <div>
                            <i class="fa-sharp fa-solid fa-address-book fa-3x"></i>
                        </div>
                        <div>
                            <h3>預約排程</h3>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
