@extends('layouts.app')

@section('title','成員管理 | ')

@section('content')
    <div class="container">
        <div class="py-5">
            <div class="">
                <h1>
                    {{ $other_schools[auth()->user()->other_code] }} 成員管理
                </h1>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center">
                        <form action="{{ route('posts.people_add') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <input type="text" name="username" class="form-control" required placeholder="請輸入成員帳號">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('確定？')">新增成員
                                </button>
                            </div>
                            <input type="hidden" name="section_id" value="{{ auth()->user()->other_code }}">
                        </form>
                        @include('layouts.errors')
                    </div>
                    <div class="card-body">
                        <h4>成員列表</h4>
                        <table class="table table-hover">
                            @foreach($users as $user)
                                <tr>
                                    <td>
                                        {{ $user->name }}({{ $user->username }})
                                    </td>
                                    <td>
                                        <a href="{{ route('posts.people_remove',$user->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('確定移除？')">移除</a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
