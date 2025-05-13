@extends('layouts.app')

@section('title', '填報結果 | ')

@section('content')
    <div class="container">
        <div class="py-5">
            <div class="">
                <h1>
                {{ $report->name }}
                </h1>
                <a href="#" class="btn btn-secondary btn-sm" onclick="history.back()">返回</a>
                <a href="{{ route('edu_report.export',$report->id) }}" class="btn btn-primary btn-sm">下載EXCEL檔</a>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>
                                學校
                            </th>
                            @foreach($report->questions as $question)
                            <th>
                                {{ $question->title }}
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                    <?php $no_report_name=""; ?>
                    @foreach($schools as $school)
                    <tr>
                        <td>
                            <?php
                            $report_school = \App\Models\ReportSchool::where('code',$school->code_no)
                                ->where('report_id',$report->id)
                                ->first();
                            ?>
                            @if($report_school->situation==3)
                                <span data-toggle="tooltip" data-placement="top" title="{{ $report_school->updated_at }} 送出">{{ $school->school_name }}</span>
                                    <small class="text-secondary">填：{{ $report_school->signed_user->name }}</small><br>
                            @elseif($report_school->situation==4)
                                <span data-toggle="tooltip" data-placement="top" title="{{ $report_school->signed_at }} 送出">{{ $school->school_name }}</span><br>
                                    <small class="text-secondary">填：{{ $report_school->signed_user->name }}</small><br>
                                    <span class="text-danger">不填報</span>
                            @else
                                {{ $school->school_name }}
                            @endif
                        </td>
                        <?php $no_report=""; ?>
                        @foreach($report->questions as $question)
                            <td>
                                @if(isset($answer_data[$school->code_no][$question->id]) and $report_school->situation==3)
                                {{ $answer_data[$school->code_no][$question->id] }}
                                @else
                                    @if($report_school->situation != 4)
                                        <?php $no_report=$school->school_name; ?>
                                    @endif
                                @endif
                            </td>
                        @endforeach
                        <?php
                            if($no_report){
                                $no_report_name .= $no_report.",";
                            }
                        ?>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                <hr>
                未填報學校：<br>
                <span class="text-danger">{{ $no_report_name }}</span>
            </div>
        </div>
    </div>
@endsection
