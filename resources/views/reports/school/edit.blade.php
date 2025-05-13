@extends('layouts.app_clean')

@section('title','資料填報 | ')

@section('content')
    <div class="container">
        <div class="col-12">
            <div class="card">
                <div class="card-head">
                    <img class="card-img-top img-responsive" src="{{ asset('images/small/school_report_edit.png') }}">
                </div>
                <div class="card-body">
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
                    @if(!empty($report_school->report->content) or !empty($files))
                        <hr>
                    @endif
                    @include('layouts.errors')
                    <form action="{{ route('school_report.update') }}" method="post">
                    @method('patch')
                    @csrf
                        <input type="hidden" name="report_school_id" value="{{ $report_school->id }}">
                        <?php $i=1; ?>
                        @foreach($report_school->report->questions as $question)
                            <div class="form-group">
                                <label for="title{{ $question->id }}"><strong>題目{{ $i }}：{{ $question->title }}</strong></label>
                                <?php
                                if($question->type=="radio" or $question->type=="checkbox"){
                                    $options = unserialize($question->options);
                                }
                                ?>
                                @if($question->type=="radio")
                                    <br>
                                    @foreach($options as $k=>$v)
                                        <?php
		                          if(isset($answer_data[$question->id])){
						  $checked = ($answer_data[$question->id] == $v)?"checked":"";
					  }else{
					          $checked = "";
					  }
                                        ?>
                                        <span>
                                        <input type="radio" name="answer[{{ $question->id }}]" id="id{{ $question->id }}{{ $k }}" {{ $checked }} value="{{ $v }}">
                                    </span>
                                        <label for="id{{ $question->id }}{{ $k }}">{{ $v }}</label>
                                        <br>
                                    @endforeach
                                @elseif($question->type=="checkbox")
                                    <br>
                                    @foreach($options as $k=>$v)
                                        <?php
                                            if(isset($answer_data[$question->id])){
                                                $answer_array = explode(',',$answer_data[$question->id]);
                                            }else{
                                                $answer_array = [];
                                            }

                                        $checked = (in_array($v,$answer_array))?"checked":"";
                                        ?>
                                        <span>
                                        <input type="checkbox" name="answer_checkbox{{ $question->id }}[]" id="id{{ $question->id }}{{ $k }}" value="{{ $v }}" {{ $checked }}>
                                    </span>
                                        <label for="id{{ $question->id }}{{ $k }}">{{ $v }}</label>
                                        <br>
                                    @endforeach
                                @elseif($question->type=="text")
                                    @if(isset($answer_data[$question->id]))
                                    <input type="text" name="answer[{{ $question->id }}]" id="title{{ $question->id }}" class="form-control" required value="{{ $answer_data[$question->id] }}">
                                    @else
                                    <input type="text" name="answer[{{ $question->id }}]" id="title{{ $question->id }}" class="form-control" required placeholder="請填寫文字">
                                    @endif
                                @elseif($question->type=="num")
                                    @if(isset($answer_data[$question->id]))
                                    <input type="number" name="answer[{{ $question->id }}]" id="title{{ $question->id }}" class="form-control" required placeholder="只能填寫數字" value="{{ $answer_data[$question->id] }}">
                                    @else
                                    <input type="number" name="answer[{{ $question->id }}]" id="title{{ $question->id }}" class="form-control" required placeholder="只能填寫數字">
                                    @endif
                                @endif
                            </div>
                            <?php $i++; ?>
                            <input type="hidden" name="type[{{ $question->id }}]" value="{{ $question->type }}">
                        @endforeach
                        <button class="btn btn-success" onclick="return confirm('確定嗎？')">送出</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
