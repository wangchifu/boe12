@extends('layouts.app')

@section('title', '通過區 | ')

@section('content')
    <div class="container">
        <div class="py-5">
            <div class="">
                @include('posts.nav')
                <div class="card my-4">
                    <div class="card-header text-center bg-info">
                        <h3 class="py-2">
                            填報系統：個人 <span class="badge bg-secondary"><i class="fas fa-check-circle"></i> 通過區</span>
                        </h3>
                    </div>
                    <div class="card-body">
                    <table class="table rwd-table">
                        <thead class="thead-light">
                        <tr>
                            <th nowrap>
                                編號
                            </th>
                            <th nowrap>
                                發佈人
                            </th>
                            <th nowrap>
                                名稱
                            </th>
                            <th nowrap>
                                創建時間<br>發佈時間
                            </th>
                            <th nowrap>
                                截止日期
                            </th>
                            <th nowrap>
                                狀態
                            </th>
                            <th nowrap>
                                動作
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($reports as $report)
                            <tr>
                                <td nowrap data-th="編號">
                                    {{ $report->id }}
                                </td>
                                <td nowrap data-th="發佈人">
                                    {{ $report->user->name }}
                                </td>
                                <td data-th="名稱">
                                    @if($report->situation==3)
                                    <a href="javascript:open_report('{{ route('edu_report.show',$report->id) }}','新視窗')">
                                        <span style="color:#000088">
                                        {{ str_limit($report->name,60)}}
                                        </span>
                                    </a>
                                    @endif
                                    @if($report->situation==4)
                                    <del>
                                    <a href="javascript:open_report('{{ route('edu_report.show',$report->id) }}','新視窗')">
                                        <span style="color:#000088">
                                        {{ str_limit($report->name,60)}}
                                        </span>
                                    </a>
                                    </del>
                                    @endif
                                </td>
                                <td nowrap data-th="創建時間">
                                    <small>{{ substr($report->created_at,0,16) }}</small><br>
                                    <small>{{ substr($report->passed_at,0,16) }}</small>
                                </td>
                                <td nowrap data-th="截止日期">
                                    <small class="text-danger">{{ $report->die_date }}</small>
                                    <a href="javascript:date_late('{{ route('edu_report.date_late',$report->id) }}','新視窗')" class="badge bg-success">延期</a>
                                </td>
                                <td nowrap data-th="狀態">
                                    {{ $situation[$report->situation] }}
                                    @if($report->situation==3)
                                        @if(isset($report->pass_user))
                                            <i class="fas fa-user" data-toggle="tooltip" data-placement="bottom" title="{{ $report->pass_user->name }} 於 {{ substr($report->passed_at,0,16) }}"></i>
                                        @endif
                                    @endif
                                    @if(date('Ymd') >= str_replace('-','',$report->die_date))
                                        <span class="text-danger">(已截止)</span>
                                    @endif
                                    @if($report->situation==3)
                                        @if(isset($post->pass_user))
                                        <i class="fas fa-user" data-toggle="tooltip" data-placement="bottom" title="{{ $report->pass_user->name }} 於 {{ $report->updated_at }}"></i>
                                        @endif
                                    @endif
                                </td>
                                <td nowrap data-th="動作">
                                    <a href="{{ route('edu_report.result',$report->id) }}" class="btn btn-info btn-sm">結果顯示</a>
                                    @if($report->situation==3)
                                        <a href="{{ route('edu_report.obsolete',$report->id) }}" class="btn btn-outline-dark btn-sm" onclick="return confirm('確定作廢？')">作廢</a>
                                        <a href="{{ route('edu_report.copy',$report->id) }}" class="btn btn-outline-primary btn-sm">
                                            複製
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    </div>
                    <div style="text-align:right">
                        {{ $reports->links() }}
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
        function date_late(url,name)
        {
            window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=850,height=650');
        }
    </script>
@endsection
