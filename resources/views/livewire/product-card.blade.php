<div class="card p-4" style="width: 18rem;">
  <img src="{{ asset('storage/product_images/' . $product->url_image) }}" class="card-img-top" style="height: 200px; object-fit: contain;" alt="{{ $product->name_product }}">
  <div class="card-body center">
    <h5 class="card-title text-center">{{ $product->name_product }}</h5>
    <p class="card-text text-center">Rp. {{ number_format($product->selling_price, 0, ',', '.') }}</p>
    <div class="d-flex align-items-center justify-content-center">
        <button wire:click="decrementQuantity" class="btn btn-secondary btn-sm">-</button>
        <input type="number" wire:model="quantity" class="form-control form-control-sm mx-2" value="{{ $quantity }}" style="width: 50px;">
        <button wire:click="incrementQuantity" class="btn btn-secondary btn-sm">+</button>
    </div>
  </div>
</div>
