@extends('layouts.app_clean')

@section('title','資料填報 | ')

@section('content')
    <br>
    <div class="container">
        <div class="col-12">
            <div class="card">
                <div class="card-head">
                    <img class="card-img-top img-responsive" src="{{ asset('images/small/school_report.png') }}">
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
                    @if(!empty($report_school->report->content) or !empty($files))
                    <hr>
                    @endif
                    @include('layouts.errors')
                    <span class="text-danger">* 每題都是必填，若題目不合，請電洽縣府承辦人，或填「無、0」。</span>
                    <form id="this_form" action="{{ route('school_report.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="report_school_id" value="{{ $report_school->id }}">
                    <input type="hidden" name="report_id" value="{{ $report_school->report_id }}">
                    <?php $i=1; ?>
                    @foreach($report_school->report->questions as $question)
                        <div class="form-group">
                            <label for="title{{ $question->id }}"><strong><span class="text-danger text-bold">*</span> 題目{{ $i }}：{{ $question->title }}</strong></label>
                            <?php
                                if($question->type=="radio" or $question->type=="checkbox"){
                                    $options = unserialize($question->options);
                                }
                            ?>
                            @if($question->type=="radio")
                                <br>
                                @foreach($options as $k=>$v)
                                    <?php $checked=($k==0)?"checked":""; ?>
                                    <span>
                                        <input type="radio" name="answer[{{ $question->id }}]" id="id{{ $question->id }}{{ $k }}" {{ $checked }} value="{{ $v }}">
                                    </span>
                                    <label for="id{{ $question->id }}{{ $k }}">{{ $v }}</label>
                                    <br>
                                @endforeach
                            @elseif($question->type=="checkbox")
                                <br>
                                @foreach($options as $k=>$v)
                                    <span>
                                        <input type="checkbox" name="answer_checkbox{{ $question->id }}[]" id="id{{ $question->id }}{{ $k }}" value="{{ $v }}">
                                    </span>
                                    <label for="id{{ $question->id }}{{ $k }}">{{ $v }}</label>
                                    <br>
                                @endforeach
                            @elseif($question->type=="text")
                            <input type="text" name="answer[{{ $question->id }}]" id="title{{ $question->id }}" class="form-control" required placeholder="請填寫文字">
                            @elseif($question->type=="num")
                            <input type="number" name="answer[{{ $question->id }}]" id="title{{ $question->id }}" class="form-control" required placeholder="只能填寫數字">
                            @endif
                            <br>
                        </div>
                    <?php $i++; ?>
                        <input type="hidden" name="type[{{ $question->id }}]" value="{{ $question->type }}">
                    @endforeach
                    <button class="btn btn-success btn-sm" onclick="return confirm('確定嗎？若無法送出，請檢查是否有無未填題目！')">送出</button>
                    <div class="text-right" id="show_pull">
                        <span class="btn btn-secondary btn-sm" onclick="if(confirm('確定嗎？會覆蓋之前的暫存檔喔！')){go_save_temp();}else{return false;}"><i class="fas fa-save"></i> 暫存</span>
                        <?php
                            $check_report_temp = \App\ReportTemp::where('code',auth()->user()->code)->where('report_id',$report_school->report_id)->first();
                        ?>
                        @if(!empty($check_report_temp))
                            <span class="btn btn-outline-secondary btn-sm" onclick="if(confirm('確定嗎？會覆蓋目前填入的資料喔！')){pull_temp();}else{return false;}"><i class="fas fa-download"></i> 拉下暫存</span>
                        @endif
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <form id="pull_form">
        @csrf
    </form>
    <script>
        function go_save_temp(){
            $.ajax({
                url: '{{ route('school_report.save_temp') }}',
                type : 'post',
                dataType : 'json',
                data : $('#this_form').serialize(),
                success : function(result) {
                    alert('暫存成功');
                    show_pull(result);
                },
                error: function() {
                    alert('暫存失敗！');
                }
            })

        }

        function show_pull(result){
            document.getElementById('show_pull').innerHTML = '<span class="btn btn-secondary btn-sm" onclick="if(confirm(\'確定嗎？會覆蓋之前的暫存檔喔！\')){go_save_temp();}else{return false;}"><i class="fas fa-save"></i> 暫存</span> <span class="btn btn-outline-secondary btn-sm" onclick="if(confirm(\'確定嗎？會覆蓋目前填入的資料喔！\')){pull_temp();}else{return false;}"><i class="fas fa-download"></i> 拉下暫存</span>';
        }

        function pull_temp(){
            $.ajax({
                url: '{{ route('school_report.pull_temp',$report_school->report_id) }}',
                type : 'post',
                dataType : 'json',
                data : $('#pull_form').serialize(),
                success : function(result) {
                    alert('拉下暫存成功');
                    insert_temp(result);
                },
                error: function() {
                    alert('拉下暫存失敗！');
                }
            })
        }

        function insert_temp(result){
            for (var k in result) {
                if(result[k]['type'] == 'text' || result[k]['type'] == 'num'){
                    document.getElementById('title'+k).value = result[k]['answer'];
                }
                if(result[k]['type'] == 'radio' || result[k]['type'] == 'checkbox'){
                    for(var k1 in result[k]['options']){
                        if(document.getElementById('id'+k+k1).value == result[k]['answer']){
                            document.getElementById('id'+k+k1).checked = true;
                        }
                    }
                }
                if(result[k]['type'] == 'checkbox'){
                    for(var k1 in result[k]['options']){
                        for(var k2 in result[k]['answer']){
                            if(document.getElementById('id'+k+k1).value == result[k]['answer'][k2]){
                                document.getElementById('id'+k+k1).checked = true;
                            }
                        }
                    }
                }
            }
        }
    </script>
@endsection
