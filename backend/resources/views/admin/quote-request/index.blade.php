@extends('layouts.app')
@section('title', __('messages.quote_requests_management'))
@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">{{ __('messages.quote_requests_management') }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('messages.all_quote_requests') }}</h4>
                <div class="card-header-right">
                    <form method="GET" action="{{ route('admin.quote-requests.index') }}" class="d-inline-flex">
                        <select name="status" class="form-control mr-2">
                            <option value="">{{ __('messages.all_status') }}</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                            <option value="quoted" {{ request('status') === 'quoted' ? 'selected' : '' }}>{{ __('messages.quoted') }}</option>
                            <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>{{ __('messages.accepted') }}</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>{{ __('messages.rejected') }}</option>
                        </select>
                        <input type="text" name="search" class="form-control mr-2" placeholder="{{ __('messages.search_by_name_title_comment') }}" value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">{{ __('messages.filter') }}</button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('messages.id') }}</th>
                                <th>{{ __('messages.product') }}</th>
                                <th>{{ __('messages.customer') }}</th>
                                <th>{{ __('messages.quantity') }}</th>
                                <th>{{ __('messages.status') }}</th>
                                <th>{{ __('messages.quoted_price') }}</th>
                                <th>{{ __('messages.date') }}</th>
                                <th>{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($quoteRequests as $quoteRequest)
                            <tr>
                                <td>{{ $quoteRequest->id }}</td>
                                <td>
                                    @php $firstItem = $quoteRequest->items->first(); @endphp
                                    @if($firstItem && $firstItem->product)
                                        <a href="/details/{{ $firstItem->product_id }}" target="_blank">
                                            {{ Session::get('locale') === 'ar' ? $firstItem->product->name_ar : $firstItem->product->name_en }}
                                        </a>
                                        @if($quoteRequest->items->count() > 1)
                                            <small class="d-block text-muted">+{{ $quoteRequest->items->count() - 1 }} more</small>
                                        @endif
                                    @else
                                        <span class="text-muted">{{ __('messages.general_inquiry') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $quoteRequest->customer_name }}</div>
                                    <small class="text-muted">{{ $quoteRequest->customer_email }}</small>
                                    <div><small>{{ $quoteRequest->customer_phone }}</small></div>
                                </td>
                                <td>{{ $quoteRequest->items->sum('quantity') }}</td>
                                <td>
                                    @if($quoteRequest->status === 'pending')
                                        <span class="badge badge-warning">{{ __('messages.pending') }}</span>
                                    @elseif($quoteRequest->status === 'quoted')
                                        <span class="badge badge-info">{{ __('messages.quoted') }}</span>
                                    @elseif($quoteRequest->status === 'accepted')
                                        <span class="badge badge-success">{{ __('messages.accepted') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('messages.rejected') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($quoteRequest->quoted_total)
                                        {{ number_format((float) $quoteRequest->quoted_total, 2) }} KWD
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $quoteRequest->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('admin.quote-requests.show', $quoteRequest->id) }}" class="btn btn-sm btn-primary">{{ __('messages.view_update') }}</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">{{ __('messages.no_quote_requests_found') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $quoteRequests->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
