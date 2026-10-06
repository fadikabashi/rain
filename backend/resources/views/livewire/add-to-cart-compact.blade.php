<div>
    <button 
        class="px-4 py-1.5 text-white text-sm bg-blue-800 rounded" 
        wire:click="addToCart"
        wire:loading.attr="disabled"
        wire:target="addToCart"
        style="border: none; cursor: pointer;"
        @disabled(!$canAddMore)
    >
        <span wire:loading.remove wire:target="addToCart">
            <i class="lnr lnr-cart"></i>
        </span>
        <span wire:loading wire:target="addToCart">
            <i class="fa fa-spinner fa-spin"></i>
        </span>
    </button>
</div>
