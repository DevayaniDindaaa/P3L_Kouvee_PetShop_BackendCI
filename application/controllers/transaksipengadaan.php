<?php

use Restserver \Libraries\REST_Controller;

Class transaksipengadaan extends REST_Controller {
    
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, ContentLength, Accept-Encoding");
        parent::__construct();
        $this->load->model('PengadaanModel');
        $this->load->library('form_validation');
        $this->load->helper(array('form','url'));
    }
    
    public function index_get() {
        $response = $this->PengadaanModel->getAll();
        return $this->returnData($response['msg'], $response['error']);
    }
    
    public function index_post($nomor_pemesanan = null) {
        $pengadaan = new pemesananProduk();
        $pengadaan->id_supplier = $this->post('ID_SUPPLIER');

        if($nomor_pemesanan == null){
            $response = $this->PengadaanModel->store($pengadaan);
        }
        else{
            $response = $this->PengadaanModel->updatePengadaan($pengadaan, $nomor_pemesanan);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function detail_post($id_detail_pengadaan = null) {
        $detailProduk = new detailPemesanan();
        $detailProduk->nomor_pemesanan = $this->post('NOMOR_PEMESANAN');
        $detailProduk->id_produk = $this->post('ID_PRODUK');
        $detailProduk->jumlah_produk_dipesan = $this->post('JUMLAH_PRODUK_DIPESAN');

        if($id_detail_pengadaan == null){
            $response = $this->PengadaanModel->tambahDetail($detailProduk);
        }
        else{
            $response = $this->PengadaanModel->updateDetail($detailProduk, $id_detail_pengadaan);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function deletedetail_post(){
        $detailProduk = new detailPemesanan();
        $detailProduk->id_detail_pengadaan = $this->post('ID_DETAIL_PENGADAAN');
        $response = $this->PengadaanModel->destroy($detailProduk);
        return $this->returnData($response['msg'], $response['error']);
    }
    
    public function returnData($msg,$error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }

    public function Rules() { return $this->rule; }

}
Class pemesananProduk{
    public $nomor_pemesanan;
    public $id_supplier;
    public $tgl_pemesanan;
    public $tgl_cetak_surat_pemesanan;
    public $status_cetak_surat;
    public $status_kedatangan_produk;
}

Class detailPemesanan{
    public $nomor_pemesanan;
    public $id_produk;
    public $jumlah_produk_dipesan;
    public $status_data;
    public $time_stamp;
    public $keterangan;
}