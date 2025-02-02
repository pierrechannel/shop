@extends('layouts.app')
@section('body-class', 'page-home')

@push('header')
  <script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}" defer></script>
  <link rel="stylesheet" href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}">

  <style>
    .hero-slider {
      position: relative;
      margin-bottom: 3rem;
      border-radius: 0.5rem;
      overflow: hidden;
    }

    .hero-slider .swiper-slide {
      height: 480px;
      background-size: cover;
      background-position: center;
    }

    .hero-slider .swiper-pagination-bullet {
      width: 12px;
      height: 12px;
      background: rgba(255, 255, 255, 0.9);
    }

    .module-title-wrap {
      text-align: center;
      margin-bottom: 3rem;
    }

    .module-title {
      font-size: 2rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
      position: relative;
      display: inline-block;
    }

    .module-title::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 60px;
      height: 3px;
      background: currentColor;
    }

    .module-sub-title {
      color: #666;
      font-size: 1.1rem;
    }

    .nav-tabs {
      border: none;
      justify-content: center;
      margin-bottom: 2rem;
      gap: 1rem;
    }

    .nav-tabs .nav-link {
      border: none;
      padding: 0.75rem 1.5rem;
      border-radius: 2rem;
      background: #f5f5f5;
      transition: all 0.3s ease;
    }

    .nav-tabs .nav-link.active {
      background: #333;
      color: white;
    }

    .product-grid {
      margin-bottom: 4rem;
    }

    .blog-card {
      background: white;
      border-radius: 0.5rem;
      overflow: hidden;
      box-shadow: 0 2px 15px rgba(0,0,0,0.1);
      transition: transform 0.3s ease;
    }

    .blog-card:hover {
      transform: translateY(-5px);
    }

    .blog-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }

    .blog-content {
      padding: 1.5rem;
    }
  </style>
@endpush

@section('content')
  @hookinsert('home.content.top')

  <div class="module-content">
    @if (system_setting('slideshow'))
      <section class="container">
        <div class="hero-slider">
          <div class="swiper" id="hero-swiper">
            <div class="swiper-wrapper">
              @foreach (system_setting('slideshow', []) as $slide)
                @if ($slide['image'][front_locale_code()] ?? false)
                  <div class="swiper-slide" style="background-image: url('{{ image_origin($slide['image'][front_locale_code()]) }}')">
                    @if($slide['link'])
                      <a href="{{ $slide['link'] }}" class="d-block w-100 h-100"></a>
                    @endif
                  </div>
                @endif
              @endforeach
            </div>
            <div class="swiper-pagination"></div>
          </div>
        </div>
      </section>

      <script>
        document.addEventListener('DOMContentLoaded', function() {
          new Swiper('#hero-swiper', {
            loop: true,
            effect: 'fade',
            speed: 1000,
            autoplay: {
              delay: 5000,
              disableOnInteraction: false,
            },
            pagination: {
              el: '.swiper-pagination',
              clickable: true,
            },
          });
        });
      </script>
    @endif

    @hookinsert('home.swiper.after')

    <section class="container">
      <div class="featured-products mb-5">
        <div class="module-title-wrap">
          <h2 class="module-title">{{ __('front/home.feature_product') }}</h2>
          <p class="module-sub-title">{{ __('front/home.feature_product_text') }}</p>
        </div>

        <ul class="nav nav-tabs" role="tablist">
          @foreach ($tab_products as $item)
            <li class="nav-item" role="presentation">
              <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                      data-bs-toggle="tab"
                      data-bs-target="#product-tab-{{ $loop->iteration }}"
                      type="button"
                      role="tab">
                {{ $item['tab_title'] }}
              </button>
            </li>
          @endforeach
        </ul>

        <div class="tab-content">
          @foreach ($tab_products as $item)
            <div class="tab-pane fade show {{ $loop->first ? 'active' : '' }}"
                 id="product-tab-{{ $loop->iteration }}"
                 role="tabpanel">
              <div class="row g-4 product-grid">
                @foreach ($item['products'] as $product)
                  <div class="col-6 col-md-4 col-lg-3">
                    @include('shared.product')
                  </div>
                @endforeach
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </section>

    <section class="container">
      <div class="latest-news">
        <div class="module-title-wrap">
          <h2 class="module-title">{{ __('front/home.news_blog') }}</h2>
          <p class="module-sub-title">{{ __('front/home.news_blog_text') }}</p>
        </div>

        <div class="row g-4">
          @foreach ($news as $new)
            <div class="col-6 col-md-4 col-lg-3">
              <article class="blog-card">
                @include('shared.blog', ['item' => $new])
              </article>
            </div>
          @endforeach
        </div>
      </div>
    </section>
  </div>

  @hookinsert('home.content.bottom')
@endsection
