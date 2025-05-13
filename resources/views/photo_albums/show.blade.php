@extends('layouts.app')

@section('title','新增照片 | ')

@section('custom_css')
    <style>
        #navigation {
            margin: 10px 0;
        }
        @media (max-width: 767px) {
            #title,
            #description {
                display: none;
            }
        }
        .fade.in {
            opacity: 1
        }
    </style>
    <link
        rel="stylesheet"
        href="https://blueimp.github.io/Gallery/css/blueimp-gallery.min.css"
    />
    <link rel="stylesheet" href="{{ asset('jQuery-File-Upload/css/jquery.fileupload.css') }}" />
    <link rel="stylesheet" href="{{ asset('jQuery-File-Upload/css/jquery.fileupload-ui.css') }}" />
@endsection

@section('content')
    <div class="container">
        <div class="py-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('photo_albums.index') }}">相簿管理</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $photo_album->album_name }}</li>
                </ol>
            </nav>
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        新增照片
                    </h3>
                </div>
                <div class="card-body">
                    <form
                        id="fileupload"
                        action="{{ route('photo_albums.store_photo',$photo_album->id) }}"
                        method="POST"
                        enctype="multipart/form-data"
                    >
                    @csrf
                    <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                        <div class="row fileupload-buttonbar">
                            <div class="col-lg-7">
                                <!-- The fileinput-button span is used to style the file input field as button -->
                                <span class="btn btn-success fileinput-button">
              <i class="fas fa-plus-circle"></i>
              <span>新增照片</span>
              <input type="file" name="files[]" multiple />
            </span>
                                <button type="submit" class="btn btn-primary start">
                                    <i class="fas fa-upload"></i>
                                    <span>開始上傳全部</span>
                                </button>
                                <button type="reset" class="btn btn-warning cancel">
                                    <i class="fas fa-ban"></i>
                                    <span>取消上傳全部</span>
                                </button>
                                <!--
                                <button type="button" class="btn btn-danger delete">
                                    <i class="glyphicon glyphicon-trash"></i>
                                    <span>Delete selected</span>
                                </button>
                                <input type="checkbox" class="toggle" />
                                -->
                                <!-- The global file processing state -->
                                <span class="fileupload-process"></span>
                            </div>
                            <!-- The global progress state -->
                            <div class="col-lg-5 fileupload-progress fade">
                                <!-- The global progress bar -->
                                <div
                                    class="progress progress-striped active"
                                    role="progressbar"
                                    aria-valuemin="0"
                                    aria-valuemax="100"
                                >
                                    <div
                                        class="progress-bar progress-bar-success"
                                        style="width: 0%;"
                                    ></div>
                                </div>
                                <!-- The extended global progress state -->
                                <div class="progress-extended">&nbsp;</div>
                            </div>
                        </div>
                        <!-- The table listing the files available for upload/download -->
                        <table role="presentation" class="table table-striped">
                            <tbody class="files"></tbody>
                        </table>
                    </form>
                    <div id="photos_area">
                        @foreach($photos as $photo)
                            <img src="{{ asset('storage/photo_albums/'.$photo_album->id.'/'.$photo->photo_name) }}" class="img-thumbnail" width="18%">
                            <a href="{{ route('photo_albums.delete_photo',$photo->id) }}" onclick="return confirm('確定刪除？')"><i class="fas fa-trash-alt text-danger"></i></a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <!-- The template to display files available for upload -->
    <script id="template-upload" type="text/x-tmpl">
      {% for (var i=0, file; file=o.files[i]; i++) { %}
          <tr class="template-upload fade{%=o.options.loadImageFileTypes.test(file.type)?' image':''%}">
              <td>
                  <span class="preview"></span>
              </td>
              <td>
                  <p class="name">{%=file.name%}</p>
                  <strong class="error text-danger"></strong>
              </td>
              <td>
                  <p class="size">Processing...</p>
                  <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
              </td>
              <td>
                  {% if (!o.options.autoUpload && o.options.edit && o.options.loadImageFileTypes.test(file.type)) { %}
                    <button class="btn btn-success edit" data-index="{%=i%}" disabled>
                        <i class="fas fa-edit"></i>
                        <span>Edit</span>
                    </button>
                  {% } %}
                  {% if (!i && !o.options.autoUpload) { %}
                      <button class="btn btn-primary start" disabled>
                          <i class="fas fa-upload"></i>
                          <span>開始上傳</span>
                      </button>
                  {% } %}
                  {% if (!i) { %}
                      <button class="btn btn-warning cancel">
                          <i class="fas fa-ban"></i>
                          <span>取消上傳</span>
                      </button>
                  {% } %}
              </td>
          </tr>
      {% } %}
</script>
    <!-- The template to display files available for download -->
    <script id="template-download" type="text/x-tmpl">
      {% for (var i=0, file; file=o.files[i]; i++) { %}
          <tr class="template-download fade{%=file.thumbnailUrl?' image':''%}">
              <td>
                  <span class="preview">
                      {% if (file.thumbnailUrl) { %}
                          <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                      {% } %}
                  </span>
              </td>
              <td>
                  <p class="name">
                      {% if (file.url) { %}
                          <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a> 上傳完成
                      {% } else { %}
                          <span>{%=file.name%} 上傳失敗</span>
                      {% } %}
                  </p>
                  {% if (file.error) { %}
                      <div><span class="badge badge-danger">錯誤</span> {%=file.error%}</div>
                  {% } %}
              </td>
              <td>
                  <span class="size">{%=o.formatFileSize(file.size)%}</span>
              </td>
              <td>
                  {% if (file.deleteUrl) { %}
                      <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                          <i class="fas fa-trash"></i>
                          <span>Delete</span>
                      </button>
                      <input type="checkbox" name="delete" value="1" class="toggle">
                  {% } else { %}
                      <button class="btn btn-warning cancel">
                          <i class="fas fa-ban"></i>
                          <span>移除此列</span>
                      </button>
                  {% } %}
              </td>
          </tr>
      {% } %}
</script>

    <script
        src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"
        integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ"
        crossorigin="anonymous"
    ></script>
    <!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
    <script src="{{ asset('jQuery-File-Upload/js/vendor/jquery.ui.widget.js') }}"></script>
    <!-- The Templates plugin is included to render the upload/download listings -->
    <script src="https://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
    <!-- The Load Image plugin is included for the preview images and image resizing functionality -->
    <script src="https://blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
    <!-- The Canvas to Blob plugin is included for image resizing functionality -->
    <script src="https://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
    <!-- blueimp Gallery script -->
    <script src="https://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
    <!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
    <script src="{{ asset('jQuery-File-Upload/js/jquery.iframe-transport.js') }}"></script>
    <!-- The basic File Upload plugin -->
    <script src="{{ asset('jQuery-File-Upload/js/jquery.fileupload.js') }}"></script>
    <!-- The File Upload processing plugin -->
    <script src="{{ asset('jQuery-File-Upload/js/jquery.fileupload-process.js') }}"></script>
    <!-- The File Upload image preview & resize plugin -->
    <script src="{{ asset('jQuery-File-Upload/js/jquery.fileupload-image.js') }}"></script>
    <!-- The File Upload audio preview plugin -->
    <script src="{{ asset('jQuery-File-Upload/js/jquery.fileupload-audio.js') }}"></script>
    <!-- The File Upload video preview plugin -->
    <script src="{{ asset('jQuery-File-Upload/js/jquery.fileupload-video.js') }}"></script>
    <!-- The File Upload validation plugin -->
    <script src="{{ asset('jQuery-File-Upload/js/jquery.fileupload-validate.js') }}"></script>
    <!-- The File Upload user interface plugin -->
    <script src="{{ asset('jQuery-File-Upload/js/jquery.fileupload-ui.js') }}"></script>
    <!-- The main application script -->
    <script>
        $(function () {
            $('#fileupload').fileupload({
                url: '{{ route('photo_albums.store_photo',$photo_album->id) }}',
                sequentialUploads:true
            });
        });

        function add_image(result){
            var photo_type = ['jpg','jpeg','JPG','JPEG','gif','png','svg','bmp','webp','heic'];
            var data = '';
            result['files'].forEach(function(value){
                if(value['error']== undefined){
                    if($.inArray(value['type'], photo_type) >=0){
                        data = data+'<img src="{{ asset('storage/photo_albums/'.$photo_album->id) }}/'+value['new_name']+'" class="img-thumbnail" width="20%">';
                        data = data + '<a href="../../../photo_albums/'+value['photo_id']+'/delete_photo" onclick="return confirm(\'確定刪除？\')"><i class="fas fa-trash-alt text-danger"></i></a>';
                    }
                }
            });
            document.getElementById('photos_area').innerHTML = data+document.getElementById('photos_area').innerHTML;
        }

    </script>
@endsection

@section('page-scripts')

@endsection
