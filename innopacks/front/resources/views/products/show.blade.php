@extends('layouts.app')
@section('body-class', 'page-product')

@push('header')
  <script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}">
  <script src="{{ asset('vendor/photoswipe/umd/photoswipe.umd.min.js') }}"></script>
  <script src="{{ asset('vendor/photoswipe/umd/photoswipe-lightbox.umd.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/photoswipe/photoswipe.css') }}">
@endpush

@section('content')
  <x-front-breadcrumb type="product" :value="$product"/>

  @hookinsert('product.show.top')

  <div class="container">
    <div class="page-product-top py-4">
      <div class="row g-4">
        <div class="col-12 col-lg-6 product-left-col">
          <div class="product-images position-relative">
            <!-- Sticky Gallery for Desktop -->
            <div class="product-gallery-wrapper position-sticky" style="top: 2rem;">
              <div class="main-product-img mb-3 rounded-3 overflow-hidden">
                <img src="{{ $product->image_url }}" class="img-fluid w-100 hover:scale-105 transition-transform"
                     alt="{{ $product->translation->name }}">
              </div>

              <div class="sub-product-img">
                <div class="swiper" id="sub-product-img-swiper">
                  <div class="swiper-wrapper">
                    @foreach($product->images as $image)
                      <div class="swiper-slide">
                        <a href="{{ image_resize($image->path, 800, 800) }}"
                           data-pswp-width="1200"
                           data-pswp-height="1200"
                           class="rounded-2 overflow-hidden d-block">
                          <img src="{{ image_resize($image->path) }}"
                               class="img-fluid w-100 hover:opacity-90 transition-opacity"
                               alt="{{ $product->translation->name }} - Image {{ $loop->iteration }}">
                        </a>
                      </div>
                    @endforeach
                  </div>

                  <div class="sub-product-btn">
                    <button class="sub-product-prev btn btn-light rounded-circle shadow-sm">
                      <i class="bi bi-chevron-compact-up"></i>
                    </button>
                    <button class="sub-product-next btn btn-light rounded-circle shadow-sm">
                      <i class="bi bi-chevron-compact-down"></i>
                    </button>
                  </div>

                  <div class="swiper-pagination sub-product-pagination"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-6">
          <div class="product-info">
            <!-- Product Title & Brand -->
            <div class="mb-4">
              <h1 class="product-title h2 mb-2">{{ $product->translation->name }}</h1>
              @if($product->brand)
                <a href="{{ $product->brand->url }}" class="text-muted text-decoration-none">
                  {{ $product->brand->name }}
                </a>
              @endif
            </div>

            <!-- Price Section -->
            @hookupdate('front.product.show.price')
            <div class="product-price mb-3">
              <span class="price h3 mb-0 text-primary">{{ $sku['price_format'] }}</span>
              @if($sku['origin_price'])
                <span class="old-price ms-2 text-decoration-line-through text-muted">
                  {{ $sku['origin_price_format'] }}
                </span>
              @endif
            </div>
            @endhookupdate

            <!-- Stock Status -->
            <div class="stock-wrap mb-4">
              <div class="in-stock badge bg-success-subtle text-success">
                <i class="bi bi-check-circle me-1"></i>
                {{ __('front/product.in_stock') }}
              </div>
              <div class="out-stock badge bg-danger-subtle text-danger d-none">
                <i class="bi bi-x-circle me-1"></i>
                {{ __('front/product.out_stock') }}
              </div>
            </div>

            <!-- Product Summary -->
            <div class="product-summary mb-4">
              <p class="text-muted">{{ $product->translation->summary }}</p>
            </div>

            <!-- Product Parameters -->
            <div class="product-params card border-0 bg-light mb-4">
              <div class="card-body">
                <ul class="list-unstyled mb-0">
                  <li class="mb-2">
                    <span class="text-muted">{{ __('front/product.sku_code') }}:</span>
                    <span class="ms-2 fw-medium">{{ $sku['code'] }}</span>
                  </li>
                  @if($sku['model'] ?? false)
                    <li class="mb-2">
                      <span class="text-muted">{{ __('front/product.model') }}:</span>
                      <span class="ms-2 fw-medium">{{ $sku['model'] }}</span>
                    </li>
                  @endif
                  @if ($product->categories->count())
                    <li class="mb-2">
                      <span class="text-muted">{{ __('front/product.category') }}:</span>
                      <span class="ms-2">
                        @foreach ($product->categories as $category)
                          <a href="{{ $category->url }}" class="text-decoration-none">
                            {{ $category->translation->name }}
                          </a>{{ !$loop->last ? ', ' : '' }}
                        @endforeach
                      </span>
                    </li>
                  @endif
                </ul>
              </div>
            </div>

            <!-- Variants Selection -->
            @include('products._variants')

            <!-- Add to Cart Section -->
            <div class="product-actions card border-0 bg-light">
              <div class="card-body">
                <div class="row g-3">
                  <div class="col-12 col-sm-4">
                    <div class="quantity-wrap d-flex align-items-center bg-white rounded p-2">
                      <button class="btn btn-link text-dark p-1 minus">
                        <i class="bi bi-dash-lg"></i>
                      </button>
                      <input type="number"
                             class="form-control border-0 text-center product-quantity"
                             value="1"
                             min="1"
                             data-sku-id="{{ $sku['id'] }}">
                      <button class="btn btn-link text-dark p-1 plus">
                        <i class="bi bi-plus-lg"></i>
                      </button>
                    </div>
                  </div>
                  <div class="col-12 col-sm-8">
                    <div class="d-grid gap-2 d-sm-flex">
                      <button class="btn btn-primary flex-fill add-cart">
                        <i class="bi bi-cart-plus me-1"></i>
                        {{ __('front/product.add_to_cart') }}
                      </button>
                      <button class="btn btn-dark flex-fill buy-now">
                        <i class="bi bi-lightning-fill me-1"></i>
                        {{ __('front/product.buy_now') }}
                      </button>
                    </div>
                  </div>
                </div>

                <!-- Wishlist Button -->
                <button class="btn btn-link text-dark add-wishlist w-100 mt-3"
                        data-in-wishlist="{{ $product->hasFavorite() }}"
                        data-id="{{ $product->id }}">
                  <i class="bi bi-heart{{ $product->hasFavorite() ? '-fill text-danger' : '' }} me-1"></i>
                  {{ __('front/product.add_wishlist') }}
                </button>
              </div>
            </div>

            @hookinsert('product.detail.after')
          </div>
        </div>
      </div>
    </div>

    <!-- Product Details Tabs -->
    <div class="product-description my-5">
      <ul class="nav nav-tabs nav-fill border-bottom-0">
        <li class="nav-item">
          <button class="nav-link active px-4 py-3"
                  data-bs-toggle="tab"
                  data-bs-target="#product-description-description">
            {{ __('front/product.description') }}
          </button>
        </li>
        @if($attributes)
          <li class="nav-item">
            <button class="nav-link px-4 py-3"
                    data-bs-toggle="tab"
                    data-bs-target="#product-description-attribute">
              {{ __('front/product.attribute') }}
            </button>
          </li>
          <li class="nav-item">
            <button class="nav-link px-4 py-3"
                    data-bs-toggle="tab"
                    data-bs-target="#product-review">
              {{ __('front/product.review') }}
            </button>
          </li>
        @endif
        <li class="nav-item">
          <button class="nav-link correlation px-4 py-3"
                  data-bs-toggle="tab"
                  data-bs-target="#product-description-correlation">
            {{__('front/product.related_product')}}
          </button>
        </li>
        @hookinsert('product.detail.tab.link.after')
      </ul>

      <div class="tab-content bg-light p-4 rounded-bottom">
        <div class="tab-pane fade show active" id="product-description-description">
          <div class="rich-text-content">
            @if($product->translation->selling_point)
              {!! parsedown($product->translation->selling_point) !!}
            @endif
            {!! $product->translation->content !!}
          </div>
        </div>

        @if($attributes)
          <div class="tab-pane fade" id="product-description-attribute">
            <div class="table-responsive">
              <table class="table table-striped mb-0">
                @foreach ($attributes as $group)
                  <thead class="table-light">
                    <tr>
                      <th colspan="2" class="h6 fw-bold">
                        {{ $group['attribute_group_name'] }}
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($group['attributes'] as $item)
                      <tr>
                        <td style="width: 30%">{{ $item['attribute'] }}</td>
                        <td>{{ $item['attribute_value'] }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                @endforeach
              </table>
            </div>
          </div>
        @endif

        <div class="tab-pane fade" id="product-review">
          @include('products.review')
        </div>

        <div class="tab-pane fade" id="product-description-correlation">
          <div class="row g-4">
            @foreach ($related as $product)
              <div class="col-6 col-md-4 col-lg-3">
                @include('shared.product')
              </div>
            @endforeach
          </div>
        </div>

        @hookinsert('product.detail.tab.pane.after')
      </div>
    </div>

    @hookinsert('product.show.bottom')
  </div>
@endsection

@push('footer')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const isMobile = window.innerWidth < 992;

  // Handle mobile image loading
  if (isMobile) {
    document.querySelectorAll('.sub-product-img .swiper-slide').forEach(slide => {
      const img = slide.querySelector('a > img');
      const link = slide.querySelector('a');
      if (img && link) {
        img.src = link.href;
      }
    });
  }

  // Initialize Swiper
  const subProductSwiper = new Swiper('#sub-product-img-swiper', {
    direction: isMobile ? 'horizontal' : 'vertical',
    slidesPerView: isMobile ? 1 : 5,
    spaceBetween: isMobile ? 0 : 10,
    autoHeight: !isMobile,
    navigation: {
      nextEl: '.sub-product-next',
      prevEl: '.sub-product-prev',
    },
    pagination: {
      el: '.sub-product-pagination',
      clickable: true,
    },
    observer: true,
    observeParents: true,
  });

  // Initialize PhotoSwipe
  const lightbox = new PhotoSwipeLightbox({
    gallery: '#sub-product-img-swiper',
    children: 'a',
    pswpModule: PhotoSwipe,
    padding: { top: 20, bottom: 20, left: 20, right: 20 },
    bgOpacity: 0.85,
  });
  lightbox.init();

  // Main image click handler
  document.querySelector('.main-product-img').addEventListener('click', () => {
    document.querySelector('#sub-product-img-swiper .swiper-slide a').click();
  });

  // Quantity controls
  document.querySelectorAll('.quantity-wrap .plus, .quantity-wrap .minus').forEach(button => {
    button.addEventListener('click', function() {
      if (this.closest('.quantity-wrap').classList.contains('disabled')) return;

      const input = this.closest('.quantity-wrap').querySelector('input');
      let quantity = parseInt(input.value);

      if (this.classList.contains('plus')) {
        input.value = quantity + 1;
      } else if (quantity > 1) {
        input.value = quantity - 1;
      }
    });
  });

  // Cart and Buy Now functionality
  document.querySelectorAll('.add-cart, .buy-now').forEach(button => {
    button.addEventListener('click', function() {
      const quantity = document.querySelector('.product-quantity').value;
      const skuId = document.querySelector('.product-quantity').dataset.skuId;
      const isBuyNow = this.classList.contains('buy-now');

      // Show loading state
      this.classList.add('disabled');
      const originalText = this.innerHTML;
      this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';

      inno.addCart({skuId, quantity, isBuyNow}, this, function(res) {
        if (isBuyNow) {
          window.location.href = '{{ front_route('carts.index') }}';
        } else {
          // Reset button state
          button.classList.remove('disabled');
          button.innerHTML = originalText;
        }
      });
    });
  });
});
</script>
@endpush
