<?php

use Restserver \Libraries\REST_Controller;

Class ukuranhewan extends REST_Controller {
    
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, ContentLength, Accept-Encoding");
        parent::__construct();
        $this->load->model('UkuranHewanModel');
        $this->load->library('form_validation');
        $this->load->helper(array('form','url'));
    }
    
    public function index_get() {
        $response = $this->UkuranHewanModel->getAll();
        return $this->returnData($response['msg'], $response['error']);
    }
    
    public function index_post($id_ukuran_hewan = null) {
        $validation = $this->form_validation;
        $rule = $this->UkuranHewanModel->rules();
        if($id_ukuran_hewan == null){
            array_push($rule,
                [
                    'field' => 'NAMA_UKURAN_HEWAN',
                    'label' => 'NAMA_UKURAN_HEWAN',
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
                    'field' => 'NAMA_UKURAN_HEWAN',
                    'label' => 'NAMA_UKURAN_HEWAN',
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

        $ukuranhewan = new ukuranhewanData();
        $ukuranhewan->nama_ukuran_hewan = $this->post('NAMA_UKURAN_HEWAN');
        $ukuranhewan->status_data = $this->post('STATUS_DATA');
        $ukuranhewan->keterangan = $this->post('KETERANGAN');

        if($id_ukuran_hewan == null){
            $response = $this->UkuranHewanModel->store($ukuranhewan);
        }
        else{
            $response = $this->UkuranHewanModel->update($ukuranhewan,$id_ukuran_hewan);
        }
        return $this->returnData($response['msg'], $response['error']);
    }
            
    public function delete_post($id_ukuran_hewan = null){
        $ukuranhewan = new ukuranhewanData();
        $ukuranhewan->status_data = $this->post('STATUS_DATA');
        $ukuranhewan->keterangan = $this->post('KETERANGAN');

        if($id_ukuran_hewan == null){
            return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        else{
            $response = $this->UkuranHewanModel->data_hapus($ukuranhewan, $id_ukuran_hewan);
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
Class ukuranhewanData{
    public $nama_ukuran_hewan;
    public $time_stamp;
    public $status_data;
    public $keterangan;
}