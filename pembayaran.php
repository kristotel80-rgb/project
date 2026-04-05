<?php
# Penggunaan Abstrak Class
abstract class Pembayaran {
    protected $jumlah;

    public function __construct($jumlah) {
        $this->jumlah = $jumlah;
    }

    #Diskon dan Pajak
    public function hitungTotal() {
        $diskon = $this->jumlah * 0.10;
        $setelahDiskon = $this->jumlah - $diskon;
        $pajak = $setelahDiskon * 0.11;

        return $setelahDiskon + $pajak;
    }

    // method wajib
    abstract public function prosesPembayaran();

    // method umum
    public function validasi() {
        return $this->jumlah > 0;
    }
}
?>