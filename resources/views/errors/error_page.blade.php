@extends('layouts.app')

@section('title','錯誤頁面')

@section('content')
<section class="section" style="margin-top: -50px;">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="title-bordered mb-5 d-flex align-items-center">
					<h1 class="h4">你是不是在想壞壞的事？</h1>				
				</div>
			</div>
			<div class="col-lg-3 col-md-4 mb-4 mb-md-0 text-center text-md-left">
				<img loading="lazy" class="rounded-lg img-fluid" src="{{ asset('images/think_bad.svg') }}">
			</div>
			<div class="col-lg-9 col-md-8 content text-left text-md-left">
				<p>別貪心！我們來猜您想幹什麼？</p>
				<ol>
					<li>看更多的內容：您可能覺得更改網址的參數，可以讓您看到更多的文章，而這些內容不是授權給您的。</li>
					<li>修改或刪除不屬於您的內容：您可能覺得可以依樣畫葫蘆，改一下參數，就可以去偷偷修改或刪除別人的內容。</li>
					<li>其他想法：我不知道您還有什麼壞壞的想法，但是，請不要再有這些邪惡的念頭了。</li>					
				</ol>
			</div>
		</div>
	</div>
</section>
@endsection