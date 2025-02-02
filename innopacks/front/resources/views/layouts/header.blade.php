@hookinsert('layout.header.top')
@push('header')

<style>
/* Header Top Section */
.header-top {
  background-color: #f8f9fa;
  padding: 8px 0;
  border-bottom: 1px solid #eee;
  font-size: 14px;
}

.language-switch .dropdown-toggle {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 6px 12px;
  color: #333;
  text-decoration: none;
}

.language-switch .dropdown-toggle img {
  width: 20px;
  height: 20px;
  border-radius: 2px;
}

.language-switch .dropdown-item {
  padding: 8px 16px;
}

.wh-20 {
  width: 20px;
  height: 20px;
}

.top-info {
  display: flex;
  align-items: center;
  gap: 24px;
}

.top-info a {
  color: #333;
  text-decoration: none;
}

.top-info span {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #666;
}

/* Desktop Header */
.header-desktop {
  padding: 20px 0;
  background: #fff;
  border-bottom: 1px solid #eee;
}

.header-desktop .logo {
  margin: 0;
}

.header-desktop .logo a {
  text-decoration: none;
  color: #333;
}

.header-desktop .logo h3 {
  margin: 0;
  font-size: 24px;
  font-weight: 700;
}

.header-desktop .left {
  display: flex;
  align-items: center;
  gap: 40px;
}

.navbar-nav {
  gap: 8px;
}

.nav-link {
  color: #333;
  padding: 8px 16px;
  font-weight: 500;
  transition: color 0.2s ease;
}

.nav-link:hover,
.nav-link.active {
  color: #007bff;
}

/* Search Form */
.search-group {
  position: relative;
  width: 300px;
}

.search-group .form-control {
  padding: 10px 40px 10px 16px;
  border-radius: 20px;
  border: 1px solid #eee;
}

.search-group .btn {
  position: absolute;
  right: 4px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  padding: 8px;
  color: #666;
}

/* Icons Section */
.icons {
  display: flex;
  align-items: center;
  gap: 24px;
  margin-left: 24px;
}

.icons .item {
  position: relative;
}

.icons .item img {
  width: 24px;
  height: 24px;
}

.icon-quantity {
  position: absolute;
  top: -8px;
  right: -8px;
  background: #007bff;
  color: #fff;
  font-size: 12px;
  min-width: 18px;
  height: 18px;
  border-radius: 9px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0 5px;
}

.account-icon .dropdown-menu {
  min-width: 200px;
  padding: 8px 0;
  margin-top: 8px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.account-icon .dropdown-item {
  padding: 8px 16px;
  color: #333;
}

.account-icon .dropdown-item:hover {
  background-color: #f8f9fa;
  color: #007bff;
}

/* Mobile Header */
.header-mobile {
  display: none;
  padding: 12px 0;
  background: #fff;
  border-bottom: 1px solid #eee;
}

.mb-icon {
  font-size: 24px;
  cursor: pointer;
  padding: 8px;
  color: #333;
}

.header-mobile .logo {
  max-width: 120px;
}

.header-mobile .logo img {
  max-height: 40px;
  width: auto;
}

/* Mobile Menu Offcanvas */
.offcanvas {
  max-width: 280px;
}

.offcanvas-header {
  padding: 16px;
  border-bottom: 1px solid #eee;
}

.close-offcanvas {
  position: absolute;
  right: -40px;
  top: 0;
  background: rgba(0,0,0,0.5);
  color: #fff;
  padding: 8px;
  cursor: pointer;
}

.mobile-menu-wrap .accordion-item {
  border: none;
}

.nav-item-text {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 16px;
  border-bottom: 1px solid #eee;
}

.nav-item-text .nav-link {
  padding: 0;
  flex: 1;
}

.nav-item-text .collapsed {
  padding: 4px;
  cursor: pointer;
}

.children-group {
  background: #f8f9fa;
  padding: 8px 0 8px 16px;
}

.ul-children {
  margin: 0;
  padding: 0;
}

.ul-children .nav-item {
  margin: 4px 0;
}

.ul-children .nav-link {
  padding: 8px 16px;
  font-size: 14px;
}

/* Responsive Styles */
@media (max-width: 991.98px) {
  .search-group {
    width: 200px;
  }

  .header-desktop .left {
    gap: 20px;
  }

  .navbar-nav {
    gap: 4px;
  }
}

@media (max-width: 767.98px) {
  .header-desktop {
    display: none;
  }

  .header-mobile {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .header-top {
    display: none;
  }

  .offcanvas .search-group {
    width: 100%;
  }

  .offcanvas-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .offcanvas-header .account-icon img {
    width: 24px;
    height: 24px;
  }
}

/* Dropdown Styles */
.dropdown-menu {
  border: 1px solid #eee;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  border-radius: 4px;
}

.dropdown-toggle::after {
  margin-left: 8px;
}

/* Utils */
.right {
  display: flex;
  align-items: center;
}

.img-fluid {
  max-width: 100%;
  height: auto;
}
</style>

@endpush


<header id="appHeader">
  <div class="header-top">
    <div class="container d-flex justify-content-between align-items-center">
      <div class="language-switch d-flex align-items-center">
        @if (locales()->count() > 1)
          <div class="dropdown">
            <a class="btn dropdown-toggle" href="javascript:void(0)">
              <img src="{{ asset($current_locale->image) }}" class="img-fluid"> {{ $current_locale->name }}
            </a>

            <div class="dropdown-menu">
              @foreach (locales() as $locale)
                <a class="dropdown-item d-flex" href="{{ front_route('locales.switch', ['code' => $locale->code]) }}">
                  <div class="wh-20 me-2"><img src="{{ image_origin($locale['image']) }}" class="img-fluid border"></div>
                  {{ $locale->name }}
                </a>
              @endforeach
            </div>
          </div>
        @endif

        @if (currencies()->count() > 1)
          <div class="dropdown ms-4">
            <a class="btn dropdown-toggle" href="javascript:void(0)">
              {{ current_currency()->name }}
            </a>

            <div class="dropdown-menu">
              @foreach (currencies() as $currency)
                <a class="dropdown-item" href="{{ front_route('currencies.switch', ['code'=> $currency->code]) }}">
                  {{ $currency->name }} ({{ $currency->symbol_left }})
                </a>
              @endforeach
            </div>
          </div>
        @endif
      </div>
      <div class="top-info">
        <a href="{{ front_route('articles.index') }}">News</a>
        @hookupdate('layouts.header.telephone')
        <span><i class="bi bi-telephone-outbound"></i> {{ system_setting('telephone') }}</span>
        @endhookupdate
      </div>
    </div>
  </div>
  <div class="header-desktop">
    <div class="container d-flex justify-content-between align-items-center">
      <div class="left">
        <h1 class="logo">
          <a href="{{ front_route('home.index') }}">
            {{-- <img src="{{ image_origin(system_setting('front_logo', 'images/logo.svg')) }}" class="img-fluid"> --}}
            <h3>Afrobeads</h3>
          </a>
        </h1>
        <div class="menu">
          <nav class="navbar navbar-expand-md navbar-light">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{ front_route('home.index') }}">{{ __('front/common.home') }}</a>
              </li>

              @hookupdate('layouts.header.menu.pc')
                @foreach($header_menus as $menu)
                  @if($menu['children'] ?? [])
                    <li class="nav-item">
                      <div class="dropdown">
                        @if($menu['name'])
                          <a class="nav-link {{ equal_url($menu['url']) ? 'active' : '' }}" href="{{ $menu['url'] }}">{{ $menu['name'] }}</a>
                        @endif
                        <ul class="dropdown-menu">
                          @foreach($menu['children'] as $child)
                            @if($child['name'])
                              <li><a class="dropdown-item" href="{{ $child['url'] }}">{{ $child['name'] }}</a></li>
                            @endif
                          @endforeach
                        </ul>
                      </div>
                    </li>
                  @else
                    @if($menu['name'])
                      <li class="nav-item">
                        <a class="nav-link {{ equal_url($menu['url']) ? 'active' : '' }}" href="{{ $menu['url'] }}">{{ $menu['name'] }}</a>
                      </li>
                    @endif
                  @endif
                @endforeach
              @endhookupdate
            </ul>
          </nav>
        </div>
      </div>
      <div class="right">
        <form action="{{ front_route('products.index') }}" method="get" class="search-group">
          <input type="text" class="form-control" name="keyword" placeholder="{{ __('front/common.search') }}" value="{{ request('keyword') }}">
          <button type="submit" class="btn"><i class="bi bi-search"></i></button>
        </form>
        <div class="icons">
          <div class="item">
            <div class="dropdown account-icon">
              <a class="btn dropdown-toggle px-0" href="{{ front_route('account.index') }}">
                <img src="{{ asset('icon/account.svg') }}" class="img-fluid">
              </a>

              <div class="dropdown-menu dropdown-menu-end">
                @if (current_customer())
                <a href="{{ front_route('account.index') }}" class="dropdown-item">{{ __('front/account.account') }}</a>
                <a href="{{ front_route('account.orders.index') }}" class="dropdown-item">{{ __('front/account.orders') }}</a>
                <a href="{{ front_route('account.favorites.index') }}" class="dropdown-item">{{ __('front/account.favorites') }}</a>
                <a href="{{ front_route('account.logout') }}" class="dropdown-item">{{ __('front/account.logout') }}</a>
                @else
                <a href="{{ front_route('login.index') }}" class="dropdown-item">{{ __('front/common.login') }}</a>
                <a href="{{ front_route('register.index') }}" class="dropdown-item">{{ __('front/common.register') }}</a>
                @endif
              </div>
            </div>
          </div>
          <div class="item">
            <a href="{{ account_route('favorites.index') }}"><img src="{{ asset('icon/love.svg') }}" class="img-fluid"><span class="icon-quantity">{{ $fav_total }}</span></a>
          </div>
          <div class="item">
            <a href="{{ front_route('carts.index') }}" class="header-cart-icon"><img src="{{ asset('icon/cart.svg') }}" class="img-fluid"><span class="icon-quantity">0</span></a>
          </div>
          @hookinsert('layouts.header.cart.after')
        </div>
      </div>
    </div>
  </div>
  <div class="header-mobile">
    <div class="mb-icon" data-bs-toggle="offcanvas" data-bs-target="#mobile-menu-offcanvas" aria-controls="offcanvasExample">
      <i class="bi bi-list"></i>
    </div>

    <div class="logo">
      <a href="{{ front_route('home.index') }}">
        <img src="{{ image_origin(system_setting('front_logo', 'images/logo.svg')) }}" class="img-fluid">
      </a>
    </div>

    <a href="{{ front_route('carts.index') }}" class="header-cart-icon"><img src="{{ asset('icon/cart.svg') }}" class="img-fluid"><span class="icon-quantity">12</span></a>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobile-menu-offcanvas">
      <div class="offcanvas-header">
        <form action="" method="get" class="search-group">
          <input type="text" class="form-control" placeholder="Search">
          <button type="submit" class="btn"><i class="bi bi-search"></i></button>
        </form>
        <a class="account-icon" href="{{ front_route('account.index') }}">
          <img src="{{ asset('icon/account.svg') }}" class="img-fluid">
        </a>
      </div>
      <div class="close-offcanvas" data-bs-dismiss="offcanvas"><i class="bi bi-chevron-compact-left"></i></div>
      <div class="offcanvas-body mobile-menu-wrap">
        <div class="accordion accordion-flush" id="menu-accordion">
          <div class="accordion-item">
            <div class="nav-item-text">
              <a class="nav-link {{ equal_route_name('home.index') ? 'active' : '' }}" aria-current="page" href="{{ front_route('home.index') }}">{{ __('front/common.home') }}</a>
            </div>
          </div>

          @hookupdate('layouts.header.menu.mobile')
            @foreach ($header_menus as $key => $menu)
              @if ($menu['name'])
                <div class="accordion-item">
                  <div class="nav-item-text">
                    <a class="nav-link" href="{{ $menu['url'] }}" data-bs-toggle="{{ !$menu['url'] ? 'collapse' : ''}}">
                      {{ $menu['name'] }}
                    </a>
                    @if (isset($menu['children']) && $menu['children'])
                    <span class="collapsed" data-bs-toggle="collapse" data-bs-target="#flush-menu-{{ $key }}"><i class="bi bi-chevron-down"></i></span>
                    @endif
                  </div>

                  @if (isset($menu['children']) && $menu['children'])
                  <div class="accordion-collapse collapse" id="flush-menu-{{ $key }}" data-bs-parent="#menu-accordion">
                    <div class="children-group">
                      <ul class="nav flex-column ul-children">
                        @foreach($menu['children'] as $c_key => $child)
                          @if($child['name'])
                            <li class="nav-item">
                              <a class="nav-link" href="{{ $child['url'] }}">{{$child['name']}}</a>
                            </li>
                          @endif
                        @endforeach
                      </ul>
                    </div>
                  </div>
                  @endif
                </div>
              @endif
            @endforeach
          @endhookupdate

        </div>
      </div>
    </div>
  </div>
</header>

@hookinsert('layout.header.bottom')
