@extends('masters.layouts.master')

@section('title', '制服管理')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-2">
                <p style="font-size: 1.8em;">
                    <a href="{{ route('masters.index') }}" class="custom-link"><i class="fa fa-home"></i></a> >
                    制服管理
                </p>
                <div class="text-size-controls btn-group btn-group-sm" role="group" aria-label="字級調整">
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('small')">小</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('medium')">中</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="setFontSize('large')">大</button>
                </div>
            </div>
            <h1 class="mt-4 text-center">制服管理</h1>
        </div>

        <div id="content" class="medium">
            <div class="table-responsive d-flex justify-content-center">
                <table class="table" style="width: 80%;" id="sortable-list">
                    <thead>
                    <tr>
                        <th scope="col" style="text-align:center; width: 15%;">師傅姓名</th>
                        <th scope="col" style="text-align:center; width: 10%;">尺寸</th>
                        <th scope="col" style="text-align:center; width: 10%;">數量</th>
                        <th scope="col" style="text-align:center; width: 15%;">時間</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rent_uniforms as $index => $rent_uniform)
                        <tr>
                            <td class="align-middle" style="text-align:center">{{ $rent_uniform->master->name }}</td>
                            <td class="align-middle" style="text-align:center">{{ $rent_uniform->size }}</td>
                            <td class="align-middle" style="text-align:center">{{ $rent_uniform->quantity }}</td>
                            <td class="align-middle" style="text-align:center">{{ $rent_uniform->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
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
