<div class="grid grid-cols-3 gap-4 mt-4">  <!-- Added gap for spacing -->
  @forelse ($products as $product)
    <livewire:product-card :product="$product"/>
  @empty
    <p class="text-center">Tidak ada produk tersedia untuk toko ini.</p>
  @endforelse
</div>
