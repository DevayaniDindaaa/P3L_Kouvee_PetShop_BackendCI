<?php

use Restserver \Libraries\REST_Controller;

Class layanan extends REST_Controller {
    
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, ContentLength, Accept-Encoding");
        parent::__construct();
        $this->load->model('LayananModel');
        $this->load->library('form_validation');
        $this->load->helper(array('form','url'));
    }
    
    public function index_get() {
        $response = $this->LayananModel->getAll();
        return $this->returnData($response['msg'], $response['error']);
    }

    public function sortHarga_get() {
        $response = $this->LayananModel->getSortHarga();
        return $this->returnData($response['msg'], $response['error']);
    }

    public function sortHarga2_get() {
        $response = $this->LayananModel->getSortHarga2();
        return $this->returnData($response['msg'], $response['error']);
    }
    
    public function index_post($id_layanan = null) {
        $validation = $this->form_validation;
        $rule = $this->LayananModel->rules();
        if($id_layanan == null){
            array_push($rule,
                [
                    'field' => 'ID_UKURAN_HEWAN',
                    'label' => 'ID_UKURAN_HEWAN',
                    'rules' => 'required'
                ],
                [
                    'field' => 'ID_JENIS_HEWAN',
                    'label' => 'ID_JENIS_HEWAN',
                    'rules' => 'required'
                ],
                [
                    'field' => 'NAMA_LAYANAN',
                    'label' => 'NAMA_LAYANAN',
                    'rules' => 'required'
                ],
                [
                    'field' => 'KETERANGAN',
                    'label' => 'KETERANGAN',
                    'rules' => 'required'
                ],
            );
        }
        else{
            array_push($rule,
                [
                    'field' => 'ID_UKURAN_HEWAN',
                    'label' => 'ID_UKURAN_HEWAN',
                    'rules' => 'required'
                ],
                [
                    'field' => 'ID_JENIS_HEWAN',
                    'label' => 'ID_JENIS_HEWAN',
                    'rules' => 'required'
                ],
                [
                    'field' => 'NAMA_LAYANAN',
                    'label' => 'NAMA_LAYANAN',
                    'rules' => 'required'
                ],
                [
                    'field' => 'KETERANGAN',
                    'label' => 'KETERANGAN',
                    'rules' => 'required'
                ],
            );
        }
    
        $validation->set_rules($rule);
        
        if (!$validation->run()) {
            return $this->returnData($this->form_validation->error_array(), true);
        }

        $Layanan = new LayananData();
        $Layanan->id_ukuran_hewan = $this->post('ID_UKURAN_HEWAN');
        $Layanan->id_jenis_hewan = $this->post('ID_JENIS_HEWAN');
        $Layanan->nama_layanan = $this->post('NAMA_LAYANAN');
        $Layanan->harga_satuan_layanan = $this->post('HARGA_SATUAN_LAYANAN');
        $Layanan->status_data = $this->post('STATUS_DATA');
        $Layanan->keterangan = $this->post('KETERANGAN');

        if($id_layanan == null){
            $response = $this->LayananModel->store($Layanan);
        }
        else{
            $response = $this->LayananModel->update($Layanan, $id_layanan);
        }
        return $this->returnData($response['msg'], $response['error']);
    }
            
    public function delete_post($id_layanan = null){
        $Layanan = new LayananData();
        $Layanan->status_data = $this->post('STATUS_DATA');
        $Layanan->keterangan = $this->post('KETERANGAN');

        if($id_layanan == null){
            return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        else{
            $response = $this->LayananModel->data_hapus($Layanan, $id_layanan);
        }
        return $this->returnData($response['msg'], $response['error']);
    }
    
    public function returnData($msg,$error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }

    public function Rules() { return $this->rule; }

}
Class LayananData{
    public $id_ukuran_hewan;
    public $id_jenis_hewan;
    public $nama_layanan;
    public $harga_satuan_layanan;
    public $time_stamp;
    public $status_data;
    public $keterangan;
}