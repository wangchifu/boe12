@extends('layouts.app')

@section('title', '新增公告 | ')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="">
            @include('posts.nav')
            <form action="{{ route('posts.store') }}" method="POST" id="this_form" enctype="multipart/form-data">
                @csrf
            <div class="card my-4">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        新增 [{{ $sections[auth()->user()->section_id] }}] 公告
                    </h3>
                </div>
                <div class="card-body">
                    @include('posts.form')
                    <div id='show_type'>
                        <div class="form-group">
                            <label>緊急程度</label><br>
                            <input name="type" type="checkbox" value="1" id="type"> <label for="type">[最速件]</label>
                        </div>
                        <div class="form-group">
                            <label>公開為「一般公告」給訪客？</label><br>
                            <input name="another" type="checkbox" value="1" id="another"> <label for="another">公開<small class="text-secondary">(任何人將看到此則公告)</small></label>
                        </div>
                        <div class="form-group">
                        <label for="schools"><strong class="text-danger">發送對象學校*</strong></label>
                        @include('posts.select_school')
                        </div>

                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-outline-primary" name="form_action" value="暫存" onclick="return confirm('確定暫存？')">                        
                        <input type="submit" class="btn btn-primary" name="form_action" value="送出審核不再修改" onclick="return confirm('送出後，無法再修改喔！')">                        
                        <a href="#" class="btn btn-secondary" onclick="history.back();"><i class="fas fa-backward"></i> 返回</a>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
    function show_type(G) {
        if(G.value == '5'){
            $("#show_type").show();
        } else {
            $("#show_type").hide();
        }
    }

    var validator = $("#this_form").validate();
    function change_button(){
        $("#submit_button").attr('disabled','disabled');
        $("#submit_button").addClass('disabled');
    }
</script>
@endsection
