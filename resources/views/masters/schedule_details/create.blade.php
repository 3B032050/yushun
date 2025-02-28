@extends('masters.layouts.master')

@section('title', '完成訂單')

@section('content')
    <div class="container">
        <h2 class="text-center mt-4">完成訂單</h2>
        <form action="{{ route('masters.schedule_details.store', $appointmenttime->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- 圖片上傳區塊 -->
            <div class="row">
                <!-- 清潔前照片 -->
                <div class="col-md-6">
                    <label for="before_photo" class="form-label">清潔前照片</label>
                    <input type="file" class="form-control" id="before_photo" name="before_photo" required onchange="previewImage(event, 'before_preview')">
                    <div class="mt-2 text-center">
                        <img id="before_preview" src="" class="img-thumbnail d-none preview-img">
                    </div>
                </div>

                <!-- 清潔後照片 -->
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
