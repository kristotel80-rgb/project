<?php
require_once 'transferbank.php';
require_once 'ewallet.php';
require_once 'qris.php';
require_once 'cod.php';
require_once 'virtualaccount.php';
require_once 'hitung.php';

$hasil = null;
$status = "";
$struk = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jumlah = $_POST['jumlah'];
    $metode = $_POST['metode'];

    // pilih metode pembayaran
    switch ($metode) {
        case 'transfer': $bayar = new TransferBank($jumlah); break;
        case 'ewallet': $bayar = new EWallet($jumlah); break;
        case 'qris': $bayar = new QRIS($jumlah); break;
        case 'cod': $bayar = new COD($jumlah); break;
        case 'va': $bayar = new VirtualAccount($jumlah); break;
    }

    // hitung diskon & pajak
    $hitung = new HitungPembayaran($jumlah);

    $diskon = $hitung->diskon();
    $setelahDiskon = $jumlah - $diskon;
    $pajak = $hitung->pajak($setelahDiskon);
    $total = $hitung->totalAkhir();

    $hasil = [
        'jumlah' => $jumlah,
        'diskon' => $diskon,
        'setelahDiskon' => $setelahDiskon,
        'pajak' => $pajak,
        'total' => $total
    ];

    $status = $bayar->prosesPembayaran();
    $struk = $bayar->cetakStruk();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistem Pembayaran</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            background: linear-gradient(135deg, #0f172a, #1e293b);
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            background: #111827;
            padding: 30px;
            width: 400px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.6);
            color: #e5e7eb;
        }

        h2 {
            text-align: center;
            color: #f9fafb;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            border-radius: 8px;
            border: 1px solid #374151;
            background: #1f2937;
            color: white;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            background: linear-gradient(45deg, #2563eb, #1e40af);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            transform: scale(1.05);
        }

        .hasil {
            margin-top: 20px;
            padding: 15px;
            border-radius: 10px;
            background: #020617;
            border-left: 5px solid #2563eb;
        }

        .total {
            font-size: 20px;
            font-weight: bold;
            color: #38bdf8;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h2>💳 Sistem Pembayaran</h2>

        <form method="POST">
            <input type="number" name="jumlah" placeholder="Masukkan jumlah" required>

            <select name="metode">
                <option value="transfer">Transfer Bank</option>
                <option value="ewallet">E-Wallet</option>
                <option value="qris">QRIS</option>
                <option value="cod">COD</option>
                <option value="va">Virtual Account</option>
            </select>

            <button type="submit">Bayar</button>
        </form>

        <?php if ($hasil): ?>
        <div class="hasil">
            <p><?= $status ?></p>
            <p><?= $struk ?></p>
            <hr>

            <p>Jumlah Awal: Rp <?= number_format($hasil['jumlah']) ?></p>
            <p>Diskon 10%: Rp <?= number_format($hasil['diskon']) ?></p>
            <p>Setelah Diskon: Rp <?= number_format($hasil['setelahDiskon']) ?></p>
            <p>Pajak 11%: Rp <?= number_format($hasil['pajak']) ?></p>

            <p class="total">Total Bayar: Rp <?= number_format($hasil['total']) ?></p>
        </div>
        <?php endif; ?>

    </div>
</div>

</body>
</html>