@extends('masters.layouts.master')

@section('title', '租借制服')

@section('content')
    <div class="container-fluid px-4">
        <div style="margin-top: 10px;">
            <p style="font-size: 1.8em;">
                <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                選擇尺寸
            </p>
        </div>
    </div>

    <h2 class="text-center mb-4">選擇制服的尺寸</h2>

    <div class="row justify-content-center">
        <div class="col-md-6">
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
        </div>
    </div>
{{--    <div class="row justify-content-center">--}}
{{--        @foreach($uniforms as $uniform)--}}
{{--            <div class="col-12 col-md-4 mb-3 d-flex justify-content-center">--}}
{{--                <form method="GET" action="{{ route('masters.rent_uniforms.create', $uniform->id) }}">--}}
{{--                    @csrf--}}
{{--                    @method("GET")--}}
{{--                    <button type="submit" class="btn uniform-btn text-center">--}}
{{--                        <img src="{{ asset('storage/uniforms/' . $uniform->photo) }}"--}}
{{--                             alt="{{ $uniform->name }}"--}}
{{--                             class="img-fluid rounded uniform-image"--}}
{{--                             style="max-height: 200px; width: auto;">--}}
{{--                        <div class="text-center mt-2">--}}
{{--                            <h5>{{ $uniform->name }}</h5>--}}
{{--                        </div>--}}
{{--                    </button>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        @endforeach--}}
{{--    </div>--}}
@endsection
