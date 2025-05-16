@extends('layouts.app')

@section('title','修改相簿 | ')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="card">
            <div class="card-header text-center">
                <h3 class="py-2">
                    編輯相簿
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('photo_albums.update',$photo_album->id) }}" method="post">
                    @csrf
                    <table class="table">
                        <tr>
                            <td>
                                <input type="text" class="form-control" name="album_name" required placeholder="請輸入相簿名稱" value="{{ $photo_album->album_name }}">
                            </td>
                            <td>
                                <button class="btn btn-success btn-sm" onclick="return confirm('確定送出？')">送出</button>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                </form>                        
            </div>
        </div>
    </div>
</div>
<br>    
@endsection
