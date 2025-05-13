@extends('layouts.app')

@section('title','管理者登入 | ')

@section('content')
<section id="contact" class="contact mb-5">
    <div class="container" data-aos="fade-up">
        <div class="row">
            <div class="col-lg-12 col-sm-12 text-center">
              <h1 class="page-title">管理者登入</h1>
            </div>
          </div>
          
        <div class="form mt-5 col-lg-6 col-sm-12 offset-md-3">
            @if(session('login_error') < 3)
                <form method="POST" action="{{ route('login') }}" id="this_form" onsubmit="change_button()" role="form" class="php-email-form">
                    @csrf
                    <div class="row">                
                        <div class="form-group col-md-12">
                            <input type="text" name="username" class="form-control" id="username" placeholder="帳號" required tabindex="1" autofocus>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <input type="password" class="form-control" name="password" id="password" placeholder="密碼" required tabindex="2">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <input type="text" name="chaptcha" class="form-control" id="chaptcha" placeholder="右圖阿拉伯數字" required tabindex="3" maxlength="5">
                        </div>          
                        <div class="form-group col-md-6">
                            <a href="{{ route('glogin') }}"><img src="{{ route('pic') }}" class="img-fluid"></a><small class="text-secondary"> (按一下更換)</small>
                        </div>      
                    </div>
                <div class="my-3">
                    <div class="loading">載入中...</div>
                    <div class="error-message"></div>
                    <div class="sent-message">登入帳號資訊已送出，謝謝！</div>
                </div>
                <div class="text-center"><button type="submit" tabindex="4" id="submit_button">管理員登入</button></div>            
                <a href="{{ route('glogin')}}"><i class="fas fa-cog"></i> GSuite 登入</a>
                </form>         
            @else
                <span class="text-danger">登入錯誤超過三次，請按 ctrl+shift+del 清掉快取才能再試。</span>
            @endif   
        </div><!-- End Contact Form -->
        @if($errors->any())
        <div class="alert alert-danger mt-2 col-lg-6 col-sm-12 offset-md-3">
            @include('layouts.errors')
        </div>
        @endif
    </div>    
</section>

<script>
    var validator = $("#this_form").validate();
</script>
@endsection
