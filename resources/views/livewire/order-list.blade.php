<div class="table-responsive">
  <table class="table">
    <tbody>
      @forelse ($orderItems as $item)
        <tr>
          <td>{{ $item['product']['name_product'] }}</td>
          <td>{{ $item['quantity'] }}</td>
          <td>Rp. {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="3">Keranjang kosong.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
