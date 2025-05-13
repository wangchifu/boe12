@extends('layouts.app')

@section('title','資料填報 | ')

@section('content')
    <div class="container">
        <div class="py-5">
            <div class="">
                @include('posts.nav')
                @if(!empty(auth()->user()->section_id))
                    <div class="card my-4">
                        <div class="card-header text-center bg-info">
                            <h3 class="py-2">
                                填報系統：個人 <span class="badge bg-warning"><i class="fas fa-exclamation-circle"></i> 作業區</span>
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
                                    截止日期
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
                                    <td data-th="發佈日期">
                                        <small>{{ substr($report->created_at,0,16) }}</small>
                                    </td>
                                    <td data-th="截止日期">
                                        <small class="text-danger">{{ $report->die_date }}</small>
                                    </td>
                                    <td data-th="狀態">
                                        {{ $situation[$report->situation] }}
                                        @if(date('Ymd') > str_replace('-','',$report->die_date))
                                            <span class="text-danger">(已截止)</span>
                                        @endif
                                    </td>
                                    <td data-th="動作">
                                        @if($report->situation ==0)
                                            <form id="resend{{ $report->id }}" action="{{ route('edu_report.resend',$report->id) }}" method="post">
                                                @csrf
                                                @method('patch')
                                            </form>
                                            <button class="btn btn-outline-primary btn-sm" onclick="if(confirm('您確定再次送審嗎?')) $('#resend{{ $report->id }}').submit();else return false">再次送審</button>
                                        @endif
                                        @if($report->situation == -1 or $report->situation ==0)
                                            <a href="{{ route('edu_report.edit',$report->id) }}" class="btn btn-outline-danger btn-sm">修改</a>
                                            <button class="btn btn-outline-dark btn-sm" onclick="if(confirm('您確定送出嗎?')) $('#del{{ $report->id }}').submit();else return false">刪除</button>
                                            <form id="del{{ $report->id }}" action="{{ route('edu_report.destroy',$report->id) }}" method="post">
                                                @csrf
                                                {{ method_field('DELETE') }}
                                            </form>
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
                @endif
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
