@extends('masters.layouts.master')

@section('title', '豫順家居媒合服務平台')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-2">
            <nav aria-label="breadcrumb" class="mb-2 mb-md-0 w-100 w-md-auto">
                <ol class="breadcrumb breadcrumb-path mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('masters.index') }}">
                            <i class="fa fa-home"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admins.uniforms.index') }}">制服資料管理</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">新增制服資料</li>
                </ol>
            </nav>
            <div class="text-size-controls btn-group btn-group-sm" role="group" aria-label="字級調整">
                <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
            </div>
        </div>
    </div>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">新增制服資料</div>
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        <form method="POST" action="{{ route('admins.uniforms.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="master_id" class="form-label">選擇師傅</label>
                                <select name="master_id" id="master_id"
                                        class="form-select @error('master_id') is-invalid @enderror" required>
                                    <option value="">請選擇</option>
                                    @foreach($masters as $master)
                                        <option value="{{ $master->id }}" {{ old('master_id') == $master->id ? 'selected' : '' }}>
                                            {{ $master->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('master_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="size" class="form-label">尺寸</label>
                                <select name="size" id="size" class="form-select @error('size') is-invalid @enderror" required>
                                    @foreach(['S','M','L','XL','XXL'] as $s)
                                        <option value="{{ $s }}" {{ old('size') === $s ? 'selected' : '' }}>{{ $s }}</option>
                                    @endforeach
                                </select>
                                @error('size')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="quantity" class="form-label">數量</label>
                                <input type="number" name="quantity" id="quantity"
                                       class="form-control @error('quantity') is-invalid @enderror"
                                       min="1" max="50" value="{{ old('quantity', 1) }}" required>
                                @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">儲存</button>
                                    <button type="button" class="btn btn-danger"
                                            onclick="window.location.href='{{ route('admins.uniforms.index') }}'">
                                        返回
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
