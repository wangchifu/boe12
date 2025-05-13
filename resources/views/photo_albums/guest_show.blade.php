@extends('layouts.app')

@section('title','精采相簿 | ')

@section('content')
    <style>
            .masonry {
                column-count: 4;
                column-gap: 0;
            }

            .item {
                padding: 5px;
                position: relative;
                counter-increment: count;
            }

            .item img {
                display: block;
                width: 100%;
                height: auto;
            }

            .item::after {
                position: absolute;
                display: block;
                top: 2px;
                left: 2px;
                width: 24px;
                height: 24px;
                text-align: center;
                line-height: 24px;
                background-color: #000;
                color: #fff;
                //content: counter(count);
            }
        </style>
    <div class="container">
            <div class="py-5">
                    <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('photo_albums.guest') }}">精采相簿</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ $photo_album->album_name }}</li>
                                </ol>
                        </nav>
                    <div class="card">
                            <div class="card-header text-center">
                                    <h3 class="py-2">
                                            {{ $photo_album->album_name }}
                                        </h3>
                                </div>
                            <div class="card-body">
                                    <div class="masonry">
                                        @foreach($photos as $photo)
                                            <?php
                                                $filename = $photo->photo_name;
                                                $filename_small_array = explode('.',$filename);
                                                $filename_small = $filename_small_array[0].'-small.'.$filename_small_array[1];
                                            ?>
                                                <div class="item">
                                                        <a href="{{ asset('storage/photo_albums/'.$photo_album->id.'/'.$photo->photo_name) }}" class="venobox" data-gall="gall1">
                                                                <img src="{{ asset('storage/photo_albums/'.$photo_album->id.'/'.$filename_small) }}" style="border-radius: 5px;">
                                                            </a>
                                                    </div>
                                            @endforeach
                                        </div>
                                </div>
                        </div>
                </div>
        </div>
    <br>
    <script>

            </script>
    @endsection
