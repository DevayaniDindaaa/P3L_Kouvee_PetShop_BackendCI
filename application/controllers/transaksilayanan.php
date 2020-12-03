<?php

use Restserver \Libraries\REST_Controller;

Class transaksilayanan extends REST_Controller {
    
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, ContentLength, Accept-Encoding");
        parent::__construct();
        $this->load->model('TransaksiLayananModel');
        $this->load->library('form_validation');
        $this->load->helper(array('form','url'));
    }
    
    public function index_get() {
        $response = $this->TransaksiLayananModel->getAll();
        return $this->returnData($response['msg'], $response['error']);
    }
    
    public function index_post($no_transaksi_layanan = null) {
        $translayanan = new transLayanan();
        $translayanan->id_hewan = $this->post('ID_HEWAN');
        $translayanan->id_customer_service = $this->post('ID_CUSTOMER_SERVICE');

        if($no_transaksi_layanan == null){
            $response = $this->TransaksiLayananModel->store($translayanan);
        }
        else{
            $response = $this->TransaksiLayananModel->updateTransaksiProduk($translayanan, $noTransaksi);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function detail_post($id_detail_trans_layanan = null) {
        $detailLayanan = new detailTransLayanan();
        $detailLayanan->no_transaksi_layanan = $this->post('NO_TRANSAKSI_LAYANAN');
        $detailLayanan->id_layanan = $this->post('ID_LAYANAN');
        $detailLayanan->jumlah_layanan = $this->post('JUMLAH_LAYANAN');
        $detailLayanan->keterangan = $this->post('KETERANGAN');

        if($id_detail_trans_layanan == null){
            $response = $this->TransaksiLayananModel->tambahDetail($detailLayanan);
        }
        else{
            $response = $this->TransaksiLayananModel->updateDetail($detailLayanan, $id_detail_trans_layanan);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function totalHarga_post() {
        $no_transaksi_layanan = $this->post('NO_TRANSAKSI_LAYANAN');

        $response = $this->TransaksiLayananModel->updateHarga($no_transaksi_layanan);
        
        return $this->returnData($response['msg'], $response['error']);
    }

    public function deletedetail_post(){
        $detailLayanan = new detailTransLayanan();
        $detailLayanan->id_detail_trans_layanan = $this->post('ID_DETAIL_TRANS_LAYANAN');
        $response = $this->TransaksiLayananModel->destroy($detailLayanan);
        return $this->returnData($response['msg'], $response['error']);
    }
    
    public function sendsms_post(){
        $no_transaksi_layanan = $this->post('NO_TRANSAKSI_LAYANAN');

        $response = $this->TransaksiLayananModel->updateStatus($no_transaksi_layanan);
        
        return $this->returnData($response['msg'], $response['error']);
    }

    public function returnData($msg,$error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }

}
Class transLayanan{
    public $no_transaksi_layanan;
    public $id_hewan;
    public $id_customer_service;
}

Class detailTransLayanan{
    public $id_detail_trans_layanan;
    public $no_transaksi_layanan;
    public $id_layanan;
    public $jumlah_layanan;
    public $status_data;
    public $time_stamp;
    public $keterangan;
}