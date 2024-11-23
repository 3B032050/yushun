@extends('masters.layouts.master')

@section('title', '租借制服')

@section('content')
    <div class="container-fluid px-4">
        <div style="margin-top: 10px;">
            <p style="font-size: 1.8em;">
                <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                租借制服
            </p>
        </div>
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
@endsection
