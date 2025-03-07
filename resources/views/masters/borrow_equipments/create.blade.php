@extends('masters.layouts.master')

@section('title', '借用設備')

@section('content')
    <div class="content-wrapper">
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
                    <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                    <a href="{{ route('masters.appointmenttime.index') }}" class="custom-link">可預約時段</a> >
                    <a href="{{ url()->previous() }}" class="custom-link">編輯可預約時段</a> >
                    借用設備
                </p>
            </div>
            <h1 class="mt-4 text-center">借用設備</h1>
        </div>

        <form method="POST" action="{{ route('masters.borrow_equipments.store', $appointmenttime->id) }}">
        @csrf
        <div class="container">
            <div class="row">
                @foreach($equipments as $equipment)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow">
                            <div class="d-flex justify-content-center align-items-center" style="height: 180px; background-color: #f8f9fa;">
                                <img src="{{ asset('storage/equipments/' . $equipment->photo) }}" class="img-fluid" alt="{{ $equipment->name }}" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $equipment->name }}</h5>
                                <p class="card-text text-muted">剩餘數量: <strong>{{ $equipment->quantity }}</strong></p>
                                <input type="hidden" name="equipment_ids[]" value="{{ $equipment->id }}">
                                <input type="number" class="form-control borrow-quantity"
                                       name="borrow_quantities[]"
                                       min="1" max="{{ $equipment->quantity }}"
                                       placeholder="輸入借用數量">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary px-5">確認借用</button>
        </div><br><br>
        </form>
    </div>
@endsection
