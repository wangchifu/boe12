@extends('layouts.app_clean')

@section('title','新增相簿 | ')

@section('content')
    <form action="{{ route('photo_albums.store') }}" method="post">
        @csrf
        <table class="table">
            <tr>
                <td>
                    <input type="text" class="form-control" name="album_name" required placeholder="請輸入相簿名稱">
                </td>
                <td>
                    <button class="btn btn-success btn-sm" onclick="return confirm('確定送出？')">送出</button>
                </td>
            </tr>
        </table>
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
    </form>
@endsection
