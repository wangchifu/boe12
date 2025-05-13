@extends('layouts.app')

@section('title','宣導網站 | ')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="">
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        宣導網站列表
                    </h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('links.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增宣導網站</a>
                    <table class="table table-striped" style="word-break:break-all;">
                        <thead class="thead-light">
                        <tr>
                            <th>排序</th>
                            <th>名稱</th>
                            <th>類別</th>
                            <th>圖片</th>
                            <th>網址</th>
                            <th>動作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i=0;$j=0; ?>
                        @foreach($links as $link)
                            <tr>
                                <td>
                                    {{ $link->order_by }}
                                </td>
                                <td>
                                    {{ $link->name }}
                                </td>
                                <td>
                                    @if($link->type=="0" or empty($link->type))
                                        不分
                                    @elseif($link->type=="1")
                                        學校用
                                    @elseif($link->type=="2")
                                        民眾用
                                    @endif
                                </td>
                                <td>
                                    <?php
                                        $image = asset('storage/links/'.$link->image);
                                    ?>
                                    <img src="{{ $image }}" class="image" height="50">
                                </td>
                                <td>
                                    <a href="{{ $link->url }}" target="_blank"><i class="fas fa-globe"></i></a>

                                </td>
                                <td>
                                    <a href="{{ route('links.edit',$link->id) }}" class="btn btn-info btn-sm">修改</a>
                                    <a href="#" class="btn btn-danger btn-sm" onclick="if(confirm('確定刪除？')) document.getElementById('delete{{ $link->id }}').submit();else return false;">刪除</a>
                                </td>
                            </tr>
                            <form action="{{ route('links.destroy', $link->id) }}" method="POST" id="delete{{ $link->id }}" onsubmit="return false;">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
