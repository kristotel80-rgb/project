<?php
require_once 'Pembayaran.php';
require_once 'Cetak.php';

#Penggunaan Class COD
class COD extends Pembayaran implements Cetak {

    public function prosesPembayaran() {
        if ($this->validasi()) {
            return "Pembayaran COD Rp {$this->jumlah} berhasil";
        }
        return "Jumlah tidak valid";
    }

    public function cetakStruk() {
        return "Struk COD: Rp {$this->jumlah}";
    }
}
?>