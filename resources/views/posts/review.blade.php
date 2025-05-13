@extends('layouts.app')

@section('title','待審公告 | ')

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
                <div class="card-header text-center">
                    <h3 class="py-2">
                        [{{ $sections[$power_section_id] }}] <i class="fas fa-user-cog"></i> 待審公告
                    </h3>
                </div>
                <div class="card-body">
                    @include('posts.list')
                </div>
            </div>
            <div class="card my-4">
                <div class="card-header text-center bg-info">
                    <h3 class="py-2">
                        [{{ $sections[$power_section_id] }}] <i class="fas fa-user-cog"></i> 待審填報
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table rwd-table">
                        <thead class="thead-light">
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
                                創建時間
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
                                <td data-th="編號">
                                    {{ $report->id }}
                                </td>
                                <td data-th="發佈人">
                                    {{ $report->user->name }}
                                </td>
                                <td data-th="名稱">
                                    <a href="javascript:open_report('{{ route('edu_report.show',$report->id) }}','新視窗')">
                                        <span style="color:#000088">
                                        {{ str_limit($report->name,60) }}
                                        </span>
                                    </a>
                                </td>
                                <td data-th="創建時間">
                                    <small>{{ substr($report->created_at,0,16) }}</small>
                                </td>
                                <td data-th="狀態">
                                    {{ $situation[$report->situation] }}
                                </td>
                                <td data-th="動作">
                                    <div style="float:left;margin-right: 5px">
                                        <form action="{{ route('reports.return',$report->id) }}" method="post">
                                            @csrf
                                            @method('patch')
                                            <button class="btn btn-outline-success btn-sm" onclick="return confirm('確定退回？')">退回</button>
                                        </form>
                                    </div>
                                    <div style="float:left;margin-right: 5px">
                                        <form action="{{ route('reports.approve',$report->id) }}" method="post" id="f{{ $report->id }}">
                                            @csrf
                                            @method('patch')
                                            <span class="btn btn-outline-info btn-sm" onclick="if(confirm('確定核准嗎？')){change_button2({{ $report->id }});}else return false;" id="b{{ $report->id }}">核准</span>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function open_report(url,name)
    {
        window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=850,height=650');
    }
    function change_button2(id){
        $("#b"+id).removeAttr('onclick');
        $("#b"+id).attr('disabled','disabled');
        $("#b"+id).addClass('disabled');
        $("#f"+id).submit();
    }
</script>
@endsection
