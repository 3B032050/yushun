@extends('masters.layouts.master')

@section('title','豫順家居服務媒合平台')
@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-2">
                <nav aria-label="breadcrumb" class="mb-2 mb-md-0 w-100 w-md-auto">
                    <ol class="breadcrumb breadcrumb-path mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('masters.index') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('masters.personal_information.index') }}">個人資料</a></li>
                        <li class="breadcrumb-item active" aria-current="page">編輯制服資料</li>
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
                    {{-- 基本資料一覽（唯讀） --}}
                    <div class="card mb-3">
                        <div class="card-header text-center">{{ __('個人資料') }}</div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label text-md-end">姓名</label>
                                <div class="col-md-6"><input type="text" class="form-control" value="{{ $master->name }}" readonly></div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label text-md-end">信箱</label>
                                <div class="col-md-6"><input type="email" class="form-control" value="{{ $master->email }}" readonly></div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label text-md-end">電話</label>
                                <div class="col-md-6"><input type="text" class="form-control" value="{{ $master->phone }}" readonly></div>
                            </div>
                        </div>
                    </div>

                    {{-- 編輯制服 --}}
                    <div class="card">
                        <div class="card-header text-center">編輯制服資料</div>
                        <div class="card-body">
                            @if(session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <form method="POST"
                                  action="{{ route('masters.rent_uniforms.update', ['hash_uniform' => \Vinkla\Hashids\Facades\Hashids::encode($uniform->id)]) }}"
                                  class="mx-auto" style="max-width:420px;">
                                @csrf
                                @method('PATCH')

                                <div class="mb-3">
                                    <label class="form-label">尺寸</label>
                                    <select name="size" class="form-select @error('size') is-invalid @enderror" required>
                                        @foreach(['S','M','L','XL','XXL'] as $s)
                                            <option value="{{ $s }}" {{ old('size', $uniform->size) === $s ? 'selected' : '' }}>{{ $s }}</option>
                                        @endforeach
                                    </select>
                                    @error('size') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">數量</label>
                                    <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                                           min="1" value="{{ old('quantity', $uniform->quantity) }}" required>
                                    @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">儲存</button>
                                    <a href="{{ route('masters.personal_information.index') }}" class="btn btn-secondary">返回</a>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function setFontSize(size) {
            const content = document.getElementById('content');
            content.className = size;
        }
    </script>
@endsection
