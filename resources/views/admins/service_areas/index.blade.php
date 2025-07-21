@extends('masters.layouts.master')

@section('title', '家居媒合服務平台')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-2 mb-3">
                <nav aria-label="breadcrumb" class="mb-2 mb-md-0 w-100 w-md-auto">
                    <ol class="breadcrumb breadcrumb-path mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('masters.index') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admins.service_areas.index') }}">服務地區管理</a></li>
                    </ol>
                </nav>
                <div class="text-size-controls btn-group btn-group-sm" role="group" aria-label="字級調整">
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
                </div>
            </div>
            <h1 class="mt-4 text-center">服務地區</h1>
        </div>

        <div id="content" class="medium container-fluid px-3 px-md-4">
            <form method="GET" action="{{ route('admins.service_areas.index') }}" class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center mb-3 gap-2 gap-sm-2">
                <input type="text" class="form-control" name="search" placeholder="搜尋服務地區" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary btn-sm">搜尋</button>
                <a href="{{ route('admins.service_areas.index') }}" class="btn btn-secondary btn-sm">取消搜尋</a>
            </form>

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3">
                <h2 class="text-center mb-3 mb-md-0 flex-grow-1">服務區域列表</h2>
                <a class="btn btn-success btn-sm" href="{{ route('admins.service_areas.create') }}">新增地區</a>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>主要區域</th>
                        <th>次要區域</th>
                        <th>狀態</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($serviceAreas->isEmpty())
                        <tr>
                            <td colspan="4" class="text-center">沒有找到符合條件的服務地區。</td>
                        </tr>
                    @else
                        @foreach ($serviceAreas as $area)
                            <tr>
                                <td>{{ $area->major_area }}</td>
                                <td>{{ $area->minor_area }}</td>
                                <td>
                                    @if($area->status == 1)
                                        <span class="text-warning">蛋黃區</span>
                                    @elseif($area->status == 0)
                                        <span class="text-dark">蛋白區</span>
                                    @endif
                                </td>
                                <td class="d-flex gap-1 flex-wrap">
                                    <a href="{{ route('admins.service_areas.edit', ['hash_service_area' => \Vinkla\Hashids\Facades\Hashids::encode($area->id)]) }}" class="btn btn-warning btn-sm flex-grow-1 flex-md-grow-0">編輯</a>
                                    <form action="{{ route('admins.service_areas.destroy', ['hash_service_area' => \Vinkla\Hashids\Facades\Hashids::encode($area->id)]) }}" method="POST" style="margin:0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm flex-grow-1 flex-md-grow-0" onclick="return confirm('確定要刪除這個項目嗎？')">刪除</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-column flex-md-row justify-content-center align-items-center mt-3 gap-2">
                <span>每頁顯示 <strong>{{ $serviceAreas->perPage() }}</strong> 筆資料，當前在第 <strong>{{ $serviceAreas->currentPage() }}</strong> 頁，共有 <strong>{{ $serviceAreas->total() }}</strong> 筆資料。</span>
                <div>
                    {{ $serviceAreas->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    <style>
        .breadcrumb-path {
            font-size: 1.4em;
            white-space: normal;
            word-break: break-word;
        }

        @media (max-width: 768px) {
            .breadcrumb-path {
                font-size: 1.3em;
            }
            .text-size-controls {
                margin-top: 0.5rem;
            }
        }

        @media (max-width: 480px) {
            .breadcrumb-path {
                font-size: 1.1em;
            }
            .d-flex.flex-column.flex-md-row > .btn-group {
                width: 100%;
                justify-content: center;
            }
        }

        #content.font-small {
            font-size: 14px;
        }

        #content.font-medium {
            font-size: 16px;
        }

        #content.font-large {
            font-size: 18px;
        }

        #content.font-small .form-control {
            font-size: 0.85rem;
        }

        #content.font-medium .form-control {
            font-size: 1rem;
        }

        #content.font-large .form-control {
            font-size: 1.15rem;
        }
    </style>

    <script>
        function setFontSize(size) {
            const content = document.getElementById('content');
            content.classList.remove('font-small', 'font-medium', 'font-large');
            content.classList.add(`font-${size}`);
        }
    </script>
@endsection
