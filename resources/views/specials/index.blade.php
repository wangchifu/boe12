@extends('layouts.app')

@section('title','特殊處理 | ')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="">
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        特殊處理
                    </h3>
                </div>
                <div class="card-body">
                    <h3 class="text-danger">
                        可以的話，不要答應進行此動作！承辦人及學校單位都應該謹慎操作本系統，不應該叫系統管理員去事後補救，這也違背行政程序。
                    </h3>
                    <h4>
                        處理公告
                    </h4>
                    <form action="{{ route('special.post') }}" method="POST">
                        @csrf
                    <input type="number" name="post_id" placeholder="請輸入公告 ID" class="form-control" required>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-plane"></i> 秀出此公告資訊
                    </button>
                    </form>
                    <hr>
                    <h4>
                        處理填報
                    </h4>
                    <form action="{{ route('special.report') }}" method="POST">
                        @csrf
                    <input type="number" name="report_id" placeholder="請輸入填報 ID" class="form-control" required>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-plane"></i> 秀出此填報資訊
                    </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
