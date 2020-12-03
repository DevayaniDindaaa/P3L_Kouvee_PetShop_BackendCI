<?php

use Restserver \Libraries\REST_Controller;

Class tambahproduk extends REST_Controller {
    
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, ContentLength, Accept-Encoding");
        parent::__construct();
        $this->load->model('TambahProdukModel');
        $this->load->library('form_validation');
        $this->load->helper(array('form','url'));
    }
    
    public function ubahstatus_post(){
        $pengadaan = new pemesananProduk();
        $pengadaan->nomor_pemesanan = $this->post('NOMOR_PEMESANAN');
        $response = $this->TambahProdukModel->update($pengadaan);
        return $this->returnData($response['msg'], $response['error']);
    }

    public function addstok_post() {
        $details = new detailPemesanan();
        $details->id_detail_pengadaan = $this->post('ID_DETAIL_PENGADAAN');
        $details->id_produk = $this->post('ID_PRODUK');
        $details->jumlah_produk_dipesan = $this->post('JUMLAH_PRODUK_DIPESAN');
        $response = $this->TambahProdukModel->addstok($details);
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
    public $id_detail_pengadaan;
    public $id_produk;
    public $jumlah_produk_dipesan;
    public $status_data;
    public $time_stamp;
    public $keterangan;
}