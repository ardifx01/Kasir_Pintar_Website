
    <div class="card shadow-sm border w-400">
        <!-- Product Header -->
        <div class="card-header bg-white">
            <h5 class="card-title mb-0 text-center fw-bold">{{ $product->name_product }}</h5>
            <p class="text-muted text-center mb-0 small">Kode: {{ $product->code_product }}</p>
        </div>

        <!-- Product Image -->
        <img src="{{ asset('storage/product_images/' . $product->url_image) }}"
             alt="{{ $product->name_product }}"
             class="card-img-top p-3"
             style="height: 200px; object-fit: contain;"
             onError="this.onerror=null;this.src='{{ asset('images/default_product.jpg') }}';">

        <!-- Product Details -->
        <div class="card-body pt-0">
            <div class="row g-2 text-sm">
                <!-- Price Information -->
                <div class="col-6">
                    <div class="d-flex align-items-center">
                        <span class="text-muted me-2">Harga Jual:</span>
                        <span class="fw-bold text-success">{{ $product->selling_price }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex align-items-center">
                        <span class="text-muted me-2">Harga Beli:</span>
                        <span class="fw-bold text-primary">{{ $product->purchase_price }}</span>
                    </div>
                </div>

                <!-- Stock Information -->
                <div class="col-6">
                    <div class="d-flex align-items-center">
                        <span class="text-muted me-2">Stok:</span>
                        <span class="fw-bold">{{ $product->stock }} {{ $product->unit }}</span>
                    </div>
                </div>

                <!-- Store Information -->
                <div class="col-6">
                    <div class="d-flex align-items-center">
                        <span class="text-muted me-2">Toko:</span>
                        <span class="fw-bold">{{ $product->store->name }}</span>
                    </div>
                </div>

                <!-- Category Information -->
                <div class="col-12">
                    <div class="d-flex align-items-center">
                        <span class="text-muted me-2">Kategori:</span>
                        <span class="badge bg-info text-white">{{ $product->categoryProduct->name }}</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between mt-3">
                @can('update', $product)
                    <a href="{{ route('products.edit', $product) }}"
                       class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil-square me-1"></i>Ubah
                    </a>
                @endcan
                @can('delete', $product)
                    <form action="{{ route('products.destroy', $product) }}"
                          method="POST"
                          class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                            <i class="bi bi-trash me-1"></i>Hapus
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
