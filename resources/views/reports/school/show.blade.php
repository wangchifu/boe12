@extends('layouts.app_clean')

@section('title',$report_school->report->name.' | ')

@section('content')
    <div class="container">
        <div class="col-12">
            <div class="card">
                <div class="card-head">
                    <img class="card-img-top img-responsive" src="{{ asset('images/small/school_report_result.png') }}">
                </div>
                <div class="card-body">
                    <span class="text-right">{{ $sections[$report_school->report->section_id] }} / {{ $report_school->report->user->name }}@if(!empty($report_school->report->user->telephone)) / <i class="fas fa-phone"></i> {{ $report_school->report->user->telephone }}@endif</span>
                    <h4>
                        {{ $report_school->report->name }}
                    </h4>
                    @if(!empty($report_school->report->content))
                        <div class="form-group">
                            <strong>說明：</strong><br>
                            {!! $report_school->report->content !!}
                        </div>
                    @endif
                    <?php
                    $files = get_files(storage_path('app/public/report_files/' . $report_school->report->id));
                    ?>
                    @if(!empty($files))
                        <div class="form-group">
                            <strong>附檔：</strong><br>
                            @foreach($files as $k=>$v)
                                <a href="{{ route('edu_report.download',['id'=>$report_school->report->id,'filename'=>$v]) }}" class="btn btn-primary btn-sm" style="margin:3px"><i class="fas fa-download"></i> {{ $v }}</a>
                            @endforeach
                        </div>
                    @endif
                    <?php $i=1; ?>
                    @foreach($report_school->report->questions as $question)
                        <div class="form-group">
                            <label><strong>題目{{ $i }}：{{ $question->title }}</strong></label>
                            @if(isset($answer_data[$question->id]))
                                @if($report_school->signed_user_id == auth()->user()->id or $report_school->review_user_id == auth()->user()->id or check_a_user(auth()->user()->code,auth()->user()->id))
                                    <p>答：<span class="text-danger">{{ $answer_data[$question->id] }}</span></p> 
                                @else
                                    <p>答：****㊙️****</p>
                                @endif
                            @endif
                        </div>
                    <?php $i++; ?>
                    @endforeach
                    填報者：{{ userid2name($report_school->signed_user_id) }}
                    <br>
                    填報日期：{{ $report_school->signed_at }}
                    <br>
                    @if($report_school->review_user_id)
                        審核者：{{ $report_school->review_user->name }}
                        <br>
                        核可日期：{{ $report_school->updated_at }}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
