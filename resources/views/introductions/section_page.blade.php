@extends('layouts.app')

@section('title',$section_name.' | ')

@section('custom_meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="container">
    <div class="py-5">
        <div class="">
            <h1>{{ $section_name }}</h1>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('introductions.organization') }}">業務簡介</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('introductions.people') }}">科室成員</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('introductions.site') }}">資源網站</a>
                </li>
                @foreach($section_pages as $sp)
                    <?php  $active=($section_page->id == $sp->id)?"active":"";  ?>
                    <li class="nav-item">
                        <a class="nav-link {{ $active }}" href="{{ route('introductions.section_page',$sp->id) }}">{{ $sp->title }}</a>
                    </li>
                @endforeach
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('introductions.section_page_add') }}"><i class="fas fa-plus-circle"></i> 頁面</a>
                </li>
            </ul>
            <br>
            <form action="{{ route('introductions.section_page_update',$section_page->id) }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="title"><strong class="text-danger">標題*</strong></label>
                    <input type="text" name="title" class="form-control" required value="{{ $section_page->title }}">
                </div>
                <div class="form-group">
                    <label for="title"><strong class="text-dark">排序</strong></label>
                    <input type="number" name="order_by" class="form-control" value="{{ $section_page->order_by }}">
                </div>
                <script src="{{ asset('tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
                <div class="form-group">                    
                    <textarea name="content" id="mytextarea" class="form-control" required>{{ old('content', $section_page->content) }}</textarea>
                </div>                
                <div class="form-group">
                    <button class="btn btn-success btn-sm" onclick="return confirm('確定？')">儲存設定</button>
                    <div class="text-right">
                        <a href="{{ route('introductions.section_page_del',$section_page->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('確定刪除？')">刪除</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
    <script>
        tinymce.init({
            selector: 'textarea#mytextarea',
            language: 'zh_TW', // 設置語言為繁體中文
            language_url: '/tinymce/langs/zh_TW.js', // 指定語言檔案路徑
            plugins: 'fullscreen code table,image link lists image paste', // 啟用表格功能
            toolbar: 'fullscreen code undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | table image link unlink openlink | bullist numlist outdent indent | removeformat',                
            //paste_data_images: true,//拖過去上傳
            //images_upload_url: '/contents/upload_image', // Laravel API
            automatic_uploads: true,
            // 不自動清理或修改 HTML
            valid_elements: '*[*]', 
            extended_valid_elements: '*[*]',
            verify_html: false,
            forced_root_block: false,  // 避免自動包裹 `<p>` 標籤
            remove_trailing_brs: false, // 不刪除尾部 <br>
            convert_urls: false, // 禁止 TinyMCE 轉換圖片 URL
            relative_urls: false, // 確保使用絕對 URL
            remove_script_host: false, // 保留完整的 URL，包括 http:// 或 https://
    
            // 改為使用 Promise 來處理圖片上傳
            images_upload_handler: function (blobInfo) {
                let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                let formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
    
                return fetch('/contents/upload_image', {//laravel API
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        return Promise.reject('伺服器回應錯誤，狀態碼：' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.location) {
                        // 返回圖片 URL，讓 TinyMCE 插入圖片
                        return data.location;
                    } else {
                        return Promise.reject('伺服器回傳的 JSON 不包含 `location` 欄位');
                    }
                })
                .catch(error => {
                    console.error('圖片上傳錯誤:', error);
                    return Promise.reject('圖片上傳失敗');
                });
            }
        });
        
    
    </script>   
@endsection
