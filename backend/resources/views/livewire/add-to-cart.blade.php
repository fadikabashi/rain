<div>
    <div class="pdetails-quantity">
        <div class="quantity-select">
            <input 
                type="number" 
                wire:model.live="quantity" 
                min="1" 
                max="{{ $maxQuantity }}"
                class="quantity-input"
            >
            <div class="inc qtybutton" wire:click="increment">
                +<i class="ion ion-ios-arrow-up"></i>
            </div>
            <div class="dec qtybutton" wire:click="decrement">
                -<i class="ion ion-ios-arrow-down"></i>
            </div>
        </div>
        <button 
            class="ho-button" 
            wire:click="addToCart"
            wire:loading.attr="disabled"
            wire:target="addToCart"
        >
            <span wire:loading.remove wire:target="addToCart">
                <i class="lnr lnr-cart"></i>
                <span>{{__('frontend.add_to_cart')}}</span>
            </span>
            <span wire:loading wire:target="addToCart">
                <i class="fa fa-spinner fa-spin"></i>
                Adding...
            </span>
        </button>
    </div>
</div>
