@extends((Session::get('locale') === 'ar' ? 'layouts.main-rtl' : 'layouts.main'))

@push('styles')
<link rel="stylesheet" href="{{ asset('/app-assets/vendors/css/forms/select/select2.min.css') }}">
<style>
    .quote-select2-wrap .select2-container--default .select2-selection--single {
        min-height: 38px;
        padding-top: 4px;
    }
    .quote-select2-wrap .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
    .quote-select2-wrap .select2-container {
        width: 100% !important;
    }
    select.quote-product-select.select2-hidden-accessible {
        position: absolute !important;
        width: 1px !important;
        height: 1px !important;
        padding: 0 !important;
        margin: 0 !important;
        overflow: hidden !important;
        clip: rect(0, 0, 0, 0) !important;
        white-space: nowrap !important;
        border: 0 !important;
    }
</style>
@endpush

@section('content')
<div class="breadcrumb-area bg-grey">
    <div class="container">
        <div class="ho-breadcrumb">
            <ul>
                <li><a href="/">{{ __('frontend.home') }}</a></li>
                <li>{{ __('frontend.request_quote') }}</li>
            </ul>
        </div>
    </div>
</div>

<div class="checkout-area bg-white ptb-30">
    <div class="container">
        @if (session('quote_request_success'))
            <div class="alert alert-success d-flex flex-wrap align-items-center gap-2 justify-content-between">
                <span>{{ session('quote_request_success') }}</span>
                @if (session('quote_pdf_data_url'))
                    <button
                        type="button"
                        class="btn btn-primary btn-sm"
                        data-quote-pdf-url="{{ session('quote_pdf_data_url') }}"
                        data-quote-pdf-loading="{{ __('frontend.quote_pdf_download_loading') }}"
                    >{{ __('frontend.download_quote_pdf') }}</button>
                @endif
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('quote-request.store') }}" method="POST" id="quoteBatchForm">
            @csrf
            <div class="row">
                <div class="col-lg-8">
                    <div class="your-order mb-4">
                        <h3>{{ __('frontend.quote_request') }}</h3>
                        <div id="quoteItemsContainer">
                            @php
                                $oldItems = old('items', []);
                                if (empty($oldItems)) {
                                    $oldItems = [['product_id' => $initialProductId, 'quantity' => 1, 'line_note' => null]];
                                }
                            @endphp
                            @foreach ($oldItems as $index => $item)
                                <div class="quote-item-row card p-3 mb-3" data-index="{{ $index }}">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <label>{{ __('frontend.product') }} *</label>
                                            <div class="quote-select2-wrap">
                                            <select class="quote-product-select" name="items[{{ $index }}][product_id]" required>
                                                <option value="">{{ __('frontend.select_product') }}</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}" @selected((int) ($item['product_id'] ?? 0) === $product->id)>
                                                        {{ Session::get('locale') === 'ar' ? $product->name_ar : $product->name_en }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label>{{ __('frontend.quantity') }} *</label>
                                            <input type="number" min="1" class="form-control" name="items[{{ $index }}][quantity]" value="{{ $item['quantity'] ?? 1 }}" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label>{{ __('frontend.your_message') }}</label>
                                            <input type="text" class="form-control" name="items[{{ $index }}][line_note]" value="{{ $item['line_note'] ?? '' }}">
                                        </div>
                                        <div class="col-md-1 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger btn-sm remove-item">&times;</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="addItemRow" class="btn btn-outline-primary mt-2">{{ __('frontend.add_product') }}</button>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="your-order mb-4">
                        <h3>{{ __('frontend.customer_details') }}</h3>
                        <div class="checkout-form">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label>{{ __('frontend.your_name') }} *</label>
                                    <input type="text" class="form-control" name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}" required>
                                </div>
                                <div class="col-12 mb-3">
                                    <label>{{ __('frontend.your_email') }} *</label>
                                    <input type="email" class="form-control" name="customer_email" value="{{ old('customer_email', auth()->user()->email ?? '') }}" required>
                                </div>
                                <div class="col-12 mb-3">
                                    <label>{{ __('frontend.phone') }} *</label>
                                    <input type="text" class="form-control" name="customer_phone" value="{{ old('customer_phone', auth()->user()->phone ?? '') }}" required>
                                </div>
                                <div class="col-12 mb-3">
                                    <label>{{ __('frontend.company_name') }}</label>
                                    <input type="text" class="form-control" name="company_name" value="{{ old('company_name') }}">
                                </div>
                                <div class="col-12 mb-3">
                                    <label>{{ __('frontend.your_message') }}</label>
                                    <textarea class="form-control" rows="4" name="message">{{ old('message') }}</textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="ho-button ho-button-fullwidth">{{ __('frontend.submit') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/quote-pdf.js'])
<script>
(function ($) {
    'use strict';

    window.quoteProductSelectPlaceholder = @json(__('frontend.select_product'));
    window.quoteProductSelectIsRtl = @json(Session::get('locale') === 'ar');

    const products = @json($products->map(fn ($product) => [
        'id' => $product->id,
        'name' => Session::get('locale') === 'ar' ? $product->name_ar : $product->name_en,
    ]));
    const container = document.getElementById('quoteItemsContainer');
    const addButton = document.getElementById('addItemRow');

    function dropdownParent() {
        const $parent = $('#quoteBatchForm').closest('.checkout-area');
        return $parent.length ? $parent : $(document.body);
    }

    function destroyNiceSelectIfPresent($select) {
        if ($select.next('.nice-select').length) {
            try {
                $select.niceSelect('destroy');
            } catch (e) {
                $select.next('.nice-select').remove();
                $select.css('display', '');
            }
        }
    }

    function initQuoteLineSelect($select) {
        if (!$select.length || $select.hasClass('select2-hidden-accessible')) {
            return;
        }

        destroyNiceSelectIfPresent($select);

        $select.select2({
            width: '100%',
            placeholder: window.quoteProductSelectPlaceholder,
            allowClear: true,
            minimumResultsForSearch: 0,
            dropdownParent: dropdownParent(),
            dir: window.quoteProductSelectIsRtl ? 'rtl' : 'ltr',
        });
    }

    function destroyQuoteLineSelect($select) {
        if ($select.length && $select.hasClass('select2-hidden-accessible')) {
            $select.select2('destroy');
        }
    }

    function buildProductOptions() {
        return ['<option value="">' + $('<div>').text(window.quoteProductSelectPlaceholder).html() + '</option>']
            .concat(products.map(function (product) {
                return '<option value="' + product.id + '">' + $('<div>').text(product.name).html() + '</option>';
            }))
            .join('');
    }

    function refreshNames() {
        const rows = container.querySelectorAll('.quote-item-row');
        rows.forEach(function (row, index) {
            row.dataset.index = String(index);
            row.querySelector('select.quote-product-select').setAttribute('name', 'items[' + index + '][product_id]');
            row.querySelector('input[type="number"]').setAttribute('name', 'items[' + index + '][quantity]');
            row.querySelector('input[type="text"]').setAttribute('name', 'items[' + index + '][line_note]');
        });
    }

    $(function () {
        $('#quoteItemsContainer .quote-product-select').each(function () {
            initQuoteLineSelect($(this));
        });

        addButton.addEventListener('click', function () {
            const row = document.createElement('div');
            row.className = 'quote-item-row card p-3 mb-3';
            row.innerHTML =
                '<div class="row">' +
                    '<div class="col-md-5">' +
                        '<label>{{ __('frontend.product') }} *</label>' +
                        '<div class="quote-select2-wrap">' +
                        '<select class="quote-product-select" required>' + buildProductOptions() + '</select>' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<label>{{ __('frontend.quantity') }} *</label>' +
                        '<input type="number" min="1" class="form-control" value="1" required>' +
                    '</div>' +
                    '<div class="col-md-4">' +
                        '<label>{{ __('frontend.your_message') }}</label>' +
                        '<input type="text" class="form-control">' +
                    '</div>' +
                    '<div class="col-md-1 d-flex align-items-end">' +
                        '<button type="button" class="btn btn-danger btn-sm remove-item">&times;</button>' +
                    '</div>' +
                '</div>';
            container.appendChild(row);
            refreshNames();
            initQuoteLineSelect($(row).find('select.quote-product-select'));
        });

        container.addEventListener('click', function (event) {
            if (!event.target.classList.contains('remove-item')) {
                return;
            }

            const rows = container.querySelectorAll('.quote-item-row');
            if (rows.length <= 1) {
                return;
            }

            const row = event.target.closest('.quote-item-row');
            destroyQuoteLineSelect($(row).find('select.quote-product-select'));
            row.remove();
            refreshNames();
        });
    });
})(jQuery);
</script>
@endpush
