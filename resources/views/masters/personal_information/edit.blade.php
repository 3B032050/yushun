@extends('masters.layouts.master')

@section('title','個人資料')

@section('content')
    <div class="container-fluid px-4">
        <div style="margin-top: 10px;">
            <p style="font-size: 1.8em;">
                <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                個人資料
            </p>
        </div>
    </div>

        <!-- 使用 Bootstrap 的 row 和 col 來置中表單 -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center">{{ __('個人資料') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('masters.update',$master->id) }}">
                            @csrf
                            @method('PATCH')

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end"><span class="required"></span>{{ __('姓名 / Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $master->name }}" required autocomplete="name" placeholder="必填" autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end"><span class="required"></span>{{ __('信箱 / Email') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $master->email }}" required autocomplete="email" placeholder="必填" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="phone" class="col-md-4 col-form-label text-md-end"><span class="required"></span>{{ __('電話 / Phone') }}</label>

                                <div class="col-md-6">
                                    <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ $master->phone }}" required autocomplete="current-password" placeholder="必填">

                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('儲存') }}
                                    </button>
                                </div>
                            </div>
                        </form><hr>

                        @if(!$rental)
                            <form method="POST" action="{{ route('masters.rent_uniforms.store') }}">
                                @csrf

                                <div class="form-group mb-3 text-center">
                                    <select name="size" id="size" class="form-control">
                                        <option value="S">
                                            S
                                        </option>
                                        <option value="M" >
                                            M
                                        </option>
                                        <option value="L" >
                                            L
                                        </option>
                                        <option value="XL" >
                                            XL
                                        </option>
                                        <option value="XXL" >
                                            XXL
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="quantity">數量</label>
                                    <input type="number" name="quantity" id="quantity"
                                           class="form-control"
                                           min="1" placeholder="請輸入數量">
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
                                                    <td class="align-middle">{{ $rental->size }}</td>
                                                    <td class="align-middle">{{ $rental->quantity }}</td>
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

    <style>
        .required {
            color: red;
            margin-left: 5px;
            font-weight: bold;
        }

        /* 調整卡片的寬度，使其在不同螢幕大小下能自動調整 */
        .card {
            max-width: 600px; /* 最大寬度設定 */
            margin: 0 auto;   /* 使卡片在螢幕中居中 */
        }
    </style>
@endsection
