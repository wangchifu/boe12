@extends('layouts.app')

@section('title',$section_name.' | ')

@section('content')
<div class="container">
    <div class="row  col">
        <div class="col">
            <h1>{{ $section_name }}</h1>
            <?php
                if($type == "organization"){
                    $action['organization'] = "active";
                    $action['people'] = "";
                    $action['site'] = "";
                }
                if($type == "people"){
                    $action['organization'] = "";
                    $action['people'] = "active";
                    $action['site'] = "";
                }
                if($type == "site"){
                    $action['organization'] = "";
                    $action['people'] = "";
                    $action['site'] = "active";
                }
            ?>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link {{ $action['organization'] }}" href="{{ route('introductions.show',['type'=>'organization','section_id'=>$section_id]) }}">業務簡介</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $action['people'] }}" href="{{ route('introductions.show',['type'=>'people','section_id'=>$section_id]) }}">科室成員</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $action['site'] }}" href="{{ route('introductions.show',['type'=>'site','section_id'=>$section_id]) }}">資源網站</a>
                </li>
                @foreach($section_pages as $section_page)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('introductions.section_page_show',[$section_id,$section_page->id]) }}">{{ $section_page->title }}</a>
                    </li>
                @endforeach
            </ul>
            <br>
            <div class="card" style="padding:10px;word-break: break-all;margin-bottom:20px;">
                {!! $content !!}
            </div>
        </div>
    </div>
</div>
@endsection
