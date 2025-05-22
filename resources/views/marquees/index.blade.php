@extends('layouts.app')

@section('title','跑馬燈 | ')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="">
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        跑馬燈列表
                    </h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('marquees.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增跑馬燈</a>
                    <table class="table table-striped" style="word-break:break-all;">
                        <thead class="thead-light">
                        <tr>
                            <th>id</th>
                            <th>標題</th>
                            <th>開始日期</th>
                            <th>結束日期</th>
                            <th>上架者</th>
                            <th>動作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i=0;$j=0; ?>
                        @foreach($marquees as $marquee)
                            <tr>
                                <td>
                                    {{ $marquee->id }}
                                </td>
                                <td>
                                    {{ $marquee->title }}
                                </td>
                                <td>
                                    {{ $marquee->start_date }}
                                </td>
                                <td>
                                    {{ $marquee->stop_date }}
                                </td>
                                <td>
                                    {{ $marquee->user->name }}
                                </td>
                                <td>
                                    @if($marquee->user_id == auth()->user()->id)
                                        <a href="{{ route('marquees.edit',$marquee->id) }}" class="btn btn-info btn-sm"><i class="fas fa-edit"></i> 修改</a>
                                        <a href="#" class="btn btn-danger btn-sm" onclick="if(confirm('確定刪除？')) document.getElementById('delete{{ $marquee->id }}').submit();else return false;"><i class="fas fa-trash"></i> 刪除</a>
                                    @endif
                                </td>
                            </tr>                            
                            <form action="{{ route('marquees.destroy', $marquee->id) }}" method="POST" id="delete{{ $marquee->id }}" onsubmit="return false;">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $marquees->links('layouts.simple-pagination') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
