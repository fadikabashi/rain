@extends((Session::get('locale') ==='ar'? 'layouts.main-rtl' : 'layouts.main'))
@section('content')
<div class="breadcrumb-area bg-grey">
    <div class="container">
        <div class="ho-breadcrumb">
            <ul>
                <li><a href="/">{{__('frontend.home')}}</a></li>
                <li><a href="/products">{{__('frontend.shop')}}</a></li>
                <li>{{__("frontend.checkout")}}</li>
            </ul>
        </div>
    </div>
</div>
<div class="checkout-area bg-white ptb-30">
    <div class="container">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <strong>{{ __('frontend.checkout_error') }}</strong> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger mb-4" role="alert">
                <strong>{{ __('frontend.checkout_validation_errors') }}</strong>
                <ul class="mb-0 mt-2 pl-3">
                    @foreach($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
<form action="/check" class="billing-info" method="post">
    @csrf
    <div class="row">
        <input type="hidden" value="{{ session('final_total') ?? Cart::total() }}" name="total" id="hidden-total">
        <input type="hidden" value="{{ session('coupon_id') ?? '' }}" name="coupon_id" id="hidden-coupon-id">
        <!-- Billing Details -->
        <div class="col-lg-6">

            <h3 class="small-title">{{__("frontend.billing")}}</h3>
            <div class="ho-form">
                <div class="ho-form-inner">
                    <div class="single-input single-input-half">
                        <label for="customer-firstname"> {{__("frontend.name")}} *</label>
                        <input type="text" name="costumer_name" id="customer-firstname" 
                               class="@error('costumer_name') is-invalid @enderror"
                               value="{{ auth()->check() ? auth()->user()->name : old('costumer_name') }}" 
                               required>
                        @error('costumer_name')
                            <span class="text-danger d-block" style="font-size: 13px; margin-top: 4px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="single-input single-input-half">
                        <label for="customer-phone">{{__("frontend.phone")}} *</label>
                        <input type="text" name="costumer_number" id="customer-phone" 
                               class="@error('costumer_number') is-invalid @enderror"
                               value="{{ auth()->check() ? auth()->user()->phone : old('costumer_number') }}" 
                               required>
                        @error('costumer_number')
                            <span class="text-danger d-block" style="font-size: 13px; margin-top: 4px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="single-input">
                        <label for="customer-address">{{__("frontend.address")}}*</label>
                        <input type="text" name="address" id="customer-address"
                            class="@error('address') is-invalid @enderror"
                            placeholder="{{__('frontend.address')}}" 
                            value="{{ $lastOrderAddress ?? old('address') }}"
                            required>
                        @error('address')
                            <span class="text-danger d-block" style="font-size: 13px; margin-top: 4px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="single-input">
                        <label for="customer-note">{{__("frontend.note")}}</label>
                        <input type="text" name="note" id="customer-note"
                            class="@error('note') is-invalid @enderror"
                            placeholder="{{__('frontend.note')}}"
                            value="{{ old('note') }}">
                        @error('note')
                            <span class="text-danger d-block" style="font-size: 13px; margin-top: 4px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="single-input">
                        <label for="coupon-code">{{__("frontend.coupon_code")}} ({{__("frontend.optional")}})</label>
                        <div style="display: flex; gap: 10px; align-items: flex-start;">
                            <input type="text" name="coupon_code" id="coupon-code"
                                placeholder="{{__('frontend.enter_coupon_code')}}" 
                                value="{{ session('coupon_code') ?? old('coupon_code') }}"
                                style="flex: 1;"
                                @if(session('coupon_id')) disabled @endif>
                            <div id="coupon-button-container">
                                @if(!session('coupon_id'))
                                <button type="button" onclick="applyCoupon()" id="apply-coupon-btn" class="ho-button ho-button-sm">
                                    {{__('frontend.apply')}}
                                </button>
                                @else
                                <button type="button" onclick="removeCoupon()" id="remove-coupon-btn" class="ho-button ho-button-sm" style="background: #dc3545;">
                                    {{__('frontend.remove')}}
                                </button>
                                @endif
                            </div>
                        </div>
                        <div id="coupon-message" style="margin-top: 5px;">
                            @if(session('coupon_error'))
                                <span class="text-danger" style="font-size: 12px; display: block;">{{ session('coupon_error') }}</span>
                            @endif
                            @if(session('coupon_success'))
                                <span class="text-success" style="font-size: 12px; display: block;">{{ session('coupon_success') }}</span>
                            @endif
                        </div>
                    </div>
                    <script>
                    // Get CSRF token from meta tag
                    function getCsrfToken() {
                        const metaTag = document.querySelector('meta[name="csrf-token"]');
                        return metaTag ? metaTag.getAttribute('content') : '{{ csrf_token() }}';
                    }

                    async function applyCoupon() {
                        const code = document.getElementById('coupon-code').value.trim();
                        if (!code) {
                            showCouponMessage('{{__('frontend.please_enter_coupon_code')}}', 'error');
                            return;
                        }

                        const btn = document.getElementById('apply-coupon-btn');
                        const originalText = btn.innerHTML;
                        btn.disabled = true;
                        btn.innerHTML = 'Loading...';

                        try {
                            const response = await fetch('{{ route('apply.coupon') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': getCsrfToken(),
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ coupon_code: code }),
                                credentials: 'same-origin'
                            });

                            let data;
                            try {
                                data = await response.json();
                            } catch (e) {
                                console.error('Failed to parse JSON response:', e);
                                showCouponMessage('An error occurred. Please try again.', 'error');
                                return;
                            }

                            if (!response.ok) {
                                console.error('Request failed:', response.status, data);
                                
                                // Handle CSRF token mismatch
                                if (response.status === 419 || (data.message && data.message.includes('CSRF'))) {
                                    showCouponMessage('Session expired. Please refresh the page and try again.', 'error');
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 2000);
                                    return;
                                }
                                
                                // Handle validation errors
                                if (data.errors) {
                                    // Laravel validation errors format
                                    if (data.errors.coupon_code) {
                                        showCouponMessage(data.errors.coupon_code[0], 'error');
                                    } else {
                                        // Get first error from any field
                                        const firstError = Object.values(data.errors)[0];
                                        showCouponMessage(Array.isArray(firstError) ? firstError[0] : firstError, 'error');
                                    }
                                } else if (data.message) {
                                    showCouponMessage(data.message, 'error');
                                } else {
                                    showCouponMessage('Failed to apply coupon. Please try again.', 'error');
                                }
                                return;
                            }

                            if (data.success) {
                                showCouponMessage(data.message, 'success');
                                updateOrderTotals(data.data);
                                updateCouponUI(true);
                            } else {
                                showCouponMessage(data.message || 'Failed to apply coupon', 'error');
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            showCouponMessage('An error occurred. Please try again.', 'error');
                        } finally {
                            btn.disabled = false;
                            btn.innerHTML = originalText;
                        }
                    }

                    async function removeCoupon() {
                        const btn = document.getElementById('remove-coupon-btn');
                        const originalText = btn.innerHTML;
                        btn.disabled = true;
                        btn.innerHTML = 'Loading...';

                        try {
                            const response = await fetch('{{ route('remove.coupon') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': getCsrfToken(),
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                },
                                credentials: 'same-origin'
                            });

                            let data;
                            try {
                                data = await response.json();
                            } catch (e) {
                                console.error('Failed to parse JSON response:', e);
                                showCouponMessage('An error occurred. Please try again.', 'error');
                                return;
                            }

                            if (!response.ok) {
                                // Handle CSRF token mismatch
                                if (response.status === 419 || (data.message && data.message.includes('CSRF'))) {
                                    showCouponMessage('Session expired. Please refresh the page and try again.', 'error');
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 2000);
                                    return;
                                }
                                
                                showCouponMessage(data.message || 'Failed to remove coupon', 'error');
                                return;
                            }

                            if (data.success) {
                                showCouponMessage(data.message, 'success');
                                updateOrderTotals(data.data);
                                updateCouponUI(false);
                            } else {
                                showCouponMessage(data.message || 'Failed to remove coupon', 'error');
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            showCouponMessage('An error occurred. Please try again.', 'error');
                        } finally {
                            btn.disabled = false;
                            btn.innerHTML = originalText;
                        }
                    }

                    function showCouponMessage(message, type) {
                        const messageDiv = document.getElementById('coupon-message');
                        const className = type === 'success' ? 'text-success' : 'text-danger';
                        messageDiv.innerHTML = `<span class="${className}" style="font-size: 12px; display: block;">${message}</span>`;
                        
                        // Clear message after 5 seconds
                        setTimeout(() => {
                            messageDiv.innerHTML = '';
                        }, 5000);
                    }

                    function updateOrderTotals(data) {
                        // Update hidden inputs
                        document.getElementById('hidden-total').value = data.final_total;
                        document.getElementById('hidden-coupon-id').value = data.coupon_id || '';

                        // Update subtotal if it exists
                        const subtotalRow = document.getElementById('subtotal-row');
                        const subtotalAmount = document.getElementById('subtotal-amount');
                        if (subtotalAmount) {
                            subtotalAmount.textContent = data.subtotal + ' KWD';
                        }

                        // Update discount row
                        const discountRow = document.getElementById('discount-row');
                        const discountAmount = document.getElementById('discount-amount');
                        const orderTotals = document.getElementById('order-totals');

                        if (data.coupon_discount && data.coupon_discount > 0) {
                            // Show discount row if it doesn't exist
                            if (!discountRow) {
                                const finalTotalRow = document.querySelector('#order-totals .total-price');
                                const newSubtotalRow = document.createElement('tr');
                                newSubtotalRow.id = 'subtotal-row';
                                newSubtotalRow.innerHTML = `
                                    <th class="text-left">{{__("frontend.subtotal")}}</th>
                                    <td class="text-right" id="subtotal-amount">${data.subtotal} KWD</td>
                                `;
                                const newDiscountRow = document.createElement('tr');
                                newDiscountRow.id = 'discount-row';
                                newDiscountRow.innerHTML = `
                                    <th class="text-left">{{__("frontend.coupon_discount")}}</th>
                                    <td class="text-right text-success" id="discount-amount">-${data.coupon_discount} KWD</td>
                                `;
                                finalTotalRow.parentNode.insertBefore(newSubtotalRow, finalTotalRow);
                                finalTotalRow.parentNode.insertBefore(newDiscountRow, finalTotalRow);
                            } else {
                                discountAmount.textContent = '-' + data.coupon_discount + ' KWD';
                            }
                        } else {
                            // Remove discount rows if no discount
                            if (subtotalRow) subtotalRow.remove();
                            if (discountRow) discountRow.remove();
                        }

                        // Update final total
                        const finalTotalAmount = document.getElementById('final-total-amount');
                        if (finalTotalAmount) {
                            finalTotalAmount.textContent = data.final_total + ' KWD';
                        }
                    }

                    function updateCouponUI(couponApplied) {
                        const couponCodeInput = document.getElementById('coupon-code');
                        const buttonContainer = document.getElementById('coupon-button-container');

                        if (couponApplied) {
                            couponCodeInput.disabled = true;
                            buttonContainer.innerHTML = `
                                <button type="button" onclick="removeCoupon()" id="remove-coupon-btn" class="ho-button ho-button-sm" style="background: #dc3545;">
                                    {{__('frontend.remove')}}
                                </button>
                            `;
                        } else {
                            couponCodeInput.disabled = false;
                            couponCodeInput.value = '';
                            buttonContainer.innerHTML = `
                                <button type="button" onclick="applyCoupon()" id="apply-coupon-btn" class="ho-button ho-button-sm">
                                    {{__('frontend.apply')}}
                                </button>
                            `;
                        }
                    }
                    </script>
                </div>
            </div>


        </div>
        <!--// Billing Details -->


        <!-- Place Order -->
        <div class="col-lg-6">
            <div class="order-infobox">
                <h3 class="small-title">{{__("frontend.your_order")}}</h3>
                <div class="checkout-table table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-left">{{__("frontend.total")}}</th>
                                <th class="text-right">{{__("frontend.product")}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cartItems as $cartItem)
                        <tr>
                        <td class="text-left"> {{$cartItem['name']}} <span>× {{$cartItem['quantity']}}</span></td>
                        <td class="text-right">{{$cartItem['price'] * $cartItem['quantity']}} KWD </td>  
                         </tr>
                          @endforeach
                        </tbody>
                        <tfoot id="order-totals">
                            @if(session('coupon_discount'))
                            <tr id="subtotal-row">
                                <th class="text-left">{{__("frontend.subtotal")}}</th>
                                <td class="text-right" id="subtotal-amount">{{ Cart::total() }} KWD</td>
                            </tr>
                            <tr id="discount-row">
                                <th class="text-left">{{__("frontend.coupon_discount")}}</th>
                                <td class="text-right text-success" id="discount-amount">-{{ session('coupon_discount') }} KWD</td>
                            </tr>
                            @endif
                            <tr class="total-price">
                                <th class="text-left">{{__("frontend.cart_totals")}}</th>
                                <td class="text-right" id="final-total-amount">
                                    @if(session('final_total'))
                                        {{ session('final_total') }} KWD
                                    @else
                                        {{ Cart::total() }} KWD
                                    @endif
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <button class="ho-button ho-button-fullwidth mt-30" type="submit">
                    <span>{{__("frontend.comfirm")}}</span>
                </button>
            </div>
        </div>
        <!--// Place Order -->

    </div>
</form>
    </div>
</div>
@endsection