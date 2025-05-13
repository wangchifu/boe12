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
    <?php
        header("Refresh:300");
    ?>
    <div class="container">
        <div class="py-5">
            <div class="">
                <div class="card my-4">
                    <div class="card-header text-center">
                        <h3 class="py-2">
                            {{ $other_schools[auth()->user()->other_code] }} 公告簽收
                        </h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover" style="word-break: break-all;">
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
                                    公告日期
                                </th>
                                <th nowrap>
                                    簽收
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($post5 as $post)
                                <tr>
                                    <td nowrap>
                                        {{ $post->post_no }}
                                    </td>

                                    <td>
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
                                            <span style="color:red">[公告作廢]</span> <strike class="text-primary"><a href="javascript:open_post('{{ route('posts.show',$post->id) }}','新視窗')">{{ str_limit($post->title,100) }}</a></strike>
                                        @else
                                            <a href="javascript:open_post('{{ route('posts.show',$post->id) }}','新視窗')">
                                                <span class="text-primary">
                                                    {{ str_limit($post->title,100) }}
                                                </span>
                                            </a>
                                        @endif
                                    </td>
                                    <td nowrap>
                                        {{ array_get($sections,$post->section_id) }}<br>{{ $post -> name }}
                                    </td>
                                    <td nowrap>
                                        {{ substr($post->created_at,0,10) }}
                                    </td>
                                    <td nowrap>
                                    @if($post->signed_at==null)
                                    <form action="{{ route('posts.signed_other', ['ps_id' => $post->ps_id]) }}" method="POST" id="sign_check_form{{ $post->id }}">
                                        @method('PATCH')
                                        @csrf

                                        <button class="btn btn-success btn-sm" type="submit"  onclick="return confirm('確定簽收嗎？')">
                                            簽收
                                        </button>
                                    </form>
                                    @endif
                                    @if($post->signed_at != null)
                                        {{ userid2name($post->signed_user_id) }}
                                    @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="card-footer d-flex flex-row justify-content-center pt-4">
                            {{ $post5->links() }}
                            <div class="text-right">
                                共 {{ count($post5) }} 筆行政公告
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Modal -->
                <div class="modal fade" id="ModalLong" tabindex="-1" role="dialog" aria-labelledby="ModalLongTitle"
                     aria-hidden="true" data-mycount="{{ count($posts5_quickly) }}">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ModalLongTitle">催收公告：</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                @foreach($posts5_quickly as $post5_quickly)
                                    第{{ $post5_quickly->post_no }}號「{{ str_limit($post5_quickly->title,40) }}....」已逾期，請速簽收
                                    <br>
                                @endforeach
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
