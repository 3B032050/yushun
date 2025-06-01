@extends('masters.layouts.master')

@section('title','豫順家居媒合服務平台')

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
            <div class="d-flex justify-content-between align-items-center mt-2">
                <p class="fs-4 mb-0">
                    <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                    個人資料
                </p>

                <div class="text-size-controls btn-group btn-group-sm" role="group" aria-label="字級調整">
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
                </div>
            </div>
        </div>

        <div id="content" class="medium">
            <div class="row justify-content-center mt-3">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header text-center">{{ __('個人資料') }}</div>

                        <div class="card-body">
                            <!-- 基本資料 -->
                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end"><span class="required"></span>{{ __('姓名') }}</label>
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" value="{{ $master->name }}" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end"><span class="required"></span>{{ __('信箱') }}</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" value="{{ $master->email }}" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="phone" class="col-md-4 col-form-label text-md-end"><span class="required"></span>{{ __('電話') }}</label>
                                <div class="col-md-6">
                                    <input id="phone" type="text" class="form-control" value="{{ $master->phone }}" readonly>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <a href="{{ route('masters.personal_information.edit') }}" class="btn btn-primary">
                                        編輯個人資料
                                    </a>
                                </div>
                            </div>

                            <hr>

                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label text-md-end">{{ __('累積總工時') }}</label>
                                <div class="col-md-6 pt-2">
                                    <div class="form-control-plaintext">{{ $master->total_hours }} 小時</div>
                                </div>
                            </div>

                            <hr>

                            @if(!$rental)
                                <form method="POST" action="{{ route('masters.rent_uniforms.store') }}">
                                    @csrf
                                    <div class="form-group mb-3 text-center"><strong>{{ __('請選擇制服尺寸和數量') }}</strong></div>

                                    <label for="size">尺寸</label>
                                    <select name="size" id="size" class="form-control">
                                        <option value="S">S</option>
                                        <option value="M">M</option>
                                        <option value="L">L</option>
                                        <option value="XL">XL</option>
                                        <option value="XXL">XXL</option>
                                    </select>

                                    <div class="form-group mb-3 mt-2">
                                        <label for="quantity">數量</label>
                                        <input type="number" name="quantity" id="quantity" class="form-control" min="1" placeholder="請輸入數量">
                                    </div>

                                    <div class="form-group text-center">
                                        <button type="submit" class="btn btn-primary">確認</button>
                                    </div>
                                </form>
                            @else
                                <div class="row justify-content-center">
                                    <div class="col-md-10">
                                        <div class="card">
                                            <div class="card-header text-center">已選擇制服尺寸</div>
                                            <div class="card-body">
                                                <table class="table table-striped text-center">
                                                    <thead>
                                                    <tr>
                                                        <th scope="col">尺寸</th>
                                                        <th scope="col">數量</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>{{ $rental->size }}</td>
                                                        <td>{{ $rental->quantity }}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .required {
            color: red;
            margin-left: 5px;
            font-weight: bold;
        }
        .card {
            max-width: 600px;
            margin: 0 auto;
        }

        .content-wrapper {
            min-height: calc(100vh - 60px);
        }
    </style>
@endsection

