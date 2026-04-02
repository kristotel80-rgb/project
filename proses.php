<?php
require_once 'transferbank.php';
require_once 'ewallet.php';
require_once 'qris.php';
require_once 'cod.php';
require_once 'virtualaccount.php';
require_once 'hitung.php';

// ambil data form
$jumlah = $_POST['jumlah'];
$metode = $_POST['metode'];

// pilih metode pembayaran
switch ($metode) {
    case 'transfer':
        $bayar = new TransferBank($jumlah);
        break;
    case 'ewallet':
        $bayar = new EWallet($jumlah);
        break;
    case 'qris':
        $bayar = new QRIS($jumlah);
        break;
    case 'cod':
        $bayar = new COD($jumlah);
        break;
    case 'va':
        $bayar = new VirtualAccount($jumlah);
        break;
}

// hitung diskon & pajak
$hitung = new HitungPembayaran($jumlah);

$diskon = $hitung->diskon();
$setelahDiskon = $jumlah - $diskon;
$pajak = $hitung->pajak($setelahDiskon);
$total = $hitung->totalAkhir();

?>

<h2>Hasil Pembayaran</h2>

<?php
echo $bayar->prosesPembayaran();
echo "<br>";
echo $bayar->cetakStruk();

echo "<hr>";
echo "Jumlah Awal: Rp $jumlah <br>";
echo "Diskon 10%: Rp $diskon <br>";
echo "Setelah Diskon: Rp $setelahDiskon <br>";
echo "Pajak 11%: Rp $pajak <br>";
echo "<b>Total Bayar: Rp $total</b>";
?>