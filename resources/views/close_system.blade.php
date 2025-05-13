<?php
if(file_exists(storage_path('app/privacy/close.txt'))){    
        $fp = fopen(storage_path('app/privacy/close.txt'), 'r');
        $close = fread($fp, filesize(storage_path('app/privacy/close.txt')));                
        fclose($fp);
}else{
    $close = 0;
}
if($close==1){
    $app = "layouts.app_clean";
}else{
    $app = "layouts.app";
}
?>

@extends($app)

@section('title','系統已關閉 | ')

@section('content')
    <div class="container">
        <div class="py-5">
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        @if($close==0)
                        「彰化縣教育處新雲端」系統開放中
                        @elseif($close==1)
                        「彰化縣教育處新雲端」系統已關閉
                        @endif
                    </h3>
                </div>
                <div class="card-body">
                    <div class="card">
                        <div class="card-body">
                            @auth
                                @if(auth()->user()->group_id==9 or auth()->user()->admin==1)
                                    @if($close==0)
                                    <a class="btn btn-danger" href="{{ route('close_system') }}" onclick="return confirm('確定關閉嗎？所有人都無法使用了喔！')">把系統關閉</a>
                                    @elseif($close==1)
                                    <a class="btn btn-success" href="{{ route('close_system') }}" onclick="return confirm('確定打開嗎？')">把系統打開</a>
                                    @endif
                                @endif
                            @endauth
                            <h5>
                                <a href="{{ route('login') }}">管理員</a>公告
                            </h5>
                            @if($close==0)                                
                                <p>
                                    本站正常運作中。
                                </p>
                            @elseif($close==1)                                
                                <p>
                                本站因應某些無法立即解決的問題，先關閉系統，請您稍後再試。
                                </p>
                                <img src="{{ asset('images/close.png') }}" class="img-thumbnail" alt="Cinque Terre">  
                            @endif
                                                       
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@endsection
