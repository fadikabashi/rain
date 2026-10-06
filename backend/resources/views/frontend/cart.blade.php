@extends((Session::get('locale') ==='ar'? 'layouts.main-rtl' : 'layouts.main'))
@section('content')
<div class="cart-page-area ptb-30 bg-white">
    <div class="container">
        @livewire('cart-component')
    </div>
</div>
@endsection
