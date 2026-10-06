@extends((Session::get('locale') ==='ar'? 'layouts.main-rtl' : 'layouts.main'))

@push('structured-data')
    @include('partials.structured-data', ['product' => $product])
@endpush

@section('content')
<div class="breadcrumb-area bg-grey">
    <div class="container">
        <div class="ho-breadcrumb">
            <ul>
                <li><a href="/">{{__('frontend.home')}}</a></li>
                <li><a href="/products">{{__('frontend.shop')}}</a></li>
                @if (Session::get('locale') ==='ar')
                    <li>{{$product->name_ar}}</li>
                @else
                    <li>{{$product->name_en}}</li>
                @endif
            </ul>
        </div>
    </div>
</div>

<div class="product-details-area bg-white ptb-30">
    <div class="container">
        <div class="pdetails">
            <div class="row">
                <!-- Product Images -->
                <div class="col-lg-6">
                    <div class="pdetails-images">
                        @if($product->images && $product->images->count() > 0)
                            <!-- Image Gallery -->
                            <div class="pdetails-largeimages pdetails-imagezoom">
                                @foreach($product->images as $index => $image)
                                <div class="pdetails-singleimage {{ $index === 0 ? 'active' : '' }}" data-src="{{ asset('storage/' . $image->image_path) }}">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $image->alt_text ?? $product->name_en }}" loading="lazy">
                                </div>
                                @endforeach
                            </div>
                            <!-- Thumbnail Navigation -->
                            <div class="pdetails-thumbnails">
                                @foreach($product->images as $index => $image)
                                <div class="pdetails-thumbnail {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $image->alt_text ?? $product->name_en }}" loading="lazy">
                                </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Fallback to main product photo -->
                            <div class="pdetails-largeimages pdetails-imagezoom">
                                <div class="pdetails-singleimage active" data-src="{{ asset($product->photo) }}">
                                    <img src="{{ asset($product->photo) }}" alt="product image" loading="lazy">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Product Info -->
                <div class="col-lg-6">
                    <div class="pdetails-content">
                        @if (Session::get('locale') ==='ar')
                           <h3>{{$product->name_ar}}</h3>
                        @else
                           <h3>{{$product->name_en}}</h3>
                        @endif

                        <!-- Stock Status -->
                        <div class="product-stock-status mb-3">
                            @if($product->is_available && $product->quantity > 0)
                                <span class="badge badge-success">
                                    <i class="lnr lnr-checkmark-circle"></i> {{__('frontend.in_stock')}}
                                </span>
                                @if($product->quantity <= 10)
                                    <span class="badge badge-warning ml-2">
                                        {{__('frontend.only_left', ['count' => $product->quantity])}}
                                    </span>
                                @endif
                            @else
                                <span class="badge badge-danger">
                                    <i class="lnr lnr-cross-circle"></i> {{__('frontend.out_of_stock')}}
                                </span>
                            @endif
                        </div>

                        <div class="pdetails-pricebox">
                            @if($product->discount > 0)
                            <del class="oldprice">{{$product->price}} KWD</del>
                            <span class="price">{{$product_price_after_discount}} KWD</span>
                            <span class="badge badge-danger">Save {{$product_discount}}%</span>
                            @else
                            <span class="price">{{$product->price}} KWD</span>
                            @endif
                        </div>

                        @if (Session::get('locale') ==='ar')
                           <p class="product-description">{{$product->description_ar}}</p>
                        @else
                           <p class="product-description">{{$product->description_en}}</p>
                        @endif

                        <div class="pdetails-quantity">
                            @livewire('add-to-cart-component', [
                                'productId' => $product->id,
                                'productName' => Session::get('locale') === 'ar' ? $product->name_ar : $product->name_en,
                                'productPrice' => $product->price,
                                'productPhoto' => $product->photo
                            ])
                        </div>

                        <!-- Quote Request Button -->
                        <div class="quote-request-section mt-3">
                            <a href="{{ route('quote-request.create', ['product_id' => $product->id]) }}" class="btn btn-outline-primary">
                                <i class="lnr lnr-file-empty"></i> {{ __('frontend.request_quote') }}
                            </a>
                        </div>

                        <!-- Product Comparison Button -->
                        <div class="comparison-section mt-3">
                            <button type="button" class="btn btn-outline-info add-to-comparison-btn" 
                                    data-product-id="{{ $product->id }}">
                                <i class="lnr lnr-layers"></i> 
                                <span class="comparison-text">{{ __('frontend.add_to_comparison') }}</span>
                            </button>
                        </div>

                        <!-- Social Sharing -->
                        <div class="social-sharing mt-3">
                            <span class="share-label">{{ __('frontend.share_product') }}:</span>
                            <div class="share-buttons" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                                <a href="https://wa.me/?text={{ urlencode((Session::get('locale') === 'ar' ? $product->name_ar : $product->name_en) . ' - ' . url()->current()) }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-success share-btn" 
                                   title="WhatsApp"
                                   style="min-width: 45px; height: 45px; display: inline-flex; align-items: center; justify-content: center; padding: 0; opacity: 1 !important; transform: none !important; transition: none !important;">
                                    <i class="fab fa-whatsapp" style="font-size: 20px;"></i>
                                </a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-primary share-btn" 
                                   title="Facebook"
                                   style="min-width: 45px; height: 45px; display: inline-flex; align-items: center; justify-content: center; padding: 0; opacity: 1 !important; transform: none !important; transition: none !important;">
                                    <i class="fab fa-facebook-f" style="font-size: 20px;"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode(Session::get('locale') === 'ar' ? $product->name_ar : $product->name_en) }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-info share-btn" 
                                   title="Twitter"
                                   style="min-width: 45px; height: 45px; display: inline-flex; align-items: center; justify-content: center; padding: 0; opacity: 1 !important; transform: none !important; transition: none !important;">
                                    <i class="fab fa-twitter" style="font-size: 20px;"></i>
                                </a>
                                <a href="mailto:?subject={{ urlencode(Session::get('locale') === 'ar' ? $product->name_ar : $product->name_en) }}&body={{ urlencode(url()->current()) }}" 
                                   class="btn btn-sm btn-secondary share-btn" 
                                   title="Email"
                                   style="min-width: 45px; height: 45px; display: inline-flex; align-items: center; justify-content: center; padding: 0; opacity: 1 !important; transform: none !important; transition: none !important;">
                                    <i class="lnr lnr-envelope" style="font-size: 18px;"></i>
                                </a>
                            </div>
                        </div>
                        <style>
                            .share-btn:hover,
                            .share-btn:focus,
                            .share-btn:active {
                                opacity: 1 !important;
                                transform: none !important;
                                box-shadow: none !important;
                                background-color: inherit !important;
                                border-color: inherit !important;
                            }
                            .share-btn.btn-success:hover {
                                background-color: #28a745 !important;
                                border-color: #28a745 !important;
                            }
                            .share-btn.btn-primary:hover {
                                background-color: #007bff !important;
                                border-color: #007bff !important;
                            }
                            .share-btn.btn-info:hover {
                                background-color: #17a2b8 !important;
                                border-color: #17a2b8 !important;
                            }
                            .share-btn.btn-secondary:hover {
                                background-color: #6c757d !important;
                                border-color: #6c757d !important;
                            }
                        </style>

                        <!-- Restock Notification (if out of stock) -->
                        @if(!$product->is_available || $product->quantity <= 0)
                        <div class="restock-notification-section mt-3">
                            <button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#restockNotificationModal">
                                <i class="lnr lnr-bell"></i> {{ __('frontend.notify_me_when_available') }}
                            </button>
                        </div>
                        @endif

                        <div class="pdetails-categories">
                            <span>{{__('frontend.categories')}}:</span>
                            <ul>
                                <li><a href="/products">
                                @if (Session::get('locale') ==='ar')
                                    {{$category->name_ar}}
                                @else
                                    {{$category->name_en}}
                                @endif</a></li>
                            </ul>
                        </div>

                        <!-- Product Videos (Featured) -->
                        @if($product->featuredVideo)
                        <div class="product-featured-video mt-4">
                            <h4>{{__('frontend.product_video')}}</h4>
                            <div class="video-container">
                                @if($product->featuredVideo->video_type === 'youtube')
                                    <iframe width="100%" height="315" 
                                        src="https://www.youtube.com/embed/{{ $product->featuredVideo->youtube_id }}" 
                                        frameborder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                        allowfullscreen>
                                    </iframe>
                                @elseif($product->featuredVideo->video_type === 'vimeo')
                                    <iframe src="https://player.vimeo.com/video/{{ $product->featuredVideo->vimeo_id }}" 
                                        width="100%" height="315" 
                                        frameborder="0" 
                                        allow="autoplay; fullscreen; picture-in-picture" 
                                        allowfullscreen>
                                    </iframe>
                                @else
                                    {!! $product->featuredVideo->video_url !!}
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Product Details Tabs -->
            <div class="row mt-30">
                <div class="col-12">
                    <div class="product-tabs">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#description" role="tab">
                                    {{__('frontend.description')}}
                                </a>
                            </li>
                            @if($product->specifications && $product->specifications->count() > 0)
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#specifications" role="tab">
                                    {{__('frontend.specifications')}}
                                </a>
                            </li>
                            @endif
                            @if($product->videos && $product->videos->count() > 0)
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#videos" role="tab">
                                    {{__('frontend.videos')}}
                                </a>
                            </li>
                            @endif
                            @if($product->documents && $product->documents->count() > 0)
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#documents" role="tab">
                                    {{__('frontend.documents')}}
                                </a>
                            </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#reviews" role="tab">
                                    {{__('frontend.reviews')}} ({{ $reviewStats['total'] ?? 0 }})
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- Description Tab -->
                            <div class="tab-pane fade show active" id="description" role="tabpanel">
                                <div class="product-description-content">
                                    @if (Session::get('locale') ==='ar')
                                        {!! nl2br(e($product->description_ar)) !!}
                                    @else
                                        {!! nl2br(e($product->description_en)) !!}
                                    @endif
                                </div>
                            </div>

                            <!-- Specifications Tab -->
                            @if($product->specifications && $product->specifications->count() > 0)
                            <div class="tab-pane fade" id="specifications" role="tabpanel">
                                <div class="product-specifications">
                                    @php
                                        $groupedSpecs = $product->specifications->groupBy('group');
                                    @endphp
                                    @foreach($groupedSpecs as $group => $specs)
                                        @if($group)
                                        <h5 class="spec-group-title">{{ $group }}</h5>
                                        @endif
                                        <table class="table table-bordered spec-table">
                                            <tbody>
                                                @foreach($specs as $spec)
                                                <tr>
                                                    <th width="40%">{{ Session::get('locale') === 'ar' && $spec->spec_key_ar ? $spec->spec_key_ar : $spec->spec_key_en }}</th>
                                                    <td>{{ Session::get('locale') === 'ar' && $spec->spec_value_ar ? $spec->spec_value_ar : $spec->spec_value_en }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Videos Tab -->
                            @if($product->videos && $product->videos->count() > 0)
                            <div class="tab-pane fade" id="videos" role="tabpanel">
                                <div class="product-videos">
                                    <div class="row">
                                        @foreach($product->videos as $video)
                                        <div class="col-md-6 mb-4">
                                            <div class="video-item">
                                                @if(Session::get('locale') === 'ar' && $video->title_ar ?: $video->title_en)
                                                <h5>{{ Session::get('locale') === 'ar' && $video->title_ar ? $video->title_ar : ($video->title_en ?? '') }}</h5>
                                                @endif
                                                <div class="video-container">
                                                    @if($video->video_type === 'youtube')
                                                        <iframe width="100%" height="315" 
                                                            src="https://www.youtube.com/embed/{{ $video->youtube_id }}" 
                                                            frameborder="0" 
                                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                            allowfullscreen>
                                                        </iframe>
                                                    @elseif($video->video_type === 'vimeo')
                                                        <iframe src="https://player.vimeo.com/video/{{ $video->vimeo_id }}" 
                                                            width="100%" height="315" 
                                                            frameborder="0" 
                                                            allow="autoplay; fullscreen; picture-in-picture" 
                                                            allowfullscreen>
                                                        </iframe>
                                                    @else
                                                        {!! $video->video_url !!}
                                                    @endif
                                                </div>
                                                @if(Session::get('locale') === 'ar' && $video->description_ar ?: $video->description_en)
                                                <p class="video-description mt-2">{{ Session::get('locale') === 'ar' && $video->description_ar ? $video->description_ar : $video->description_en }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Documents Tab -->
                            @if($product->documents && $product->documents->count() > 0)
                            <div class="tab-pane fade" id="documents" role="tabpanel">
                                <div class="product-documents">
                                    <div class="document-list">
                                        @foreach($product->documents as $document)
                                        <div class="document-item mb-3 p-3 border rounded">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1">
                                                        <i class="lnr lnr-file-empty"></i> 
                                                        {{ Session::get('locale') === 'ar' && $document->title_ar ? $document->title_ar : $document->title_en }}
                                                    </h6>
                                                    @if(Session::get('locale') === 'ar' && $document->description_ar ?: $document->description_en)
                                                    <p class="text-muted mb-0 small">{{ Session::get('locale') === 'ar' && $document->description_ar ? $document->description_ar : $document->description_en }}</p>
                                                    @endif
                                                    <span class="badge badge-secondary">{{ strtoupper($document->document_type) }}</span>
                                                    @if($document->formatted_file_size)
                                                    <span class="badge badge-info ml-2">{{ $document->formatted_file_size }}</span>
                                                    @endif
                                                </div>
                                                <a href="{{ asset($document->file_path) }}" 
                                                   download="{{ $document->file_name }}" 
                                                   class="btn btn-primary btn-sm">
                                                    <i class="lnr lnr-download"></i> {{__('frontend.download')}}
                                                </a>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Reviews Tab -->
                            <div class="tab-pane fade" id="reviews" role="tabpanel">
                                <div class="product-reviews">
                                    <!-- Review Summary -->
                                    <div class="review-summary mb-4 p-4 rounded" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
                                        <div class="row">
                                            <div class="col-md-4 text-center">
                                                <div class="average-rating">
                                                    <h2 class="mb-0" style="color: #FFD700; font-size: 3rem; font-weight: bold; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">{{ number_format($reviewStats['average'] ?? 0, 1) }}</h2>
                                                    <div class="stars mb-2" style="font-size: 1.5rem;">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="lnr {{ ($reviewStats['average'] ?? 0) >= $i ? 'lnr-star' : (($reviewStats['average'] ?? 0) >= ($i - 0.5) ? 'lnr-star-half' : 'lnr-star-empty') }}" style="color: #FFD700; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);"></i>
                                                        @endfor
                                                    </div>
                                                    <p class="mb-0" style="color: rgba(255,255,255,0.9); font-size: 0.95rem;">{{ __('frontend.based_on') }} <strong style="color: #FFD700;">{{ $reviewStats['total'] ?? 0 }}</strong> {{ __('frontend.reviews') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="rating-distribution">
                                                    @for($rating = 5; $rating >= 1; $rating--)
                                                    <div class="rating-bar mb-2">
                                                        <div class="d-flex align-items-center">
                                                            <span class="rating-label" style="color: white; font-weight: 500; min-width: 80px;">{{ $rating }} {{ __('frontend.star') }}</span>
                                                            <div class="progress flex-grow-1 mx-2" style="height: 24px; background-color: rgba(255,255,255,0.2); border-radius: 12px; overflow: hidden;">
                                                                @php
                                                                    $count = $reviewStats['distribution'][$rating] ?? 0;
                                                                    $percentage = $reviewStats['total'] > 0 ? ($count / $reviewStats['total']) * 100 : 0;
                                                                @endphp
                                                                <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%; background: linear-gradient(90deg, #FFD700 0%, #FFA500 100%); border-radius: 12px; transition: width 0.6s ease;"></div>
                                                            </div>
                                                            <span class="rating-count" style="color: #FFD700; font-weight: bold; min-width: 30px; text-align: center;">{{ $count }}</span>
                                                        </div>
                                                    </div>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Review Form -->
                                    <div class="review-form mb-4">
                                        <h4>{{ __('frontend.write_review') }}</h4>
                                        <form action="{{ route('reviews.store') }}" method="POST" id="review-form">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            
                                            <div class="form-group">
                                                <label>{{ __('frontend.your_name') }}</label>
                                                <input type="text" name="customer_name" class="form-control" 
                                                       value="{{ Auth::check() ? Auth::user()->name : old('customer_name') }}" required>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>{{ __('frontend.your_email') }}</label>
                                                <input type="email" name="customer_email" class="form-control" 
                                                       value="{{ Auth::check() ? Auth::user()->email : old('customer_email') }}" required>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label style="font-weight: 600; color: #2c3e50; margin-bottom: 10px;">{{ __('frontend.rating') }}</label>
                                                <div class="rating-input">
                                                    @for($i = 5; $i >= 1; $i--)
                                                    <input type="radio" name="rating" id="rating{{ $i }}" value="{{ $i }}" required>
                                                    <label for="rating{{ $i }}" class="star-label">
                                                        <i class="lnr lnr-star"></i>
                                                    </label>
                                                    @endfor
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>{{ __('frontend.review_title') }}</label>
                                                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>{{ __('frontend.review_comment') }}</label>
                                                <textarea name="comment" class="form-control" rows="5" required>{{ old('comment') }}</textarea>
                                            </div>
                                            
                                            <button type="submit" class="btn btn-primary">{{ __('frontend.submit_review') }}</button>
                                        </form>
                                    </div>

                                    <!-- Reviews List -->
                                    <div class="reviews-list">
                                        <h4>{{ __('frontend.customer_reviews') }}</h4>
                                        
                                        @if($reviews->count() > 0)
                                            @foreach($reviews as $review)
                                            <div class="review-item mb-4 p-4 rounded" style="background: #f8f9fa; border: 2px solid #e9ecef; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s ease;">
                                                <div class="review-header d-flex justify-content-between mb-3" style="border-bottom: 2px solid #e9ecef; padding-bottom: 10px;">
                                                    <div>
                                                        <strong style="color: #2c3e50; font-size: 1.1rem;">{{ $review->customer_name }}</strong>
                                                        @if($review->verified_purchase)
                                                        <span class="badge ml-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 5px 12px; border-radius: 20px; font-size: 0.85rem;">{{ __('frontend.verified_purchase') }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="review-date" style="color: #6c757d; font-size: 0.9rem;">
                                                        <i class="lnr lnr-calendar-full" style="margin-right: 5px;"></i>{{ $review->created_at->format('Y-m-d') }}
                                                    </div>
                                                </div>
                                                
                                                <div class="review-rating mb-3" style="font-size: 1.2rem;">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="lnr {{ $i <= $review->rating ? 'lnr-star' : 'lnr-star-empty' }}" style="color: {{ $i <= $review->rating ? '#FFD700' : '#ddd' }}; margin-right: 3px; text-shadow: 0 1px 2px rgba(0,0,0,0.1);"></i>
                                                    @endfor
                                                </div>
                                                
                                                <h5 class="review-title mb-2" style="color: #2c3e50; font-weight: 600; font-size: 1.15rem;">{{ $review->title }}</h5>
                                                <p class="review-comment mb-3" style="color: #495057; line-height: 1.6; font-size: 0.95rem;">{{ $review->comment }}</p>
                                                
                                                <div class="review-helpful">
                                                    <button class="btn btn-sm helpful-btn" data-review-id="{{ $review->id }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 8px 16px; border-radius: 20px; transition: all 0.3s ease; box-shadow: 0 2px 5px rgba(102, 126, 234, 0.3);">
                                                        <i class="lnr lnr-thumbs-up"></i> {{ __('frontend.helpful') }} ({{ $review->helpful_count }})
                                                    </button>
                                                </div>
                                            </div>
                                            @endforeach
                                            
                                            <!-- Pagination -->
                                            <div class="mt-4">
                                                {{ $reviews->links() }}
                                            </div>
                                        @else
                                            <p class="text-muted">{{ __('frontend.no_reviews_yet') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Compatible Products -->
            @if(isset($compatibleProducts) && $compatibleProducts->count() > 0)
            <div class="row mt-30">
                <div class="col-12">
                    <div class="section-title">
                        <h3>{{__('frontend.compatible_products')}}</h3>
                    </div>
                    <div class="product-slider compatible-products-slider slider-navigation-2">
                        @foreach($compatibleProducts as $compatibility)
                        @php
                            $compatibleProduct = $compatibility->compatibleProduct;
                        @endphp
                        @if($compatibleProduct)
                        <div class="product-slider-col">
                            <article class="hoproduct flex-row">
                                <div class="hoproduct-image">
                                    <a class="hoproduct-thumb" href="/details/{{$compatibleProduct->id}}">
                                        <img class="hoproduct-frontimage" src="{{ asset($compatibleProduct->photo)}}" alt="product image" loading="lazy">
                                        <img class="hoproduct-backimage" src="{{ asset($compatibleProduct->photo)}}" alt="product image" loading="lazy">
                                    </a>
                                    <ul class="hoproduct-actionbox">
                                        <li>
                                            @livewire('add-to-cart-component', [
                                                'productId' => $compatibleProduct->id,
                                                'productName' => Session::get('locale') === 'ar' ? $compatibleProduct->name_ar : $compatibleProduct->name_en,
                                                'productPrice' => $compatibleProduct->price,
                                                'productPhoto' => $compatibleProduct->photo,
                                                'compact' => true
                                            ], key('add-to-cart-compatible-' . $compatibleProduct->id))
                                        </li>
                                        <li><a href="/details/{{$compatibleProduct->id}}" class="quickview-trigger"><i class="lnr lnr-eye"></i></a></li>
                                    </ul>
                                    @if($compatibility->compatibility_type === 'required')
                                    <ul class="hoproduct-flags">
                                        <li class="flag-new" style="background-color: #dc3545;">{{__('frontend.required_accessories')}}</li>
                                    </ul>
                                    @elseif($compatibility->compatibility_type === 'recommended')
                                    <ul class="hoproduct-flags">
                                        <li class="flag-new" style="background-color: #28a745;">{{__('frontend.recommended_accessories')}}</li>
                                    </ul>
                                    @endif
                                </div>
                                <div class="hoproduct-content">
                                    <h3 class="hoproduct-title">
                                        <a href="/details/{{$compatibleProduct->id}}">
                                            @if (Session::get('locale') ==='ar')
                                                {{$compatibleProduct->name_ar}}
                                            @else
                                                {{$compatibleProduct->name_en}}
                                            @endif
                                        </a>
                                    </h3>
                                    <div class="hoproduct-pricebox">
                                        <span class="hoproduct-price">{{$compatibleProduct->price}} KWD</span>
                                    </div>
                                </div>
                            </article>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Related Products -->
            @if($relatedProducts && $relatedProducts->count() > 0)
            <div class="row mt-30">
                <div class="col-12">
                    <div class="section-title">
                        <h3>{{__('frontend.related_products')}}</h3>
                    </div>
                    <div class="product-slider related-products-slider slider-navigation-2">
                        @foreach($relatedProducts as $relatedProduct)
                        <div class="product-slider-col">
                            <article class="hoproduct flex-row">
                                <div class="hoproduct-image">
                                    <a class="hoproduct-thumb" href="/details/{{$relatedProduct->id}}">
                                        <img class="hoproduct-frontimage" src="{{ asset($relatedProduct->photo)}}" alt="product image" loading="lazy">
                                        <img class="hoproduct-backimage" src="{{ asset($relatedProduct->photo)}}" alt="product image" loading="lazy">
                                    </a>
                                    <ul class="hoproduct-actionbox">
                                        <li>
                                            @livewire('add-to-cart-component', [
                                                'productId' => $relatedProduct->id,
                                                'productName' => Session::get('locale') === 'ar' ? $relatedProduct->name_ar : $relatedProduct->name_en,
                                                'productPrice' => $relatedProduct->price,
                                                'productPhoto' => $relatedProduct->photo,
                                                'compact' => true
                                            ], key('add-to-cart-related-' . $relatedProduct->id))
                                        </li>
                                        <li><a href="/details/{{$relatedProduct->id}}" class="quickview-trigger" data-product-id="{{$relatedProduct->id}}"><i class="lnr lnr-eye"></i></a></li>
                                    </ul>
                                    @if($relatedProduct->discount > 0)
                                    <ul class="hoproduct-flags">
                                        <li class="flag-discount">-{{$relatedProduct->discount * 100}}%</li>
                                    </ul>
                                    @endif
                                </div>
                                <div class="hoproduct-content">
                                    <h5 class="hoproduct-title">
                                        <a href="/details/{{$relatedProduct->id}}">
                                            {{ Session::get('locale') === 'ar' ? $relatedProduct->name_ar : $relatedProduct->name_en }}
                                        </a>
                                    </h5>
                                    <div class="hoproduct-pricebox">
                                        @if($relatedProduct->discount > 0)
                                        <span class="price">{{ $relatedProduct->price - ($relatedProduct->discount * $relatedProduct->price) }} KWD</span>
                                        <span class="oldprice">{{ $relatedProduct->price }} KWD</span>
                                        @else
                                        <span class="price">{{ $relatedProduct->price }} KWD</span>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Image Gallery Styles */
.pdetails-thumbnails {
    display: flex;
    gap: 10px;
    margin-top: 15px;
    flex-wrap: wrap;
}

.pdetails-thumbnail {
    width: 80px;
    height: 80px;
    border: 2px solid transparent;
    cursor: pointer;
    overflow: hidden;
    border-radius: 4px;
    transition: all 0.3s;
}

.pdetails-thumbnail.active {
    border-color: #007bff;
}

.pdetails-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.pdetails-singleimage {
    display: none;
}

.pdetails-singleimage.active {
    display: block;
}

.pdetails-singleimage img {
    width: 100%;
    height: auto;
    border-radius: 8px;
}

/* Product Tabs */
.product-tabs {
    margin-top: 30px;
}

.product-tabs .nav-tabs {
    border-bottom: 2px solid #e9ecef;
}

.product-tabs .nav-link {
    color: #495057;
    border: none;
    border-bottom: 2px solid transparent;
    padding: 15px 25px;
    font-weight: 500;
}

.product-tabs .nav-link:hover {
    border-color: #007bff;
    color: #007bff;
}

.product-tabs .nav-link.active {
    color: #007bff;
    border-bottom-color: #007bff;
    background: transparent;
}

.tab-content {
    padding: 30px 0;
}

/* Specifications Table */
.spec-table {
    margin-bottom: 30px;
}

.spec-group-title {
    margin-top: 20px;
    margin-bottom: 15px;
    color: #333;
    font-weight: 600;
    padding-bottom: 10px;
    border-bottom: 2px solid #007bff;
}

.spec-table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

/* Video Container */
.video-container {
    position: relative;
    padding-bottom: 56.25%; /* 16:9 aspect ratio */
    height: 0;
    overflow: hidden;
    border-radius: 8px;
}

.video-container iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.video-item {
    margin-bottom: 20px;
}

/* Document List */
.document-item {
    transition: all 0.3s;
}

.document-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

/* Stock Status */
.product-stock-status {
    margin-bottom: 15px;
}

.badge {
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 14px;
}

.badge-success {
    background-color: #28a745;
    color: white;
}

.badge-danger {
    background-color: #dc3545;
    color: white;
}

.badge-warning {
    background-color: #ffc107;
    color: #212529;
}

.badge-info {
    background-color: #17a2b8;
    color: white;
}

.badge-secondary {
    background-color: #6c757d;
    color: white;
}

/* Related Products */
.related-products-slider {
    margin-top: 20px;
}

@media (max-width: 768px) {
    .pdetails-thumbnail {
        width: 60px;
        height: 60px;
    }
    
    .product-tabs .nav-link {
        padding: 10px 15px;
        font-size: 14px;
    }
}
</style>

<script>
// Image Gallery Functionality
document.addEventListener('DOMContentLoaded', function() {
    const thumbnails = document.querySelectorAll('.pdetails-thumbnail');
    const largeImages = document.querySelectorAll('.pdetails-singleimage');
    
    thumbnails.forEach((thumbnail, index) => {
        thumbnail.addEventListener('click', function() {
            // Remove active class from all thumbnails and images
            thumbnails.forEach(t => t.classList.remove('active'));
            largeImages.forEach(img => img.classList.remove('active'));
            
            // Add active class to clicked thumbnail and corresponding image
            thumbnail.classList.add('active');
            if (largeImages[index]) {
                largeImages[index].classList.add('active');
            }
        });
    });

    // Review form submission
    document.getElementById('review-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message || '{{ __("frontend.review_submitted_successfully") }}');
                form.reset();
                location.reload();
            } else {
                alert(data.message || '{{ __("frontend.error_occurred") }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("frontend.error_occurred") }}');
        });
    });

    // Mark review as helpful
    document.querySelectorAll('.helpful-btn').forEach(button => {
        button.addEventListener('click', function() {
            const reviewId = this.getAttribute('data-review-id');
            fetch(`/reviews/${reviewId}/helpful`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const count = parseInt(this.textContent.match(/\d+/)[0]) + 1;
                    this.innerHTML = `<i class="lnr lnr-thumbs-up"></i> {{ __('frontend.helpful') }} (${count})`;
                    this.disabled = true;
                }
            });
        });
    });
});
</script>

<style>
.rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating-input input[type="radio"] {
    display: none;
}

.rating-input label {
    cursor: pointer;
    font-size: 28px;
    color: #ddd;
    margin-right: 5px;
    transition: all 0.2s ease;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.rating-input input[type="radio"]:checked ~ label,
.rating-input label:hover,
.rating-input label:hover ~ label {
    color: #FFD700 !important;
    transform: scale(1.1);
}

/* Review Item Hover Effects */
.review-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.12) !important;
    border-color: #667eea !important;
}

/* Helpful Button Hover */
.helpful-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(102, 126, 234, 0.4) !important;
}

.review-item {
    background: #f8f9fa;
}

.stars {
    color: #ffc107;
    font-size: 20px;
}
</style>

<script>
// Wait for DOM and jQuery to be ready
(function() {
    function waitForJQuery(callback, maxAttempts) {
        maxAttempts = maxAttempts || 100; // Max 5 seconds wait
        if (typeof window.$ !== 'undefined' && typeof window.jQuery !== 'undefined') {
            callback();
        } else if (maxAttempts > 0) {
            setTimeout(function() {
                waitForJQuery(callback, maxAttempts - 1);
            }, 50);
        } else {
            // jQuery not available, proceed without it
            callback();
        }
    }

    function initScripts() {
        // Restock Notification Form
        const restockForm = document.getElementById('restockNotificationForm');
        if (restockForm) {
            restockForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;
                const formData = new FormData(form);
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message || '{{ __("frontend.restock_notification_subscribed") }}');
                        // Hide modal
                        const modal = document.getElementById('restockNotificationModal');
                        if (modal) {
                            if (typeof bootstrap !== 'undefined') {
                                const bsModal = bootstrap.Modal.getInstance(modal);
                                if (bsModal) {
                                    bsModal.hide();
                                } else {
                                    modal.style.display = 'none';
                                    document.body.classList.remove('modal-open');
                                }
                            } else if (typeof window.$ !== 'undefined' && window.$.fn && window.$.fn.modal) {
                                window.$('#restockNotificationModal').modal('hide');
                            } else {
                                modal.style.display = 'none';
                                document.body.classList.remove('modal-open');
                            }
                        }
                        form.reset();
                    } else {
                        alert(data.message || '{{ __("frontend.error_occurred") }}');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('{{ __("frontend.error_occurred") }}');
                });
            });
        }

        // Product Comparison - use event delegation
        document.addEventListener('click', function(e) {
            const comparisonBtn = e.target.closest('.add-to-comparison-btn');
            if (comparisonBtn) {
                e.preventDefault();
                const productId = comparisonBtn.getAttribute('data-product-id');
                
                fetch('{{ route("comparison.add") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ product_id: productId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        const textSpan = comparisonBtn.querySelector('.comparison-text');
                        if (textSpan) {
                            textSpan.textContent = '{{ __("frontend.in_comparison") }}';
                        }
                        comparisonBtn.classList.add('btn-info');
                        comparisonBtn.classList.remove('btn-outline-info');
                        updateComparisonCount();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('{{ __("frontend.error_occurred") }}');
                });
            }
        });

        // Check if product is in comparison on page load
        const productId = {{ $product->id }};
        fetch('{{ route("comparison.check", ":id") }}'.replace(':id', productId))
            .then(response => response.json())
            .then(data => {
                if (data.in_comparison) {
                    const comparisonBtn = document.querySelector('.add-to-comparison-btn');
                    if (comparisonBtn) {
                        const textSpan = comparisonBtn.querySelector('.comparison-text');
                        if (textSpan) {
                            textSpan.textContent = '{{ __("frontend.in_comparison") }}';
                        }
                        comparisonBtn.classList.add('btn-info');
                        comparisonBtn.classList.remove('btn-outline-info');
                    }
                }
            });
        
        updateComparisonCount();
    }

    function updateComparisonCount() {
        fetch('{{ route("comparison.count") }}')
            .then(response => response.json())
            .then(data => {
                const countElements = document.querySelectorAll('.comparison-count');
                countElements.forEach(el => {
                    el.textContent = data.count || 0;
                });
            });
    }

    // Initialize when DOM is ready
    function startInit() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                waitForJQuery(initScripts);
            });
        } else {
            waitForJQuery(initScripts);
        }
    }
    
    // Start initialization
    startInit();
})();
</script>

<!-- Restock Notification Modal -->
<div class="modal fade" id="restockNotificationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('frontend.notify_me_when_available') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="restockNotificationForm" action="{{ route('restock-notification.subscribe') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="form-group">
                        <label>{{ __('frontend.your_email') }} *</label>
                        <input type="email" name="email" class="form-control" 
                               value="{{ auth()->check() ? auth()->user()->email : old('email') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label>{{ __('frontend.your_name') }}</label>
                        <input type="text" name="name" class="form-control" 
                               value="{{ auth()->check() ? auth()->user()->name : old('name') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('frontend.close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('frontend.subscribe') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
