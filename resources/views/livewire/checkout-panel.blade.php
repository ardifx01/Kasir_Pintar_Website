 <div class="card w-300">
    <div class="card-body">
        <div class="input-group mb-3">
            <div class="input-group-prepend m-0"">
                <span class="input-group-text h-full"  id="basic-addon1">
                    @svg('ionicon-barcode-outline',["width"=>20])
                </span>
            </div>
            <input type="text" wire:model.live="search" class="form-control" placeholder="kode barang" aria-label="Username" aria-describedby="basic-addon1">
        </div>
        <div class="h-200 overflow-auto mb-2 card p-2">
            @livewire('order-list', ['orderItems' => $orderItems])
        </div>

        <div class="h-300 mt-3 overflow-auto p-2 card">

            <table class="table">
                <tr>
                    <td>
                        <label for="discount" class="text-center">Diskon:</label>
                    </td>
                    <td>
                        <input type="number" wire:model.live="discount" id="discount" class="form-control" value="{{ $discount }}">
                        @error('discount') <span class="error">{{ $message }}</span> @enderror
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="taxPercentage">Pajak (%):</label>
                    </td>
                    <td>
                        <input type="number" wire:model.live="taxPercentage" id="taxPercentage" class="form-control" value="{{ $taxPercentage }}">
                        @error('taxPercentage') <span class="error">{{ $message }}</span> @enderror
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="payment-method">Metode Pembayaran:</label>
                    </td>
                    <td>
                        <select wire:model="paymentMethod" id="payment-method" class="form-select">
                            <option value="cash">Tunai</option>
                            <option value="card">Transfer</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Subtotal: </p>
                    </td>
                    <td>
                        <p>Rp. {{ number_format($subtotal, 0, ',', '.') }}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Total Belanja:</p>
                    </td>
                    <td>
                        <p>Rp. {{ number_format($total_belanja, 0, ',', '.') }}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="paymentAmount">Nominal Bayar:</label>
                    </td>
                    <td>
                        <input type="number" wire:model.live="paymentAmount" id="paymentAmount" class="form-control">
                        @error('paymentAmount') <span class="error">{{ $message }}</span> @enderror
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Kembalian:</label>
                    </td>
                    <td>
                        <p> Rp. {{ number_format($change, 0, ',', '.') }}</p>
                    </td>
                </tr>
                <tr >
                    <td colspan="2">
                        <p class="text-center mb-0">{{ $this->transactionType == "selling" ? "Customer :" : "Supplier :"}}</p>

                        @if($this->transactionType == 'selling')
                            <select wire:model.live="clientId" class="form-select" {{ $change <= 0 ? '' : 'disabled' }}>
                                <option value="0">Pilih Client</option>
                                @foreach ($clients as $id => $name)
                                    <option value="{{$id}}">{{ $name }}</option>
                                @endforeach
                            </select>
                        @elseif($this->transactionType == 'purchasing')
                            <select wire:model.live="supplierId" class="form-select" >
                                <option value="0">Pilih Supplier</option>
                                @foreach ($clients as $id => $name)
                                    <option value="{{$id}}">{{ $name }}</option>
                                @endforeach
                            </select>
                        @endif

                        @error('clientId') <span class="error text-sm text-danger">{{ $message }}</span> @enderror
                    </td>
                </tr>
            </table>
        </div>

        <button wire:click="saveTransaction" class="btn btn-primary mt-3">Proses Pembayaran</button>
    </div>
</div>
