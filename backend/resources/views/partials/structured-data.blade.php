@php
    $locale = Session::get('locale', 'en');
@endphp

{{-- Organization Schema --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "Rain Technology",
    "alternateName": "رين تكنولجي",
    "url": "{{ url('/') }}",
    "logo": "{{ asset('/app-assets/images/logo/Rain-tech.ico') }}",
    "description": "{{ $locale === 'ar' ? 'شركة متخصصة في بيع الأجهزة الكهربائية والإلكترونية، أنظمة التحكم في المنزل الذكي، الأنظمة الأمنية، كاميرات المراقبة في الكويت.' : 'We are a company for the retail sale of electrical and electronic devices, smart home control systems, security systems, surveillance cameras in Kuwait.' }}",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "Hawalli, Tunis Street, Jumana Complex Basement, Office 13",
        "addressLocality": "Hawalli",
        "addressCountry": "KW"
    },
    "geo": {
        "@type": "GeoCoordinates",
        "latitude": "29.342274585850735",
        "longitude": "48.01916934758884"
    },
    "contactPoint": {
        "@type": "ContactPoint",
        "contactType": "Customer Service",
        "areaServed": "KW",
        "availableLanguage": ["en", "ar"]
    }
}
</script>

@if(isset($product))
{{-- Product Schema --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "{{ $locale === 'ar' ? $product->name_ar : $product->name_en }}",
    "description": "{{ strip_tags($locale === 'ar' ? $product->description_ar : $product->description_en) }}",
    "image": [
        @if($product->images && $product->images->count() > 0)
            @foreach($product->images as $index => $image)
            "{{ asset('storage/' . $image->image_path) }}"{{ !$loop->last ? ',' : '' }}
            @endforeach
        @else
            "{{ asset($product->photo) }}"
        @endif
    ],
    "sku": "{{ $product->id }}",
    "brand": {
        "@type": "Brand",
        "name": "{{ $product->manfacturer ? ($locale === 'ar' ? $product->manfacturer->name_ar : $product->manfacturer->name_en) : 'Rain Technology' }}"
    },
    "offers": {
        "@type": "Offer",
        "url": "{{ url("/details/{$product->id}") }}",
        "priceCurrency": "KWD",
        "price": "{{ $product->price_after_discount }}",
        "priceValidUntil": "{{ now()->addYear()->toIso8601String() }}",
        "availability": "{{ $product->is_available && $product->quantity > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
        "itemCondition": "https://schema.org/NewCondition"
    },
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "4.5",
        "reviewCount": "0"
    }
}
</script>

{{-- BreadcrumbList Schema --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@type": "ListItem",
            "position": 1,
            "name": "{{ $locale === 'ar' ? 'الرئيسية' : 'Home' }}",
            "item": "{{ url('/') }}"
        },
        {
            "@type": "ListItem",
            "position": 2,
            "name": "{{ $locale === 'ar' ? 'المتجر' : 'Shop' }}",
            "item": "{{ url('/products') }}"
        },
        {
            "@type": "ListItem",
            "position": 3,
            "name": "{{ $locale === 'ar' ? $product->name_ar : $product->name_en }}",
            "item": "{{ url("/details/{$product->id}") }}"
        }
    ]
}
</script>
@endif

@if(isset($pageType) && $pageType === 'home')
{{-- Website Schema --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "Rain Technology",
    "alternateName": "رين تكنولجي",
    "url": "{{ url('/') }}",
    "potentialAction": {
        "@type": "SearchAction",
        "target": {
            "@type": "EntryPoint",
            "urlTemplate": "{{ url('/search?q={search_term_string}') }}"
        },
        "query-input": "required name=search_term_string"
    }
}
</script>
@endif
