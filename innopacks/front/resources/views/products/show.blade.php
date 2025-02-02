@extends('layouts.app')
@section('body-class', 'page-product')

@push('header')
<script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}" defer></script>
<link rel="stylesheet" href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}">

<script src="{{ asset('vendor/photoswipe/umd/photoswipe.umd.min.js') }}" defer></script>
<script src="{{ asset('vendor/photoswipe/umd/photoswipe-lightbox.umd.min.js') }}" defer></script>
<link rel="stylesheet" href="{{ asset('vendor/photoswipe/photoswipe.css') }}">

<style>
  .product-container {
    padding: 2rem 0;
  }

  .product-gallery {
    position: relative;
    display: flex;
    gap: 1rem;
  }

  .thumbnail-gallery {
    width: 100px;
  }

  .thumbnail-gallery .swiper {
    height: 400px;
  }

  .thumbnail-gallery img {
    border: 2px solid transparent;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .thumbnail-gallery .swiper-slide-active img {
    border-color: #333;
  }

  .main-image {
    flex: 1;
    border-radius: 0.5rem;
    overflow: hidden;
  }

  .main-image img {
    width: 100%;
    height: 500px;
    object-fit: cover;
    cursor: zoom-in;
  }

  .product-info {
    padding-left: 2rem;
  }

  .product-title {
    font-size: 2rem;
    margin-bottom: 1rem;
  }

  .product-price {
    font-size: 1.5rem;
    margin: 1.5rem 0;
  }

  .price {
    color: #e53e3e;
    font-weight: 600;
  }

  .old-price {
    color: #718096;
    text-decoration: line-through;
    font-size: 1rem;
  }

  .stock-wrap {
    margin: 1rem 0;
  }

  .in-stock {
    background: #48bb78;
    color: white;
    padding: 0.5rem 1rem;
  }

  .product-param {
    list-style: none;
    padding: 0;
    margin: 1.5rem 0;
    border-top: 1px solid #e2e8f0;
    border-bottom: 1px solid #e2e8f0;
    padding: 1.5rem 0;
  }

  .product-param li {
    margin-bottom: 0.75rem;
    display: flex;
    gap: 0.5rem;
  }

  .product-param .title {
    color: #718096;
    min-width: 120px;
  }

  .quantity-wrap {
    display: inline-flex;
    align-items: center;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    overflow: hidden;
  }

  .quantity-wrap .minus,
  .quantity-wrap .plus {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    background: #f7fafc;
    transition: all 0.2s;
  }

  .quantity-wrap .minus:hover,
  .quantity-wrap .plus:hover {
    background: #edf2f7;
  }

  .quantity-wrap input {
    width: 60px;
    text-align: center;
    border: none;
    border-left: 1px solid #e2e8f0;
    border-right: 1px solid #e2e8f0;
    height: 40px;
  }

  .product-info-btns {
    margin: 1.5rem 0;
    display: flex;
    gap: 1rem;
  }

  .btn-primary {
    background: #3182ce;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
  }

  .btn-primary:hover {
    background: #2c5282;
  }

  .buy-now {
    background: #e53e3e;
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
  }

  .buy-now:hover {
    background: #c53030;
  }

  .add-wishlist {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    color: #718096;
    transition: all 0.2s;
  }

  .add-wishlist:hover {
    color: #e53e3e;
  }

  .add-wishlist.active {
    color: #e53e3e;
  }

  .product-tabs {
    margin-top: 4rem;
  }

  .nav-tabs {
    border: none;
    gap: 1rem;
    margin-bottom: 2rem;
  }

  .nav-link {
    border: none;
    padding: 1rem 2rem;
    border-radius: 0.5rem;
    background: #f7fafc;
    color: #4a5568;
    transition: all 0.3s ease;
  }

  .nav-link:hover,
  .nav-link.active {
    background: #333;
    color: white;
  }

  .tab-content {
    padding: 2rem;
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
  }

  .attribute-table {
    margin: 0;
  }

  .attribute-table td {
    padding: 1rem;
  }

  @media (max-width: 992px) {
    .product-gallery {
      flex-direction: column-reverse;
    }

    .thumbnail-gallery {
      width: 100%;
      height: 100px;
    }

    .thumbnail-gallery .swiper {
      height: auto;
    }

    .main-image img {
      height: 300px;
    }

    .product-info {
      padding-left: 0;
      margin-top: 2rem;
    }
  }
</style>
@endpush

@section('content')
<div class="container product-container">
  <x-front-breadcrumb type="product" :value="$product"/>

  @hookinsert('product.show.top')

  <div class="row">
    <div class="col-12 col-lg-6">
      <div class="product-gallery">
        <div class="thumbnail-gallery">
          <div class="swiper" id="thumbnail-swiper">
            <div class="swiper-wrapper">
              @foreach($product->images as $image)
                <div class="swiper-slide">
                  <img src="{{ image_resize($image->path) }}" class="img-fluid" alt="{{ $product->translation->name }}">
                </div>
              @endforeach
            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
          </div>
        </div>
        <div class="main-image">
          <img src="{{ $product->image_url }}" alt="{{ $product->translation->name }}" id="main-product-image">
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-6">
      <div class="product-info">
        <h1 class="product-title">{{ $product->translation->name }}</h1>

        @hookupdate('front.product.show.price')
        <div class="product-price">
          <span class="price">{{ $sku['price_format'] }}</span>
          @if($sku['origin_price'])
            <span class="old-price">{{ $sku['origin_price_format'] }}</span>
          @endif
        </div>
        @endhookupdate

        <div class="stock-wrap">
          <span class="in-stock">{{ __('front/product.in_stock') }}</span>
        </div>

        @if($product->translation->summary)
          <p class="product-summary">{{ $product->translation->summary }}</p>
        @endif

        <ul class="product-param">
          <li>
            <span class="title">{{ __('front/product.sku_code') }}</span>
            <span class="value">{{ $sku['code'] }}</span>
          </li>

          @if($sku['model'] ?? false)
            <li>
              <span class="title">{{ __('front/product.model') }}</span>
              <span class="value">{{ $sku['model'] }}</span>
            </li>
          @endif

          @if ($product->categories->count())
            <li>
              <span class="title">{{ __('front/product.category') }}</span>
              <span class="value">
                @foreach ($product->categories as $category)
                  <a href="{{ $category->url }}">{{ $category->translation->name }}</a>{{ !$loop->last ? ', ' : '' }}
                @endforeach
              </span>
            </li>
          @endif

          @if($product->brand)
            <li>
              <span class="title">{{ __('front/product.brand') }}</span>
              <span class="value">
                <a href="{{ $product->brand->url }}">{{ $product->brand->name }}</a>
              </span>
            </li>
          @endif
        </ul>

        @include('products._variants')

        <div class="product-actions">
          <div class="quantity-wrap">
            <button class="minus" type="button"><i class="bi bi-dash"></i></button>
            <input type="number" class="form-control product-quantity" value="1" min="1" data-sku-id="{{ $sku['id'] }}">
            <button class="plus" type="button"><i class="bi bi-plus"></i></button>
          </div>

          <div class="product-info-btns">
            <button class="btn btn-primary add-cart">
              <i class="bi bi-cart-plus"></i>
              {{ __('front/product.add_to_cart') }}
            </button>
            <button class="btn buy-now">
              <i class="bi bi-lightning"></i>
              {{ __('front/product.buy_now') }}
            </button>
          </div>

          <button class="add-wishlist {{ $product->hasFavorite() ? 'active' : '' }}"
                  data-id="{{ $product->id }}">
            <i class="bi bi-heart{{ $product->hasFavorite() ? '-fill' : '' }}"></i>
            <span>{{ __('front/product.add_wishlist') }}</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="product-tabs">
    <ul class="nav nav-tabs">
      <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#description">
          {{ __('front/product.description') }}
        </button>
      </li>
      @if($attributes)
        <li class="nav-item">
          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#attributes">
            {{ __('front/product.attribute') }}
          </button>
        </li>
      @endif
      <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#reviews">
          {{ __('front/product.review') }}
        </button>
      </li>
      <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#related">
          {{__('front/product.related_product')}}
        </button>
      </li>
    </ul>

    <div class="tab-content">
      <div class="tab-pane fade show active" id="description">
        @if($product->translation->selling_point)
          {!! parsedown($product->translation->selling_point) !!}
        @endif
        {!! $product->translation->content !!}
      </div>

      @if($attributes)
        <div class="tab-pane fade" id="attributes">
          <table class="table attribute-table">
            @foreach ($attributes as $group)
              <thead>
                <tr>
                  <th colspan="2">{{ $group['attribute_group_name'] }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($group['attributes'] as $item)
                  <tr>
                    <td width="30%">{{ $item['attribute'] }}</td>
                    <td>{{ $item['attribute_value'] }}</td>
                  </tr>
                @endforeach
              </tbody>
            @endforeach
          </table>
        </div>
      @endif

      <div class="tab-pane fade" id="reviews">
        @include('products.review')
      </div>

      <div class="tab-pane fade" id="related">
        <div class="row g-4">
          @foreach ($related as $product)
            <div class="col-6 col-md-4 col-lg-3">
              @include('shared.product')
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>

@hookinsert('product.show.bottom')
@endsection

@push('footer')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const isMobile = window.innerWidth < 992;

  // Initialize thumbnail swiper
  const thumbnailSwiper = new Swiper('#thumbnail-swiper', {
    direction: isMobile ? 'horizontal' : 'vertical',
    slidesPerView: isMobile ? 4 : 5,
    spaceBetween: 10,
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    watchSlidesProgress: true,
  });

  // Initialize PhotoSwipe
  const lightbox = new PhotoSwipeLightbox({
    gallery: '.product-gallery',
    children: '.main-image',
    pswpModule: PhotoSwipe,
    padding: { top: 0, bottom: 0, left: 0, right: 0 },
  });
  lightbox.init();

  // Handle thumbnail clicks
  document.querySelectorAll('#thumbnail-swiper .swiper-slide img').forEach((img, index) => {
    img.addEventListener('click', () => {
      document.getElementById('main-product-image').src = img.src.replace(/\?.*$/, '');
      thumbnailSwiper.slideTo(index);
    });
  });

 // Quantity controls
 const quantityInput = document.querySelector('.product-quantity');

  document.querySelector('.plus').addEventListener('click', () => {
    const currentValue = parseInt(quantityInput.value);
    quantityInput.value = currentValue + 1;
    updateQuantityState();
  });

  document.querySelector('.minus').addEventListener('click', () => {
    const currentValue = parseInt(quantityInput.value);
    if (currentValue > 1) {
      quantityInput.value = currentValue - 1;
    }
    updateQuantityState();
  });

  quantityInput.addEventListener('change', () => {
    if (parseInt(quantityInput.value) < 1) {
      quantityInput.value = 1;
    }
    updateQuantityState();
  });

  function updateQuantityState() {
    const minusBtn = document.querySelector('.minus');
    minusBtn.classList.toggle('disabled', parseInt(quantityInput.value) <= 1);
  }

  // Add to cart functionality
  const addToCartBtn = document.querySelector('.add-cart');
  const buyNowBtn = document.querySelector('.buy-now');

  function handleAddToCart(isBuyNow = false) {
    const quantity = parseInt(quantityInput.value);
    const skuId = quantityInput.dataset.skuId;
    const button = isBuyNow ? buyNowBtn : addToCartBtn;

    // Show loading state
    button.disabled = true;
    const originalText = button.innerHTML;
    button.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`;

    inno.addCart({ skuId, quantity, isBuyNow }, button, function(res) {
      // Reset button state
      button.disabled = false;
      button.innerHTML = originalText;

      if (isBuyNow && res.status) {
        window.location.href = '{{ front_route('carts.index') }}';
      } else if (res.status) {
        // Show success toast
        const toast = document.createElement('div');
        toast.className = 'position-fixed top-0 end-0 p-3';
        toast.style.zIndex = '9999';
        toast.innerHTML = `
          <div class="toast show" role="alert">
            <div class="toast-header">
              <strong class="me-auto">Success</strong>
              <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
              Product added to cart successfully!
            </div>
          </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
      }
    });
  }

  addToCartBtn.addEventListener('click', () => handleAddToCart(false));
  buyNowBtn.addEventListener('click', () => handleAddToCart(true));

  // Wishlist functionality
  const wishlistBtn = document.querySelector('.add-wishlist');

  wishlistBtn.addEventListener('click', function() {
    const productId = this.dataset.id;
    const isActive = this.classList.contains('active');

    // Optimistic UI update
    this.classList.toggle('active');
    this.querySelector('i').classList.toggle('bi-heart');
    this.querySelector('i').classList.toggle('bi-heart-fill');

    fetch('/api/wishlist', {
      method: isActive ? 'DELETE' : 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ product_id: productId }),
    })
    .then(response => {
      if (!response.ok) {
        // Revert UI changes if request failed
        this.classList.toggle('active');
        this.querySelector('i').classList.toggle('bi-heart');
        this.querySelector('i').classList.toggle('bi-heart-fill');
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .catch(error => {
      console.error('Error:', error);
    });
  });

  // Lazy loading for product images
  const lazyImages = document.querySelectorAll('img[loading="lazy"]');
  if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const img = entry.target;
          img.src = img.dataset.src;
          img.removeAttribute('data-src');
          imageObserver.unobserve(img);
        }
      });
    });

    lazyImages.forEach(img => imageObserver.observe(img));
  }

  // Smooth scroll to reviews when clicking on review tab
  document.querySelector('[data-bs-target="#reviews"]').addEventListener('click', () => {
    const reviewsSection = document.getElementById('reviews');
    if (reviewsSection) {
      reviewsSection.scrollIntoView({ behavior: 'smooth' });
    }
  });
});
</script>
@endpush
