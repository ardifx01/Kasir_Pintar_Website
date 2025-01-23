<div class="card h-180 text-center" wire:click="clickProduct">
    <div class="card-header">
        {{ $product->name_product }}
    </div>
    <div class="card-body center pt-3">
        <img src="{{ asset('storage/product_images/' . $product->url_image) }}" class="card-img-top" style="height: 60px; object-fit: contain;" alt="{{ $product->name_product }}">
        <p class="card-text text-center m-3">Rp. {{ number_format($product->selling_price, 0, ',', '.') }}</p>
    </div>
</div>
