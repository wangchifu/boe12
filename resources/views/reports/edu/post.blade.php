@extends('layouts.app')

@section('title', '催促公告 | ')

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
                        催促公告
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="category_id"><strong>公告類別*</strong></label>
                        <br>
                        行政公告
                        <input type="hidden" name="category_id" value="5">
                    </div>
                    <div class="form-group">
                        <label for="title"><strong>公告主旨*</strong></label>
                        <input type="text" name="title" value="催促填報!! 資料填報編號{{ $report->id }} 未送達!!" id="title" class="form-control" placeholder="請輸入標題" required>
                    </div>
                    <div class="form-group">
                        <label for="content"><strong>公告內容*</strong></label>
                        <textarea name="content" id="content" class="form-control" rows="10" placeholder="請輸入內容" required>
                            請貴校盡速填報編號：{{ $report->id }}-{{ $report->name }}
                        </textarea>
                    </div>
                    <div class="form-group">
                        <label for="schools"><strong>發送對象學校*</strong></label>
                        <br>
                        {{ $schools }}
                        @foreach($school_array as $k=>$v)
                            <input type="hidden" name="sel_school[]" value="{{ $v }}">
                        @endforeach
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
        var validator = $("#this_form").validate();
    </script>
@endsection
