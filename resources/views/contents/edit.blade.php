@extends('layouts.app')

@section('title','內容管理 | ')

@section('custom_meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="container">
        <div class="py-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">首頁</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('contents.index') }}">內容列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">修改內容</li>
                </ol>
            </nav>
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        修改內容
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('contents.update', $content->id) }}" method="POST" id="this_form">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">標題*</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ $content->title }}" placeholder="必填" required>
                        </div>
                        <script src="{{ asset('tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>    
                        <div class="mb-3">
                            <label for="title" class="form-label">內文*</label>
                            <textarea id="mytextarea" name="content" class="form-control">{{ $content->content }}</textarea>
                        </div>
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        <div class="mb-3">
                            <button type="submit" class="btn btn-secondary btn-sm" onclick="window.history.back();"><i class="fas fa-backward"></i> 返回</button>
                            <button class="btn btn-primary btn-sm" onclick="return confirm('確定？')">儲存</button>
                        </div>
                    </form>
                </div>
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
