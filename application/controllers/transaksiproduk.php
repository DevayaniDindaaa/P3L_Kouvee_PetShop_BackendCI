<?php

use Restserver \Libraries\REST_Controller;

Class transaksiproduk extends REST_Controller {
    
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, ContentLength, Accept-Encoding");
        parent::__construct();
        $this->load->model('TransaksiProdukModel');
        $this->load->library('form_validation');
        $this->load->helper(array('form','url'));
    }
    
    public function index_get() {
        $response = $this->TransaksiProdukModel->getAll();
        return $this->returnData($response['msg'], $response['error']);
    }
    
    public function index_post($no_transaksi_produk = null) {
        $transproduk = new transProduk();
        $transproduk->id_hewan = $this->post('ID_HEWAN');
        $transproduk->id_customer_service = $this->post('ID_CUSTOMER_SERVICE');

        if($no_transaksi_produk == null){
            $response = $this->TransaksiProdukModel->store($transproduk);
        }
        else{
            $response = $this->TransaksiProdukModel->updateTransaksiProduk($transproduk, $no_transaksi_produk);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function detail_post($id_detail_trans_produk = null) {
        $detailProduk = new detailTransProduk();
        $detailProduk->no_transaksi_produk = $this->post('NO_TRANSAKSI_PRODUK');
        $detailProduk->id_produk = $this->post('ID_PRODUK');
        $detailProduk->jumlah_produk = $this->post('JUMLAH_PRODUK');
        $detailProduk->keterangan = $this->post('KETERANGAN');

        if($id_detail_trans_produk == null){
            $response = $this->TransaksiProdukModel->tambahDetail($detailProduk);
        }
        else{
            $response = $this->TransaksiProdukModel->updateDetail($detailProduk, $id_detail_trans_produk);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function totalHarga_post() {
        $no_transaksi_produk = $this->post('NO_TRANSAKSI_PRODUK');

        $response = $this->TransaksiProdukModel->updateHarga($no_transaksi_produk);
        
        return $this->returnData($response['msg'], $response['error']);
    }

    public function deletedetail_post(){
        $detailProduk = new detailTransProduk();
        $detailProduk->id_detail_trans_produk = $this->post('ID_DETAIL_TRANS_PRODUK');
        $response = $this->TransaksiProdukModel->destroy($detailProduk);
        return $this->returnData($response['msg'], $response['error']);
    }
    
    public function returnData($msg,$error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }

}
Class transProduk{
    public $no_transaksi_produk;
    public $id_hewan;
    public $id_customer_service;
}

Class detailTransProduk{
    public $id_detail_trans_produk;
    public $no_transaksi_produk;
    public $id_produk;
    public $jumlah_produk;
    public $status_data;
    public $time_stamp;
    public $keterangan;
}