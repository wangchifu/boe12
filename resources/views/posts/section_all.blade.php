@extends('layouts.app')

@section('title','待審公告 | ')

@section('page-style')
    <style>
        DIV.table {
            display: table;
        }

        FORM.tr, DIV.tr {
            display: table-row;
        }

        SPAN.td {
            display: table-cell;
        }
    </style>
@endsection

@section('content')
<div class="container">
    <div class="py-5">
        <div class="">
            @include('posts.nav')
            <div class="card my-4">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        <i class="fas fa-list"></i> [{{ $sections[auth()->user()->section_id] }}] 全數公告
                    </h3>
                    <form action="{{ route('posts.do_search_in_section') }}" method="post" id="this_form">
                        @csrf
                        發佈人/主旨/內文：<input type="text" name="want" required placeholder="關鍵字">
                        <input type="submit" value="搜尋">
                    </form>
                </div>
                <div class="card-body">
                    <table class="table rwd-table" style="word-break: break-all;">
                        <thead class="thead-light">
                        <tr>
                            <th nowrap>
                                編號
                            </th>
                            <th nowrap>
                                類別
                            </th>
                            <th nowrap>
                                發佈人
                            </th>
                            <th nowrap>
                                主旨
                            </th>
                            <th nowrap>
                                創建時間<br>發佈時間
                            </th>
                            <th nowrap>
                                狀態
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($posts as $post)
                            <tr>
                                <td data-th="編號" nowrap>
                                    @if($post->post_no)
                                        {{ $post->post_no }}
                                    @else
                                        {{ $post->id }}
                                    @endif
                                </td>
                                <td data-th="類別" nowrap>
                                    {{ $categories[$post->category_id] }}
                                </td>
                                <td data-th="發佈人">
                                    {{ $sections[$post->section_id] }}<br>
                                    {{ $post->user->name }}
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
                                    @if( $post->situation ===4)
                                            <a href="javascript:open_post('{{ route('posts.show_doing_post',$post->id) }}','新視窗')">
                        <span
                            style="color:red">[公告作廢]
                        </span>
                                            <strike class="text-primary">
                                                {{ str_limit($post->title,160) }}
                                            </strike></a>
                                    @else
                                            <a href="javascript:open_post('{{ route('posts.show_doing_post',$post->id) }}','新視窗')">
                        <span style="color:#000088">
                        {{ str_limit($post->title,160) }}
                        </span>
                                        </a>
                                    @endif
                                </td>
                                <td data-th="創建時間" nowrap>
                                    {{ substr($post->created_at,0,16) }}<br>
                                    {{ substr($post->passed_at,0,16) }}
                                </td>
                                <td data-th="狀態" nowrap>
                                    {{ $situation[$post->situation] }}
                                    @if($post->situation ==3 and $post->user_id == auth()->user()->id)
                                        <a href="{{ route('posts.copy',$post->id) }}" class="btn btn-outline-primary btn-sm">
                                            複製
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <script>
                        <!--
                        function open_post(url, name) {
                            window.open(url, name, 'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=850,height=850');
                        }

                        // -->
                        $('#category').change(function(){
                            if($('#category').val() != '0'){
                                $('#select_category_form').submit();
                            }
                        });
                        $('#situation').change(function(){
                            if($('#situation').val() != 'a'){
                                $('#select_situation_form').submit();
                            }
                        });
                    </script>
                </div>
                <div class="card-footer d-flex flex-row justify-content-center pt-4">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
