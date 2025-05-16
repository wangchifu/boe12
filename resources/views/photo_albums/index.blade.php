@extends('layouts.app')

@section('title','相簿管理 | ')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="card">
            <div class="card-header text-center">
                <h3 class="py-2">
                    相簿管理
                </h3>
            </div>
            <div class="card-body">
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <i class="fas fa-plus text-info"></i> 新增相簿(按一下)
                          </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                          <div class="accordion-body">
                            <form action="{{ route('photo_albums.store') }}" method="POST" id='photo_form'>
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">名稱*</label>
                                    <input type="text" class="form-control" name="album_name" required placeholder="請輸入相簿名稱">  
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                </div>                     
                                <button class="btn btn-primary btn-sm" onclick="return confirm('確定儲存嗎？')">
                                    <i class="fas fa-save"></i> 新增相簿
                                </button>
                                </form>
                            </form>                    
                          </div>
                        </div>
                    </div>          
                </div>  
                <br>                
                <a href="{{ route('photo_albums.guest') }}" class="btn btn-outline-primary btn-sm" target="_blank"><i class="fas fa-user-friends"></i> 訪客頁面</a>
                <hr>
                <h4>列表</h4>
                <table class="table table-striped" style="word-break:break-all;">
                    <thead class="table-secondary">
                    <tr>
                        <th>相簿</th>
                        <th class="col-3">動作</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($photo_albums as $photo_album)
                        <tr>
                            <td>
                                @php
                                    $check = \App\Models\Photo::where('photo_album_id',$photo_album->id)->first();
                                    if(!empty($check)){
                                        $img = asset('storage/photo_albums/'.$photo_album->id.'/'.$check->photo_name);
                                    }else{
                                        $img = asset('images/no-image.png');
                                    }
                                @endphp
                                <a href="{{ route('photo_albums.show',$photo_album->id) }}">
                                    <img src="{{ $img }}" style="height:10rem;">
                                </a><br>
                                {{ $photo_album->album_name }} ({{ count($photo_album->photos) }})
                            </td>
                            <td>
                                <h6>{{ $photo_album->album_name }}</h6>
                                <a href="{{ route('photo_albums.show',$photo_album->id) }}" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-magnifying-glass"></i> 顯示</a>
                                <a href="{{ route('photo_albums.edit',$photo_album->id) }}" class="btn btn-success btn-sm"><i class="fas fa-edit"></i> 編輯</a>
                                <a href="{{ route('photo_albums.delete',$photo_album->id) }}" onclick="return confirm('確定刪除整本相簿？')" class="btn btn-danger btn-sm"><i class="fas fa-times-circle"></i> 刪除</a>                            
                            </td>
                        </tr>                                                                                                     
                        @endforeach
                    </tbody>
                </table>                
            </div>
        </div>
    </div>
</div>
<br>
@endsection
