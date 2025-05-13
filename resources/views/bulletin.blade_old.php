@extends('layouts.app')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="">
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="py-2">
                        {{ $category }}
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>主旨</th>
                                <th>日期</th>
                        </thead>
                        <tbody>
                            @foreach($posts as $post)
                            <tr>
                                <td>
                                    <?php
                                    $images = get_files(storage_path('app/public/post_photos/' . $post->id));
                                    ?>
                                    @if(!empty($images))
                                    <div class="media">
                                        <img class="mr-3" src="{{ asset('storage/post_photos/'.$post->id.'/'.$images[0]) }}" width="100">
                                        <div class="media-body">
                                            <a href="javascript:open_post('{{ route('posts.show',$post->id) }}','新視窗')">{{ str_limit($post->title,100) }}</a>
                                        </div>
                                    </div>
                                    @else
                                    <a href="javascript:open_post('{{ route('posts.show',$post->id) }}','新視窗')">{{ str_limit($post->title,100) }}</a>
                                    @endif
                                </td>
                                <td nowrap>
                                    {{ date_format($post->updated_at,'Y-m-d') }}
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
<!--
        function open_post(url, name) {
            window.open(url, name, 'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=850,height=800');
        }

        // -->
</script>
@endsection