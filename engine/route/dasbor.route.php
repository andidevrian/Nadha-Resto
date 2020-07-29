<?php

class dasbor extends Route{
    //inisialisasi state
    private $sn = 'dasborData';
    private $su = 'utilityData';

    public function __construct()
    {
        $this -> st = new state;
    }

    public function index()
    {     
        $this -> bind('/dasbor/index');   
    }

    public function beranda()
    {
        $this -> bind('/dasbor/beranda');   
    }

    public function getJlhPengunjung()
    {
        $data['pengunjung'] = $this -> state($this -> su) -> getJlhPengunjung();
        $this -> toJson($data);
    }

    public function getMenuTerlaris()
    {
        $data['menuTerlaris'] = $this -> state('utilityData') -> getMenuTerlaris();
        $this -> toJson($data);
    }

    public function getTransaksiTerakhir()
    {
        $gtt = $this -> state('utilityData') -> getTransaksiTerakhir();
        foreach($gtt as $gt){
            $kdPesanan = $gt['kd_pesanan'];
            //ambil 
            $kdPelanggan = $this -> state('utilityData') -> getPelangganFromPesanan($kdPesanan);
            $namaPelanggan = $this -> state('utilityData') -> getNamaPelanggan($kdPelanggan);
            $arrTemp['namaPelanggan'] = $namaPelanggan;
            $arrTemp['total'] = $gt['total_final'];
            $arrTemp['waktu'] = $gt['waktu'];
            $data['lt'][] = $arrTemp;
        }
        $this -> toJson($data);
    }

    public function logOut()
    {
        $this -> destses();
        $this -> goto(HOMEBASE.'login');
    }

}