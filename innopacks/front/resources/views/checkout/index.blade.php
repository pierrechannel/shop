@extends('layouts.app')
@section('body-class', 'page-checkout')

@section('content')

  @push('header')
    <script src="{{ asset('vendor/vue/3.5/vue.global' . (!config('app.debug') ? '.prod' : '') . '.js') }}"></script>
    <script src="https://js.stripe.com/v3/"></script>
  @endpush

  <x-front-breadcrumb type="route" value="checkout.index" title="{{ __('front/checkout.checkout') }}"/>

  @hookinsert('checkout.top')

  <div class="container checkout-container h-min-600">
    <div class="row" id="app-checkout" v-cloak>
      <div class="col-12 col-md-7">
        <div class="checkout-info">

          <div class="address-box">
            <div class="checkout-item" v-if="!source.addressEdit">
              <div class="addresses-wrap">
                <div class="shipping-address">
                  <div class="title-wrap">
                    <div class="title">
                      {{ __('front/checkout.shipping_address') }}
                    </div>
                    <div>
                      <label class="form-check-label me-4">
                        <input class="form-check-input" type="checkbox" v-model="source.same_as_shipping_address">
                        {{ __('front/checkout.same_shipping_address') }}
                      </label>
                      <span class="cursor-pointer" v-if="!source.addressEdit" @click="addressEdit(true)"><i
                            class="bi bi-plus-lg"></i>{{ __('front/checkout.create_address') }}</span>
                    </div>
                  </div>
                  <div class="checkout-select-wrap address-select"
                       v-if="source.addresses.length && !source.addressEdit">
                    <div :class="['select-item', current.shipping_address_id == address.id ? 'active' : '']"
                         v-for="address, index in source.addresses" :key="address.id"
                         @click="updateCheckout('shipping_address_id', address.id)">
                      <div class="left">
                        <i class="bi bi-circle"></i>
                        <div class="select-title">
                          <div class="address-name mb-1">@{{ address.name }} @{{ address.phone }} @{{ address.zipcode
                            }}
                          </div>
                          <div class="address-info">@{{ address.address_1 }} @{{ address.address_2 }} @{{ address.city }} @{{ address.state }} @{{ address.country_name }}
                          </div>
                        </div>
                      </div>
                      <div class="edit-address text-decoration-underline text-secondary"
                           @click.stop="editAddress(index)"> {{ __('front/common.edit') }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="checkout-item" v-if="!source.addressEdit && !source.same_as_shipping_address">
              <div class="addresses-wrap">
                <div class="shipping-address">
                  <div class="title-wrap">
                    <div class="title">{{ __('front/checkout.billing_address') }}</div>
                    <span class="cursor-pointer" v-if="!source.addressEdit" @click="addressEdit(true)"><i
                          class="bi bi-plus-lg"></i>{{ __('front/checkout.create_address') }}</span>
                    <span class="cursor-pointer" v-else @click="addressEdit(false)">
                      <i class="bi bi-plus-lg"></i>{{ __('front/checkout.cancel_create') }}
                    </span>
                  </div>
                  <div class="checkout-select-wrap address-select"
                       v-if="source.addresses.length && !source.addressEdit">
                    <div :class="['select-item', current.billing_address_id  == address.id ? 'active' : '']"
                         v-for="address, index in source.addresses" :key="address.id"
                         @click="updateCheckout('billing_address_id', address.id)">
                      <div class="left">
                        <i class="bi bi-circle"></i>
                        <div class="select-title">
                          <div class="address-name mb-1">@{{ address.name }} @{{ address.phone }} @{{ address.zipcode
                            }}
                          </div>
                          <div class="address-info">@{{ address.address_1 }} @{{ address.address_2 }} @{{ address.state
                            }} @{{ address.city }} @{{ address.country_id }}
                          </div>
                        </div>
                      </div>
                      <div class="edit-address text-decoration-underline text-secondary" @click="editAddress(index)">
                        {{ __('front/common.edit') }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div v-show="source.addressEdit">
              <div class="checkout-item">
                <div class="title-wrap">
                  <div class="title">{{ __('front/checkout.create_address') }}</div>
                  @if (!current_customer())
                  <span class="cursor-pointer btn btn-sm btn-outline-primary" @click="login"><i
                        class="bi bi-box-arrow-in-right"></i> {{ __('front/common.login') }}</span>
                  @endif
                  <span class="cursor-pointer" v-if="source.addresses.length" @click="addressEdit(false)"><i
                        class="bi bi-plus-lg"></i> {{ __('front/checkout.cancel_create') }}</span>
                </div>
                @include('shared.address-form')
              </div>
            </div>
          </div>

          <div class="checkout-item">
            <div class="title-wrap">
              <div class="title">{{ __('front/checkout.shipping_methods') }}</div>
            </div>
            <div class="checkout-select-wrap">
              <div v-for="item in source.shippingMethods" :key="item.code">
                <div v-for="quote in item.quotes" :key="quote.code" @click="updateCheckout('shipping_method_code', quote.code)"
                     :class="['select-item', current.shipping_method_code  == quote.code ? 'active' : '']">
                  <div class="left">
                    <i class="bi bi-circle"></i>
                    <div class="select-title">
                      <span class="name"> @{{ quote.name }}</span> &nbsp;&nbsp;
                      <span class="cost"> @{{ quote.cost_format }}</span>
                    </div>
                  </div>
                  <div class="icon"><img :src="quote.icon" class="img-fluid"></div>
                </div>
              </div>
              <div v-if="!source.shippingMethods.length" class="alert alert-warning">
                <i class="bi bi-exclamation-circle-fill"></i> {{ __('front/checkout.no_shipping_methods') }}</div>
            </div>
          </div>

          <div class="checkout-item">
            <div class="title-wrap">
              <div class="title">{{ __('front/checkout.billing_methods') }}</div>
            </div>
            <div class="checkout-select-wrap">
              <div :class="['select-item', current.billing_method_code  == item.code ? 'active' : '']"
                   v-for="item in source.billingMethods" :key="item.code"
                   @click="updateCheckout('billing_method_code', item.code)">
                <div class="left">
                  <i class="bi bi-circle"></i>
                  <div class="select-title">@{{ item.name }}</div>
                </div>
                <div class="icon"><img :src="item.icon" class="img-fluid"></div>
              </div>
              <div v-if="!source.billingMethods.length" class="alert alert-warning"><i class="bi bi-exclamation-circle-fill"></i> {{ __('front/checkout.no_billing_methods') }}</div>
            </div>
          </div>
          <div class="checkout-item">
            <div class="title-wrap">
              <div class="title">{{ __('front/checkout.payment_details') }}</div>
            </div>
            <div id="card-element" class="form-control"></div>
            <div id="card-errors" role="alert"></div>
          </div>


          <div class="checkout-item">
            <div class="title-wrap">
              <div class="title">{{ __('front/checkout.order_comment') }}</div>
            </div>
            <div class="checkout-select">
              <textarea class="form-control" rows="4" v-model="current.comment" placeholder="{{ __('front/checkout.order_comment') }}"></textarea>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-5">
        <div class="checkout-data">
          <div class="checkout-data-content">
            <div class="title-wrap">
              <div class="title">{{ __('front/checkout.my_order') }}</div>
            </div>
            <div class="products-table">
              <div class="products-table-title"><span>{{ __('front/cart.product') }}</span><span class="text-end">{{ __('front/cart.price') }}</span></div>
              <div class="products-table-wrap">
                @foreach ($cart_list as $product)
                  <div class="products-table-list">
                    <div>
                      <div class="product-item">
                        <div class="product-image"><img
                              src="{{ $product['image'] }}" class="img-fluid"></div>
                        <div class="product-info">
                          <div class="name">{{ $product['product_name'] }}</div>
                          <div class="sku mt-2 text-secondary">{{ $product['sku_code'] }}
                            @if ($product['variant_label']) - {{ $product['variant_label'] }} @endif
                            x {{ $product['quantity'] }}
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="text-end">{{ $product['price_format'] }}</div>
                  </div>
                @endforeach
              </div>
            </div>

            <ul class="cart-data-list">
              <li class="cart-data-list" v-for="fee in source.feeList" :key="fee.title">
                <span>@{{ fee.title }}</span><span> @{{ fee.total_format }} </span>
              </li>
              <li><span>{{ __('front/cart.total') }}</span><span>@{{ source.totalAmount }}</span></li>
            </ul>

            @hookinsert('checkout.confirm.before')
            {{-- <button class="btn btn-primary btn-lg fw-bold w-100 to-checkout" :disabled="isCheckout"
                    type="button" @click="submitCheckout">{{ __('front/checkout.place_order') }}
            </button> --}}
            <button class="btn btn-primary btn-lg fw-bold w-100 to-checkout" :disabled="isCheckout"
        type="button" @click="submitCheckout">{{ __('front/checkout.place_order') }}
</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  @hookinsert('checkout.bottom')

@endsection

@push('footer')
  <script>

const stripe = Stripe('{{ env('STRIPE_PUBLIC_KEY') }}');
const elements = stripe.elements();
const cardElement = elements.create('card');
cardElement.mount('#card-element');

    const {createApp, ref, reactive, onMounted, computed} = Vue
    const api = {
      address: @json(front_route('addresses.store')),
      checkout: @json(front_route('checkout.index')),
      checkoutConfirm: @json(front_route('checkout.confirm')),
    }

    const addressApp = createApp({
      setup() {
        const source = reactive({
          addresses: @json($address_list),
          shippingMethods: @json($shipping_methods),
          billingMethods: @json($billing_methods),
          addressEdit: @json($address_list).length ? false : true,
          same_as_shipping_address: true,
          feeList: @json($fee_list),
          totalAmount: @json(currency_format($total)),
        })

        const current = reactive({
          shipping_address_id: @json($checkout['shipping_address_id'] ?? 0),
          billing_address_id: @json($checkout['billing_address_id'] ?? 0),
          shipping_method_code: @json($checkout['shipping_method_code'] ?? ''),
          billing_method_code: @json($checkout['billing_method_code'] ?? ''),
          comment: '',
        })

        const isCheckout = computed(() => {
          return !current.shipping_address_id || !current.billing_address_id || !current.shipping_method_code || !current.billing_method_code
        })

        editAddress = (index) => {
          source.addressEdit = true
          const address = source.addresses[index]

          getZones(address.country_code, function () {
            $('.address-form').find('input, select').each(function () {
              $(this).val(address[$(this).attr('name')])
            })
          })
        }

        const updataAddress = (params) => {
          const id = new URLSearchParams(params).get('id');
          const url = id ? api.address + '/' + id : api.address
          const method = id ? 'put' : 'post'
          axios[method](url, params).then(function (res) {
            if (res.success) {
              inno.msg(res.message)
              if (id) {
                const index = source.addresses.findIndex(address => address.id == id)
                source.addresses[index] = res.data
              } else {
                source.addresses.push(res.data)

                if (source.addresses.length == 1) {
                  current.shipping_address_id = res.data.id
                  current.billing_address_id = res.data.id
                  updateCheckout('shipping_address_id', res.data.id)
                }
              }

              source.addressEdit = false
              clearForm()
            }
          })
        }

        const addressEdit = (status) => {
          source.addressEdit = status
          clearForm()
        }

        const updateCheckout = (key, value) => {
          current[key] = value
          if (source.same_as_shipping_address && key == 'shipping_address_id') {
            current.billing_address_id = value
          }

          axios.put(api.checkout, current).then(function (res) {
            if (res.success) {
              source.feeList = res.data.fee_list
              source.totalAmount = res.data.amount_format
              //window.location.href = '{{ front_route('checkout.index') }}'
            }
          })
        }

        // const submitCheckout = () => {
        //   layer.load(2, {shade: [0.3, '#fff']})
        //   axios.post(api.checkoutConfirm, current).then(function (res) {
        //     if (res.success) {
        //       layer.msg(res.message, {time: 1000}, function () {
        //         // location.href = '{{ front_route('checkout.success') }}?order_number=' + res.data.number;
        //         location.href = inno.getBase() + '/orders/' + res.data.number + '/pay'
        //       })
        //     }
        //   }).finally(function () {
        //     layer.closeAll('loading')
        //   });
        // }
        const submitCheckout = async () => {
  layer.load(2, {shade: [0.3, '#fff']});

  // Create a payment intent on your server
  const response = await axios.post('/create-payment-intent', {
    amount: Math.round(source.totalAmount * 100), // Convert to cents
  });

  const clientSecret = response.data.clientSecret;

  // Confirm the payment with Stripe.js
  const result = await stripe.confirmCardPayment(clientSecret, {
    payment_method: {
      card: cardElement,
      billing_details: {
        name: current.name, // Make sure to pass the customer's name
      },
    },
  });

  if (result.error) {
    // Show error to your customer
    layer.msg(result.error.message);
  } else {
    if (result.paymentIntent.status === 'succeeded') {
      // The payment has been processed!
      layer.msg('Payment succeeded!', {time: 1000}, function () {
        location.href = inno.getBase() + '/orders/' + result.paymentIntent.id + '/pay';
      });
    }
  }

  layer.closeAll('loading');
};


        const login = () => {
          inno.openLogin()
        }

        return {
          source,
          login,
          current,
          editAddress,
          updateCheckout,
          addressEdit,
          isCheckout,
          updataAddress,
          submitCheckout,
        }
      }
    }).mount('#app-checkout')

    function updataAddress(params) {
      addressApp.updataAddress(params)
    }
  </script>
  <script>
    // First, ensure you have the proper Stripe initialization
const stripe = Stripe('{{ env('STRIPE_PUBLIC_KEY') }}');
const elements = stripe.elements();
const style = {
  base: {
    color: '#32325d',
    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
    fontSmoothing: 'antialiased',
    fontSize: '16px',
    '::placeholder': {
      color: '#aab7c4'
    }
  },
  invalid: {
    color: '#fa755a',
    iconColor: '#fa755a'
  }
};

const cardElement = elements.create('card', { style });
cardElement.mount('#card-element');

// Handle real-time validation errors from the card Element
cardElement.on('change', function(event) {
  const displayError = document.getElementById('card-errors');
  if (event.error) {
    displayError.textContent = event.error.message;
  } else {
    displayError.textContent = '';
  }
});

// Update the submitCheckout method in your Vue app
const submitCheckout = async () => {
  try {
    layer.load(2, { shade: [0.3, '#fff'] });

    // First, submit the order to your backend to create it
    const orderResponse = await axios.post(api.checkoutConfirm, current);

    if (!orderResponse.data.success) {
      throw new Error(orderResponse.data.message);
    }

    // Create a payment intent
    const paymentResponse = await axios.post('/api/create-payment-intent', {
      order_number: orderResponse.data.data.number,
      amount: orderResponse.data.data.total,
      currency: 'usd' // Change this according to your currency
    });

    if (!paymentResponse.data.clientSecret) {
      throw new Error('Failed to create payment intent');
    }

    // Confirm the card payment
    const result = await stripe.confirmCardPayment(paymentResponse.data.clientSecret, {
      payment_method: {
        card: cardElement,
        billing_details: {
          name: current.billing_address.name,
          email: current.billing_address.email,
          phone: current.billing_address.phone,
          address: {
            line1: current.billing_address.address_1,
            line2: current.billing_address.address_2,
            city: current.billing_address.city,
            state: current.billing_address.state,
            postal_code: current.billing_address.zipcode,
            country: current.billing_address.country_code
          }
        }
      }
    });

    if (result.error) {
      throw new Error(result.error.message);
    }

    if (result.paymentIntent.status === 'succeeded') {
      // Update the order status on your backend
      await axios.post(`/api/orders/${orderResponse.data.data.number}/payment-completed`, {
        payment_intent_id: result.paymentIntent.id,
        payment_method_id: result.paymentIntent.payment_method,
        status: result.paymentIntent.status
      });

      layer.msg('Payment successful!', { time: 1000 }, function() {
        location.href = `${inno.getBase()}/checkout/success?order_number=${orderResponse.data.data.number}`;
      });
    }
  } catch (error) {
    layer.msg(error.message || 'Payment failed. Please try again.');
  } finally {
    layer.closeAll('loading');
  }
};
    </script>
@endpush
