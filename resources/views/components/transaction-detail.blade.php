<div class="transaction-card">
    <div class="transaction-header">
        Detail Transaksi - {{ $transactionType }}
    </div>
    <div class="transaction-body">
        <div class="transaction-info">
            <div class="info-item">
                <span class="info-label">ID Transaksi</span>
                <span class="info-value">{{ $transaction->id }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Toko</span>
                <span class="info-value">{{ $transaction->store->name }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Tanggal Transaksi</span>
                <span class="info-value">{{ $transaction->created_at }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Metode Pembayaran</span>
                <span class="info-value">{{ $transaction->payment_method }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Status Transaksi</span>
                <span class="status-badge w-fit {{ $transaction->transaction_status == 'Selesai' ? 'status-success' : 'status-pending' }}">
                    {{ $transaction->transaction_status }}
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Piutang</span>
                <span class="info-value">{{ $transaction->is_debt ? 'Ya' : 'Tidak' }}</span>
            </div>
        </div>

        <h2 class="text-xl font-semibold mb-4">Detail Item</h2>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Nama Item</th>
                    <th>Kuantitas</th>
                    <th>Harga Satuan</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($details as $detail)
                    <tr>
                        <td>{{ $detail->product->name_product }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>{{ number_format($detail->unit_price, 0, ',', '.') }}</td>
                        <td>{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">Tidak ada detail item.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-row">
                <span class="total-label">Total Diskon</span>
                <span class="total-value">Rp {{ number_format($transaction->total_discount, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span class="total-label">Total Pajak</span>
                <span class="total-value">Rp {{ number_format($transaction->total_tax, 0, ',', '.') }}</span>
            </div>
            <div class="total-row grand-total">
                <span class="total-label">Total Tagihan</span>
                <span class="total-value">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span class="total-label">Jumlah Bayar</span>
                <span class="total-value">Rp {{ number_format($transaction->amount_paid, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span class="total-label">Kembalian</span>
                <span class="total-value">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>
