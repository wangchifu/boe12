@extends('layouts.app')

@section('title','內容管理 | ')

@section('content')
    <div class="container">
   
            <div>
                <h1>
                    {{ $content->title }}
                </h1>
                <div class="card my-4">
                    <div class="card-body">
                        {!! $content->content !!}
                    </div>
                </div>
            </div>

    </div>
@endsection
