@extends('layouts.app')

@section('title','404錯誤頁面')

@section('content')
<section class="section" style="margin-top: -50px;">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="title-bordered mb-5 d-flex align-items-center">
					<h1 class="h4">500錯誤頁面</h1>
					<ul class="list-inline social-icons ms-auto mr-3 d-none d-sm-block">
						<li class="list-inline-item"><a href="#"><i class="ti-facebook"></i></a>
						</li>
						<li class="list-inline-item"><a href="#"><i class="ti-twitter-alt"></i></a>
						</li>
						<li class="list-inline-item"><a href="#"><i class="ti-github"></i></a>
						</li>
					</ul>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 mb-4 mb-md-0 text-center text-md-left">
				<img loading="lazy" class="rounded-lg img-fluid" src="{{ asset('images/500.svg') }}">
			</div>
			<div class="col-lg-9 col-md-8 content text-left text-md-left">
				<p>抱歉，你所執行的頁面出現問題（500 錯誤）。這可能是因為：</p>
				<ul>
					<li>這個網址的程式有問題！</li>
					<li>資料庫哪出錯了！</li>					
				</ul>
				<p>可以的話，麻煩您記錄您做了什麼事出現這個畫面，然後通知系統維護者：</p>
				<ul>
					<li>wangchifu@hdes.chc.edu.tw</li>
					<li>和東國小 資訊組長 王老師 7552724 #114</li>					
				</ul>
			</div>
		</div>
	</div>
</section>
@endsection