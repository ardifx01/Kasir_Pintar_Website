<div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-2 fs-6">
    <div class="flex-grow-1">
        <p class="mb-0 fs-6">{{ $this->product['name_product'] }}</p>
        <p class="mb-0 text-xs">Rp. {{ number_format($this->subtotal, 0, ',', '.') }}</p>
    </div>
    <div class="d-flex align-items-center">
        <div class="input-group input-group-sm me-2 d-flex gap-2 align-items-center">
            <input type="number" wire:model.live="quantity" min="1" class="form-control form-control-sm me-2" style="width: 50px;">
            <div wire:click="deleteOrder">
                @svg('lucide-x',["width"=>20, "style" => "text-gray"])
            </div>
        </div>
    </div>
</div>
