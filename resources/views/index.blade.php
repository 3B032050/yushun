@extends('layouts.master')

@section('title','天筑精舍')

@section('content')
    <!-- 幻燈片 -->
    <section>
        <div class="carousel-background" style="background-color: #EEEDEC;">
            <div class="carousel-container" style="margin: 0 auto; width: 80vw;">
                @if($slides->count() > 0)
                    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            @foreach($slides as $index => $slide)
                                <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}" aria-current="{{ $index == 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"></button>
                            @endforeach
                        </div>
                        <div class="carousel-inner">
                            @foreach($slides as $index => $slide)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}" data-index="{{ $index }}">
                                    <img src="{{ asset('storage/slides/' . $slide->image_path) }}?v={{ time() }}" class="d-block w-100" alt="{{ $slide->title }}" style="width: 100%; height: 300px; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                        <!-- 轮播控制按钮 -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                @else
                    <!-- 如果沒有幻燈片，顯示預設的圖片 -->
                    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="{{ asset('images/1705423957.jpg') }}?v={{ time() }}" class="d-block w-100" style="width: 100%; height: 500px; object-fit: cover;">
                            </div>
                        </div>
                        <!-- 轮播控制按钮 -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- 宗旨 -->
    <section class="py-4" style="background-color: #EEEDEC; text-align: center;">
        <div class="container" style="margin: 0 auto; width: 80vw;">
            <p style="font-size: 28px; font-weight: bold; font-family: 'Great Vibes', cursive;">老實修行 &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp  淨土為歸</p>
            <p style="font-size: 28px; font-weight: bold; font-family: 'Great Vibes', cursive;">培養僧才 &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp  續佛慧命</p>
            <p style="font-size: 28px; font-weight: bold; font-family: 'Great Vibes', cursive;">深入緣起 &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp  踏實利生</p>
        </div>
    </section>

    <!-- 公告 -->
    <section class="py-0">
        <div class="carousel-background" style="background-color: #EEEDEC; padding-top: 0;"> <!-- 去掉 padding-top -->
            <div class="carousel-container" style="margin: 0 auto; width: 80vw; background-color:#FFFFFF;">
                <div class="wrapper mx-auto" style="text-align:center">
                    <div class="table">
                        <h3 class="mt-4 text-center" style="font-family: 'Microsoft JhengHei', cursive;">最新消息 | News</h3><br>
                        <table class="table" style="text-align:center; margin-bottom: 0; width:80%;"> <!-- 去掉 margin-bottom -->
                            <tbody>
                            @foreach($posts as $post)
                                <tr>
                                    <td style="text-align:center;">
                                        <a href="{{ route('posts.show', $post->id) }}" style="color:black; display: block;" onmouseover="this.style.color='gray'" onmouseout="this.style.color='black'">
                                            {{ $post->title }}
                                        </a>
                                    </td>
                                    <td style="text-align:center">
                                        <a href="{{ route('posts.show', $post->id) }}" style="color:black; display: block;" onmouseover="this.style.color='gray'" onmouseout="this.style.color='black'">
                                            {{ $post->updated_at }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <br>
                        <div class="text-center">
                            <a href="{{ route('posts.index') }}" class="btn btn-secondary">檢視全部公告</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        var myCarousel = new bootstrap.Carousel(document.getElementById('carouselExampleControls'), {
            interval: 3200,  // 调整切换间隔时间
            wrap: true,
        });

        // 監聽 Bootstrap Carousel 的 slide 事件，更新當前幻燈片的索引
        $('#carouselExampleControls').on('slide.bs.carousel', function (e) {
            var currentSlideIndex = $(e.relatedTarget).data('index');
            // 更新顯示當前幻燈片索引的元素
            $('#currentSlideIndex').text('Current Slide Index: ' + currentSlideIndex);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var carousel = document.querySelector('#carouselExampleControls');
            var indicators = carousel.querySelectorAll('.carousel-indicators button');

            carousel.addEventListener('slide.bs.carousel', function (event) {
                // 移除當前活動狀態
                indicators.forEach(function (indicator) {
                    indicator.classList.remove('active');
                    indicator.removeAttribute('aria-current');
                });
                // 設置新的活動狀態
                var newActiveIndicator = indicators[event.to];
                newActiveIndicator.classList.add('active');
                newActiveIndicator.setAttribute('aria-current', 'true');
            });
        });
    </script>
@endsection
