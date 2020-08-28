<?php

class pelanggan extends Route{

    private $sn = 'pelangganData';
    private $su = 'utilityData';

    public function index()
    {
      $this -> bind('dasbor/pelanggan/pelanggan');
    }

    public function getDataPelanggan()
    {
      $requestData = $_REQUEST;
      $totalPelanggan = $this -> state($this -> sn) -> getJlhPelanggan();
      $dataPelanggan = $this -> state($this -> sn) -> getDataPelanggan($requestData);
      $data = array();

      foreach($dataPelanggan as $dp){
        $btnDetail = '<a href="#!" class="btn btn-sm btn-primary btnDetail" data-id='.$dp['id_pelanggan'].'><i class="fas fa-info-circle"></i> Detail</a>';
        $btnHapus = "<a href='#!' class='btn btn-sm btn-warning btnHapus' data-id='".$dp['id_pelanggan']."|".$dp['nama']."'><i class='fas fa-trash-alt'></i> Hapus</a>";
        $nestedData = array();
        $nestedData[] = $dp['nama'];
        $nestedData[] = $dp['alamat'];
        $nestedData[] = $dp['no_hp'];
        $nestedData[] = $dp['last_visit'];
        $nestedData[] = $this -> state($this -> sn) -> totalTransaksi($dp['id_pelanggan']);
        $nestedData[] = $btnDetail;
        $data[] = $nestedData;
      }

      $json_data = array(
        "draw"            => intval( $requestData['draw'] ),  
        "recordsTotal"    => intval( $totalPelanggan ), 
        "recordsFiltered" => intval( $totalPelanggan ), 
        "data"            => $data );

      echo json_encode($json_data);
    }

    public function prosesTambahPelanggan()
    {
      $nama           = $this -> inp('nama');
      $alamat         = $this -> inp('alamat');
      $email          = $this -> inp('email');
      $hp             = $this -> inp('hp');
      $visit          = $this -> waktu();
      $idPelanggan    = $this -> rnint(8);
      //cek apakah ada nomor handphone & nama yang sama
      $jlhPelanggan   = $this -> state($this -> sn) -> getNamaHandphone($nama, $hp);
      if($jlhPelanggan > 0){
        $data['status'] = 'error';
      }else{
        $this -> state($this -> sn) -> tambahPelanggan($idPelanggan, $nama, $alamat, $hp, $email, $visit);
        $data['status'] = 'sukses';
      }
      $this -> toJson($data);
    }

    public function detailPelanggan($kdPelanggan)
    {
      $data['kdPelanggan'] = $kdPelanggan;
      $data['totalTransaksi'] = $this -> state($this -> sn) -> totalTransaksi($kdPelanggan);
      $data['pelanggan'] = $this -> state($this -> sn) -> getDetailPelanggan($kdPelanggan);
      $data['historyPesanan'] = $this -> state($this -> sn) -> historyPesanan($kdPelanggan);
      $this -> bind('dasbor/pelanggan/detailPelanggan', $data);
    }

    public function updatePelanggan()
    {
      $kdPelanggan = $this -> inp('kdPelanggan');
      $nama = $this -> inp('nama');
      $alamat = $this -> inp('alamat');
      $nomorHp = $this -> inp('nomorHp');
      $email = $this -> inp('email');
      $this -> state($this -> sn) -> updatePelanggan($nama, $alamat, $nomorHp, $email, $kdPelanggan);
      $data['status'] = 'sukses';
      $this -> toJson($data);
    }

    public function hapusPelanggan()
    {
      $kdPelanggan = $this -> inp('kdPelanggan');
      $this -> state($this -> sn) -> hapusPelanggan($kdPelanggan);
      $data['status'] = 'sukses';
      $this -> toJson($data);
    }

}
