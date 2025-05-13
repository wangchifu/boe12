@extends('layouts.app')

@section('title',$category.' | ')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="">
            <div class="card">
                <div class="card-header text-center bg-light">
                    <h3 class="py-2">
                        {{ $category }}
                    </h3>
                    <?php
                    $key = rand(100,999);
                    session(['search' => $key]);
                    ?>
                    <form action="{{ route('bulletin_search') }}" method="post" id="this_form">
                        @csrf
                        發佈人/主旨/內文：<input type="text" name="want" required placeholder="關鍵字">
                        <input type="text" name="check" placeholder="請輸入：{{ session('search') }}" required maxlength="3">
                        <input type="hidden" name="category_id" value="{{ $category_id }}">
                        <input type="submit" value="搜尋">
                    </form>
                    @include('layouts.errors')
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>主旨</th>
                            <th nowrap>發佈<br>時間</th>
                        </thead>
                        <tbody>
                        @foreach($posts as $post)
                            <tr>
                                <td>
                                    <a href="javascript:open_post('{{ route('posts.show',$post->id) }}','新視窗')" class="venobox" data-vbtype="iframe">{{ str_limit($post->title,100) }}</a>
                                </td>
                                <td style="word-break: break-all;">
                                    {{ substr($post->passed_at,0,16) }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-flex flex-row justify-content-center">
                    <div class="pt-3">{{ $posts->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    <!--
    function open_post(url, name) {
        window.open(url, name, 'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=850,height=650');
    }

    // -->
</script>
@endsection
