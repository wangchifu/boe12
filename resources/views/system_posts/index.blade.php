@extends('layouts.app')

@section('title','系統公告 | ')

@section('content')
    <div class="container">
        <div class="py-5">
            <div class="">
                <div class="card">
                    <div class="card-header text-center">
                        <h3 class="py-2">
                            系統公告(全站帳號都會收到)
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('system_posts.store') }}" method="POST">
                            @csrf
                        <div class="form-group">
                            <label class="text-danger"><strong>內容*</strong></label>
                            <textarea name="content" class="form-control" required></textarea>
                        </div>   
                        <div class="form-group">
                            <label class="text-danger"><strong>開始日期*</strong></label>
                            <input type="date" name="start_date" value="{{ date('Y-m-d') }}" class="form-control" required>
                        </div> 
                        <div class="form-group">
                            <label class="text-danger"><strong>結束日期*</strong></label>
                            <input type="date" name="end_date" value="" class="form-control" required>
                        </div>
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        <div class="form-group">
                            <button class="btn btn-primary btn-sm" onclick="return confirm('確定送出？')">送出</button>
                        </div>
                        </form>
                        <hr>                                                
                        <h4>已公告列表</h4>
                        @foreach($system_posts as $system_post)
                            <div class="card">
                                <div class="card-header" style="background-color: #FFCC22">
                                    <small>[<a href="{{ route('system_posts.destroy',$system_post->id) }}" class="text-danger" onclick="return confirm('確定刪除嗎？')">刪除</a>]</small> ({{ $system_post->id }}) 開始：{{ $system_post->start_date }} 結束：{{ $system_post->end_date }}
                                </div>
                                <div class="card-body" style="background-color: #FFFFBB">
                                    {!! nl2br($system_post->content) !!}
                                </div>
                            </div>
                            <br>
                        @endforeach
                        <div style="text-align:right">
                            {{ $system_posts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
