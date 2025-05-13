@extends('layouts.app')

@section('title','相簿管理 | ')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="card">
            <div class="card-header text-center">
                <h3 class="py-2">
                    相簿管理 <a href="javascript:open_window('{{ route('photo_albums.create') }}','新視窗')" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增相簿</a>
                    <a href="{{ route('photo_albums.guest') }}" target="_blank"><i class="fas fa-user-friends"></i> 訪客頁面</a>
                </h3>
            </div>
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        @foreach($photo_albums as $photo_album)
                            <div class="col-3">
                                <div class="card" style="box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;">
                                     <?php
                                        $check = \App\Models\Photo::where('photo_album_id',$photo_album->id)->first();
                                        if(!empty($check)){
                                            $img = asset('storage/photo_albums/'.$photo_album->id.'/'.$check->photo_name);
                                        }else{
                                            $img = asset('images/no_image.svg');
                                        }
                                     ?>
                                    <a href="{{ route('photo_albums.show',$photo_album->id) }}">
                                        <img class="card-img-top" src="{{ $img }}" style="height:10rem;object-fit: cover;">
                                    </a>
                                    <div class="card-body" style="padding: 5px;">
                                        <p class="card-text">{{ $photo_album->album_name }} ({{ count($photo_album->photos) }})
                                            <a href="javascript:open_window('{{ route('photo_albums.edit',$photo_album->id) }}','新視窗')"><i class="fas fa-edit text-primary"></i></a>
                                            <a href="{{ route('photo_albums.delete',$photo_album->id) }}" onclick="return confirm('確定刪除整本相簿？')"><i class="fas fa-times-circle text-danger"></i></a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<script>
    function open_window(url, name) {
        window.open(url, name, 'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=850,height=200');
    }
</script>
@endsection
