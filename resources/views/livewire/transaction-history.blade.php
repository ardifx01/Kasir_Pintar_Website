<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Filters Section - Single Row -->
            <div class="d-flex gap-3 align-items-end mb-4">
                <div class="flex-grow-1">
                    <label for="storeId" class="form-label">Toko</label>
                    <select wire:model.live="storeId" class="form-select">
                        <option value="">Semua Toko</option>
                        @foreach ($stores as $store)
                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-grow-1">
                    <label for="startDate" class="form-label">Tanggal Mulai</label>
                    <input type="date" wire:model.live="startDate" class="form-control">
                </div>
                <div class="flex-grow-1">
                    <label for="endDate" class="form-label">Tanggal Akhir</label>
                    <input type="date" wire:model.live="endDate" class="form-control">
                </div>
                <div class="flex-grow-1">
                    <label for="isDebt" class="form-label">Hutang</label>
                    <select wire:model.live="isDebt" class="form-select">
                        <option value="">Semua</option>
                        <option value="1">Ya</option>
                        <option value="0">Tidak</option>
                    </select>
                </div>
            </div>

            <!-- Table Section -->
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Toko</th>
                            <th>Total</th>
                            <th>Metode Pembayaran</th>
                            <th>Status</th>
                            <th>Hutang</th>
                            <th>Tipe Transaksi</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->created_at->format('d/m/Y') }}</td>
                                <td>{{ $transaction->store->name }}</td>
                                <td>{{ $transaction->total_amount }}</td>
                                <td>{{ $transaction->payment_method }}</td>
                                <td>
                                    <span class="badge bg-{{ $transaction->transaction_status === 'completed' ? 'success' : 'warning' }}">
                                        {{ $transaction->transaction_status }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $transaction->is_debt ? 'danger' : 'success' }}">
                                        {{ $transaction->is_debt ? 'Ya' : 'Tidak' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $transactionType === 'selling' ? 'Penjualan' : 'Pembelian' }}
                                    </span>
                                </td>

                                <td>
                                    <button class="btn btn-primary" type="button" wire:click="showDetailTransaction({{ $transaction->id }})" >
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
