<!--當科長審核時，會出現修改、退回、核准的button-->
@if($uri_name == 'review')
    <a href="{{ route('posts.eduadminedit',$post->id) }}"><button class="btn btn-outline-danger btn-sm">修改</button></a>
    <button class="btn btn-outline-success btn-sm" onclick="if(confirm('您確定要退回嗎?')) $('#return{{ $post->id }}').submit();else return false">退回</button>
    <button class="btn btn-outline-info btn-sm" onclick="if(confirm('確定核准嗎？')){change_button({{ $post->id }});}else return false;" id="post_b{{ $post->id }}">核准</button>
    <form id="return{{ $post->id }}" class="tr" action="{{ route('posts.return',$post->id) }}" method="post">
        @csrf
        {{ method_field('PATCH') }}
    </form>
    <!--假裝核准，但其實是將公告先寫到post_schools資料表，再導到posts.approve執行update 更新situation狀態為3-->
    <form id="ok{{ $post->id }}" class="tr" action="{{ route('posts.addPostSchools',$post->id) }}" method="post">
        @csrf
        {{ method_field('POST') }}
    </form>

    <!--當送審中時，會依不同的進度，顯示可使用的按鈕-->
@elseif($uri_name == 'reviewing')
    @if ( $post->situation  === 0)
        <a href="{{ route('posts.edit',$post->id) }}">
            <button class="btn btn-outline-danger btn-sm">修改</button>
        </a>
        <button class="btn btn-outline-dark btn-sm" onclick="if(confirm('您確定送出嗎?')) $('#del{{ $post->id }}').submit();else return false">刪除</button>
        <button class="btn btn-outline-primary btn-sm" onclick="if(confirm('您確定送出嗎?')) $('#resend{{ $post->id }}').submit();else return false">再次送審</button>
        <form id="del{{ $post->id }}" class="tr" action="{{ route('posts.destroy',$post->id) }}" method="post">
            @csrf
            {{ method_field('DELETE') }}
        </form>
        <form id="resend{{ $post->id }}" class="tr" action="{{ route('posts.resend',$post->id) }}" method="post">
            @csrf
            {{ method_field('PATCH') }}
        </form>
    @endif

    @if ( $post->situation  === -1)
        <a href="{{ route('posts.edit',$post->id) }}">
            <button class="btn btn-outline-danger btn-sm">修改</button>
        </a>
        <button class="btn btn-outline-dark btn-sm" onclick="if(confirm('您確定送出嗎?')) $('#del{{ $post->id }}').submit();else return false">刪除</button>
        <form id="del{{ $post->id }}" class="tr" action="{{ route('posts.destroy',$post->id) }}" method="post">
            @csrf
            {{ method_field('DELETE') }}
            <a href="#" onclick="return window.confirm('確定刪除？');">
            </a>
        </form>
    @endif
    @if ( $post->situation  === 2)
        <form class="tr" action="{{ route('posts.destroy',$post->id) }}" method="post">
            @csrf
            {{ method_field('DELETE') }}
            <a href="#" onclick="return window.confirm('確定刪除？');">
                <button class="btn btn-outline-dark btn-sm">刪除</button>
            </a>
        </form>
    @endif
    <!--當審核通過時，只能作廢一途-->
@elseif($uri_name == 'passing')
    @if ( $post->situation  === 3)
        <form class="tr" action="{{ route('posts.obsolete',$post->id) }}" method="post">
            @csrf
            {{ method_field('PATCH') }}
            <a href="#" onclick="return window.confirm('確定作廢？');">
                <button class="btn btn-outline-secondary btn-sm">作廢</button>
            </a>
            <a href="{{ route('posts.copy',$post->id) }}" class="btn btn-outline-primary btn-sm">
                複製
            </a>
        </form>
    @endif

@endif
<script>
    function change_button(id){
        $("#post_b"+id).removeAttr('onclick');
        $("#post_b"+id).attr('disabled','disabled');
        $("#post_b"+id).addClass('disabled');
        $("#ok"+id).submit();
    }
</script>
