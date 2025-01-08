<!-- application/views/receipt_view.php -->
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembelian</title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 8pt;
            text-align: center;
        }

        .content {
            width: 100%;
        }

        .content p {
            margin: 3px 0;
        }

        .header,
        .footer {
            margin-bottom: 15px;
        }

        .line {
            border-top: 1px solid #000;
            margin: 10px 0;
        }

        .table {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 5px;
            text-align: left;
            border: 1px solid #000;
        }

        .table th {
            text-align: center;
        }

        .table .item-name {
            text-align: left;
        }

        .table .item-quantity,
        .table .item-price,
        .table .item-subtotal {
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="content">
        <!-- Header -->
        <div class="header">
            <h3>Aplikasi Kasir 1.0</h3>
            <div class="line"></div>
            <p><strong>No Transaksi:</strong> <?= $id_transaksi ?></p>
            <p><strong>Tanggal:</strong> <?= date('d-m-Y H:i:s', strtotime($tanggal)) ?></p>
            <p><strong>Kasir:</strong> <?= $kasir ?></p>
        </div>

        <!-- Transaction Items (Table) -->
        <div class="items">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($details as $item): ?>
                        <tr>
                            <td class="item-name"><?= $item->nama_barang ?></td>
                            <td class="item-quantity"><?= $item->jumlah ?> x</td>
                            <td class="item-price"><?= number_format($item->harga, 0, ',', '.') ?></td>
                            <td class="item-subtotal"><?= number_format($item->subtotal, 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="line"></div>

        <!-- Totals -->
        <div class="totals">
            <p><strong>Total:</strong> <?= number_format($total, 0, ',', '.') ?></p>
            <p><strong>Bayar:</strong> <?= number_format($bayar, 0, ',', '.') ?></p>
            <p><strong>Kembali:</strong> <?= number_format($kembali, 0, ',', '.') ?></p>
        </div>

        <div class="line"></div>

        <!-- Footer -->
        <div class="footer">
            <p>Terima Kasih atas Kunjungan Anda</p>
        </div>
    </div>

</body>

</html>