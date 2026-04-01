<?php
require_once 'pembayaran.php';
require_once 'cetak.php';

#Penggunaan Class QRIS
class Virtualaccount extends Pembayaran implements Cetak {

    public function prosesPembayaran() {
        if ($this->validasi()) {
            return "Pembayaran Virtual Account Rp {$this->jumlah} berhasil";
        }
        return "Jumlah tidak valid";
    }

    public function cetakStruk() {
        return "Virtual Account: Rp {$this->jumlah}";
    }
}
?>