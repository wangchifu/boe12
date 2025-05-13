@extends('layouts.app')

@section('title', '待審填報 | ')

@section('page-style')
    <style>
        DIV.table {
            display: table;
        }

        FORM.tr, DIV.tr {
            display: table-row;
        }

        SPAN.td {
            display: table-cell;
        }
    </style>
@endsection

@section('content')
<div class="container">
    <div class="py-5">
        <div class="">
            @include('posts.nav')
            <div class="card my-4">
                <div class="card-header text-center bg-info">
                    <h3 class="py-2">
                        [{{ $sections[$power_section_id] }}] <i class="fas fa-user-cog"></i> 待審填報
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>
                                編號
                            </th>
                            <th>
                                發佈人
                            </th>
                            <th>
                                名稱
                            </th>
                            <th>
                                日期
                            </th>
                            <th>
                                狀態
                            </th>
                            <th>
                                動作
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($reports as $report)
                            <tr>
                                <td>
                                    {{ $report->id }}
                                </td>
                                <td>
                                    {{ $report->user->name }}
                                </td>
                                <td>
                                    <a href="javascript:open_report('{{ route('edu_report.show',$report->id) }}','新視窗')">{{ str_limit($report->name,60) }}</a>
                                </td>
                                <td>
                                    <small>{{ substr($report->created_at,0,16) }}</small>
                                </td>
                                <td>
                                    {{ $situation[$report->situation] }}
                                </td>
                                <td>
                                    <div style="float:left;margin-right: 5px">
                                        <form action="{{ route('reports.return',$report->id) }}" method="post">
                                            @csrf
                                            @method('patch')
                                            <button class="btn btn-outline-success btn-sm" onclick="return confirm('確定退回？')">退回</button>
                                        </form>
                                    </div>
                                    <div style="float:left;margin-right: 5px">
                                        <form action="{{ route('reports.approve',$report->id) }}" method="post" onsubmit="change_button(b{{ $report->id }})">
                                            @csrf
                                            @method('patch')
                                            <button class="btn btn-outline-info btn-sm" onclick="return confirm('確定核准？')" id="b{{ $report->id }}">核准</button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div style="text-align:right">
                {{ $reports->links() }}
            </div>
        </div>
    </div>
</div>
<script>
    function open_report(url,name)
    {
        window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=850,height=650');
    }
    function change_button(id){
        $("#".id).attr('disabled','disabled');
        $("#".id).addClass('disabled');
    }
</script>
@endsection
