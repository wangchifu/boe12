@extends('layouts.app')

@section('title','公告簽收 | ')

@section('page-scripts')
    <script>
        <!--
        function open_post(url, name) {
            window.open(url, name, 'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=850,height=800');
        }

        // -->

        $(document).ready(function () {
            var yetVisited = localStorage['visited'];

            var mycount = $("#ModalLong").data('mycount');
            if (mycount > 0) {
                if (!yetVisited) {
                    $("#ModalLong").modal('show');
                    //localStorage['visited'] = "yes";
                }
            }
        });
    </script>
@endsection
@section('content')
    <div class="container">
        <div class="py-5">
            <div class="">
                <?php
                $posts_all_not = \App\Models\PostSchool::where('code','like', "%".auth()->user()->code."%")
                    ->where('signed_user_id',null)
                    ->get();
                $posts_quick = 0;
                $posts_not = 0;
                foreach($posts_all_not as $post_all_not){
                    if($post_all_not->post->situation === 3){
                        if($post_all_not->post->type == "1"){
                            $posts_quick++;
                        }
                        $posts_not++;
                    }
                }

                $c_p = $posts_not;
                $c_r = \App\Models\ReportSchool::where('code','like', "%".auth()->user()->code."%")
                    ->where(function($q){
                        $q->where('situation','=',0)
                            ->orWhere('situation','=',1)
                            ->orWhere('situation','=',2)
                            ->orWhere('situation',null);
                    })
                    ->get()->count();
                ?>
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a class="btn btn-success btn-sm" href="{{ route('posts.showSigned') }}">公告簽收 ({{ $c_p }})</a>
                    <a class="btn btn-light btn-sm" href="{{ route('school_report.index') }}">資料填報 ({{ $c_r }})</a>
                </div>
                <div class="card my-4">
                    <div class="card-header text-center">
                        <h3 class="py-2">
                            公告簽收
                        </h3>
                    </div>
                    <div class="card-body">
                        @include('schools.search_nav',['section_id'=>'all'])

                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('posts.showSigned') }}">全部</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="{{ route('posts.show_not_Signed') }}">未簽收({{ $posts_not }})</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('posts.show_quick_Signed') }}">最速件({{ $posts_quick }})</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('posts.show_person_Signed') }}">個人已簽收</a>
                            </li>
                        </ul>
                        <div class="table-responsive">
                            <table class="table rwd-table" style="word-break: break-all;">
                                <thead>
                                <tr>
                                    <th nowrap>
                                        編號
                                    </th>
                                    <th nowrap>
                                        主旨
                                    </th>
                                    <th nowrap>
                                        發佈人
                                    </th>
                                    <th nowrap>
                                        發佈日期
                                    </th>
                                    <th nowrap>
                                        簽收
                                    </th>
                                    <th nowrap>
                                        列印
                                    </th>
                                </tr>
                                </thead>
                                <tr>
                                    <th colspan="6" class="text-right"><button onclick="if(confirm('您確定打勾的都要簽收嗎?')) more_post();else return false">打勾者批次簽收</button></th>
                                </tr>
                                <tbody>
                                @foreach($post5 as $post)
                                    <tr>
                                        <td nowrap data-th="編號">
                                            <span data-toggle="tooltip" data-placement="top" title="給 {{ $schools[$post->code] }}">{{ $post->post_no }}</span>
                                        </td>
    
                                        <td data-th="主旨">
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
                                        </td>
                                        <td nowrap data-th="發佈人">
                                            {{ array_get($sections,$post->section_id) }}<br>{{ $post -> name }}
                                        </td>
                                        <td data-th="發佈日期" nowrap>
                                            {{ substr($post->passed_at,0,10) }}<br>{{ substr($post->passed_at,11,5) }}
                                        </td>
                                        <td nowrap data-th="簽收">
                                        @if($post->signed_at==null)
                                        <form action="{{ route('posts.signed2', ['ps_id' => $post->ps_id]) }}" method="POST" id="sign_check_form{{ $post->id }}">
                                            @method('PATCH')
                                            @csrf
                                            <input type="hidden" name="page" value="{{ $page }}">
                                            <input type="hidden" value="{{ $user_power -> power_type }}"
                                                   id="h_user_power">
    
                                            <button class="btn btn-success btn-sm" type="button"  onclick="if(confirm('您確定簽收嗎?')) signcheck{{ $post->id }}();else return false">
                                                簽收
                                            </button>
                                            <input type="checkbox" name="more_post[{{ $post->ps_id }}]" class="more_post" id="m{{ $post->ps_id }}"> <label class="small" for="m{{ $post->ps_id }}">打勾</label>
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
                                        </td>
                                        <td data-th="列印">
                                            <a href="{{ route('posts.showSigned_print2',$post->id) }}" class="btn btn-outline-secondary btn-sm" target="_blank"><i class="fas fa-print"></i> <i class="fas fa-sort-amount-up"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tr>
                                    <th colspan="6" class="text-right"><button onclick="if(confirm('您確定打勾的都要簽收嗎?')) more_post();else return false">打勾者批次簽收</button></th>
                                </tr>
                            </table>
                        </div>                        
                        <form id="more_post_form" action="{{ route('posts.signed_more') }}" method="post">
                            @csrf
                            <input name="posts_id" id="more_post_value" type="hidden">
                        </form>
                        <div class="card-footer d-flex flex-row justify-content-center pt-4">
                            <div class="text-center">
                                {{ $post5->links() }}
                                共 {{ count($post5) }} 筆行政公告
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Modal -->
                <div class="modal fade" id="ModalLong" tabindex="-1" aria-labelledby="ModalLongTitle" aria-hidden="true" data-mycount="{{ count($posts5_quickly) }}">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="ModalLongTitle">催收公告：</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @foreach($posts5_quickly as $post5_quickly)
                                第{{ $post5_quickly->post_no }}號「{{ str_limit($post5_quickly->title,40) }}....」已逾期，請速簽收
                                <br>
                            @endforeach
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>                        
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .alert {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            min-width: 300px;
            max-width: 600px;
            transform: translate(-50%,-50%);
            z-index: 99999;
            text-align: center;
            padding: 15px;
            border-radius: 3px;
        }

        .alert-success {
            color: #3c763d;
            background-color: #dff0d8;
            border-color: #d6e9c6;
        }

        .alert-info {
            color: #31708f;
            background-color: #d9edf7;
            border-color: #bce8f1;
        }

        .alert-warning {
            color: #8a6d3b;
            background-color: #fcf8e3;
            border-color: #faebcc;
        }

        .alert-danger {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
        }
    </style>
    <div class="alert"></div>

    @if($posts_not>0)
        <script>
            $('.alert').html('{{ $posts_not }} 則公告未簽收').addClass('alert-danger').show().delay(6000).fadeOut();
            function more_post(){
                var $boxes = $('.more_post');   
                var posts_id = [];             
                $boxes.each(function(){
                    if( $(this).is(':checked') ){
                        var name = $(this).attr('name');
                        var id = parseInt(name.match(/[0-9]+/));                        
                        posts_id.push(id);
                    }
                });   
                $('#more_post_value').val(posts_id);
                $('#more_post_form').submit();
            
            }
        </script>
    @endif
@endsection
