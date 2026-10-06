@extends('layouts.app')
@section('title', __('messages.quote_request_details'))
@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">{{ __('messages.quote_request_details') }} #{{ $quoteRequest->id }}</h2>
            </div>
        </div>
    </div>
</div>

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('messages.request_details') }}</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>{{ __('messages.product') }}:</strong>
                        <div>{{ $quoteRequest->items->count() }} {{ __('frontend.product') }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>{{ __('messages.quantity') }}:</strong> {{ $quoteRequest->items->sum('quantity') }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>{{ __('messages.customer_name') }}:</strong> {{ $quoteRequest->customer_name }}
                    </div>
                    <div class="col-md-6">
                        <strong>{{ __('messages.email') }}:</strong> {{ $quoteRequest->customer_email }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>{{ __('messages.phone') }}:</strong> {{ $quoteRequest->customer_phone }}
                    </div>
                    <div class="col-md-6">
                        <strong>{{ __('messages.company') }}:</strong> {{ $quoteRequest->company_name ?? '-' }}
                    </div>
                </div>

                @if($quoteRequest->message)
                <div class="mb-3">
                    <strong>{{ __('messages.message') }}:</strong>
                    <p class="mt-2">{{ $quoteRequest->message }}</p>
                </div>
                @endif

                <div class="table-responsive mt-3">
                    <table class="table table-bordered">
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
                                        @if($item->product)
                                            {{ session('locale') === 'ar' ? $item->product->name_ar : $item->product->name_en }}
                                        @else
                                            {{ __('messages.general_inquiry') }}
                                        @endif
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>
                                        <input type="number"
                                            form="quote-update-form"
                                            name="items[{{ $loop->index }}][quoted_price]"
                                            class="form-control"
                                            step="0.01"
                                            min="0"
                                            value="{{ $item->quoted_price }}">
                                        <input type="hidden"
                                            form="quote-update-form"
                                            name="items[{{ $loop->index }}][id]"
                                            value="{{ $item->id }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mb-3">
                    <strong>{{ __('messages.request_date') }}:</strong> {{ $quoteRequest->created_at->format('Y-m-d H:i') }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('messages.update_quote') }}</h4>
            </div>
            <div class="card-body">
                <form id="quote-update-form" action="{{ route('admin.quote-requests.update', $quoteRequest->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>{{ __('messages.status') }} *</label>
                        <select name="status" class="form-control" required>
                            <option value="pending" {{ $quoteRequest->status === 'pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                            <option value="quoted" {{ $quoteRequest->status === 'quoted' ? 'selected' : '' }}>{{ __('messages.quoted') }}</option>
                            <option value="accepted" {{ $quoteRequest->status === 'accepted' ? 'selected' : '' }}>{{ __('messages.accepted') }}</option>
                            <option value="rejected" {{ $quoteRequest->status === 'rejected' ? 'selected' : '' }}>{{ __('messages.rejected') }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>{{ __('messages.admin_notes') }}</label>
                        <textarea name="admin_notes" class="form-control" rows="4">{{ $quoteRequest->admin_notes }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">{{ __('messages.update_quote_button') }}</button>
                </form>

                <div class="mt-3">
                    <a href="{{ route('admin.quote-requests.index') }}" class="btn btn-secondary btn-block">{{ __('messages.back_to_list') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
