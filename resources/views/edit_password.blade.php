@extends('layouts.app')

@section('title','變更密碼 | ')

@section('content')
<div class="container">
    <div class="row justify-content-center pt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        變更密碼
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('update_password') }}" method="post">
                        @csrf
                        @method('patch')
                        <div class="form-group">
                            <label for="exampleInputPassword0">舊密碼*</label>
                            <input type="password" class="form-control" name="password0" id="exampleInputPassword0" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">新密碼*</label>
                            <input type="password" class="form-control" name="password1" id="exampleInputPassword1" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword2">確認新密碼*</label>
                            <input type="password" class="form-control" name="password2" id="exampleInputPassword2" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> 送出</button>
                    </form>
                    @include('layouts.errors')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
