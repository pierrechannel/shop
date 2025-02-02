@extends('layouts.app')
@section('body-class', 'page-categories')

@push('header')
<style>
  .products-header {
    background: #f8f9fa;
    padding: 1.5rem 0;
    margin-bottom: 2rem;
  }

  .filter-sidebar {
    background: white;
    border-radius: 0.5rem;
    padding: 1.5rem;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    position: sticky;
    top: 20px;
  }

  .toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
    background: white;
    border-radius: 0.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
  }

  .toolbar-left {
    color: #666;
  }

  .toolbar-right {
    display: flex;
    gap: 1rem;
    align-items: center;
  }

  .toolbar-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .form-select {
    min-width: 200px;
    border-radius: 0.5rem;
    border-color: #e2e8f0;
    padding: 0.5rem 1rem;
  }

  .view-switcher {
    display: flex;
    gap: 0.5rem;
  }

  .view-btn {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.5rem;
    border: 1px solid #e2e8f0;
    background: white;
    cursor: pointer;
    transition: all 0.2s;
  }

  .view-btn:hover {
    background: #f8f9fa;
  }

  .view-btn.active {
    background: #333;
    color: white;
    border-color: #333;
  }

  .product-grid {
    margin-bottom: 2rem;
  }

  .product-list-view .product-card {
    display: flex;
    gap: 2rem;
  }

  .product-list-view .product-image {
    flex: 0 0 250px;
  }

  .product-list-view .product-details {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .pagination {
    justify-content: center;
    gap: 0.5rem;
  }

  .page-link {
    border-radius: 0.5rem;
    border: none;
    padding: 0.75rem 1rem;
    color: #333;
  }

  .page-item.active .page-link {
    background: #333;
    color: white;
  }

  @media (max-width: 768px) {
    .toolbar {
      flex-direction: column;
      gap: 1rem;
    }

    .toolbar-right {
      width: 100%;
      flex-wrap: wrap;
    }

    .form-select {
      min-width: 150px;
    }
  }
</style>
@endpush

@section('content')
  <div class="products-header">
    <div class="container">
      <x-front-breadcrumb type="route" value="products.index" title="{{ __('front/product.products') }}"/>
    </div>
  </div>

  @hookinsert('product.index.top')

  <div class="container">
    <div class="row g-4">
      <div class="col-12 col-lg-3">
        <aside class="filter-sidebar">
          @include('shared.filter_sidebar')
        </aside>
      </div>

      <div class="col-12 col-lg-9">
        <div class="toolbar">
          <div class="toolbar-left">
            {{ __('front/common.page_total_show', ['first' => $products->firstItem(), 'last' => $products->lastItem(), 'total' => $products->total()]) }}
          </div>

          <div class="toolbar-right">
            <div class="toolbar-item">
              <span>{{ __('front/common.sort') }}:</span>
              <select class="form-select order-select">
                <option value="">{{ __('/front/category.default') }}</option>
                <option value="products.sales|asc" {{ request('sort') == 'products.sales' && request('order') == 'asc' ? 'selected' : '' }}>
                  {{ __('/front/category.sales') }} ({{ __('/front/category.low') }} - {{ __('/front/category.high')}})
                </option>
                <option value="products.sales|desc" {{ request('sort') == 'products.sales' && request('order') == 'desc' ? 'selected' : '' }}>
                  {{ __('/front/category.sales') }} ({{ __('/front/category.high') }} - {{ __('/front/category.low')}})
                </option>
                <option value="pt.name|asc" {{ request('sort') == 'pt.name' && request('order') == 'asc' ? 'selected' : '' }}>
                  {{ __('/front/category.name') }} (A - Z)
                </option>
                <option value="pt.name|desc" {{ request('sort') == 'pt.name' && request('order') == 'desc' ? 'selected' : '' }}>
                  {{ __('/front/category.name') }} (Z - A)
                </option>
                <option value="ps.price|asc" {{ request('sort') == 'ps.price' && request('order') == 'asc' ? 'selected' : '' }}>
                  {{ __('/front/category.price') }} ({{ __('/front/category.low') }} - {{ __('/front/category.high')}})
                </option>
                <option value="ps.price|desc" {{ request('sort') == 'ps.price' && request('order') == 'desc' ? 'selected' : '' }}>
                  {{ __('/front/category.price') }} ({{ __('/front/category.high') }} - {{ __('/front/category.low')}})
                </option>
              </select>
            </div>

            <div class="toolbar-item">
              <span>{{ __('front/common.show') }}:</span>
              <select class="form-select per-page-select">
                @foreach ($per_page_items as $val)
                  <option value="{{ $val }}" {{ request('per_page') == $val ? 'selected' : '' }}>{{ $val }}</option>
                @endforeach
              </select>
            </div>

            <div class="view-switcher">
              <label class="view-btn {{ !request('style_list') || request('style_list') == 'grid' ? 'active' : ''}}">
                <i class="bi bi-grid"></i>
                <input class="d-none" value="grid" type="radio" name="style_list">
              </label>

              <label class="view-btn {{ request('style_list') == 'list' ? 'active' : ''}}">
                <i class="bi bi-list"></i>
                <input class="d-none" value="list" type="radio" name="style_list">
              </label>
            </div>
          </div>
        </div>

        <div class="row g-4 {{ request('style_list') == 'list' ? 'product-list-view' : 'product-grid' }}">
          @foreach ($products as $product)
            <div class="{{ !request('style_list') || request('style_list') == 'grid' ? 'col-6 col-md-4' : 'col-12'}}">
              @include('shared.product')
            </div>
          @endforeach
        </div>

        {{ $products->links('panel::vendor/pagination/bootstrap-4') }}
      </div>
    </div>
  </div>

  @hookinsert('product.index.bottom')
@endsection

@push('footer')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Debounce function to prevent multiple rapid requests
  function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }

  // Handle filters and sorting
  const handleFilterChange = debounce(function() {
    const url = new URL(window.location.href);
    const orderSelect = document.querySelector('.order-select');
    const perPageSelect = document.querySelector('.per-page-select');
    const styleList = document.querySelector('input[name="style_list"]:checked');

    // Show loading indicator
    const loadingOverlay = document.createElement('div');
    loadingOverlay.className = 'position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-white bg-opacity-75';
    loadingOverlay.style.zIndex = '9999';
    loadingOverlay.innerHTML = '<div class="spinner-border text-primary" role="status"></div>';
    document.body.appendChild(loadingOverlay);

    // Update URL parameters
    if (orderSelect.value) {
      const [sort, order] = orderSelect.value.split('|');
      url.searchParams.set('sort', sort);
      url.searchParams.set('order', order);
    } else {
      url.searchParams.delete('sort');
      url.searchParams.delete('order');
    }

    if (perPageSelect.value) {
      url.searchParams.set('per_page', perPageSelect.value);
    }

    if (styleList) {
      url.searchParams.set('style_list', styleList.value);
    }

    // Navigate to new URL
    window.location.href = url.toString();
  }, 300);

  // Add event listeners
  document.querySelectorAll('.form-select, input[name="style_list"]').forEach(element => {
    element.addEventListener('change', handleFilterChange);
  });

  // Helper function for attribute filtering
  window.filterAttrChecked = function(data) {
    return data.reduce((acc, item) => {
      const checkedValues = item.values
        .filter(val => val.selected)
        .map(val => val.id);

      if (checkedValues.length) {
        acc.push(`${item.id}:${checkedValues.join(',')}`);
      }
      return acc;
    }, []).join('|');
  };
});
</script>
@endpush
