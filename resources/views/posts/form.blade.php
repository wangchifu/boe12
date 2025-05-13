@include('layouts.errors')
<div class="form-group">
    <label for="category_id"><strong class="text-danger">公告類別*</strong></label>
    <select name="category_id" id="category_id" class="form-control" onchange="show_type(this)">
        <option value="" disabled selected>選擇類別</option>
        @foreach($categories as $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="title"><strong class="text-danger">公告主旨*</strong></label>
    <input type="text" name="title" id="title" class="form-control" placeholder="請輸入標題" required>
</div>
<div class="form-group">
    <label for="telephone">公務電話</label>
    <input type="text" name="telephone" id="telephone" class="form-control" placeholder="請輸入聯絡電話" value="{{ auth()->user()->telephone }}">
</div>
<div class="form-group">
    <label for="content"><strong class="text-danger">公告內容*</strong></label>
    <label for="content"><strong  class="text-danger">文字縮排請不要自行鍵入空格，請用編輯器的功能<span class="tox-icon tox-tbtn__icon-wrap"><svg width="24" height="24"><path d="M7 5h12c.6 0 1 .4 1 1s-.4 1-1 1H7a1 1 0 1 1 0-2zm5 4h7c.6 0 1 .4 1 1s-.4 1-1 1h-7a1 1 0 0 1 0-2zm0 4h7c.6 0 1 .4 1 1s-.4 1-1 1h-7a1 1 0 0 1 0-2zm-5 4h12a1 1 0 0 1 0 2H7a1 1 0 0 1 0-2zm-2.6-3.8L6.2 12l-1.8-1.2a1 1 0 0 1 1.2-1.6l3 2a1 1 0 0 1 0 1.6l-3 2a1 1 0 1 1-1.2-1.6z" fill-rule="evenodd"></path></svg></span></strong></label>
    <textarea name="content" id="content" class="form-control" rows="10" placeholder="請輸入內容" required></textarea>

    <script src="{{ asset('tinymce5/tinymce.min.js') }}" referrerpolicy="origin"></script>
<script>
		tinyMCE.init({
		selector: "textarea",
			plugins: [
      'advlist autolink link image lists charmap print preview hr anchor pagebreak',
      'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
      'table emoticons template paste help code codesample'
    ],
    toolbar: 'code undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
      'bullist numlist outdent indent | link | ' +
      'forecolor backcolor emoticons | preview fullscreen',
    menu: {
      favs: {title: 'My Favorites', items: 'code visualaid | searchreplace | emoticons'}
    },
    menubar: false,
    language: 'zh_TW',
});
</script>
<!--
    <script>

        tinymce.init({
	selector: 'textarea',  // change this value according to your HTML
        language: 'zh_TW'  // site absolute URL
	});


    </script>
-->
    <!--
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
    -->
</div>
<div class="form-group">
    <label for="url">相關網址( 請記得加上http://或https://)</label>
    <input type="text" name="url" id="url" class="form-control">
</div>
<div class="form-group">
    <label for="files[]">附加檔案( 單檔不大於10MB，請以ODF格式附加 ) <small class="text-secondary">csv,txt,zip,jpg,jpeg,gif,png,pdf,odt,ods</small></label>
    <input type="file" name="files[]" class="form-control" multiple onchange="checkfile(this);">
</div>
<div class="form-group">
    <label for="photos[]">相關照片( 四張以內，單檔不大於5MB的圖檔 )</label>
    <input type="file" name="photos[]" class="form-control" multiple accept="image/*">
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
