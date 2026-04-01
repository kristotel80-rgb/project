<?php
require_once 'pembayaran.php';
require_once 'cetak.php';

#Penggunaan Class QRIS
class QRIS extends Pembayaran implements Cetak {

    public function prosesPembayaran() {
        if ($this->validasi()) {
            return "Pembayaran QRIS Rp {$this->jumlah} berhasil";
        }
        return "Jumlah tidak valid";
    }

    public function cetakStruk() {
        return "Struk QRIS: Rp {$this->jumlah}";
    }
}
?>