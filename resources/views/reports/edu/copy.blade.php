@extends('layouts.app')

@section('title', '新增填報 | ')

@section('content')
    <script src="{{ asset('gijgo/js/gijgo.min.js') }}" type="text/javascript"></script>
    <link href="{{ asset('gijgo/css/gijgo.min.css') }}" rel="stylesheet" type="text/css">
    <style>
        .blinking{
            animation:blinkingText 1.2s infinite;
        }
        @keyframes blinkingText{
            0%{     color: #000;    }
            49%{    color: #000; }
            60%{    color: transparent; }
            99%{    color:transparent;  }
            100%{   color: #000;    }
        }
    </style>
<div class="container">
    <div class="py-5">
        <div class="">
            @include('reports.edu.nav')
            <div class="card my-4">
                <div class="card-header text-center bg-info">
                    <h3 class="py-2">
                        新增填報
                    </h3>
                </div>
                <div class="card-body">
                    @include('layouts.errors')
                    <form action="{{ route('edu_report.store') }}" method="post" enctype="multipart/form-data" id="this_form" onsubmit="change_button()">
                        @csrf
                    <div class="form-group">
                        <label for="name"><strong class="text-danger">1.請務必先選擇對象*</strong></label>
                        @include('posts.select_school')
                    </div>
                    <div class="form-group">
                        <label for="name"><strong class="text-danger">2.填報名稱*</strong></label>
                        <input type="text" name="name" value="{{ $report->name }}" id="name" class="form-control" placeholder="請輸入名稱" required>
                    </div>
                    <div class="form-group">
                        <label for="telephone">公務電話</label>
                        <input type="text" name="telephone" value="{{ auth()->user()->telephone }}" id="telephone" class="form-control" placeholder="請輸入聯絡電話">
                    </div>
                    <div class="form-group">
                        <label for="die_date"><strong class="text-danger">3.截止日期*</strong></label>
                        <input id="die_date" name="die_date" required maxlength="10" placeholder="十碼：2019-01-01" class="form-control" value="" width="250">
                        <script src="{{ asset('gijgo/js/messages/messages.zh-TW.js') }}"></script>
                        <script>
                            $('#die_date').datepicker({
                                uiLibrary: 'bootstrap4',
                                format: 'yyyy-mm-dd',
                                locale: 'zh-TW',
                            });
                        </script>
                    </div>
                    <div class="form-group">
                        <label for="content">4.填報說明</label>
                        <textarea name="content" id="content" class="form-control" rows="6" placeholder="請輸入內容" required>{{ $report->content }}</textarea>
                        <script src="{{ asset('tinymce5/tinymce.min.js') }}" referrerpolicy="origin"></script>
                        <script>
                            tinymce.init({
                                selector: 'textarea',  // change this value according to your HTML
                                language: 'zh_TW'  // site absolute URL
                            });

                        </script>
                        <!--
                        <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
                        <script>
                            CKEDITOR.replace('content',{
                                toolbar: [
                                    { name: 'document', items: [ 'Bold', 'Italic','TextColor','-','NumberedList','BulletedList','Outdent', 'Indent', '-', 'Undo', 'Redo' ] },
                                ]
                            });
                        </script>
                        -->
                    </div>
                    <div class="form-group">
                        <label for="files[]">5.附加檔案( 單檔不大於10MB )</label>
                        <input type="file" name="files[]" class="form-control" multiple>
                        @foreach($files as $k=>$v)
                            <a href="{{ route('edu_report.delete_file',['id'=>$report->id,'filename'=>$v]) }}" class="btn btn-danger btn-sm" style="margin:3px" onclick="return confirm('確定刪除此附件？')"><i class="fas fa-trash"></i> {{ $v }}</a>
                        @endforeach
                    </div>

                    <div class="form-group">
                        <label><strong class="text-danger">6.設計題目*</strong></label>
                        <?php
                        $types = [
                            'radio'=>'1.單選題',
                            'checkbox'=>'2.多選題',
                            'text'=>'3.文字題',
                            'num'=>'4.數字題',
                        ];
                        $q=1;
                        ?>
                        <div id='show_question'>
                            @foreach($report->questions as $question)
                            <div style="border-style:dashed;padding: 10px;margin: 15px;">
                                <div class="form-group">
                                    <label for="title1"><strong>題目{{ $q }}*</strong></label>
                                    <input type="text" name="title[{{ $q }}]" value="{{ $question->title }}" id="title1" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label><strong>題目{{ $q }}-題型*</strong></label>
                                    <select name="type[{{ $q }}]" id="type1" onchange="show_type(this, {{ $q }});" class="form-control" required>
                                        <option value="" disabled selected>選擇題型</option>
                                        @foreach ($types as $key => $value)
                                            <option value="{{ $key }}" {{ $key == $question->type ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                    <?php
                                        $options = unserialize($question->options);
                                    ?>
                                    @if($question->type == "radio" or $question->type =="checkbox")
                                        <div class="form-group" id='show_type{{ $q }}'>
                                            @foreach($options as $k=>$v)
                                            <p>
                                                <label>選項*：</label>
                                                <input type='text' name='option{{ $q }}[]' id='option{{ $q }}' value="{{ $v }}">
                                                @if($k==1)
                                                    <i class='fas fa-plus-circle text-success' onclick="add_a({{ $q }})"></i>
                                                @endif
                                                @if($k>1)
                                                    <i class='fas fa-trash text-danger' onclick='remove_a(this)'></i>
                                                @endif
                                            </p>
                                            @endforeach
                                        </div>
                                    @elseif($question->type == "text" or $question->type == "num")
                                        <div class="form-group" id='show_type{{ $q }}' style="display:none">
                                            <p>
                                                <label>選項*：</label>
                                                <input type='text' name='option{{ $q }}[]' id='option{{ $q }}'>
                                            </p>
                                            <p>
                                                <label>選項*：</label>
                                                <input type='text' name='option{{ $q }}[]' id='option{{ $q }}'>
                                                <i class='fas fa-plus-circle text-success' onclick="add_a({{ $q }})"></i>
                                            </p>
                                        </div>
                                    @endif

                                <button type="button" onclick="add()">+增題</button>
                                @if($q != 1)
                                    <button type="button" onclick="remove(this)">-刪題</button>
                                @endif
                                <?php $q++; ?>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <input type="hidden" id="category_id" value="5">
                    <input type="submit" class="btn btn-outline-primary" name="form_action" value="暫存" onclick="return confirm('確定暫存？')">                        
                    <input type="submit" class="btn btn-primary" name="form_action" value="送出審核不再修改" onclick="return confirm('送出後，無法再修改喔！')">                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
    <script>
        var q = {{ $q-1 }};

        function add() {
            var content;
            q++;
            content = "<div style='border-style:dashed;padding: 10px;margin: 15px;'>" +
                "<div class='form-group'>"+
                "<label for='title"+q+"'><strong>題目"+q+"*</strong></label>" +
                "<input type='text' name='title["+q+"]' id='title"+q+"' class='form-control' required> " +
                "</div>"+
                "<div class='form-group'>"+
                "<label for='type"+q+"'><strong>題目"+q+"-題型*</strong></label>" +
                "<select name='type["+q+"]' id='type"+q+"' required onchange='show_type(this,"+q+");'>"+
                "<option value=''>選擇題型</option>"+
                "<option value='radio'>1.單選題</option>"+
                "<option value='checkbox'>2.多選題</option>"+
                "<option value='text'>3.文字題</option>"+
                "<option value='num'>4.數字題</option>"+
                "</select>"+
                "</div>"+
                "<div class='form-group' id='show_type"+q+"' style='display:none'>"+
                "<p>"+
                "<label for='var"+q+"1'>選項*：</label>"+
                "<input type='text' name='option"+q+"[]' id='option"+q+"'>"+
                "</p>"+
                "<p>"+
                "<label for='var"+q+"2'>選項*：</label>"+
                "<input type='text' name='option"+q+"[]' id='option"+q+"'>"+
                "<i class='fas fa-plus-circle text-success' onclick='add_a("+q+")'></i>"+
                "</p>"+
                "</div>"+
                "<button type='button' onclick='add()'>+增題</button>"+
                "<button type='button' onclick='remove(this)'>-刪題</button>"+
                "</div>";
            $("#show_question").append(content);
        }

        function remove(obj) {
            $(obj).parent().remove();
            q--;
        }

        function show_type(G,this_q) {
            if(G.value == 'radio' || G.value == 'checkbox'){
                $("#show_type"+this_q).show();
                $("[id='option"+this_q+"']").attr("required", true);
            } else {
                $("#show_type"+this_q).hide();
                $("[id='option"+this_q+"']").attr("required", false);
            }
        }

        function add_a(this_q) {
            var content;
            content = "<p>" +
                "<label for='var"+this_q+"'>選項*：</label>" +
                "<input type='text' name='option"+this_q+"[]'> " +
                "<i class='fas fa-trash text-danger' onclick='remove_a(this)'></i>" +
                "</p>";
            $("#show_type"+this_q).append(content);
        }

        function remove_a(obj) {
            $(obj).parent().remove();
        }


        var validator = $("#this_form").validate();

        function change_button(){
            $("#submit_button").attr('disabled','disabled');
            $("#submit_button").addClass('disabled');
        }

    </script>
@endsection
