@extends('layouts.app_clean')

@section('title','指定審核者 | ')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="">
            <div class="card">
                <div class="card-head">
                    <img class="card-img-top img-responsive" src="{{ asset('images/small/power_d.png') }}">
                </div>
                <div class="card-body">
                    <h5>
                        {{ $sections[auth()->user()->section_id] }}
                    </h5>
                    <p>
                        方式一：從教育處所有帳號選擇
                        <form action="{{ route('my_section.power_update1') }}" method="POST">
                            @csrf
                    <div class="form-group">
                        <select name="user_id" class="form-control">
                            <option value="" disabled selected>選擇使用者</option>
                            @foreach($select_users as $key => $value)
                                <option value="{{ $key }}" {{ old('user_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('確定？')">方式一送出
                        </button>
                    </div>
                    <input type="hidden" name="section_id" value="{{ auth()->user()->section_id }}">
                        </form>
                    </p>
                    <p>
                        方式二：輸入本站任一帳號(可加入帳號掛學校的調府教師，例如要加入foo@chc.edu.tw，請輸入foo)
                    @include('layouts.errors')
                    <form action="{{ route('my_section.power_update2') }}" method="POST">
                        @csrf
                    <div class="form-group">
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('確定？')">方式二送出
                        </button>
                    </div>
                    <input type="hidden" name="section_id" value="{{ auth()->user()->section_id }}">
                    </form>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
