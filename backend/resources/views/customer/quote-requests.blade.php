@extends((Session::get('locale') === 'ar' ? 'layouts.main-rtl' : 'layouts.main'))
@section('content')
<div class="breadcrumb-area bg-grey">
    <div class="container">
        <div class="ho-breadcrumb">
            <ul>
                <li><a href="/">{{ __('frontend.home') }}</a></li>
                <li>{{ __('frontend.quote_request') }}</li>
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
                <h3 class="mb-3">{{ __('frontend.quote_request') }}</h3>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('messages.status') }}</th>
                                <th>{{ __('messages.quoted_price') }}</th>
                                <th>{{ __('messages.request_date') }}</th>
                                <th>{{ __('frontend.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($quoteRequests as $quoteRequest)
                                <tr>
                                    <td>#{{ $quoteRequest->id }}</td>
                                    <td>{{ ucfirst($quoteRequest->status) }}</td>
                                    <td>{{ $quoteRequest->quoted_total ? number_format((float) $quoteRequest->quoted_total, 2) . ' KWD' : '-' }}</td>
                                    <td>{{ $quoteRequest->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{ route('customer.account.quote_requests.details', $quoteRequest->id) }}" class="btn btn-sm btn-primary">
                                            {{ __('frontend.view_details') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">{{ __('messages.no_quote_requests_found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $quoteRequests->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
