<?php
class HitungPembayaran {
    private $jumlah;

    public function __construct($jumlah) {
        $this->jumlah = $jumlah;
    }

    public function diskon() {
        return $this->jumlah * 0.10;
    }

    public function pajak($setelahDiskon) {
        return $setelahDiskon * 0.11;
    }

    public function totalAkhir() {
        $diskon = $this->diskon();
        $setelahDiskon = $this->jumlah - $diskon;
        $pajak = $this->pajak($setelahDiskon);
        return $setelahDiskon + $pajak;
    }
}
?>