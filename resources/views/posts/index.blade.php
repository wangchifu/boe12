@extends('layouts.app')

@section('title', '行政公告 | ')

@section('content')
    <div class="container">
        <div class="py-5">
            <div class="col-md-8">
                @include('posts.nav')
                <div class="card">
                    <div class="card-header text-center">
                        <h3 class="py-2">
                            行政公告
                        </h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>
                                    編號
                                </th>
                                <th>
                                    主旨
                                </th>
                                <th>
                                    公告單位
                                </th>
                                <th>
                                    公告人
                                </th>
                                <th>
                                    公告時間
                                </th>
                                <th>
                                    簽收人
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($post5 as $post)
                                <tr>
                                    <th>
                                        {{ $post -> id }}
                                    </th>

                                    <th>
                                        <a href="javascript:open_post('{{ route('posts.show',$post->id) }}','新視窗')">{{ str_limit($post->title,100) }}</a>
                                    </th>
                                    <th>
                                        {{ array_get($sections,$post->section_id) }}
                                    </th>
                                    <th>
                                        {{ $post -> name }}
                                    </th>
                                    <th>
                                        {{ substr($post->created_at,0,10) }}
                                    </th>
                                    @if($post->signed_at==null)
                                        <th>
                                            <form action="{{ route('posts.signed', $post->ps_id) }}" method="POST" id="sign_check_form">
                                                @method('PATCH')
                                                @csrf                                            

                                                    <input type="hidden" value="{{ $user_power -> power_type }}" id="h_user_power">

                                                <button class="btn btn-success" type="button" onclick="signcheck()">
                                                    簽收
                                                </button>
                                            </form>
                                        </th>
                                    @endif
                                    @if($post->signed_at != null)
                                        <th>
                                            {{ auth()->user()->name }}
                                        </th>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="card-footer" style="text-align: right">
                            共{{ count($post5) }}筆行政公告
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        一般公告
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>
                                    編號
                                </th>
                                <th>
                                    主旨
                                </th>
                                <th>
                                    公告單位
                                </th>
                                <th>
                                    公告人
                                </th>
                                <th>
                                    公告時間
                                </th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($post1 as $post)
                                <tr>
                                    <th>
                                        {{ $post -> id }}
                                    </th>
                                    <th>
                                        <a href="javascript:open_post('{{ route('posts.show',$post->id) }}','新視窗')">{{ str_limit($post->title,100) }}</a>
                                    </th>
                                    <th>
                                        {{ array_get($sections,$post->section_id) }}
                                    </th>
                                    <th>
                                        {{ $post->user->name }}
                                    </th>
                                    <th>
                                        {{ substr($post->updated_at,0,10) }}
                                    </th>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        <div class="col-12">
                            <nav>
                                <ul class="pagination justify-content-end">
                                    {{$post1->links()}}
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="card-footer" style="text-align: right">
                        共{{ count($countpost1) }}筆一般公告
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        <!--
        function open_post(url,name)
        {
            window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=850,height=650');
        }
        // -->

        function signcheck() {
            var user_power = $("#h_user_power").val();
            if(user_power == "B") {
                $("#sign_check_form").submit();
            }else {
                alert('您沒有簽收的權限');
            }
        }
    </script>

@endsection

