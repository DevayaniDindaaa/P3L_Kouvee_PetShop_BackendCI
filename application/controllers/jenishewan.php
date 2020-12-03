<?php

use Restserver \Libraries\REST_Controller;

Class jenishewan extends REST_Controller {
    
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, ContentLength, Accept-Encoding");
        parent::__construct();
        $this->load->model('JenisHewanModel');
        $this->load->library('form_validation');
        $this->load->helper(array('form','url'));
    }
    
    public function index_get() {
        $response = $this->JenisHewanModel->getAll();
        return $this->returnData($response['msg'], $response['error']);
    }
    
    public function index_post($id_jenis_hewan = null) {
        $validation = $this->form_validation;
        $rule = $this->JenisHewanModel->rules();
        if($id_jenis_hewan == null){
            array_push($rule,
                [
                    'field' => 'NAMA_JENIS_HEWAN',
                    'label' => 'NAMA_JENIS_HEWAN',
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
                    'field' => 'NAMA_JENIS_HEWAN',
                    'label' => 'NAMA_JENIS_HEWAN',
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

        $jenishewan = new jenishewanData();
        $jenishewan->nama_jenis_hewan = $this->post('NAMA_JENIS_HEWAN');
        $jenishewan->status_data = $this->post('STATUS_DATA');
        $jenishewan->keterangan = $this->post('KETERANGAN');

        if($id_jenis_hewan == null){
            $response = $this->JenisHewanModel->store($jenishewan);
        }
        else{
            $response = $this->JenisHewanModel->update($jenishewan,$id_jenis_hewan);
        }
        return $this->returnData($response['msg'], $response['error']);
    }
            
    public function delete_post($id_jenis_hewan = null){
        $jenishewan = new jenishewanData();
        $jenishewan->status_data = $this->post('STATUS_DATA');
        $jenishewan->keterangan = $this->post('KETERANGAN');

        if($id_jenis_hewan == null){
            return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        else{
            $response = $this->JenisHewanModel->data_hapus($jenishewan, $id_jenis_hewan);
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
Class jenishewanData{
    public $nama_jenis_hewan;
    public $time_stamp;
    public $status_data;
    public $keterangan;
}