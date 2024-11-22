@extends('masters.layouts.master')

@section('title', '租借制服')

@section('content')
    @if (session('error'))
        <script>
            alert("{{ session('error') }}");
        </script>
    @endif
    <div class="container">
        <div style="margin-top: 10px;">
            <p style="font-size: 1.8em;">
                <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                <a href="{{ route('masters.rent_uniforms.index') }}" class="custom-link">租借制服</a> >
                選擇尺寸與數量
            </p>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center">
                        <strong>租借制服：{{ $uniform->name }}</strong>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 text-center">
                                <img src="{{ asset('storage/uniforms/' . $uniform->photo) }}"
                                     alt="{{ $uniform->name }}"
                                     class="img-fluid rounded"
                                     style="max-height: 300px; width: auto;">
                            </div>
                            <div class="col-md-6">
                                <form method="POST" action="{{ route('masters.rent_uniforms.store') }}">
                                    @csrf

                                    <input type="hidden" name="uniform_id" value="{{ $uniform->id }}">

                                    <div class="form-group mb-3">
                                        <label for="size">選擇尺寸</label>
                                        <select name="size" id="size" class="form-control">
                                            <option value="S" @if($uniform->S == 0) disabled style="color: #808080FF;" @endif>
                                                S (庫存: {{ $uniform->S }})
                                            </option>
                                            <option value="M" @if($uniform->M == 0) disabled style="color: #808080FF;" @endif>
                                                M (庫存: {{ $uniform->M }})
                                            </option>
                                            <option value="L" @if($uniform->L == 0) disabled style="color: #808080FF;" @endif>
                                                L (庫存: {{ $uniform->L }})
                                            </option>
                                            <option value="XL" @if($uniform->XL == 0) disabled style="color: #808080FF;" @endif>
                                                XL (庫存: {{ $uniform->XL }})
                                            </option>
                                            <option value="XXL" @if($uniform->XXL == 0) disabled style="color: #808080FF;" @endif>
                                                XXL (庫存: {{ $uniform->XXL }})
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
                                        <button type="submit" class="btn btn-primary">確認租借</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
