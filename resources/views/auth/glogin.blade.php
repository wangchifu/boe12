@extends('layouts.app')

@section('title','GSuite登入 | ')

@section('content')
<section id="contact" class="contact mb-5">
    <div class="container" data-aos="fade-up">
        <div class="row">
            <div class="col-lg-12 col-sm-12 text-center">
              <h1 class="page-title">登入</h1>
            </div>
          </div>
          
        <div class="form mt-5 col-lg-6 col-sm-12 offset-md-3">
            @if(session('login_error') < 3)            
                <form method="POST" action="{{ route('gauth') }}" id="this_form" onsubmit="change_button()" role="form" class="php-email-form">
                    @csrf
                    <div class="row">                
                        <div class="form-group col-md-5">
                            <input type="text" name="username" class="form-control" id="username" placeholder="GSuite 帳號" required tabindex="1" autofocus>
                        </div>
                        <div class="form-group col-md-4">
                            <span>@chc.edu.tw</span>
                        </div>
                        <div class="form-group col-md-3">
                            <a href="https://gsuite.chc.edu.tw" target="_blank"><img src="{{ asset('images/gsuite_logo.png') }}" class="img-fluid"></a>
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
                <div class="text-center"><button type="submit" tabindex="4" id="submit_button">GSuite 登入</button></div>            
                <a href="{{ route('login')}}"><i class="fas fa-cog"></i> 管理者登入</a>
                </form>            
            @else
                <span class="text-danger">登入錯誤超過三次，請按 ctrl+shift+del 清掉快取才能再試。</span>
            @endif
          </div><!-- End Contact Form -->
          <div class="alert alert-warning mt-2 col-lg-6 col-sm-12 offset-md-3">
            @include('layouts.errors')
            忘記密碼請由校務系統修改!調校者請登入後職稱會自動修改!兼任者用不同學校校務密碼即可區分!
            <br>
            使用者登入(無G Suite者，或忘記帳號的，請 [<a href="https://gsuite.chc.edu.tw/" target="_blank">按此</a>])
        </div>
    </div>    
</section>

<script>
    var validator = $("#this_form").validate();
    function change_button(){
        $("#submit_button").attr('disabled','disabled');
        $("#submit_button").addClass('disabled');
    }
</script>
@endsection
