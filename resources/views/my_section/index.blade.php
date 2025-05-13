@extends('layouts.app')

@section('title','選填科室 | ')

@section('content')
    <div class="container">
        <div class="py-5">
            <div class="">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('posts.reviewing') }}">公告系統</a></li>
                        <li class="breadcrumb-item active" aria-current="page">選填科室</li>
                    </ol>
                </nav>
                <div class="card">
                    <div class="card-header text-center">
                        <h3 class="py-2">
                            選填科室
                            @if($user->my_section_id)
                                --{{ $sections[$user->my_section_id] }}
                            @endif
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('my_section.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                        <div class="form-group">
                            <select name="my_section_id" class="form-control" required>
                                <option value="" disabled selected>選擇一個區域</option>
                                @foreach($sections as $key => $value)
                                    <option value="{{ $key }}" {{ old('my_section_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定嗎？')">送出</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
