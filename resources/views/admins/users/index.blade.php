@extends('masters.layouts.master')

@section('title', '豫順家居媒合服務平台')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-2 flex-column flex-md-row">
                <nav aria-label="breadcrumb" class="mb-2 mb-md-0 w-100 w-md-auto">
                    <ol class="breadcrumb breadcrumb-path">
                        <li class="breadcrumb-item">
                            <a href="{{ route('masters.index') }}"><i class="fa fa-home"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">客戶資料管理</li>
                    </ol>
                </nav>
                <div class="btn-group btn-group-sm text-size-controls" role="group" aria-label="字級調整">
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
                </div>
            </div>
            <h1 class="mt-4 text-center">客戶資料管理</h1>
        </div>

        <div id="content" class="medium">
            {{-- 成功 / 錯誤訊息 --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover w-100" id="sortable-list">
                    <thead class="table-light">
                    <tr>
                        <td colspan="7"></td>
                        <td class="text-center">
                            <a class="btn btn-success btn-sm" href="{{ route('admins.users.create') }}">
                                <i class="fa fa-plus"></i> 新增客戶資料
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-center" style="width:4%;">#</th>
                        <th class="text-center" style="width:14%;">姓名</th>
                        <th class="text-center" style="width:18%;">Email</th>
                        <th class="text-center" style="width:12%;">電話</th>
                        <th class="text-center" style="width:12%;">手機</th>
                        <th class="text-center" style="width:22%;">地址</th>
                        <th class="text-center" style="width:10%;">LINE ID</th>
                        <th class="text-center" style="width:8%;">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $index => $user)
                        <tr>
                            <td class="text-center">
                                {{ ($users instanceof \Illuminate\Pagination\AbstractPaginator) ? ($users->firstItem() + $index) : $loop->iteration }}
                            </td>
                            <td class="text-center">{{ $user->name }}</td>
                            <td class="text-center">{{ $user->email }}</td>
                            <td class="text-center">{{ $user->phone }}</td>
                            <td class="text-center">{{ $user->mobile }}</td>
                            <td class="text-center text-truncate" style="max-width: 260px;" title="{{ $user->address }}">
                                {{ $user->address }}
                            </td>
                            <td class="text-center">{{ $user->line_id }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    <a href="{{ route('admins.users.edit', ['hash_user' => \Vinkla\Hashids\Facades\Hashids::encode($user->id)]) }}"
                                       class="btn btn-secondary btn-sm" title="編輯">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                    <form id="deleteForm_{{ $user->id }}"
                                          action="{{ route('admins.users.destroy', ['hash_user' => \Vinkla\Hashids\Facades\Hashids::encode($user->id)]) }}"
                                          method="POST" style="margin:0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm" title="刪除"
                                                onclick="confirmDelete('{{ $user->name }}', {{ $user->id }})">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">目前沒有客戶資料</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

{{--            --}}{{-- 分頁資訊 --}}
{{--            @if($users instanceof \Illuminate\Pagination\AbstractPaginator)--}}
{{--                <div class="d-flex flex-column flex-md-row justify-content-center align-items-center mt-3 gap-2">--}}
{{--                    <div>--}}
{{--                        <div>每頁顯示 <strong>{{ $users->perPage() }}</strong> 筆資料</div>--}}
{{--                        <div>當前在第 <strong>{{ $users->currentPage() }}</strong> 頁</div>--}}
{{--                        <div>共有 <strong>{{ $users->total() }}</strong> 筆資料</div>--}}
{{--                    </div>--}}
{{--                    <div>--}}
{{--                        {{ $users->appends(request()->query())->links() }}--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @endif--}}
        </div>
    </div>

    <script>
        function confirmDelete(name, id) {
            if (confirm('確定要刪除使用者「' + name + '」嗎？')) {
                document.getElementById('deleteForm_' + id).submit();
            }
        }

        function setFontSize(size) {
            const content = document.getElementById('content');
            content.classList.remove('small', 'medium', 'large');
            content.classList.add(size);
        }
    </script>
@endsection
