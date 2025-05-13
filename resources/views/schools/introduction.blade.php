@extends('layouts.app')

@section('title','學校簡介管理 | ')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="">
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        學校簡介管理 - 「{{ auth()->user()->school }}」
                    </h3>
                </div>
                <div class="card-body">
                    <div class="container">
                        <form action="{{ route('school_introduction.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                        <div class="row">
                            <div class="col-12">    
                              [<a href="images/school_sample.png" target="_blank">參考範本</a>] <a href={{"/school/" . auth()->user()->code . "/school_show"}} class="btn btn-sm btn-primary" target="_blank">瀏覽</a>
                            </div>                   
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="pic1">左欄圖片(尺寸約515 *146)</label>
                                    <input type="file" name="pic1" class="form-control" accept="image/*">
                                </div>
                                @if(!empty($school_introduction))
                                    @if(file_exists(storage_path('app/public/school_introductions/'. auth()->user()->code.'/'.$school_introduction->pic1)) and $school_introduction->pic1)
                                    <div class="text-center">
                                        <img src ="{{ asset('storage/school_introductions/'. auth()->user()->code.'/'.$school_introduction->pic1) }}" class="col-12">
                                    </div>                                
                                    @endif
                                @endif
                                <hr>
                                <div class="form-group">
                                    <label for="introduction1">上欄文字</label>
                                    <textarea name="introduction1" id="introduction1" class="form-control" rows="21" placeholder="請輸入內容">{{ $introduction1 }}</textarea>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="introduction2">下欄文字</label>
                                    <textarea name="introduction2" id="introduction2" class="form-control" rows="10" placeholder="請輸入內容">{{ $introduction2 }}</textarea>
                                </div>         
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="pic2">右欄圖片(尺寸約972 *550)</label>
                                    <input type="file" name="pic2" id="pic2" class="form-control" accept="image/*">
                                </div>
                                @if(!empty($school_introduction))
                                    @if(file_exists(storage_path('app/public/school_introductions/'. auth()->user()->code.'/'.$school_introduction->pic2)) and $school_introduction->pic2)
                                        <img src ="{{ asset('storage/school_introductions/'. auth()->user()->code.'/'.$school_introduction->pic2) }}" class="col-12">
                                    @endif
                                @endif       
                                <hr>                         
                                <div class="form-group">
                                    <label for="website">學校網址</label>
                                    <input type="text" name="website" id="website" class="form-control" value="{{ $website }}">

                                </div>
                                <div class="form-group">
                                    <label for="facebook">facebook 粉絲團</label>
                                    <input type="text" name="facebook" id="facebook" class="form-control" value="{{ $facebook }}">
                                </div>
                                <div class="form-group">
                                    <label for="wiki">維基百科介紹</label>
                                    <input type="text" name="wiki" id="wiki" class="form-control" value="{{ $wiki }}">
                                </div>                   
                            </div>                        
                        </div>
                        <br>                    
                        <button class="btn btn-success" onclick="return confirm('確定送出？')">儲存</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('tinymce5/tinymce.min.js') }}" referrerpolicy="origin"></script>
<script>
        tinyMCE.init({
        selector: "textarea",
            plugins: [
    'advlist autolink link image lists charmap print preview hr anchor pagebreak',
    'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
    'table emoticons template paste help code codesample'
    ],
    toolbar: 'nonbreaking undo redo | link | forecolor backcolor emoticons bold italic | alignleft aligncenter alignright | ' +
    'bullist numlist outdent indent | ' +
    'preview fullscreen',
    menu: {
    favs: {title: 'My Favorites', items: 'code visualaid | searchreplace | emoticons'}
    },
    menubar: false,
    language: 'zh_TW',
});
</script>
@endsection
