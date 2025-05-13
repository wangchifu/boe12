@extends('layouts.app')

@section('title','首頁')

@section('hero')
<?php
$title_images = \App\TitleImage::where('disable', null)->get();
?>
@if(!empty($title_images))
<!-- ======= Hero Section ======= -->
<section id="hero">
    <div class="hero-container">
        <div id="heroCarousel" data-bs-interval="2000" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <ol class="carousel-indicators" id="hero-carousel-indicators"></ol>
            <div class="carousel-inner" role="listbox">
                <?php $n = 0 ?>
                @foreach($title_images as $title_image)
                <?php $active = ($n == 0) ? "active" : ""; ?>
                @if(!empty($title_image->link))
                <a href="{{ $title_image->link }}" target="_blank">
                    @endif
                    <div class="carousel-item {{ $active }}" style="background-image: url({{ asset('storage/title_image/'.$title_image->photo_name) }})">
                    </div>
                    @if(!empty($title_image->link))
                </a>
                @endif
                <?php $n++; ?>
                @endforeach
            </div>
            <a class="carousel-control-prev" href="#heroCarousel" role="button" data-bs-slide="prev">
                <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
            </a>
            <a class="carousel-control-next" href="#heroCarousel" role="button" data-bs-slide="next">
                <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
            </a>
        </div>
    </div>
</section><!-- End Hero -->
@endif
@endsection

@section('content')
@if($marquees->first())
<div class="alert alert-warning mt-2">
    <marquee behavior="scroll" direction="up" scrollamount="1" height="30px">
        @foreach($marquees as $marquee)
        <p>
            {{ $marquee->title }}
        </p>
        @endforeach
    </marquee>
</div>
@endif

<div class="row mt-1">
    <nav class="col">
        <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active  " style="background-color:#ff5252;font-size:1.1rem;color:#fff" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="true">活動成果 Splendid Event</a>
            <a class="nav-item nav-link btn" style="background-color:#1de9b6;font-size:1.1rem;color:#fff" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="false">一般公告 General Notice</a>
            <a class="nav-item nav-link btn" style="background-color:#fbc02d;font-size:1.1rem;color:#fff" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">競賽訊息 Competition Message</a>
            <a class="nav-item nav-link btn" style="background-color:#64b5f6;font-size:1.1rem;color:#fff" id="nav-about-tab" data-toggle="tab" href="#nav-about" role="tab" aria-controls="nav-about" aria-selected="false">新聞快訊 News Flash</a>
        </div>
    </nav>
    <div class="tab-content px-sm-0" id="nav-tabContent">
        <div class="tab-pane fade " id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <section id="posts1" class="about-lists">
                <div class="container">
                    <div class="section-bg ">
                        <div class="section-title">
                            <h2>一般公告 <a href="{{ url('bulletin/1') }}"><i class="fas fa-angle-double-right"></i></a></h2>
                        </div>
                        <div class="row no-gutters" style="margin-top: -10px;">
                            <?php $n = 0; ?>
                            @if(!empty($title_images))
                            @foreach($post1 as $post)
                            @if($n>0)
                            <div class="col-lg-4 col-md-6 content-item" data-aos="fade-up">
                                <h4 style="word-break: break-all">{{ str_limit($post->title,38) }}</h4>
                                <p>
                                    {{ $post->passed_at }}<br>
                                    {{ str_limit(strip_tags($post->content),200) }}<br>
                                    <a href="{{ route('posts.show',$post->id) }}" class="venobox btn btn-info btn-sm" data-vbtype="iframe">繼續閱讀...</a>
                                </p>
                            </div>
                            @endif
                            <?php $n++; ?>
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
            <section id="posts2" class="about-lists">
                <div class="container">
                    <div class="section-bg">
                        <div class="section-title">
                            <h2>競賽訊息 <a href="{{ url('bulletin/2') }}"><i class="fas fa-angle-double-right"></i></a></h2>
                        </div>

                        <div class="row d-flex align-items-stretch">
                            @foreach($post2 as $post)
                            <div class="col-lg-6 faq-item" data-aos="fade-up">
                                <h4>{{ str_limit($post->title,60) }}</h4>
                                <p>
                                    <span>{{ $post->passed_at }}</span><br>
                                    {{ str_limit(strip_tags($post->content),200) }}<br>
                                    <a href="{{ route('posts.show',$post->id) }}" class="venobox btn btn-outline-secondary btn-sm" data-vbtype="iframe">繼續閱讀...</a>
                                </p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="tab-pane fade show active" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
            <section id="posts3" class="about-lists">
                <div class="container">
                    <div class="section-bg">
                        @if(!empty($post3) && !empty($post3->first()))
                        <?php
                        $post3_first = $post3->first();
                        ?>
                        <div class="row no-gutters">
                            <div class="col-lg-6 video-box">
                                <?php
                                $images = get_files(storage_path('app/public/post_photos/' . $post3_first->id));
                                ?>
                                @if(!empty($images))
                                <img src="{{ asset('storage/post_photos/'.$post3_first->id.'/'.$images[0]) }}" class="img-fluid" alt="">
                                @else
                                <i class="bx bx-image h1"></i>
                                @endif
                            </div>

                            <div class="col-lg-6 d-flex flex-column justify-content-center about-content">
                                <div class="section-title">
                                    <h2>活動成果 <a href="{{ url('bulletin/3') }}"><i class="fas fa-angle-double-right"></i></a></h2>
                                    <h4 class="title">{{ str_limit($post3_first->title,60) }}</h4>
                                    <p class="description">
                                        <span>{{ $post3_first->passed_at }}</span><br>
                                        {{ str_limit(strip_tags($post3_first->content),400) }}<br>
                                        <a href="{{ route('posts.show',$post3_first->id) }}" class="venobox btn btn-outline-primary btn-sm" data-vbtype="iframe">繼續閱讀...</a>
                                    </p>
                                </div>
                            </div>

                            <?php $n = 0; ?>
                            @foreach($post3 as $post)
                            @if($n>0)
                            <div class="col-lg-6 video-box">
                                <?php
                                $images = get_files(storage_path('app/public/post_photos/' . $post->id));
                                ?>
                                @if(!empty($images))
                                <img src="{{ asset('storage/post_photos/'.$post->id.'/'.$images[0]) }}" class="img-fluid" alt="">
                                @else
                                <i class="bx bx-image h1"></i>
                                @endif
                            </div>

                            <div class="col-lg-6 d-flex flex-column justify-content-center about-content">
                                <div class="section-title">
                                    <h4 class="title">{{ str_limit($post->title,60) }}</h4>
                                    <p class="description">
                                        <span>{{ $post->passed_at }}</span><br>
                                        {{ str_limit(strip_tags($post->content),400) }}<br>
                                        <a href="{{ route('posts.show',$post->id) }}" class="venobox btn btn-outline-primary btn-sm" data-vbtype="iframe">繼續閱讀...</a>
                                    </p>
                                </div>
                            </div>
                            @endif
                            <?php $n++; ?>
                            @endforeach
                            @endif
                            <hr>

                        </div>

                    </div>
                </div>
            </section>
        </div>
        <div class="tab-pane fade" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
            <section id="posts4" class="services">
                <div class="container">
                    <div class="section-bg">
                        <div class="section-title">
                            <h2>新聞快訊</h2>
                        </div>

                        <div class="row no-gutters">
                            <?php
                            $icons = [
                                'icofont-newspaper',
                                'icofont-megaphone-alt',
                                'icofont-magic-alt',
                                'icofont-envelope-open',
                                'icofont-info-circle',
                                'icofont-interface',
                            ];
                            $n = 0;
                            ?>

                            @foreach($post4 as $post)
                            <div class="col-lg-6 col-md-6 icon-box " data-aos="fade-up">
                                <div class="icon"><i class="{{ $icons[$n] }}"></i></div>
                                <h4 class="title"><a href="{{ route('posts.show',$post->id) }}" class="venobox" data-vbtype="iframe">{{ str_limit($post->title,60) }}</a></h4>
                                <p class="description">
                                    <span>{{ $post->passed_at }}</span><br>
                                    {{ str_limit(strip_tags($post->content),200) }}<br>
                                    <a href="{{ route('posts.show',$post->id) }}" class="venobox btn btn-outline-dark btn-sm" data-vbtype="iframe">繼續閱讀...</a>
                                </p>
                            </div>
                            <?php $n++; ?>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

</div>
</div>
</div>
</div>










@endsection