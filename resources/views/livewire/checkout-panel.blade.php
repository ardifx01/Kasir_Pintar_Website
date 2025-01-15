<div class="card">
    <div class="card-body">
        <div>
            @livewire('order-list', ['orderItems' => $orderItems])
        </div>

        <div class="mt-3">
            <label for="discount">Diskon:</label>
            <input type="number" wire:model="discount" id="discount" class="form-control" value="{{ $discount }}">
            @error('discount') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="mt-2">
            <label for="taxPercentage">Pajak (%):</label>
            <input type="number" wire:model="taxPercentage" id="taxPercentage" class="form-control" value="{{ $taxPercentage }}">
            @error('taxPercentage') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="mt-2">
            <label for="payment-method">Metode Pembayaran:</label>
            <select wire:model="paymentMethod" id="payment-method" class="form-select">
                <option value="cash">Tunai</option>
                <option value="card">Transfer</option>
            </select>
        </div>

        <div class="mt-3">
            <div class="row">
                <div class="col-6">
                    <p>Subtotal: Rp. {{ number_format($subtotal, 0, ',', '.') }}</p>  <!-- Diperbaiki -->
                    <p>Total Belanja: Rp. {{ number_format($total_belanja, 0, ',', '.') }}</p>
                </div>
                <div class="col-6">
                    <label for="paymentAmount">Nominal Bayar:</label>
                    <input type="number" wire:model="paymentAmount" id="paymentAmount" class="form-control">
                    @error('paymentAmount') <span class="error">{{ $message }}</span> @enderror
                    <p>Kembalian: Rp. {{ number_format($change, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <button wire:click="saveTransaction" class="btn btn-primary mt-3">Proses Pembayaran</button>
    </div>
</div>
