<?php
require_once 'transferbank.php';
require_once 'ewallet.php';
require_once 'qris.php';
require_once 'cod.php';
require_once 'virtual.account.php';

// objek
$transfer = new TransferBank(100000);
$ewallet  = new EWallet(50000);
$qris     = new QRIS(75000);
$cod     = new COD(80000);
$virtualaccount    = new VirtualAccount(700000);

// output
echo $transfer->prosesPembayaran();
echo "<br>";
echo $transfer->cetakStruk();

echo "<hr>";

echo $ewallet->prosesPembayaran();
echo "<br>";
echo $ewallet->cetakStruk();

echo "<hr>";

echo $qris->prosesPembayaran();
echo "<br>";
echo $qris->cetakStruk();

echo "<hr>";

echo $cod->prosesPembayaran();
echo "<br>";
echo $cod->cetakStruk();

echo "<hr>";

echo $virtualaccount->prosesPembayaran();
echo "<br>";
echo $virtualaccount->cetakStruk();
?>