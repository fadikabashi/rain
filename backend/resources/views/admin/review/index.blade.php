@extends('layouts.app')
@section('title', __('messages.reviews_management'))
@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">{{ __('messages.reviews_management') }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-2">
    <div class="col-lg-4 col-md-6 col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">{{ __('messages.total_reviews') }}</h6>
                        <h3 class="mb-0">{{ $stats['total'] ?? 0 }}</h3>
                    </div>
                    <div class="avatar bg-light-primary">
                        <div class="avatar-content">
                            <i class="feather icon-star font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">{{ __('messages.pending_reviews') }}</h6>
                        <h3 class="mb-0 text-warning">{{ $stats['pending'] ?? 0 }}</h3>
                    </div>
                    <div class="avatar bg-light-warning">
                        <div class="avatar-content">
                            <i class="feather icon-clock font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">{{ __('messages.approved_reviews') }}</h6>
                        <h3 class="mb-0 text-success">{{ $stats['approved'] ?? 0 }}</h3>
                    </div>
                    <div class="avatar bg-light-success">
                        <div class="avatar-content">
                            <i class="feather icon-check-circle font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('messages.all_reviews') }}</h4>
                <div class="card-header-right">
                    <form method="GET" action="{{ route('admin.reviews.index') }}" class="d-inline-flex align-items-center">
                        <select name="status" class="form-control mr-2" style="width: auto;">
                            <option value="">{{ __('messages.all_status') }}</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>{{ __('messages.approved') }}</option>
                        </select>
                        <select name="rating" class="form-control mr-2" style="width: auto;">
                            <option value="">{{ __('messages.all_ratings') }}</option>
                            <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 {{ __('messages.stars') }}</option>
                            <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 {{ __('messages.stars') }}</option>
                            <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 {{ __('messages.stars') }}</option>
                            <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 {{ __('messages.stars') }}</option>
                            <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 {{ __('messages.star') }}</option>
                        </select>
                        <input type="text" name="search" class="form-control mr-2" placeholder="{{ __('messages.search_by_name_title_comment') }}" value="{{ request('search') }}" style="width: 250px;">
                        <button type="submit" class="btn btn-primary mr-2">{{ __('messages.filter') }}</button>
                        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">{{ __('messages.clear') }}</a>
                    </form>
                </div>
            </div>
            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('messages.id') }}</th>
                                <th>{{ __('messages.product') }}</th>
                                <th>{{ __('messages.customer') }}</th>
                                <th>{{ __('messages.email') }}</th>
                                <th>{{ __('messages.rating') }}</th>
                                <th>{{ __('messages.title') }}</th>
                                <th>{{ __('messages.status') }}</th>
                                <th>{{ __('messages.helpful') }}</th>
                                <th>{{ __('messages.date') }}</th>
                                <th>{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reviews as $review)
                            <tr>
                                <td>{{ $review->id }}</td>
                                <td>
                                    <a href="/details/{{ $review->product_id }}" target="_blank" class="text-primary">
                                        <strong>{{ Session::get('locale') === 'ar' ? $review->product->name_ar : $review->product->name_en }}</strong>
                                    </a>
                                </td>
                                <td>
                                    <strong>{{ $review->customer_name }}</strong>
                                    @if($review->user)
                                        <br><small class="text-muted">{{ __('messages.user_id') }}: {{ $review->user_id }}</small>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $review->customer_email }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="lnr {{ $i <= $review->rating ? 'lnr-star' : 'lnr-star-empty' }}" style="color: #ffc107; font-size: 14px;"></i>
                                        @endfor
                                        <span class="ml-1">({{ $review->rating }})</span>
                                    </div>
                                </td>
                                <td>
                                    <span title="{{ $review->title }}">{{ Str::limit($review->title, 30) }}</span>
                                </td>
                                <td>
                                    @if($review->is_approved)
                                        <span class="badge badge-success">{{ __('messages.approved') }}</span>
                                    @else
                                        <span class="badge badge-warning">{{ __('messages.pending') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $review->helpful_count }}</span>
                                </td>
                                <td>{{ $review->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @if(!$review->is_approved)
                                        <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="{{ __('messages.approve_review') }}">
                                                <i class="feather icon-check"></i> {{ __('messages.approve') }}
                                            </button>
                                        </form>
                                        @else
                                        <button type="button" class="btn btn-sm btn-success disabled" title="{{ __('messages.already_approved') }}">
                                            <i class="feather icon-check"></i> {{ __('messages.approved') }}
                                        </button>
                                        @endif
                                        <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.confirm_reject_review') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="{{ __('messages.reject_review') }}">
                                                <i class="feather icon-x"></i> {{ __('messages.reject') }}
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#reviewModal{{ $review->id }}" title="{{ __('messages.view_full_review') }}">
                                            <i class="feather icon-eye"></i> {{ __('messages.view') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Review Modal -->
                            <div class="modal fade" id="reviewModal{{ $review->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ __('messages.review_details') }} #{{ $review->id }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <p><strong>{{ __('messages.product') }}:</strong><br>
                                                        <a href="/details/{{ $review->product_id }}" target="_blank" class="text-primary">
                                                            {{ session('locale') === 'ar' ? $review->product->name_ar : $review->product->name_en }}
                                                        </a>
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>{{ __('messages.date') }}:</strong><br>
                                                        {{ $review->created_at->format('Y-m-d H:i:s') }}
                                                    </p>
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <p><strong>{{ __('messages.customer_name') }}:</strong><br>
                                                        {{ $review->customer_name }}
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>{{ __('messages.email') }}:</strong><br>
                                                        <a href="mailto:{{ $review->customer_email }}">{{ $review->customer_email }}</a>
                                                    </p>
                                                </div>
                                            </div>

                                            @if($review->user)
                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <p><strong>{{ __('messages.user_account') }}:</strong><br>
                                                        <span class="badge badge-info">{{ __('messages.registered_user') }} ({{ __('messages.id') }}: {{ $review->user_id }})</span>
                                                    </p>
                                                </div>
                                            </div>
                                            @endif

                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <p><strong>{{ __('messages.rating') }}:</strong><br>
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="lnr {{ $i <= $review->rating ? 'lnr-star' : 'lnr-star-empty' }}" style="color: #ffc107; font-size: 20px;"></i>
                                                        @endfor
                                                        <span class="ml-2">({{ $review->rating }} {{ __('messages.out_of_5') }})</span>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <p><strong>{{ __('messages.title') }}:</strong><br>
                                                        <span class="h6">{{ $review->title }}</span>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <p><strong>{{ __('messages.comment') }}:</strong></p>
                                                    <div class="border p-3 rounded bg-light">
                                                        <p class="mb-0">{{ $review->comment }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <p><strong>{{ __('messages.status') }}:</strong>
                                                        @if($review->is_approved)
                                                            <span class="badge badge-success ml-2">{{ __('messages.approved') }}</span>
                                                        @else
                                                            <span class="badge badge-warning ml-2">{{ __('messages.pending_approval') }}</span>
                                                        @endif
                                                        @if($review->verified_purchase)
                                                            <span class="badge badge-primary ml-2">{{ __('messages.verified_purchase') }}</span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p><strong>{{ __('messages.helpful_votes') }}:</strong> 
                                                        <span class="badge badge-info">{{ $review->helpful_count }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            @if(!$review->is_approved)
                                            <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success">
                                                    <i class="feather icon-check"></i> {{ __('messages.approve_review') }}
                                                </button>
                                            </form>
                                            @endif
                                            <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.confirm_reject_review_modal') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="feather icon-x"></i> {{ __('messages.reject_and_delete') }}
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('messages.close') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="feather icon-inbox" style="font-size: 48px;"></i>
                                        <p class="mt-2">{{ __('messages.no_reviews_found') }}</p>
                                        @if(request()->has('status') || request()->has('rating') || request()->has('search'))
                                            <a href="{{ route('admin.reviews.index') }}" class="btn btn-sm btn-primary mt-2">{{ __('messages.clear_filters') }}</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $reviews->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
