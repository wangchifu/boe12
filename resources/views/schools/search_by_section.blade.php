@extends('layouts.app')

@section('title','公告簽收 | ')

@section('page-scripts')
    <script>
        <!--
        function open_post(url, name) {
            window.open(url, name, 'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=850,height=650');
        }

        // -->

        function signcheck() {
            var user_power = $("#h_user_power").val();
            if (user_power == "B") {
                $("#sign_check_form").submit();
            } else {
                alert('您沒有簽收的權限');
            }
        }

        $(document).ready(function () {
            var yetVisited = localStorage['visited'];
            var mycount = $("#ModalLong").data('mycount');
            if (mycount > 0) {
                if (!yetVisited) {
                    $("#ModalLong").modal('show')
                    localStorage['visited'] = "yes";
                }
            }
        });
    </script>
@endsection
@section('content')
    <div class="container">
        <div class="py-5">
            <div class="alert alert-primary" role="alert">
                <a href="{{ route('posts.showSigned') }}">公告列表</a> / 科室：「{{ $sections[$section_id] }}」
            </div>
            @include('posts.nav')
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        公告簽收
                    </h3>
                </div>
                <div class="card-body">
                    @include('schools.search_nav',[$section_id])
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th nowrap>
                                編號
                            </th>
                            <th nowrap>
                                主旨
                            </th>
                            <th nowrap>
                                公告單位
                            </th>
                            <th nowrap>
                                公告人
                            </th>
                            <th nowrap>
                                公告時間
                            </th>
                            <th nowrap>
                                簽收人
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($posts))
                            @foreach($posts as $post)
                            <tr>
                                <th>
                                    {{ $post->post_no }}
                                </th>

                                <th>
                                    @if($post->another ===1)
                                        <span class="text-success">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    @endif
                                    @if($post->type ===1)
                                        <span class="text-danger">
                                            [最速件]
                                        </span>
                                    @endif
                                    @if( $post->situation ===4 )
                                        <span style="color:red">[公告作廢]</span> <strike class="text-primary"><a href="javascript:open_post('{{ route('posts.show',$post->id) }}','新視窗')">{{ str_limit($post->title,160) }}</a></strike>
                                    @else
                                        <a href="javascript:open_post('{{ route('posts.show',[$post->id,$post->ps_id]) }}','新視窗')">
                                            <span style="color:#000088">
                                                {{ str_limit($post->title,160) }}
                                            </span>
                                        </a>
                                    @endif
                                </th>
                                <th>
                                    {{ array_get($sections,$post->section_id) }}
                                </th>
                                <th>
                                    {{ userid2name($post->user_id) }}
                                </th>
                                <th nowrap>
                                    <small>
                                        {{ substr($post->passed_at,0,10) }}<br>{{ substr($post->passed_at,11,5) }}
                                    </small>
                                </th>
                                <th>
                                    @if($post->signed_at==null and $post->situation != 4)
                                    <form action="{{ route('posts.signed', ['ps_id' => $post->ps_id]) }}" method="POST" id="sign_check_form{{ $post->id }}">
                                        @method('PATCH')
                                        @csrf                                        

                                        <input type="hidden" value="{{ $user_power -> power_type }}"
                                               id="h_user_power">

                                        <button class="btn btn-success btn-sm" type="button"  onclick="if(confirm('您確定簽收嗎?')) signcheck{{ $post->id }}();else return false">
                                            簽收
                                        </button>
                                    </form>
                                        <script>
                                            function signcheck{{ $post->id }}() {
                                                var user_power = $("#h_user_power").val();
                                                if (user_power == "B") {
                                                    $("#sign_check_form{{ $post->id }}").submit();
                                                } else {
                                                    alert('您沒有簽收的權限');
                                                }
                                            }
                                        </script>
                                    @endif
                                    @if($post->signed_at != null)
                                        {{ userid2name($post->signed_user_id) }}
                                    @endif
                                </th>
                            </tr>
                            @endforeach
                        @else
                            <p class="text-danger">查無資料！</p>
                        @endif
                        </tbody>
                    </table>
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>


@endsection
