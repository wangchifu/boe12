@extends('layouts.app')

@section('title','其他連結 | ')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="">
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        其他連結列表
                    </h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('others.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增連結</a>
                    <p class="text-danger">有更動要登出登入才會有改變</p>
                    <table class="table table-striped" style="word-break:break-all;">
                        <thead class="thead-light">
                        <tr>
                            <th>排序</th>
                            <th>名稱</th>
                            <th>網址</th>
                            <th>動作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i=0;$j=0; ?>
                        @foreach($others as $other)
                            <tr>
                                <td>
                                    {{ $other->order_by }}
                                </td>
                                <td>
                                    {{ $other->name }}
                                </td>
                                <td>
                                    <a href="{{ $other->url }}" target="_blank"><i class="fas fa-globe"></i></a>
                                </td>

                                <td>
                                    <a href="{{ route('others.edit',$other->id) }}" class="btn btn-info btn-sm">修改</a>
                                    <a href="#" class="btn btn-danger btn-sm" onclick="if(confirm('確定刪除？')) document.getElementById('delete{{ $other->id }}').submit();else return false;">刪除</a>
                                </td>
                            </tr>
                            <form action="{{ route('others.destroy', $other->id) }}" method="POST" id="delete{{ $other->id }}" onsubmit="return false;">
                                @method('DELETE')
                                @csrf
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
