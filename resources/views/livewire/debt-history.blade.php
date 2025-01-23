<div class="p-2">
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="storeId" class="form-label">Toko:</label>
            <select wire:model="storeId" class="form-select">
                <option value="">Semua Toko</option>
                @foreach ($stores as $store)
                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label for="startDate" class="form-label">Tanggal Mulai:</label>
            <input type="date" wire:model="startDate" class="form-control">
        </div>
        <div class="col-md-4 mb-3">
            <label for="endDate" class="form-label">Tanggal Akhir:</label>
            <input type="date" wire:model="endDate" class="form-control">
        </div>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Tanggal Jatuh Tempo</th>
                <th>Toko</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>{{ $debtType === 'payable' ? 'Supplier' : 'Pelanggan' }}</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($debts as $debt)
                <tr>
                    <td>{{ $debt->due_date->format('d/m/Y') }}</td>
                    <td>{{ $debt->transaction->store->name }}</td>
                    <td>{{ $debt->amount_due }}</td>
                    <td>{{ $debt->payment_status }}</td>
                    <td>{{ $debtType === 'payable' ? $debt->supplier->name : $debt->customer->name }}</td>
                    <td><button class="btn btn-primary" wire:click="showDebtDetail({{ $debt->id }})">Detail</button></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $debts->links() }}
</div>
