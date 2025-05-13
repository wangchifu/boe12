@extends('layouts.app')

@section('title','特殊處理 | ')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="">
            @if($post)
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        特殊處理 - 處理公告 ID：{{ $post->id }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="card">
                        <div class="card-header bg-primary">
                            [{{ $post->post_no }}]{{ $post->title }}
                        </div>
                        <div class="card-body">
                            {!! $post->content !!}
                            <hr>
                            <span class="text-danger">含給各校的 post_school 共 {{ count($post_schools) }} 校。</span>
                        </div>
                    </div>
                </div>
                <div class="footer">
                    <form action="{{ route('special.post_delete') }}" method="POST">
                        @csrf
                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('確定嗎？完全無法救回喔！')">
                        <i class="fas fa-trash-alt"></i> 刪除此公告及其已發給各校的 post_school
                    </button>
                    </form>
                </div>
            </div>
            <br>
            <br>
            <a href="{{ route('special') }}" class="btn btn-secondary"><i class="fas fa-backward"></i> 返回</a>
            <hr>
            <form action="{{ route('special.post_update', $post->id) }}" method="POST">
                @method('PATCH')
                @csrf
            <table class="table table-striped">
                <tr>
                    <th>
                        項目
                    </th>
                    <th>
                        內容
                    </th>
                </tr>
                <tr>
                    <td>
                        ID 流水號
                    </td>
                    <td>
                        {{ $post->id }}
                    </td>
                </tr>
                <tr>
                    <td>
                        post_no 公文號
                    </td>
                    <td>
                        {{ $post->post_no }}
                    </td>
                </tr>
                <tr>
                    <td>
                        user_id 發佈人
                    </td>
                    <td>
                        {{ $post->user_id }} - {{ $post->user->name }}
                    </td>
                </tr>
                <tr>
                    <td>
                        pass_user_id 審核人
                    </td>
                    <td>
                        @if($post->pass_user_id)
                            {{ $post->pass_user_id }} - {{ $post->pass_user->name }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>
                        category_id 分類
                    </td>
                    <td>
                        <select name="category_id" id="category_id" class="form-control">
                            @foreach ($categories as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        title 標題
                    </td>
                    <td>
                        <input type="text" name="title" class="form-control" value="{{ $post->title }}">
                    </td>
                </tr>
                <tr>
                    <td>
                        content 內容
                    </td>
                    <td>
                        <textarea name="content" class="form-control">
                            {{ $post->content }}
                        </textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        feedback_reason 退回理由
                    </td>
                    <td>
                        {{ $post->feedback_reason }}
                    </td>
                </tr>
                <tr>
                    <td>
                        school_set_0 發給的學校編碼0
                    </td>
                    <td>
                        {{ $post->school_set_0 }}
                    </td>
                </tr>
                <tr>
                    <td>
                        school_set_1 發給的學校編碼1
                    </td>
                    <td>
                        {{ $post->school_set_1 }}
                    </td>
                </tr>
                <tr>
                    <td>
                        school_set_2 發給的學校編碼2
                    </td>
                    <td>
                        {{ $post->school_set_2 }}
                    </td>
                </tr>
                <tr>
                    <td>
                        school_set_3 發給的學校編碼3
                    </td>
                    <td>
                        {{ $post->school_set_3 }}
                    </td>
                </tr>
                <tr>
                    <td>
                        school_set_4 發給的學校編碼4
                    </td>
                    <td>
                        {{ $post->school_set_4 }}
                    </td>
                </tr>
                <tr>
                    <td>
                        situation 審核過程
                    </td>
                    <td>
                        <select name="situation" id="situation" class="form-control">
                            @foreach ($situation as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        type 急件？ 1 是；null 否
                    </td>
                    <td>
                        <input type="text" name="type" class="form-control" value="{{ $post->type }}">
                    </td>
                </tr>
                <tr>
                    <td>
                        another 同時為一般公告？ 1 是；null 否
                    </td>
                    <td>
                        <input type="text" name="another" class="form-control" value="{{ $post->another }}">
                    </td>
                </tr>
                <tr>
                    <td>
                        section_id 教育處科別
                    </td>
                    <td>
                        <select name="section_id" id="section_id" class="form-control">
                            @foreach ($sections as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        views 觀看次數
                    </td>
                    <td>
                        {{ $post->views }}
                    </td>
                </tr>
                <tr>
                    <td>
                        url 連結網址
                    </td>
                    <td>
                        {{ $post->url }}
                    </td>
                </tr>
                <tr>
                    <td>
                        passed_at 通過時間
                    </td>
                    <td>
                        {{ $post->passed_at }}
                    </td>
                </tr>
                <tr>
                    <td>
                        created_at 創建日期
                    </td>
                    <td>
                        {{ $post->created_at }}
                    </td>
                </tr>
                <tr>
                    <td>
                        updated_at 審核日期
                    </td>
                    <td>
                        {{ $post->updated_at }}
                    </td>
                </tr>
            </table>
            <button type="submit" class="btn btn-success" onclick="return confirm('確定嗎更新？')">
                <i class="fas fa-save"></i> 更新此公告
            </button>
            </form>
            @else
                無此公告
            @endif
                <br>
                <a href="{{ route('special') }}" class="btn btn-secondary"><i class="fas fa-backward"></i> 返回</a>
        </div>
    </div>
</div>
@endsection
