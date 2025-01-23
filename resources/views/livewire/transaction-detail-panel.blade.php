<div class="offcanvas-container" style="display: {{ $showDetailPanel ? 'block' : 'none' }}; position: fixed; top: 0; right: 0; width: 300px; height: 100%; overflow-y: auto; background-color: white; box-shadow: -2px 0px 10px rgba(0,0,0,0.2); z-index: 1000; ">  <!--Gaya Offcanvas Manual-->
    <div class="offcanvas-header">
        <h5 >Detail Transaksi</h5>
        <button wire:click="toggleDetailPanel" class="btn-close text-reset" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-4">
        @if ($transaction)
            <p><strong>Tipe Transaksi:</strong> {{ $transactionType }}</p>
            <p><strong>ID Transaksi:</strong> {{ $idTransaction }}</p>
            <p><strong>Total:</strong> {{ $transaction->total_amount }}</p>
            <p><strong>Metode Pembayaran:</strong> {{ $transaction->payment_method }}</p>
            @if ($transactionDetails->isNotEmpty())
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Kuantitas</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactionDetails as $detail)
                            <tr>
                                <td>{{ $detail->product ? $detail->product->name_product : 'Product not found' }}</td>
                                <td>{{ $detail->quantity }}</td>
                                <td>{{ $detail->subtotal }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Tidak ada detail transaksi.</p>
            @endif
        @else
            <p>Transaksi tidak ditemukan.</p>
        @endif
    </div>
</div>
