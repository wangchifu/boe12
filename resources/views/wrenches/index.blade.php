@extends('layouts.app')

@section('title','系統報錯與建議 | ')

@section('content')
    <div class="container">
        <div class="py-5">
            <div class="">
                <div class="card">
                    <div class="card-header text-center">
                        <h3 class="py-2">
                            系統報錯與建議
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('wrench.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                        <div class="form-group">
                            <label class="text-primary"><strong>填 EMail 可收回覆信件</strong></label>
                            <input type="email" name="email" value="{{ auth()->user()->email }}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="text-danger"><strong>反應內容*</strong></label>
                            <textarea name="content" class="form-control" required placeholder="請詳細描述，或留公務電話聯絡"></textarea>
                        </div>
                        <div class="form-group">
                            <label>附件</label><br>
                            <input type="file" name="files[]" class="form-control" multiple>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-sm" onclick="return confirm('確定送出？')">送出</button>
                        </div>
                        </form>
                        <hr>
                        若不願意公開留言，可 email 至 {{ env('ADMIN_MAIL') }} 反應。
                        <hr>
                        <h4>已填報列表</h4>
                        @foreach($wrenches as $wrench)
                            <div class="card">
                                <div class="card-header" style="background-color: #FFCC22">
                                    @if(auth()->user()->group_id==9 or auth()->user()->admin==1)
                                        @if($wrench->show !=1 and (auth()->user()->group_id==9 or auth()->user()->admin==1))
                                            <a href="{{ route('wrench.set_show',$wrench->id) }}" class="btn btn-success btn-sm" onclick="return confirm('確定嗎？')">設為顯示</a>
                                        @endif
                                        @if($wrench->user->school)
                                            {{ $wrench->user->school }} {{ $wrench->user->title }}
                                        @endif
                                        @if($wrench->user->section_id)
                                            <?php $sections = config('boe.sections'); ?>
                                            {{ $sections[$wrench->user->section_id] }}
                                        @endif
                                        {{ $wrench->user->name }} /
                                    @else
                                        {{ mb_substr($wrench->user->name,0,1) }}** /
                                    @endif
                                    {{ $wrench->created_at}}
                                    @if(auth()->user()->group_id==9 or auth()->user()->admin==1)
                                        <a href="{{ route('wrench.destroy',$wrench->id) }}" onclick="return confirm('確定刪除？')"><i class="fas fa-times-circle text-danger"></i></a>
                                    @endif
                                </div>
                                <div class="card-body" style="background-color: #FFFFBB">
                                    @if($wrench->show ==1 or (auth()->user()->group_id==9 or auth()->user()->admin==1))
                                        @if($wrench->show !=1)
                                            <span class="text-danger">**審核中**</span><br>
                                        @endif
                                        {!! nl2br($wrench->content) !!}
                                        <?php
                                            $files = get_files(storage_path('app/public/wrenches/' . $wrench->id));
                                        ?>
                                        <br>
                                            @if(!empty($files))
                                                <small>
                                                附檔：
                                                @foreach($files as $file)
                                                    <a href="{{ route('wrench.download',['wrench_id'=>$wrench->id,'filename'=>$file]) }}"
                                                       title="點選下載附加檔案({{ $file }})">
                                                        {{ $file }}
                                                    </a>,
                                                @endforeach
                                                </small>
                                            @endif
                                    @else
                                        <span class="text-danger">**審核中**</span>
                                    @endif
                                    @if(!$wrench->reply and (auth()->user()->group_id==9 or auth()->user()->admin==1))
                                        <br>
                                        @if(!$wrench->reply)
                                        <form action="{{ route('wrench.reply') }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <textarea name="reply" class="form-control" placeholder="管理者回覆"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <button class="btn btn-primary btn-sm" onclick="return confirm('確定送出？')">送出</button>
                                            </div>
                                            <input type="hidden" name="id" value="{{ $wrench->id }}">
                                        </form>
                                        @endif
                                    @endif
                                    @if($wrench->reply)
                                        <hr>
                                            <strong>管理者回覆：</strong><br>
                                            <span class="text-danger">{!! nl2br($wrench->reply) !!}</span>
                                    @endif
                                </div>
                            </div>
                            <br>
                        @endforeach
                        <div style="text-align:right">
                            {{ $wrenches->links('layouts.simple-pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
