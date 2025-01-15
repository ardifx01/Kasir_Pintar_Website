
    <div class="card shadow-sm border w-300">
        <!-- Store Header -->
        <div class="card-header bg-white">
            <h5 class="card-title mb-0 text-center fw-bold">{{ $store->name ?? 'Unknown Store' }}</h5>
        </div>

        <!-- Store Image -->
        <img src="{{ $store->url_image ?? 'placeholder.jpg' }}"
             alt="{{ $store->name ?? 'Store Image' }}"
             class="card-img-top p-3"
             style="height: 200px; object-fit: contain;">

        <!-- Store Details -->
        <div class="card-body">
            <div class="mb-3">
                <label class="text-muted mb-1 d-block">Phone Number</label>
                <div class="d-flex align-items-center">
                    <i class="bi bi-telephone-fill text-primary me-2"></i>
                    <span class="fw-semibold">{{ $store->number_phone ?? 'N/A' }}</span>
                </div>
            </div>

            <div class="mb-3">
                <label class="text-muted mb-1 d-block">Postal Code</label>
                <div class="d-flex align-items-center">
                    <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                    <span class="fw-semibold">{{ $store->postal_code ?? 'N/A' }}</span>
                </div>
            </div>

            <div class="mb-3">
                <label class="text-muted mb-1 d-block">Address</label>
                <div class="d-flex">
                    <i class="bi bi-building-fill text-primary me-2"></i>
                    <span class="fw-semibold">{{ $store->address ?? 'N/A' }}</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('stores.edit', $store->id) }}"
                   class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil-fill me-1"></i>Edit
                </a>

                <form action="{{ route('stores.destroy', $store->id) }}"
                      method="POST"
                      class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure you want to delete this store?')">
                        <i class="bi bi-trash-fill me-1"></i>Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
