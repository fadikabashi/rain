@extends((Session::get('locale') === 'ar' ? 'layouts.main-rtl' : 'layouts.main'))

@section('content')
<div class="breadcrumb-area bg-grey">
    <div class="container">
        <div class="ho-breadcrumb">
            <ul>
                <li><a href="/">{{ __('frontend.home') }}</a></li>
                <li><a href="{{ route('customer.account.quote_requests') }}">{{ __('frontend.quote_request') }}</a></li>
                <li>#{{ $quoteRequest->id }}</li>
            </ul>
        </div>
    </div>
</div>

<div class="account-dashboard-area bg-white ptb-30">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-4">
                @include('customer.partials.sidebar')
            </div>
            <div class="col-lg-9 col-md-8">
                <h3>{{ __('messages.quote_request_details') }} #{{ $quoteRequest->id }}</h3>
                <p class="mb-2">
                    <button
                        type="button"
                        class="btn btn-outline-primary btn-sm"
                        data-quote-pdf-url="{{ route('customer.account.quote_requests.pdf-data', $quoteRequest) }}"
                        data-quote-pdf-loading="{{ __('frontend.quote_pdf_download_loading') }}"
                    >{{ __('frontend.download_quote_pdf') }}</button>
                </p>
                <p><strong>{{ __('messages.status') }}:</strong> {{ ucfirst($quoteRequest->status) }}</p>
                <p><strong>{{ __('messages.quoted_price') }}:</strong> {{ $quoteRequest->quoted_total ? number_format((float) $quoteRequest->quoted_total, 2) . ' KWD' : '-' }}</p>

                <div class="table-responsive mt-3">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('messages.product') }}</th>
                                <th>{{ __('messages.quantity') }}</th>
                                <th>{{ __('messages.quoted_price') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quoteRequest->items as $item)
                                <tr>
                                    <td>
                                        @if ($item->product)
                                            {{ Session::get('locale') === 'ar' ? $item->product->name_ar : $item->product->name_en }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->quoted_price ? number_format((float) $item->quoted_price, 2) . ' KWD' : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/quote-pdf.js'])
@endpush
