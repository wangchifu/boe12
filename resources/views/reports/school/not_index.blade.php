@extends('layouts.app')

@section('title','資料填報 | ')

@section('content')
    <div class="container">
        <div class="py-5">
            <div class="">
                <?php
                $posts_all_not = \App\Models\PostSchool::where('code','like', "%".auth()->user()->code."%")
                    ->where('signed_user_id',null)
                    ->get();
                $posts_quick = 0;
                $posts_not = 0;
                foreach($posts_all_not as $post_all_not){
                    if($post_all_not->post->type == "1"){
                        $posts_quick++;
                    }
                    if($post_all_not->post->situation === 3){
                        $posts_not++;
                    }
                }

                $c_p = $posts_not;
                $c_r = \App\Models\ReportSchool::where('code','like', "%".auth()->user()->code."%")
                    ->where(function($q){
                        $q->where('situation','=',0)
                            ->orWhere('situation','=',1)
                            ->orWhere('situation','=',2)
                            ->orWhere('situation',null);
                    })
                    ->get()->count();
                ?>
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a class="btn btn-light btn-sm" href="{{ route('posts.showSigned') }}">公告簽收 ({{ $c_p }})</a>
                    <a class="btn btn-success btn-sm" href="{{ route('school_report.index') }}">資料填報 ({{ $c_r }})</a>
                </div>
                <div class="card my-4">
                    <div class="card-header text-center">
                        <h3 class="py-2">
                            資料填報
                        </h3>
                    </div>
                    <div class="card-body">
                        @include('reports.school.search_nav')
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('school_report.index') }}">全部({{ $c_r }})</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="{{ route('school_report_not.index') }}">未填報({{ $c_r }})</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('school_report.show_person_Signed') }}">個人已填報</a>
                            </li>
                        </ul>
                        <div class="table-responsive">
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
                                    <tr>
                                        <td nowrap data-th="編號">
                                            <span data-toggle="tooltip" data-placement="top" title="給 {{ $schools[$report_school->code] }}">{{ $report_school->report_id }}</span>
                                        </td>
                                        <td nowrap data-th="日期">
                                            <small>{{ substr($report_school->report->passed_at,0,16) }}</small>
                                            <br>
                                            <small class="text-danger">{{ $report_school->report->die_date }}</small>
                                        </td>
                                        <td data-th="名稱" style="color:#000000;">
                                            @if($report_school->report->situation==3)
                                                {{ str_limit($report_school->report->name,160) }}
                                            @endif
                                            @if($report_school->report->situation==4)
                                                <del>
                                                    {{ str_limit($report_school->report->name,160) }}
                                                </del>
                                                <span class="text-danger">(已作廢)</span>
                                            @endif
                                        </td>
                                        <td nowrap data-th="發佈人">
                                            @if(isset($sections[$report_school->report->user->section_id]))
                                                {{ $sections[$report_school->report->user->section_id] }}<br>
                                            @endif
                                            {{ $report_school->report->user->name }}
                                        </td>
                                        <td nowrap data-th="狀態">
                                            @if($report_school->situation === null)
                                                未填報
                                            @elseif($report_school->situation == 1)
                                                <span class="text-info">校審中</span>
                                                <br>
                                                <small class="text-secondary">填:{{ $report_school->signed_user->name }}</small>
                                            @elseif($report_school->situation == 3)
                                                <span class="text-success">已通過</span>
                                                <br>
                                                <small class="text-secondary">填:{{ $report_school->signed_user->name }}</small>
                                            @elseif($report_school->situation == 4)
                                                <span class="text-danger">已不填報</span>
                                                <br>
                                                <small class="text-secondary">填:{{ $report_school->signed_user->name }}</small>
                                            @elseif($report_school->situation === 0)
                                                <span class="text-danger">已退回</span>
                                                <br>
                                                <small class="text-secondary">填:{{ $report_school->signed_user->name }}</small>
                                            @endif
                                            @if(date('Ymd') > str_replace('-','',$report_school->report->die_date))
                                                <span class="text-danger">(已截止)</span>
                                            @endif
                                        </td>
                                        <td nowrap data-th="動作">
                                            @if($report_school->situation === null)
                                                @if(date('Ymd') <= str_replace('-','',$report_school->report->die_date))
                                                    @if($report_school->report->situation != 4)
                                                        <a href="javascript:open_report('{{ route('school_report.create',$report_school->id) }}','新視窗')" class="btn btn-primary btn-sm">
                                                            填報
                                                        </a>
                                                    @endif
                                                @endif
                                                <!--
                                                    <a href="{{ route('school_report.no_report',$report_school->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('確定不填報嗎？')">
                                                        不填報
                                                    </a>
                                                -->
                                            @elseif($report_school->situation ===4)
        
                                            @else
                                                <a href="javascript:open_report('{{ route('school_report.show',$report_school->id) }}','新視窗')" class="btn btn-success btn-sm">
                                                    查看
                                                </a>
                                            @endif
        
                                            @if($report_school->situation === 0)
                                                @if(date('Ymd') <= str_replace('-','',$report_school->report->die_date))
                                                    <a href="javascript:open_report('{{ route('school_report.edit',$report_school->id) }}','新視窗')" class="btn btn-primary btn-sm">
                                                        編輯
                                                    </a>
                                                @else
                                                <!--
                                                    <a href="{{ route('school_report.no_report',$report_school->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('確定不填報嗎？')">
                                                        不填報
                                                    </a>
                                                -->
                                                @endif
                                            @endif
                                                <a href="{{ route('school_report.print',$report_school->id) }}" class="btn btn-outline-secondary btn-sm" target="_blank"><i class="fas fa-print"></i> <i class="fas fa-sort-amount-up"></i></a>
                                        </td>
                                        <td nowrap data-th="審核">
                                        @if(check_a_user(auth()->user()->code,auth()->user()->id))
                                                @if($report_school->situation === 1 and date('Ymd') <= str_replace('-','',$report_school->report->die_date) and $report_school->report->situation != 4)
                                                    <div style="float:left;margin-right: 5px">
                                                        <form action="{{ route('school_report.back',$report_school->id) }}" method="post">
                                                            @csrf
                                                            @method('patch')
                                                            <button class="btn btn-outline-danger btn-sm" onclick="return confirm('確定退回？')">退回</button>
                                                        </form>
                                                    </div>
                                                    <div style="float:left;margin-right: 5px">
                                                        <form action="{{ route('school_report.passing',$report_school->id) }}" method="post">
                                                            @csrf
                                                            @method('patch')
                                                            <button class="btn btn-outline-success btn-sm" onclick="return confirm('確定通過？')">通過</button>
                                                        </form>
                                                    </div>
                                                @endif
                                                @if(($report_school->situation === 1 or $report_school->situation === 0) and date('Ymd') > str_replace('-','',$report_school->report->die_date))
                                                    <form action="{{ route('school_report.delay',$report_school->id) }}" method="post">
                                                        @csrf
                                                        @method('patch')
                                                        <button class="btn btn-outline-danger btn-sm" onclick="return confirm('確定？')">已知逾期未送</button>
                                                    </form>
                                                @endif
                                                @if(date('Ymd') > str_replace('-','',$report_school->report->die_date) and $report_school->situation === null)
                                                    <form action="{{ route('school_report.delay',$report_school->id) }}" method="post">
                                                        @csrf
                                                        @method('patch')
                                                        <button class="btn btn-outline-danger btn-sm" onclick="return confirm('行政公告及填報視同公文，請勿再缺交填報。 ')">已知逾期未交</button>
                                                    </form>
                                                @endif
                                        @endif
                                        @if($report_school->situation === 3 or $report_school->situation === 0 or $report_school->situation === 5 or $report_school->situation === 6)
                                            @if($report_school->situation === 5)
                                                已知未交<br>
                                            @endif    
                                            @if($report_school->situation === 6)
                                                已知作廢<br>
                                            @endif
                                            @if($report_school->review_user_id)
                                                <small class="text-secondary">審:{{ $report_school->review_user->name }}</small>
                                            @endif
                                        @endif
                                        @if($report_school->report->situation===4 and $report_school->situation != 6 and check_a_user(auth()->user()->code,auth()->user()->id))
                                        <form action="{{ route('school_report.cancel',$report_school->id) }}" method="post">
                                            @csrf
                                            @method('patch')
                                            <button class="btn btn-outline-danger btn-sm" onclick="return confirm('確定已知此填報作廢？')">已知作廢</button>
                                        </form>
                                        @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>                        
                    </div>
                    <div class="card-footer d-flex flex-row justify-content-center pt-4">
                        <div class="text-center">
                            {{ $report_schools->links() }}
                        </div>
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
    </script>
@endsection
