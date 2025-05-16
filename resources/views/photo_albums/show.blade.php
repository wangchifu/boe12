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
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                <i class="fas fa-plus text-info"></i> 新增相片(按一下)
                              </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                              <div class="accordion-body">
                                <form action="{{ route('photo_albums.store_photo',$photo_album->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="formFile" class="form-label">選擇圖檔*(可多選)</label>
                                        <input class="form-control" type="file" accept="image/*" id="formFile" name='files[]' required multiple>
                                    </div>                    
                                    <button type="submit" class="btn btn-secondary btn-sm" onclick="window.history.back();">
                                        <i class="fas fa-backward"></i> 返回
                                    </button>
                                    <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存嗎？')">
                                        <i class="fas fa-save"></i> 上傳相片
                                    </button>
                                    </form>
                                </form>                    
                              </div>
                            </div>
                        </div>          
                    </div> 
                    <br>   
                    @foreach($photos as $photo)
                    <img src="{{ asset('storage/photo_albums/'.$photo_album->id.'/'.$photo->photo_name) }}" class="img-thumbnail" width="18%">
                    <a href="{{ route('photo_albums.delete_photo',$photo->id) }}" onclick="return confirm('確定刪除？')"><i class="fas fa-trash-alt text-danger"></i></a>
                    @endforeach                                
                </div>
            </div>
        </div>
    </div>
    <br>
@endsection