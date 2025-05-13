@extends('layouts.app_clean')

@section('title', $report->name.' | ')

@section('content')
    <div class="container">
        <div class="col-12">
            <div class="card">
                <div class="card-head">
                    <img class="card-img-top img-responsive" src="{{ asset('images/small/report.png') }}">
                </div>
                <div class="card-body">
                    @if($report->situation==3)
                        <h2>{{ $report->name }}</h2>
                    @endif
                    @if($report->situation==4)
                        <h2><del>{{ $report->name }}</del></h2>
                    @endif
                    {{ $report->user->name }} {{ $report->created_at }} 創建
                    @if(!empty($report->user->telephone))
                        <i class="fas fa-phone"></i> {{ $report->user->telephone }}
                    @endif
                    <hr>
                    <strong>截止日期：</strong>
                    <div class="form-group text-danger">
                        {{ $report->die_date }}
                    </div>
                    <strong>說明：</strong>
                    <div class="form-group">
                        {!! $report->content !!}
                    </div>
                    <strong>附檔：</strong>
                    <div class="form-group">
                        @foreach($files as $k=>$v)
                            <a href="{{ route('edu_report.download',['id'=>$report->id,'filename'=>$v]) }}" class="btn btn-primary btn-sm" style="margin:3px"><i class="fas fa-download"></i> {{ $v }}</a>
                        @endforeach
                    </div>
                    <strong>題目：</strong>
                    <div class="form-group">
                        <?php  $i=1; ?>
                        @foreach($report->questions as $question)
                            <div class="card" style="margin: 5px;">
                                <div class="card-header">
                                    題目{{ $i }}：
                                    {{ $question->title }}
                                </div>
                                <div class="card-body">
                                    @if($question->type=="radio" or $question->type=="checkbox")
                                        <?php $options = unserialize($question->options); ?>
                                            @if($question->type=="radio")
                                                <strong>單選選項：</strong>
                                            @elseif($question->type=="checkbox")
                                                <strong>多選選項：</strong>
                                            @endif
                                            <br>
                                        @foreach($options as $k=>$v)
                                            <span>
                                                @if($question->type=="radio")
                                                    <?php $checked=($k==0)?"checked":""; ?>
                                                    <input type="radio" name="radio" id="id{{ $question->id }}{{ $k }}" {{ $checked }}>
                                                @elseif($question->type=="checkbox")
                                                    <input type="checkbox" name="checkbox" id="id{{ $question->id }}{{ $k }}">
                                                @endif
                                                <label for="id{{ $question->id }}{{ $k }}">{{ $v }}</label>
                                            </span><br>
                                        @endforeach
                                    @elseif($question->type=="text")
                                        <input type="text" placeholder="填寫文字">
                                    @elseif($question->type=="num")
                                        <input type="text" placeholder="填寫數字">
                                    @endif
                                </div>
                            </div>
                        <?php $i++; ?>
                        @endforeach
                    </div>
                    <div class="form-group">
                        <strong>對象學校：</strong>
                        <br>
                        @foreach($schools as $school)
                            <span>{{ $school->school_name }}</span>,
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
