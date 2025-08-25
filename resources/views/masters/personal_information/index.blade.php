@extends('masters.layouts.master')

@section('title','豫順家居服務媒合平台')
@section('content')

    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-2">
                <nav aria-label="breadcrumb" class="mb-2 mb-md-0 w-100 w-md-auto">
                    <ol class="breadcrumb breadcrumb-path mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('masters.index') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item">個人資料</li>
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
                        <div class="card-header text-center">{{ __('個人資料') }}</div>

                        <div class="card-body">
                            <script>
                                function setFontSize(size) {
                                    const content = document.getElementById('content');
                                    content.className = size;
                                }
                            </script>

                            <!-- Existing content continues here -->
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
                                        <i class="fa fa-edit"></i> 編輯個人資料
                                    </a>
                                </div>
                            </div>

                            <hr>

                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label text-md-end">{{ __('累積總工時') }}</label>
                                <div class="col-md-6 pt-2">
                                    <div class="form-control-plaintext">{{ $master->total_hours }}</div>
                                </div>
                            </div>

                            <hr>

                            {{-- 多尺寸：無資料 -> 顯示新增表單；有資料 -> 顯示列表 + 新增按鈕 --}}
                            <div class="row justify-content-center">
                                <div class="col-md-10">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <span>制服尺寸資料</span>
                                        </div>

                                        <div class="card-body">
                                            @if($uniforms->isEmpty())
                                                {{-- 無資料：顯示新增表單 --}}
                                                <form method="POST" action="{{ route('masters.rent_uniforms.store') }}" class="mx-auto" style="max-width:420px;">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label class="form-label">尺寸</label>
                                                        <select name="size" class="form-select" required>
                                                            @foreach(['S','M','L','XL','XXL'] as $s)
                                                                <option value="{{ $s }}">{{ $s }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('size') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">數量</label>
                                                        <input type="number" name="quantity" class="form-control" min="1" required>
                                                        @error('quantity') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                                    </div>

                                                    <div class="text-center">
                                                        <button type="submit" class="btn btn-primary">新增制服</button>
                                                    </div>
                                                </form>
                                            @else
                                                {{-- 有資料：顯示列表 --}}
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
                                                                <a href="{{ route('masters.rent_uniforms.edit',['hash_uniform' => \Vinkla\Hashids\Facades\Hashids::encode($u->id)]) }}#uniform-edit"
                                                                   class="btn btn-warning btn-sm">
                                                                    <i class="fa fa-edit"></i> 編輯
                                                                </a>
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
