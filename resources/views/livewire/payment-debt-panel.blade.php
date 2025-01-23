<div class="offcanvas-container w-400" style="display: {{ $showDetailPanel ? 'block' : 'none' }}; position: fixed; top: 0; right: 0; width: 300px; height: 100%; overflow-y: auto; background-color: white; box-shadow: -2px 0px 10px rgba(0,0,0,0.2); z-index: 1000;">
    <div class="offcanvas-header">
        <h5>Detail Pembayaran Hutang/Piutang</h5>
        <button wire:click="toggleDetailPanel" class="btn-close text-reset" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-4">
        @if ($error)
            <div class="alert alert-danger">{{ $error }}</div>
        @elseif ($debt)
            <p><strong>Tipe Debt:</strong> {{ $debtType === 'payable' ? 'Hutang' : 'Piutang' }}</p>
            <p><strong>ID Debt:</strong> {{ $debtId }}</p>
            <p><strong>Total:</strong> {{ $debt->amount_due }}</p>
            <p><strong>Status:</strong> {{ $debt->payment_status }}</p>
            @if($debt->payment_status == 'paid')
                <button class="btn btn-primary btn-sm" disabled>Buat Pembayaran</button>
            @else
                <button class="btn btn-primary btn-sm" wire:click="showPaymentModal({{ $debt->id }})">Buat Pembayaran</button>
            @endif

            @if ($paymentHistories->isNotEmpty())
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>Tanggal Pembayaran</th>
                            <th>Jumlah</th>
                            <th>Metode Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paymentHistories as $history)
                            <tr>
                                <td>{{ $history->payment_date->format('Y-m-d') }}</td>
                                <td>{{ $history->amount_paid }}</td>
                                <td>{{ $history->payment_method }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Belum ada pembayaran.</p>
            @endif
        @else
            <p>Tidak ada hutang/piutang yang dipilih.</p>
        @endif
    </div>
</div>
