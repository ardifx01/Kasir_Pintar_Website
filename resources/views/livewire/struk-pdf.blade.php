<!DOCTYPE html>
<html>
<head>
    <title>Struk {{ $type === 'selling' ? 'Penjualan' : 'Pembelian' }}</title>
    <style>
        body {
            font-family: sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
        }
    </style>
</head>
<body>
    <h1>Struk {{ $type === 'selling' ? 'Penjualan' : 'Pembelian' }}</h1>
    <p>Tanggal: {{ $tanggal }}</p>
    <p>Total: {{ $transaction->total_amount }}</p>

    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaction->{$type === 'selling' ? 'sellingDetailTransactions' : 'purchaseDetailTransactions'} as $detail)
                <tr>
                    <td>{{ $detail->product->name_product }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ $detail->subtotal }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
