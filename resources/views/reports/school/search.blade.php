@extends('layouts.app')

@section('title','資料填報 | ')

@section('content')
    <div class="container">
        <div class="py-5">
            <div class="alert alert-primary" role="alert">
                <a href="{{ route('school_report.index') }}">填報列表</a> / 搜尋：「{{ $want }}」
            </div>                
            <div class="card my-4">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        資料填報
                    </h3>
                </div>
                <div class="card-body">
                    @include('reports.school.search_nav')

                    <table class="table rwd-table">
                    <thead>
                    <tr>
                        <th nowrap>
                            編號
                        </th>
                        <th nowrap>
                            發佈時間<br>
                            截止日期
                        </th>
                        <th>
                            名稱
                        </th>
                        <th nowrap>
                            發佈人
                        </th>
                        <th nowrap>
                            狀態
                        </th>
                        <th nowrap>
                            動作
                        </th>
                        <th nowrap>
                            審核
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($report_schools as $report_school)
                        <?php
                            $report = \App\Models\Report::find($report_school->report_id);
                            $signed_user = \App\Models\User::find($report_school->signed_user_id);
                            $review_user = \App\Models\User::find($report_school->review_user_id);
                            $r_s = \App\Models\ReportSchool::where('report_id',$report->id)->where('code','like',"%".auth()->user()->code."%")->first();
                        ?>
                        <tr>
                            <td nowrap data-th="編號">
                                <span data-toggle="tooltip" data-placement="top" title="給 {{ $schools[$report_school->code] }}">{{ $report_school->report_id }}</span>
                            </td>
                            <td nowrap data-th="日期">
                                <small>{{ substr($report->passed_at,0,16) }}</small>
                                <br>
                                <small class="text-danger">{{ $report->die_date }}</small>
                            </td>
                            <td data-th="名稱" style="color:#000000;">
                                @if($report->situation==3)
                                    {{ str_limit($report->name,160) }}
                                @endif
                                @if($report->situation==4)
                                    <del>
                                        {{ str_limit($report->name,160) }}
                                    </del>
                                    <span class="text-danger">(已作廢)</span>
                                @endif
                            </td>
                            <td nowrap data-th="發佈人">
                                @if(isset($sections[$report->user->section_id]))
                                    {{ $sections[$report->user->section_id] }}<br>
                                @endif
                                {{ $report->user->name }}
                            </td>
                            <td nowrap data-th="狀態">
                                @if($r_s->situation === null)
                                    未填報
                                @elseif($r_s->situation == 1)
                                    <span class="text-info">校審中</span>
                                    <br>
                                    <small class="text-secondary">填:@if($signed_user){{ $signed_user->name }}@endif</small>
                                @elseif($r_s->situation == 3)
                                    <span class="text-success">已通過</span>
                                    <br>
                                    <small class="text-secondary">填:@if($signed_user){{ $signed_user->name }}@endif</small>
                                @elseif($r_s->situation == 4)
                                    <span class="text-danger">已不填報</span>
                                    <br>
                                    <small class="text-secondary">填:@if($signed_user){{ $signed_user->name }}@endif</small>
                                @elseif($r_s->situation === 0)
                                    <span class="text-danger">已退回</span>
                                    <br>
                                    <small class="text-secondary">填:@if($signed_user){{ $signed_user->name }}@endif</small>
                                @endif
                                @if(date('Ymd') > str_replace('-','',$report->die_date))
                                    <span class="text-danger">(已截止)</span>
                                @endif
                            </td>
                            <td nowrap data-th="動作">
                                @if($r_s->situation === null)
                                    @if(date('Ymd') <= str_replace('-','',$report->die_date))
                                        @if($report->situation != 4)
                                            <a href="javascript:open_report('{{ route('school_report.create',$r_s->id) }}','新視窗')" class="btn btn-primary btn-sm">
                                                填報
                                            </a>
                                        @endif
                                    @endif
                                    <!--
                                        <a href="{{ route('school_report.no_report',$r_s->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('確定不填報嗎？')">
                                            不填報
                                        </a>
                                    -->
                                @elseif($r_s->situation ===4)

                                @elseif($r_s->situation ===5)
                                @else
                                    <a href="javascript:open_report('{{ route('school_report.show',$r_s->id) }}','新視窗')" class="btn btn-success btn-sm">
                                        查看
                                    </a>
                                @endif

                                @if($r_s->situation === 0)
                                    @if(date('Ymd') <= str_replace('-','',$report->die_date))
                                        <a href="javascript:open_report('{{ route('school_report.edit',$r_s->id) }}','新視窗')" class="btn btn-primary btn-sm">
                                            編輯
                                        </a>
                                    @else
                                    <!--
                                        <a href="{{ route('school_report.no_report',$r_s->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('確定不填報嗎？')">
                                            不填報
                                        </a>
                                    -->
                                    @endif
                                @endif
                            </td>
                            <td nowrap data-th="審核">
                            @if(check_a_user(auth()->user()->code,auth()->user()->id))
                                    @if($r_s->situation === 1 and date('Ymd') <= str_replace('-','',$report->die_date))
                                        <div style="float:left;margin-right: 5px">
                                            <form action="{{ route('school_report.back',$r_s->id) }}" method="post">
                                                @csrf
                                                @method('patch')
                                                <button class="btn btn-outline-danger btn-sm" onclick="return confirm('確定退回？')">退回</button>
                                            </form>
                                        </div>
                                        <div style="float:left;margin-right: 5px">
                                            <form action="{{ route('school_report.passing',$r_s->id) }}" method="post">
                                                @csrf
                                                @method('patch')
                                                <button class="btn btn-outline-success btn-sm" onclick="return confirm('確定通過？')">通過</button>
                                            </form>
                                        </div>
                                    @endif
                                    @if($r_s->situation === 1 and date('Ymd') > str_replace('-','',$report->die_date))
                                        <form action="{{ route('school_report.back',$r_s->id) }}" method="post">
                                            @csrf
                                            @method('patch')
                                            <button class="btn btn-outline-danger btn-sm" onclick="return confirm('確定退回？')">逾期撤消</button>
                                        </form>
                                    @endif
                                    @if(date('Ymd') > str_replace('-','',$r_s->report->die_date) and $r_s->situation === null)
                                    <form action="{{ route('school_report.delay',$r_s->id) }}" method="post">
                                        @csrf
                                        @method('patch')
                                        <button class="btn btn-outline-danger btn-sm" onclick="return confirm('行政公告及填報視同公文，請勿再缺交填報。 ')">已知逾期未交</button>
                                    </form>
                                @endif
                            @endif
                            @if($r_s->situation === 3 or $r_s->situation === 0 or $r_s->situation === 5)
                                @if($r_s->situation === 5)
                                    已知未交<br>
                                @endif
                                @if($report_school->review_user_id)
                                    <small class="text-secondary">審:@if($review_user){{ $review_user->name }}@endif</small>
                                @endif
                            @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        function open_report(url,name)
        {
            window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=850,height=650');
        }
    </script>
@endsection
