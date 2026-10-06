@php
    $starCount   = rand(4, 5);
    $reviewCount = rand(12, 148);
@endphp
<div class="product-stars" aria-label="{{ $starCount }} out of 5 stars">
    <span class="stars-filled" aria-hidden="true">{{ str_repeat('★', $starCount) }}</span>@if($starCount < 5)<span class="stars-empty" aria-hidden="true">★</span>@endif
    <span class="review-count">({{ $reviewCount }})</span>
</div>
