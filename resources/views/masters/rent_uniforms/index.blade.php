@extends('masters.layouts.master')

@section('title', '租借制服')

@section('content')
    <div class="container">
        <div style="margin-top: 10px;">
            <p style="font-size: 1.8em;">
                <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                租借制服
            </p>
        </div>

        <h2 class="text-center mb-4">選擇要租借的制服</h2>

        <div class="row justify-content-center">
            @foreach($uniforms as $uniform)
                <div class="col-12 col-md-4 mb-3 d-flex justify-content-center">
                    <form method="GET" action="{{ route('masters.rent_uniforms.create', $uniform->id) }}">
                        @csrf
                        @method("GET")
                        <button type="submit" class="btn uniform-btn text-center">
                            <img src="{{ asset('storage/uniforms/' . $uniform->photo) }}"
                                 alt="{{ $uniform->name }}"
                                 class="img-fluid rounded uniform-image"
                                 style="max-height: 200px; width: auto;">
                            <div class="text-center mt-2">
                                <h5>{{ $uniform->name }}</h5>
                            </div>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
@endsection

<style>
    /* 自訂按鈕樣式 */
    .uniform-btn {
        background-color: #fff;
        border: none;
        padding: 0;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        transition: transform 0.2s ease-in-out;
    }

    /* 按鈕懸停效果 */
    .uniform-btn:hover {
        transform: scale(1.05);
    }

    /* 控制制服圖片大小 */
    .uniform-image {
        max-height: 200px;
        width: auto;
    }
</style>
