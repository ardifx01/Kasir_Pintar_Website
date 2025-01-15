<div>
    <div class="row">
        <div class="col-md-6">
            <div class="mb-4">
                <input type="text" wire:model="searchTerm" placeholder="Cari Produk..." class="form-control">
            </div>

            <div class="mb-4">
                @foreach ($products as $product)
                    <button wire:click="addProduct({{ $product->id }})" class="btn btn-secondary mb-2">
                        {{ $product->name_product }} ({{ $product->code_product }}) - {{ $product->selling_price }}
                    </button>
                @endforeach
            </div>
        </div>
        <div class="col-md-6">
            <h2>Side Orders</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Kuantitas</th>
                        <th>Harga</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sideOrders as $productId => $order)
                        <tr>
                            <td>{{ $order['product']->name_product }}</td>
                            <td>
                                <input type="number" wire:model="sideOrders.{{ $productId }}.quantity"
                                       wire:change="updateQuantity({{ $productId }}, $this.value)" min="1">
                            </td>
                            <td>{{ number_format($order['product']->selling_price, 0, ',', '.') }}</td>
                            <td>{{ number_format($order['product']->selling_price * $order['quantity'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mb-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" wire:model="hasDebt" id="hasDebt">
                    <label for="hasDebt" class="form-check-label">Ada Hutang</label>
                </div>
            </div>

            @if ($hasDebt)
                <div class="mb-3">
                    <label for="customer">Pelanggan:</label>
                    <select wire:model="selectedCustomerId" id="customer" class="form-select">
                        <option value="">Pilih Pelanggan</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="mb-3">
                <label for="payment-method">Metode Pembayaran:</label>
                <select wire:model="selectedPaymentMethod" id="payment-method" class="form-select">
                    @foreach ($paymentMethods as $method)
                        <option value="{{ $method }}">{{ ucfirst($method) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="discount">Diskon:</label>
                <input type="number" wire:model="discountAmount" id="discount" class="form-control"
                       wire:change="calculateTotalAmount">
            </div>

            <div class="mb-3">
                <strong>Subtotal:</strong> {{ number_format($subtotal, 0, ',', '.') }}
            </div>
            <div class="mb-3">
                <strong>Total:</strong> {{ number_format($totalAmount, 0, ',', '.') }}
            </div>

            <button wire:click="completeTransaction" class="btn btn-success">Selesaikan Transaksi</button>
        </div>
    </div>
</div>
