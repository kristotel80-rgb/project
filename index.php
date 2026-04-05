<?php
require_once 'transferbank.php';
require_once 'ewallet.php';
require_once 'qris.php';
require_once 'cod.php';
require_once 'virtualaccount.php';

function rupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

$hasil = null;
$status = "";
$struk = "";
$statusText = "";
$warnaStatus = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // ambil input
    $jumlah = str_replace(['Rp', '.', ' '], '', $_POST['jumlah']);
    $metode = $_POST['metode'];

    // pilih metode pembayaran (OOP)
    switch ($metode) {
        case 'transfer': $bayar = new TransferBank($jumlah); break;
        case 'ewallet': $bayar = new EWallet($jumlah); break;
        case 'qris': $bayar = new QRIS($jumlah); break;
        case 'cod': $bayar = new COD($jumlah); break;
        case 'va': $bayar = new VirtualAccount($jumlah); break;
    }

    $total = $bayar->hitungTotal();

    $diskon = $jumlah * 0.10;
    $setelahDiskon = $jumlah - $diskon;
    $pajak = $setelahDiskon * 0.11;

    // status
    if ($metode == 'cod') {
        $statusText = "✔ COD Berhasil (Bayar di Tempat)";
    } else {
        $statusText = "✔ Pembayaran Berhasil";
    }
    $warnaStatus = "#22c55e";

    $status = $bayar->prosesPembayaran();
    $struk = $bayar->cetakStruk();

    $hasil = compact('jumlah','diskon','setelahDiskon','pajak','total');
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Sistem Pembayaran</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

<style>
* { font-family: 'Poppins'; }

body {
    margin: 0;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: radial-gradient(circle at top, #1e3a5f, #020617);
    color: #e2e8f0;
}

/* LAYOUT */
.container {
    display: flex;
    gap: 30px;
}

/* CARD */
.card {
    background: rgba(15, 23, 42, 0.95);
    padding: 25px;
    width: 350px;
    border-radius: 15px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.5);
    animation: fadeUp 0.8s ease;
}

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(40px); }
    to { opacity: 1; transform: translateY(0); }
}

.card:hover {
    transform: translateY(-5px);
    transition: 0.3s;
}

h2 {
    text-align: center;
    color: #38bdf8;
}

input, select {
    width: 100%;
    padding: 12px;
    margin-top: 10px;
    border-radius: 10px;
    border: none;
    background: #020617;
    color: white;
}

button {
    width: 100%;
    padding: 12px;
    margin-top: 15px;
    border-radius: 10px;
    border: none;
    background: linear-gradient(45deg, #0ea5e9, #2563eb);
    color: white;
    font-weight: bold;
    cursor: pointer;
}

.hasil {
    margin-top: 15px;
    padding: 15px;
    border-radius: 10px;
    background: #020617;
    border: 2px dashed #334155;
}

.status {
    padding: 8px;
    border-radius: 8px;
    text-align: center;
    font-weight: bold;
    margin-bottom: 10px;
    color: black;
}

.total {
    font-size: 18px;
    font-weight: bold;
    color: #22c55e;
}

.print-btn {
    margin-top: 10px;
    background: linear-gradient(45deg, #22c55e, #16a34a);
}
</style>
</head>

<body>

<div class="container">

    <!-- FORM -->
    <div class="card">
        <h2> Form Pembayaran</h2>

        <form method="POST">
            <input type="text" id="rupiah" name="jumlah" placeholder="Masukkan jumlah" required>

            <select name="metode" required>
                <option value="">-- Pilih Metode --</option>
                <option value="transfer">Transfer Bank</option>
                <option value="ewallet">E-Wallet</option>
                <option value="qris">QRIS</option>
                <option value="cod">COD</option>
                <option value="va">Virtual Account</option>
            </select>

            <button type="submit">Bayar</button>
        </form>
    </div>

    <!-- STRUK -->
    <div class="card">
        <h2> Struk Pembayaran</h2>

        <?php if ($hasil): ?>
        <div class="hasil" id="areaStruk">

            <div class="status" style="background: <?= $warnaStatus ?>">
                <?= $statusText ?>
            </div>

            <p><?= $status ?></p>
            <p><?= $struk ?></p>

            <hr>

            <p>Jumlah: <?= rupiah($hasil['jumlah']) ?></p>
            <p>Diskon 10%: <?= rupiah($hasil['diskon']) ?></p>
            <p>Setelah Diskon: <?= rupiah($hasil['setelahDiskon']) ?></p>
            <p>Pajak 11%: <?= rupiah($hasil['pajak']) ?></p>

            <p class="total">Total: <?= rupiah($hasil['total']) ?></p>

            <button onclick="printStruk()" class="print-btn">🖨 Print Struk</button>
        </div>
        <?php else: ?>
            <p style="text-align:center; color:#94a3b8;">
                Struk akan muncul di sini
            </p>
        <?php endif; ?>

    </div>

</div>

<script>
// AUTO FORMAT RP
const input = document.getElementById("rupiah");

input.addEventListener("keyup", function() {
    this.value = formatRupiah(this.value);
});

function formatRupiah(angka) {
    let number_string = angka.replace(/[^,\d]/g, ""),
        sisa = number_string.length % 3,
        rupiah = number_string.substr(0, sisa),
        ribuan = number_string.substr(sisa).match(/\d{3}/g);

    if (ribuan) {
        let separator = sisa ? "." : "";
        rupiah += separator + ribuan.join(".");
    }

    return rupiah ? "Rp " + rupiah : "";
}

// PRINT
function printStruk() {
    let isi = document.getElementById("areaStruk").innerHTML;
    let win = window.open('', '', 'width=400,height=600');
    win.document.write('<html><body>' + isi + '</body></html>');
    win.document.close();
    win.print();
}
</script>

</body>
</html>