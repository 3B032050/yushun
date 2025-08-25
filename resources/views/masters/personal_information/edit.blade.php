@extends('masters.layouts.master')

@section('title','豫順家居服務媒合平台')
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

                            {{-- 多尺寸：無資料 -> 顯示新增表單（整段鎖住）；有資料 -> 顯示列表（編輯鈕鎖住） --}}
                            <div class="row justify-content-center">
                                <div class="col-md-10">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <span>制服尺寸資料</span>
                                        </div>

                                        <div class="card-body">

                                            @if($uniforms->isEmpty())
                                                {{-- 無資料：顯示新增表單，但整段禁用 --}}
                                                <fieldset disabled>
                                                    <div class="mb-3">
                                                        <label class="form-label">尺寸</label>
                                                        <select class="form-select">
                                                            @foreach(['S','M','L','XL','XXL'] as $s)
                                                                <option value="{{ $s }}">{{ $s }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">數量</label>
                                                        <input type="number" class="form-control" min="1">
                                                    </div>

                                                    <div class="text-center">
                                                        <button type="button" class="btn btn-primary" disabled>新增制服</button>
                                                    </div>
                                                </fieldset>
                                                <div class="text-center text-muted mt-3">尚未登記</div>

                                            @else
                                                {{-- 有資料：顯示列表，編輯按鈕禁用 --}}
                                                <table class="table table-striped text-center align-middle">
                                                    <thead>
                                                    <tr>
                                                        <th scope="col">尺寸</th>
                                                        <th scope="col">數量</th>
                                                        <th scope="col">建立時間</th>
                                                        <th scope="col" style="width: 140px;">操作</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($uniforms as $u)
                                                        <tr>
                                                            <td>{{ $u->size }}</td>
                                                            <td>{{ $u->quantity }}</td>
                                                            <td>{{ $u->created_at?->format('Y-m-d') ?? '-' }}</td>
                                                            <td>
                                                                {{-- 1) 用 button disabled 當作假按鈕 --}}
                                                                <button type="button" class="btn btn-warning btn-sm" disabled>
                                                                    <i class="fa fa-edit"></i> 編輯
                                                                </button>

                                                                {{-- 2) 或者若一定要用 <a>，就這樣（擇一保留）
                                                                <a href="javascript:void(0)"
                                                                   class="btn btn-warning btn-sm disabled no-click"
                                                                   tabindex="-1" aria-disabled="true">
                                                                    <i class="fa fa-edit"></i> 編輯
                                                                </a>
                                                                --}}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<style>
    a.disabled, .no-click {
        pointer-events: none;  /* 不能點 */
        opacity: .65;          /* 視覺灰掉 */
        cursor: not-allowed;
    }
</style>
