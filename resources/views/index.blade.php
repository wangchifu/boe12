@extends('layouts.app')

@section('title','首頁')

@section('hero')
<?php
    $title_images = \App\Models\TitleImage::where('disable',null)->get();
?>
<!-- ======= Hero Slider Section ======= -->
<section id="hero-slider" class="hero-slider" style="margin-bottom:-30px; ">
    <div class="container-md" data-aos="fade-in">
      <div class="row">
        <div class="col-12">
          <div class="swiper sliderFeaturedPosts">
            <div class="swiper-wrapper">

              @foreach($title_images as $title_image)
              <div class="swiper-slide">
                @if(!empty($title_image->link ))
                <a href="{{ $title_image->link }}" class="img-bg d-flex align-items-end" style="background-image: url('{{ asset('storage/title_image/'.$title_image->photo_name) }}');" target="_blank">
                  <!--
                  <div class="img-bg-inner">
                    <h2>The Best Homemade Masks for Face (keep the Pimples Away)</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem neque est mollitia! Beatae minima assumenda repellat harum vero, officiis ipsam magnam obcaecati cumque maxime inventore repudiandae quidem necessitatibus rem atque.</p>
                  </div>
                  -->
                </a>
                @else
                <div class="img-bg d-flex align-items-end" style="background-image: url('{{ asset('storage/title_image/'.$title_image->photo_name) }}');">
                  <!--
                  <div class="img-bg-inner">
                    <h2>The Best Homemade Masks for Face (keep the Pimples Away)</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem neque est mollitia! Beatae minima assumenda repellat harum vero, officiis ipsam magnam obcaecati cumque maxime inventore repudiandae quidem necessitatibus rem atque.</p>
                  </div>
                  -->
                </div>
                @endif
              </div>
              @endforeach
              
            </div>
            <div class="custom-swiper-button-next">
              <span class="bi-chevron-right"></span>
            </div>
            <div class="custom-swiper-button-prev">
              <span class="bi-chevron-left"></span>
            </div>

            <div class="swiper-pagination"></div>
          </div>
        </div>
      </div>
    </div>
  </section><!-- End Hero Slider Section -->
@endsection

@section('content')

<div class="container">
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
  <ul class="nav nav-tabs col-12" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">活動成果</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">新聞快訊</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">一般公告。競賽訊息</button>
    </li>
  </ul>
</div>

<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
    <?php
    $n=0;
    foreach($post3 as $post){
      $id3[$n] = $post->id;
      $images3[$n] = get_files(storage_path('app/public/post_photos/' . $post->id));
      $title3[$n] = $post->title;
      $content3[$n] = str_replace('&nbsp;','',str_limit(strip_tags($post->content),600));
      $passed_at3[$n] = $post->passed_at;
      $user3[$n] = $post->user->name;
      $user_section3[$n] = $post->user->section_id;
      $n++;
    }
    $sections = config('boe.sections');
  ?>
  
  <section id="posts" class="posts">
    <div class="container" data-aos="fade-up">
      <div class="section-header d-flex justify-content-between align-items-center mb-5">
        <h2>活動成果</h2>
        <div><a href="{{ url('/bulletin').'/4' }}" class="more">更多活動成果</a></div>
      </div>
      <div class="row g-5">
        <div class="col-lg-4">
          <div class="post-entry-1 lg">
            <a href="javascript:open_post('{{ route('posts.show',$id3[0]) }}','新視窗')">
              @if(!empty($images3[0]))
                <img src="{{ asset('storage/post_photos/'.$id3[0].'/'.$images3[0][0]) }}" class="img-fluid" alt="">
              @else
                <img src="{{ asset('images/image.png') }}" class="img-fluid" alt="">
              @endif
            </a>
            <div class="post-meta"><span class="date">活動成果</span> <span class="mx-1">&bullet;</span> <span>{{ $passed_at3[0] }}</span></div>
            <h2><a href="javascript:open_post('{{ route('posts.show',$id3[0]) }}','新視窗')">{{ $title3[0]}}</a></h2>
            <p class="mb-4 d-block">{{ $content3[0] }}</p>
  
            <div class="d-flex align-items-center author">
              <div class="photo"><img src="{{ asset('images/user.png') }}" alt="" class="img-fluid"></div>
              <div class="name">
                <?php  
                $sections[$user_section3[0]] = (isset($sections[$user_section3[0]]))?$sections[$user_section3[0]]:"";
                ?>
                <h3 class="m-0 p-0">{{ $sections[$user_section3[0]] }} {{ $user3[0] }}</h3>
              </div>
            </div>
          </div>
  
        </div>
  
        <div class="col-lg-8">
          <div class="row g-5">
            <div class="col-lg-4 border-start custom-border">
              <?php $n = 3; ?>
                @for($n=1;$n<4;$n++)
                  <div class="post-entry-1">
                    <a href="javascript:open_post('{{ route('posts.show',$id3[$n]) }}','新視窗')">
                      @if(!empty($images3[$n][0]))
                        <img src="{{ asset('storage/post_photos/'.$id3[$n].'/'.$images3[$n][0]) }}" class="img-fluid" alt="">
                      @else
                        <img src="{{ asset('images/image.png') }}" class="img-fluid" alt="">
                      @endif
                    </a>
                    <div class="post-meta"><span class="date">活動成果</span> <span class="mx-1">&bullet;</span> <span>{{ $passed_at3[$n] }}</span></div>
                    <h2><a href="javascript:open_post('{{ route('posts.show',$id3[$n]) }}','新視窗')">{{ $title3[$n] }}</a></h2>
                  </div>
                  <hr>
                @endfor
            </div>
            <div class="col-lg-4 border-start custom-border">
              @for($n=4;$n<7;$n++)
                  <div class="post-entry-1">
                    <a href="javascript:open_post('{{ route('posts.show',$id3[$n]) }}','新視窗')">
                      @if(!empty($images3[$n][0]))
                        <img src="{{ asset('storage/post_photos/'.$id3[$n].'/'.$images3[$n][0]) }}" class="img-fluid" alt="">
                      @else
                        <img src="{{ asset('images/image.png') }}" class="img-fluid" alt="">
                      @endif
                    </a>
                    <div class="post-meta"><span class="date">活動成果</span> <span class="mx-1">&bullet;</span> <span>{{ $passed_at3[$n] }}</span></div>
                    <h2><a href="javascript:open_post('{{ route('posts.show',$id3[$n]) }}','新視窗')">{{ $title3[$n] }}</a></h2>
                  </div>
                  <hr>
                @endfor
            </div>
  
            <!-- Trending Section -->
            <div class="col-lg-4">
  
              <div class="trending">
                <ul class="trending-post">
                  <?php $k=1; ?>
                  @for($n=7;$n<12;$n++)
                  <li>
                    <a href="javascript:open_post('{{ route('posts.show',$id3[$n]) }}','新視窗')">
                      <span class="number">{{ $k }}</span>
                      <h3>{{ $title3[$n] }}</h3>
                      <span>{{ $passed_at3[$n] }}</span>
                      <span class="author">{{ $user3[$n] }}</span>
                    </a>
                  </li>
                  <?php $k++; ?>
                  @endfor
                </ul>
              </div>
            </div> <!-- End Trending Section -->
          </div>
        </div>
  
      </div> <!-- End .row -->
    </div>
  </section> <!-- End Post Grid Section -->

  </div>
  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
      <?php
      $n=0;
      foreach($post4 as $post){
        $id4[$n] = $post->id;
        $images4[$n] = get_files(storage_path('app/public/post_photos/' . $post->id));
        $title4[$n] = $post->title;
        $content4[$n] = str_replace('&nbsp;','',str_limit(strip_tags($post->content),200));
        $passed_at4[$n] = $post->passed_at;
        $user4[$n] = $post->user->name;
        $user_section4[$n] = $post->user->section_id;
        $n++;
      }
    ?>
    <section class="category-section">
      <div class="container" data-aos="fade-up">
    
        <div class="section-header d-flex justify-content-between align-items-center mb-5">
          <h2>新聞快訊</h2>
          <div><a href="{{ url('/bulletin').'/4' }}" class="more">更多新聞快訊</a></div>
        </div>
    
        <div class="row">
          <div class="col-md-9">
    
            <div class="d-lg-flex post-entry-2">
              <a href="javascript:open_post('{{ route('posts.show',$id4[0]) }}','新視窗')" class="me-4 thumbnail mb-4 mb-lg-0 d-inline-block">
                @if(!empty($images4[0][0]))
                  <img src="{{ asset('storage/post_photos/'.$id4[0].'/'.$images4[0][0]) }}" class="img-fluid" alt="">
                @else
                  <img src="{{ asset('images/image.png') }}" class="img-fluid" alt="">
                @endif
              </a>
              <div>
                <div class="post-meta"><span class="date">新聞快訊</span> <span class="mx-1">&bullet;</span> <span>{{ $passed_at4[0] }}</span></div>
                <h3><a href="javascript:open_post('{{ route('posts.show',$id4[0]) }}','新視窗')">{{ $title4[0] }}</a></h3>
                <p>{{ $content4[0] }}</p>
                <div class="d-flex align-items-center author">
                  <div class="photo"><img src="{{ asset('images/user.png') }}" alt="" class="img-fluid"></div>
                  <div class="name">
                    <?php  
                      $sections[$user_section4[0]] = (isset($sections[$user_section4[0]]))?$sections[$user_section4[0]]:"";
                    ?>
                    <h3 class="m-0 p-0">{{ $sections[$user_section4[0]] }}  {{ $user4[0] }}</h3>
                  </div>
                </div>
              </div>
            </div>
    
            <div class="row">
              <div class="col-lg-4">
                <div class="post-entry-1 border-bottom">
                  <a href="javascript:open_post('{{ route('posts.show',$id4[1]) }}','新視窗')">
                    @if(!empty($images4[1][0]))
                      <img src="{{ asset('storage/post_photos/'.$id4[1].'/'.$images4[1][0]) }}" class="img-fluid" alt="">
                    @else
                      <img src="{{ asset('images/image.png') }}" class="img-fluid" alt="">
                    @endif
                  </a>
                  <div class="post-meta"><span class="date">新聞快訊</span> <span class="mx-1">&bullet;</span> <span>{{ $passed_at4[1] }}</span></div>
                  <h2 class="mb-2"><a href="javascript:open_post('{{ route('posts.show',$id4[1]) }}','新視窗')">{{ $title4[1] }}</a></h2>
                  <?php  
                    $sections[$user_section4[1]] = (isset($sections[$user_section4[1]]))?$sections[$user_section4[1]]:"";
                  ?>
                  <span class="author mb-3 d-block">{{ $sections[$user_section4[1]] }}  {{ $user4[1] }}</span>
                  <p class="mb-4 d-block">{{ str_limit($content4[1],100) }}</p>
                </div>
                <div class="post-entry-1 border-bottom">
                  <div class="post-meta"><span class="date">新聞快訊</span> <span class="mx-1">&bullet;</span> <span>{{ $passed_at4[2] }}</span></div>
                  <h2 class="mb-2"><a href="javascript:open_post('{{ route('posts.show',$id4[2]) }}','新視窗')">{{ $title4[2] }}</a></h2>
                  <?php  
                    $sections[$user_section4[2]] = (isset($sections[$user_section4[2]]))?$sections[$user_section4[2]]:"";
                  ?>
                  <span class="author mb-3 d-block">{{ $sections[$user_section4[2]] }}  {{ $user4[2] }}</span>
                  <p class="mb-4 d-block">{{ str_limit($content4[2],100) }}</p>
                </div>
              </div>
              <div class="col-lg-8">
                <div class="post-entry-1">
                  <a href="javascript:open_post('{{ route('posts.show',$id4[3]) }}','新視窗')">
                    @if(!empty($images4[3][0]))
                      <img src="{{ asset('storage/post_photos/'.$id4[3].'/'.$images4[3][0]) }}" class="img-fluid" alt="">
                    @else
                      <img src="{{ asset('images/image.png') }}" class="img-fluid" alt="">
                    @endif
                  </a>
                  <div class="post-meta"><span class="date">新聞快訊</span> <span class="mx-1">&bullet;</span> <span>{{ $passed_at4[3] }}</span></div>
                  <h2 class="mb-2"><a href="javascript:open_post('{{ route('posts.show',$id4[3]) }}','新視窗')">{{ $title4[3] }}</a></h2>
                  <?php  
                    $sections[$user_section4[3]] = (isset($sections[$user_section4[3]]))?$sections[$user_section4[3]]:"";
                  ?>
                  <span class="author mb-3 d-block">{{ $sections[$user_section4[3]] }}  {{ $user4[3] }}</span>
                  <p class="mb-4 d-block">{{ $content4[3] }}</p>
                </div>
              </div>
            </div>
          </div>
    
          <div class="col-md-3">
            @for($n=4;$n<10;$n++)
            <div class="post-entry-1 border-bottom">
              <div class="post-meta"><span class="date">新聞快訊</span> <span class="mx-1">&bullet;</span> <span>{{ $passed_at4[$n] }}</span></div>
              <h2 class="mb-2"><a href="javascript:open_post('{{ route('posts.show',$id4[$n]) }}','新視窗')">{{ $title4[$n] }}</a></h2>
              <?php  
                $sections[$user_section4[$n]] = (isset($sections[$user_section4[$n]]))?$sections[$user_section4[$n]]:"";
              ?>
              <span class="author mb-3 d-block">{{ $sections[$user_section4[$n]] }} {{ $user4[$n] }}</span>
            </div>
            @endfor
          </div>
        </div>
      </div>
    </section><!-- End Culture Category Section -->
  </div>

  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
    <!-- ======= Search Results ======= -->
    <section id="search-result" class="search-result">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <div class="section-header d-flex justify-content-between align-items-center mb-5">
              <h2>一般公告</h2>
              <div><a href="{{ url('/bulletin').'/1' }}" class="more">更多一般公告</a></div>
            </div>
            @foreach($post1 as $post)
            <?php 
              $images1 = get_files(storage_path('app/public/post_photos/' . $post->id));
            ?>
            <div class="d-md-flex post-entry-2 small-img">
              <a href="javascript:open_post('{{ route('posts.show',$post->id) }}','新視窗')" class="me-4 thumbnail">
                @if(!empty($images1[0]))
                  <img src="{{ asset('storage/post_photos/'.$post->id.'/'.$images1[0]) }}" class="img-fluid" alt="">
                @else
                  <img src="{{ asset('images/image.png') }}" class="img-fluid" alt="">
                @endif
              </a>
              <div>
                <div class="post-meta"><span class="date">一般公告</span> <span class="mx-1">&bullet;</span> <span>{{ $post->passed_at }}</span></div>
                <h3><a href="javascript:open_post('{{ route('posts.show',$post->id) }}','新視窗')">{{ $post->title }}</a></h3>
                <p>{{ str_replace('&nbsp;','',str_limit(strip_tags($post->content),500)) }}</p>
                <div class="d-flex align-items-center author">
                  <div class="photo"><img src="assets/img/person-2.jpg" alt="" class="img-fluid"></div>
                  <div class="name">
                    <?php  
                      $sections[$post->user->section_id] = (isset($sections[$post->user->section_id]))?$sections[$post->user->section_id]:"";
                    ?>
                    <h3 class="m-0 p-0">{{ $sections[$post->user->section_id] }} {{ $post->user->name }}</h3>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
          </div>
          <div class="col-md-6">
            <div class="section-header d-flex justify-content-between align-items-center mb-5">
              <h2>競賽訊息</h2>
              <div><a href="{{ url('/bulletin').'/2' }}" class="more">更多競賽訊息</a></div>
            </div>
            @foreach($post2 as $post)
            <?php 
              $images4 = get_files(storage_path('app/public/post_photos/' . $post->id));
            ?>
            <div class="d-md-flex post-entry-2 small-img">
              <a href="javascript:open_post('{{ route('posts.show',$post->id) }}','新視窗')" class="me-4 thumbnail">
                @if(!empty($images4[0]))
                  <img src="{{ asset('storage/post_photos/'.$post->id.'/'.$images4[0]) }}" class="img-fluid" alt="">
                @else
                  <img src="{{ asset('images/image.png') }}" class="img-fluid" alt="">
                @endif
              </a>
              <div>
                <div class="post-meta"><span class="date">競賽訊息</span> <span class="mx-1">&bullet;</span> <span>{{ $post->passed_at }}</span></div>
                <h3><a href="javascript:open_post('{{ route('posts.show',$post->id) }}','新視窗')">{{ $post->title }}</a></h3>
                <p>{{ str_replace('&nbsp;','',str_limit(strip_tags($post->content),500)) }}</p>
                <div class="d-flex align-items-center author">
                  <div class="photo"><img src="assets/img/person-2.jpg" alt="" class="img-fluid"></div>
                  <div class="name">
                    <?php  
                      $sections[$post->user->section_id] = (isset($sections[$post->user->section_id]))?$sections[$post->user->section_id]:"";
                    ?>
                    <h3 class="m-0 p-0">{{ $sections[$post->user->section_id] }} {{ $post->user->name }}</h3>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </section> <!-- End Search Result -->
  </div>
</div>


<!-- ======= Post Grid Section ======= -->





    


<script>
  <!--
  function open_post(url,name)
  {
      window.open(url,name,'statusbar=no,scrollbars=yes,status=yes,resizable=yes,width=850,height=650');
  }
</script>
@endsection
