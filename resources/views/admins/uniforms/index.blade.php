@extends('masters.layouts.master')

@section('title', '豫順家居媒合服務平台')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-2">
                <nav aria-label="breadcrumb" class="mb-2 mb-md-0 w-100 w-md-auto">
                    <ol class="breadcrumb breadcrumb-path mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('masters.index') }}">
                                <i class="fa fa-home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admins.uniforms.index') }}">制服管理</a>
                        </li>
                    </ol>
                </nav>
                <div class="text-size-controls btn-group btn-group-sm" role="group" aria-label="字級調整">
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
                </div>
            </div>
            <h2 class="mt-4 text-center">制服管理</h2>
        </div>

        <div id="content" class="medium">
            <div class="table-responsive d-flex justify-content-center flex-column align-items-end mb-2" style="width: 80%; margin: 0 auto;">
                <a href="{{ route('admins.uniforms.create') }}" class="btn btn-primary mb-2">新增制服</a>
                <table class="table table-bordered w-100" id="sortable-list">
                    <thead class="table-light text-center">
                    <tr>
                        <th style="width: 15%;">師傅姓名</th>
                        <th style="width: 10%;">尺寸</th>
                        <th style="width: 10%;">數量</th>
                        <th style="width: 15%;">時間</th>
                    </tr>
                    </thead>
                    <tbody class="text-center">
                    @forelse($rent_uniforms as $rent_uniform)
                        <tr>
                            <td class="align-middle">{{ $rent_uniform->master->name }}</td>
                            <td class="align-middle">{{ $rent_uniform->size }}</td>
                            <td class="align-middle">{{ $rent_uniform->quantity }}</td>
                            <td class="align-middle">{{ $rent_uniform->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">尚無資料</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
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

        #content.font-small .table th,
        #content.font-small .table td {
            font-size: 0.85rem;
        }

        #content.font-medium .table th,
        #content.font-medium .table td {
            font-size: 1rem;
        }

        #content.font-large .table th,
        #content.font-large .table td {
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
