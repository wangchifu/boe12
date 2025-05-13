@extends('layouts.app')

@section('title','內容管理 | ')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="card">
            <div class="card-header text-center">
                <h3 class="py-2">
                    內容管理
                </h3>
            </div>
            <div class="card-body">
                <a href="{{ route('contents.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增內容</a>
                <table class="table table-striped" style="word-break:break-all;">
                    <thead class="thead-light">
                    <tr>
                        <th>標題</th>
                        <th class="col-3">最後編輯</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($contents as $content)
                        <tr>
                            <td>
                                <a href="{{ route('contents.show',$content->id) }}" target="_blank">{{ $content->title }}</a><br>
                                <a href="{{ route('contents.edit',$content->id) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i> 修改</a>
                                <a href="#" class="btn btn-danger btn-sm" onclick="if(confirm('確定刪除？')) document.getElementById('delete{{ $content->id }}').submit();else return false;"><i class="fas fa-trash"></i> 刪除</a>
                            </td>
                            <td>
                                <?php $section_name = (!empty($content->section_id))?$sections[$content->section_id]:""; ?>
                                {{ $content->updated_at }}<br>{{ $section_name }} {{ $content->user->name }}
                            </td>
                        </tr>                        
                        <form action="{{ route('contents.destroy', $content->id) }}" method="POST" id="delete{{ $content->id }}">
                            @csrf                           
                        </form>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<br>
@endsection
