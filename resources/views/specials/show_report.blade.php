@extends('layouts.app')

@section('title','特殊處理 | ')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="">
            @if($report)
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        特殊處理 - 處理填報 ID：{{ $report->id }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="card">
                        <div class="card-header bg-primary">
                            {{ $report->name }}
                        </div>
                        <div class="card-body">
                            {!! $report->content !!}
                            <hr>
                            <span class="text-danger">含給各校的 report_school 共 {{ count($report_schools) }} 校。</span><br>
                            <span class="text-danger">含問題 questions 共 {{ count($questions) }} 題。</span><br>
                            <span class="text-danger">含各校的 answers 回答 共 {{ count($answers) }} 則。</span><br>
                        </div>
                    </div>
                </div>
                <div class="footer">
                    <form action="{{ route('special.report_delete') }}" method="POST">
                        @csrf
                    <input type="hidden" name="report_id" value="{{ $report->id }}">
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('確定嗎？完全無法救回喔！')">
                        <i class="fas fa-trash-alt"></i> 刪除此填報及其已發給各校的 report_school 及其問題與學校的答案
                    </button>
                    </form>
                </div>
            </div>
            <br>
            <br>
            <a href="{{ route('special') }}" class="btn btn-secondary"><i class="fas fa-backward"></i> 返回</a>
            <hr>
            <h3>
                詳細資料
            </h3>
            <form action="{{ route('special.report_update', $report->id) }}" method="POST">
                @csrf
                @method('PATCH')
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
                        {{ $report->id }}
                    </td>
                </tr>
                <tr>
                    <td>
                        user_id 發佈人
                    </td>
                    <td>
                        {{ $report->user_id }} - {{ $report->user->name }}
                    </td>
                </tr>
                <tr>
                    <td>
                        pass_user_id 審核人
                    </td>
                    <td>
                        @if($report->pass_user_id)
                            {{ $report->pass_user_id }} - {{ $report->pass_user->name }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>
                        name 名稱
                    </td>
                    <td>
                        <input type="text" name="name" class="form-control" value="{{ $report->name }}">
                    </td>
                </tr>
                <tr>
                    <td>
                        content 內容
                    </td>
                    <td>
                        <textarea name="content" class="form-control">
                            {{ $report->content }}
                        </textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        school_set_0 發給的學校編碼0
                    </td>
                    <td>
                        {{ $report->school_set_0 }}
                    </td>
                </tr>
                <tr>
                    <td>
                        school_set_1 發給的學校編碼1
                    </td>
                    <td>
                        {{ $report->school_set_1 }}
                    </td>
                </tr>
                <tr>
                    <td>
                        school_set_2 發給的學校編碼2
                    </td>
                    <td>
                        {{ $report->school_set_2 }}
                    </td>
                </tr>
                <tr>
                    <td>
                        school_set_3 發給的學校編碼3
                    </td>
                    <td>
                        {{ $report->school_set_3 }}
                    </td>
                </tr>
                <tr>
                    <td>
                        school_set_4 發給的學校編碼4
                    </td>
                    <td>
                        {{ $report->school_set_4 }}
                    </td>
                </tr>
                <tr>
                    <td>
                        situation 審核過程
                    </td>
                    <td>
                        <select id="situation" name="situation" class="form-control">
                            @foreach($situation as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        section_id 教育處科別
                    </td>
                    <td>
                        <select id="section_id" name="section_id" class="form-control">
                            @foreach($sections as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        die_date 停止填報日期
                    </td>
                    <td>
                        {{ $report->die_date }}
                    </td>
                </tr>
                <tr>
                    <td>
                        passed_at 通過時間
                    </td>
                    <td>
                        {{ $report->passed_at }}
                    </td>
                </tr>
                <tr>
                    <td>
                        created_at 創建日期
                    </td>
                    <td>
                        {{ $report->created_at }}
                    </td>
                </tr>
                <tr>
                    <td>
                        updated_at 審核日期
                    </td>
                    <td>
                        {{ $report->updated_at }}
                    </td>
                </tr>
            </table>
            <button type="submit" class="btn btn-success" onclick="return confirm('確定嗎更新？')">
                <i class="fas fa-save"></i> 更新此填報
            </button>
            </form>
            <hr>
                <h3>
                    所屬問題
                </h3>
                <form action="{{ route('special.question_update') }}" method="POST">
                    @csrf
                    @method('PATCH')
            <table class="table table-striped">
                <tr>
                    <th>
                        id
                    </th>
                    <th>
                        title
                    </th>
                    <th>
                        type：radio,checkbox,text,num
                    </th>
                    <th>
                        options
                    </th>
                    <th>
                        show 顯示：1；移除：0
                    </th>
                </tr>
                @foreach($questions as $question)
                    <tr>
                        <td>
                            {{ $question->id }}
                        </td>
                        <td>
                            <input type="text" name="title[{{ $question->id }}]" class="form-control" value="{{ $question->title }}">
                        </td>
                        <td>
                            <input type="text" name="type[{{ $question->id }}]" class="form-control" value="{{ $question->type }}">
                        </td>
                        <td>
                            <input type="text" name="options[{{ $question->id }}]" class="form-control" value="{{ $question->options }}">
                        </td>
                        <td>
                            <input type="text" name="show[{{ $question->id }}]" class="form-control" value="{{ $question->show }}">
                        </td>
                    </tr>
                @endforeach
            </table>
            <button type="submit" class="btn btn-success" onclick="return confirm('確定嗎更新？')">
                <i class="fas fa-save"></i> 更新這些問題
            </button>
                </form>
            @else
                無此填報
            @endif
                <br>
                <a href="{{ route('special') }}" class="btn btn-secondary"><i class="fas fa-backward"></i> 返回</a>
        </div>
    </div>
</div>
@endsection
