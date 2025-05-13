@extends('layouts.app') 
@section('title','學校列表 | ') 
@section('content')


<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card mt-4">
                <div class="card-body">					
					@foreach($town_ships as $k=>$v)
						<h4><i class="fab fa-fort-awesome"></i> {{ $v }}</h4>
						@foreach($all_school[$k] as $k1=>$v2)
							<?php 
							//去掉潭漧國小074752
							if($k1=="074752"){
								continue;
							}
							?>
							@if($v2['type'] == 1)
								<a href="{{ route('introductions.school_show',$k1) }}" class="btn btn-primary btn-sm" style="margin:3px">{{ $v2['school'] }} <span class="badge badge-light">國小</span></a>
							@endif
							@if($v2['type'] == 2)
								<a href="{{ route('introductions.school_show',$k1) }}" class="btn btn-success btn-sm" style="margin:3px">{{ $v2['school'] }} <span class="badge badge-light">國中</span></a>
							@endif
							@if($v2['type'] == 12)
								<a href="{{ route('introductions.school_show',$k1) }}" class="btn btn-info btn-sm" style="margin:3px">{{ $v2['school'] }} <span class="badge badge-light">國中小</span></a>
							@endif
							@if($v2['type'] == 23)
								<a href="{{ route('introductions.school_show',$k1) }}" class="btn btn-warning btn-sm" style="margin:3px">{{ $v2['school'] }} <span class="badge badge-light">國高中</span></a>
							@endif
						@endforeach
						<br><br>
					@endforeach
				</div>
			</div>
		</div>
    </div>
</div>


@endsection