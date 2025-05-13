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
            @include('posts.nav')
            <div class="card my-4">
                <div class="card-header text-center bg-info">
                    <h3 class="py-2">
                        新增 [{{ $sections[auth()->user()->section_id] }}] 填報
                    </h3>
                </div>
                <div class="card-body">
                    @include('layouts.errors')
                    <form action="{{ route('edu_report.store') }}" method="post" enctype="multipart/form-data" id="this_form">
                        @csrf
                    <div class="form-group">
                        <label for="name"><strong class="text-danger">1.請務必先選擇對象*</strong></label>
                        @include('posts.select_school')
                    </div>
                    <div class="form-group">
                        <label for="name"><strong class="text-danger">2.填報名稱*</strong></label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="telephone">公務電話</label>
                        <input type="text" name="telephone" value="{{ auth()->user()->telephone }}" id="telephone" class="form-control" placeholder="請輸入聯絡電話">
                    </div>
                    <div class="form-group">
                        <label for="die_date"><strong class="text-danger">3.截止日期*</strong></label>
                        <input id="die_date" name="die_date" required maxlength="10" class="form-control" width="250">
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
                        <textarea name="content" id="content" class="form-control" rows="6" placeholder="請輸入內容" required></textarea>
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
                                    { name: 'document', items: [ 'Bold', 'Italic','TextColor','-','Outdent', 'Indent', '-', 'Undo', 'Redo' ] },
                                ]
                            });
                        </script>
                        -->
                    </div>
                    <div class="form-group">
                        <label for="files[]">5.附加檔案( 單檔不大於10MB )</label>
                        <input type="file" name="files[]" class="form-control" multiple>
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
                        ?>
                        <div id='show_question'>
                            <div style="border-style:dashed;padding: 10px;margin: 15px;">
                                <div class="form-group">
                                    <label for="title1"><strong>題目1*</strong></label>
                                    <input type="text" name="title[1]" id="title1" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="type1"><strong>題目1-題型*</strong></label>
                                    <select name="type[1]" id="type1" class="form-control" onchange="show_type(this, 1);" required>
                                        <option value="" disabled selected>選擇題型</option>
                                        @foreach ($types as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group" id='show_type1' style="display:none">
                                    <p>
                                        <label for='var11'>選項*：</label>
                                        <input type='text' name='option1[]' id='option1'>
                                    </p>
                                    <p>
                                        <label for='var12'>選項*：</label>
                                        <input type='text' name='option1[]' id='option1'>
                                        <i class='fas fa-plus-circle text-success' onclick="add_a(1)"></i>
                                    </p>
                                </div>
                                <button type="button" onclick="add()">+增題</button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="category_id" value="5">
                    <input type="submit" class="btn btn-outline-primary" name="form_action" value="暫存" onclick="return confirm('確定暫存？')">                        
                    <input type="submit" class="btn btn-primary" name="form_action" value="送出審核不再修改" onclick="return confirm('送出後，無法再修改喔！')">                        
                    <a href="#" class="btn btn-secondary" onclick="history.back();"><i class="fas fa-backward"></i> 返回</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $( document ).ready(function() {
            alert('請注意！發填報給學校，每個學校都必須填送，請勿廣發填報，又請學校可不送，造成學校困擾！');
    });

    var q = 1;

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
