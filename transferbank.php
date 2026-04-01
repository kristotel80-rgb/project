<?php
require_once 'pembayaran.php';
require_once 'cetak.php';

#Penggunaan Class Transfer Bank dari Extend
class TransferBank extends Pembayaran implements Cetak {

    public function prosesPembayaran() {
        if ($this->validasi()) {
            return "Transfer Bank sebesar Rp {$this->jumlah} berhasil";
        }
        return "Jumlah tidak valid";
    }

    public function cetakStruk() {
        return "Struk Transfer Bank: Rp {$this->jumlah}";
    }
}
?>