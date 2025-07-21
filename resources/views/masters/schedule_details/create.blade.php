@extends('masters.layouts.master')

@section('title', '家居媒合服務平台')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div style="margin-top: 10px;">
                <p style="font-size: 1.8em;">
                    <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> &gt;
                    <a href="{{ route('masters.appointmenttime.index') }}" class="custom-link">可預約時段</a> &gt;
                    <a href="{{ url()->previous() }}" class="custom-link">編輯可預約時段</a> >
                    上傳照片
                </p>
            </div>
        </div>

        <div class="container">
            <h2 class="text-center mt-4">完成訂單</h2>
            <form action="{{ route('masters.schedule_details.store', ['hash_appointmenttime' => Hashids::encode($appointmenttime->id)]) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <label for="before_photo" class="form-label">清潔前照片</label>
                        <input type="file" class="form-control" id="before_photo" name="before_photo" required onchange="previewImage(event, 'before_preview')">
                        <div class="mt-2 text-center">
                            <img id="before_preview" src="" class="img-thumbnail d-none preview-img">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="after_photo" class="form-label">清潔後照片</label>
                        <input type="file" class="form-control" id="after_photo" name="after_photo" required onchange="previewImage(event, 'after_preview')">
                        <div class="mt-2 text-center">
                            <img id="after_preview" src="" class="img-thumbnail d-none preview-img">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100 mt-4">提交完成訂單</button>
            </form>
        </div>
    </div>
    <script>
        function previewImage(event, previewId) {
            const reader = new FileReader();
            reader.onload = function() {
                const preview = document.getElementById(previewId);
                preview.src = reader.result;
                preview.classList.remove('d-none'); // 顯示預覽圖片
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

    <style>
        .preview-img {
            width: 200px;
            height: 200px;
            object-fit: cover; /* 確保圖片填滿且不變形 */
            border-radius: 10px; /* 圓角 */
        }
    </style>
@endsection
