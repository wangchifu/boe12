@extends('layouts.app')

@section('title','精采相簿 | ')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="card">
            <div class="card-header text-center">
                <h3 class="py-2">
                    精采相簿
                </h3>
            </div>
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        @foreach($photo_albums as $photo_album)
                            <div class="col-xs-12 col-md-6 col-xl-3" style="margin-bottom: 20px;">
                                <div class="card" style="box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;">
                                     <?php
                                        $check = \App\Models\Photo::where('photo_album_id',$photo_album->id)->first();
                                        if(!empty($check)){
                                            $img = asset('storage/photo_albums/'.$photo_album->id.'/'.$check->photo_name);
                                        }else{
                                            $img = asset('images/no_image.svg');
                                        }
                                     ?>
                                    <a href="{{ route('photo_albums.guest_show',$photo_album->id) }}">
                                        <img class="card-img-top" src="{{ $img }}" style="height:10rem;object-fit: cover;">
                                    </a>
                                    <div class="card-body" style="padding: 5px;">
                                        <p class="card-text">{{ $photo_album->album_name }} ({{ count($photo_album->photos) }})</p>
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
@endsection
