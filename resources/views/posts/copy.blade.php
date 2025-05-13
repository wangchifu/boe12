@extends('layouts.app')

@section('title', '複製公告 | ')

@section('page-scripts')
    <script>
        function show_type(G) {
            if (G.value == '5') {
                $("#show_type").show();
            } else {
                $("#show_type").hide();
            }
        }
    </script>
@endsection

@section('content')
    <div class="container">
        <div class="py-5">
            <div class="">
                @include('posts.nav')
                <form action="{{ route('posts.store') }}" method="POST" id="this_form" enctype="multipart/form-data" onsubmit="change_button()">
                    @csrf
                <div class="card my-4">
                    <div class="card-header text-center">
                        <h3 class="py-2">
                            編輯公告
                        </h3>
                    </div>
                    <div class="card-body">
                        @include('layouts.errors')
                        <div class="form-group">
                            <label for="category_id"><strong class="text-danger">公告類別*</strong></label>
                            <select name="category_id" id="category_id" class="form-control" onchange="show_type(this)">
                                @foreach ($categories as $id => $name)
                                    <option value="{{ $id }}" {{ $id == $post->category_id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="title"><strong class="text-danger">公告主旨*</strong></label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="請輸入標題" required="required" value="{{ old('title', $post->title) }}">
                        </div>
                        <div class="form-group">
                            <label for="content"><strong class="text-danger">公告內容*</strong></label>
                            <textarea name="content" id="content" class="form-control" rows="10" placeholder="請輸入內容" required="required">{{ old('content', $post->content) }}</textarea>
                            <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
                            <script>
                                CKEDITOR.replace('content',{
                                    toolbar: [
                                        { name: 'document', items: [ 'Bold', 'Italic','TextColor','-','Outdent', 'Indent', '-', 'Undo', 'Redo' ] },
                                    ],
                                    filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                                    filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images',
                                    filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
                                    filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files',
                                });
                            </script>
                        </div>
                        <div class="form-group">
                            <label for="url">相關網址( 請記得加上http://或https://)</label>
                            <input type="text" name="url" id="url" class="form-control" value="{{ old('url', $post->url) }}">
                        </div>
                        <div class="form-group">
                            <label for="files[]">附加檔案( 單檔不大於10MB，請以ODF格式附加 ) <small class="text-secondary">csv,txt,zip,jpeg,png,pdf,odt,ods</small></label>                            
                            <input type="file" name="files[]" class="form-control" multiple="multiple" onchange="checkfile(this);">
                        </div>
                        <div class="form-group">
                            <label for="photos[]">相關照片( 四張以內，單檔不大於5MB的圖檔 )</label>
                            <input type="file" name="photos[]" class="form-control" multiple="multiple" accept="image/*">
                        </div>
                        <script>
                            function checkfile(sender) {

                                // 可接受的附檔名
                                var validExts = new Array(".csv", ".txt", ".zip", ".jpg", ".jpeg", ".png", ".pdf", ".odt", ".ods", ".PDF", ".JPG", ".JPEG");

                                var fileExt = sender.value;
                                fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
                                if (validExts.indexOf(fileExt) < 0) {
                                    alert("檔案類型錯誤，可接受的副檔名有：" + validExts.toString());
                                    sender.value = null;
                                    return false;
                                }
                                else return true;
                            }
                        </script>

                        <div id='show_type'
                             @if($post->category_id!=5)
                             style="display:none"
                                @endif
                        >
                            <div class="form-group">
                                <label>緊急程度</label><br>
                                <input name="type" type="checkbox" value="1" id="type"
                                    @if($post->type===1)
                                        checked
                                   @endif
                                > <label for="type">[最速件]</label>
                            </div>
                            <div class="form-group">
                                <label>公開為「一般公告」給訪客？</label><br>
                                <input name="another" type="checkbox" value="1" id="another"
                                       @if($post->another===1)
                                       checked
                                    @endif
                                > <label for="another">公開<small class="text-secondary">(任何人將看到此則公告)</small></label>
                            </div>
                            <div class="form-group">
                                <label for="schools"><strong class="text-danger">發送對象學校*</strong></label>
                                @include('posts.select_school')
                            </div>

                        </div>

                        <div class="form-group">
                            <input type="submit" class="btn btn-outline-primary" name="form_action" value="暫存" onclick="return confirm('確定暫存？')">                        
                            <input type="submit" class="btn btn-primary" name="form_action" value="送出審核不再修改" onclick="return confirm('送出後，無法再修改喔！')">                        
                            <a href="#" class="btn btn-secondary" onclick="history.back();"><i class="fas fa-backward"></i>
                                返回</a>
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
