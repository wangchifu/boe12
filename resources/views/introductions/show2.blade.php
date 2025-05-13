@extends('layouts.app')

@section('title',$section_name.' | ')

@section('content')
<div class="container ">
    <div class="">
        <div class="">
            <h1>{{ $section_name }}</h1>
            <div class="card" style="padding:10px;margin-bottom:20px;"">
                {!! $content !!}
            </div>
        </div>
    </div>
</div>
@endsection
