<div>
    <div id="debtPaymentModal" class="modal" style="display: {{ $showModal ? 'block' : 'none' }}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Pembayaran Hutang/Piutang</h5>
                <button type="button" class="btn-close" wire:click="closeModal"></button>
            </div>
            <div class="modal-body">
                <!-- Isi formulir pembayaran di sini -->
                <form>

                    <div class="mb-3">
                        <label for="debtType">Jenis Hutang/Piutang:</label>
                        <select wire:model="debtType" disabled class="form-select">
                            <option value="payable">Hutang</option>
                            <option value="receivable">Piutang</option>
                        </select>
                        @error('debtType') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="debtId">ID Hutang/Piutang:</label>
                        <input type="number" wire:model.live="debtId" value="{{$debtId}}" disabled class="form-control" placeholder="ID Hutang/Piutang">
                        @error('debtId') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="amountPaid">Jumlah Bayar:</label>
                        <input type="number" wire:model="amountPaid" step="0.01" class="form-control" placeholder="Jumlah Bayar">
                        @error('amountPaid') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="paymentMethod">Metode Pembayaran:</label>
                        <select wire:model="paymentMethod" class="form-select">
                            <option value="cash">Cash</option>
                            <option value="transfer">Transfer</option>
                            <option value="other">Other</option>
                        </select>
                        @error('paymentMethod') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="paymentDate">Tanggal Pembayaran:</label>
                        <input type="date" wire:model="paymentDate" class="form-control">
                        @error('paymentDate') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description">Deskripsi (Opsional):</label>
                        <textarea wire:model="description" class="form-control" placeholder="Deskripsi"></textarea>
                    </div>
                    @if ($successMessage)
                        <div class="alert alert-success mt-2">{{ $successMessage }}</div>
                    @endif
                    @if ($errorMessage)
                        <div class="alert alert-danger mt-2">{{ $errorMessage }}</div>
                    @endif
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" wire:click="closeModal">Tutup</button>
                <button type="button" class="btn btn-primary" wire:click="submit">Simpan</button>
            </div>
        </div>
    </div>

    <div class="modal-backdrop" style="display: {{ $showModal ? 'block' : 'none' }}"></div>
</div>
