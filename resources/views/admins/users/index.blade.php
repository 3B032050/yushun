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


            <div class="table-responsive">
                <table class="table table-bordered table-hover w-100" id="sortable-list">
                    <thead class="table-light">
                    <tr>
                        <td colspan="8"></td>
                        <td class="text-center">
                            <a class="btn btn-success btn-sm" href="{{ route('admins.users.create') }}">
                                <i class="fa fa-plus"></i> 新增客戶資料
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-center" style="width:4%;">#</th>
                        <th class="text-center" style="width:12%;">姓名</th>
                        <th class="text-center" style="width:16%;">Email</th>
                        <th class="text-center" style="width:10%;">電話</th>
                        <th class="text-center" style="width:10%;">手機</th>
                        <th class="text-center" style="width:18%;">地址</th>
                        <th class="text-center" style="width:10%;">LINE ID</th>
                        <th class="text-center" style="width:10%;">客戶類型</th> {{-- 新增 --}}
                        <th class="text-center" style="width:10%;">操作</th>
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
                                @if($user->is_recurring == 0)
                                    非定期
                                @elseif($user->is_recurring == 1)
                                    定期
                                @else
                                    <span class="text-muted">未設定</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- 編輯 --}}
                                    <a href="{{ route('admins.users.edit', ['hash_user' => \Vinkla\Hashids\Facades\Hashids::encode($user->id)]) }}"
                                       class="btn btn-secondary btn-sm btn-icon" title="編輯">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>

                                    {{-- 刪除 --}}
                                    <form action="{{ route('admins.users.destroy', ['hash_user' => \Vinkla\Hashids\Facades\Hashids::encode($user->id)]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-delete btn btn-danger btn-sm btn-icon" title="刪除">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">目前沒有客戶資料</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
