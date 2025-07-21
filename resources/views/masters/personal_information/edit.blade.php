@extends('masters.layouts.master')

@section('title','家居媒合服務平台')
@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-2">
                <nav aria-label="breadcrumb" class="mb-2 mb-md-0 w-100 w-md-auto">
                    <ol class="breadcrumb breadcrumb-path mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('masters.index') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('masters.personal_information.index') }}"> 個人資料</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> 編輯個人資料</li>
                    </ol>
                </nav>

                <div class="text-size-controls btn-group btn-group-sm" role="group" aria-label="字程調整">
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
                        <div class="card-header text-center">{{ __('編輯個人資料') }}</div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('masters.personal_information.update', ['hashedMasterId' => $hashedMasterId]) }}">
                                @csrf
                                @method('PATCH')

                                <div class="row mb-3">
                                    <label for="name" class="col-md-4 col-form-label text-md-end"><span class="required"></span>{{ __('姓名') }}</label>
                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $master->name }}" required placeholder="必填" autofocus>
                                        @error('name')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="email" class="col-md-4 col-form-label text-md-end"><span class="required"></span>{{ __('信箱') }}</label>
                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $master->email }}" required placeholder="必填">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="phone" class="col-md-4 col-form-label text-md-end"><span class="required"></span>{{ __('電話') }}</label>
                                    <div class="col-md-6">
                                        <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ $master->phone }}" required placeholder="必填">
                                        @error('phone')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary">{{ __('儲存') }}</button>
                                        <a href="javascript:history.back()" class="btn btn-secondary ml-2">{{ __('取消') }}</a>
                                    </div>
                                </div>
                            </form>

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
                                                        <th>尺寸</th>
                                                        <th>數量</th>
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

@endsection
@push('styles')
    <style>
        .breadcrumb-path {
            font-size: 1.4em;
            white-space: normal;
            word-break: break-word;
        }

        @media (max-width: 768px) {
            .breadcrumb-path {
                font-size: 1.3em;
            }

            .text-size-controls {
                margin-top: 0.5rem;
            }
        }

        @media (max-width: 480px) {
            .breadcrumb-path {
                font-size: 1.1em;
            }

            .text-size-controls {
                width: 100%;
                justify-content: center;
            }
        }
        #calendar {
            max-width: 100%;
            margin: 0 auto;
            height: 600px;  /* 設置明確的高度 */
        }  .required {
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
@endpush

