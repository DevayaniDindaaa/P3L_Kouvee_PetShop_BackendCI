<?php

use Restserver \Libraries\REST_Controller;

Class hewan extends REST_Controller {
    
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, ContentLength, Accept-Encoding");
        parent::__construct();
        $this->load->model('HewanModel');
        $this->load->library('form_validation');
        $this->load->helper(array('form','url'));
    }
    
    public function index_get() {
        $response = $this->HewanModel->getAll();
        return $this->returnData($response['msg'], $response['error']);
    }
    
    public function index_post($id_hewan = null) {
        $validation = $this->form_validation;
        $rule = $this->HewanModel->rules();
        if($id_hewan == null){
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
                    'field' => 'ID_KONSUMEN',
                    'label' => 'ID_KONSUMEN',
                    'rules' => 'required'
                ],
                [
                    'field' => 'NAMA_HEWAN',
                    'label' => 'NAMA_HEWAN',
                    'rules' => 'required'
                ],
                [
                    'field' => 'TGL_LAHIR_HEWAN',
                    'label' => 'TGL_LAHIR_HEWAN',
                    'rules' => array('required', 'regex_match[/^(\d{4})-(\d{1,2})-(\d{1,2})$/]')
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
                    'field' => 'ID_KONSUMEN',
                    'label' => 'ID_KONSUMEN',
                    'rules' => 'required'
                ],
                [
                    'field' => 'NAMA_HEWAN',
                    'label' => 'NAMA_HEWAN',
                    'rules' => 'required'
                ],
                [
                    'field' => 'TGL_LAHIR_HEWAN',
                    'label' => 'TGL_LAHIR_HEWAN',
                    'rules' => array('required', 'regex_match[/^(\d{4})-(\d{1,2})-(\d{1,2})$/]')
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

        $hewan = new hewanData();
        $hewan->id_ukuran_hewan = $this->post('ID_UKURAN_HEWAN');
        $hewan->id_jenis_hewan = $this->post('ID_JENIS_HEWAN');
        $hewan->id_konsumen = $this->post('ID_KONSUMEN');
        $hewan->nama_hewan = $this->post('NAMA_HEWAN');
        $hewan->tgl_lahir_hewan = $this->post('TGL_LAHIR_HEWAN');
        $hewan->keterangan = $this->post('KETERANGAN');

        if($id_hewan == null){
            $response = $this->HewanModel->store($hewan);
        }
        else{
            $response = $this->HewanModel->update($hewan, $id_hewan);
        }
        return $this->returnData($response['msg'], $response['error']);
    }
            
    public function delete_post($id_hewan = null){
        $hewan = new hewanData();
        $hewan->status_data = $this->post('STATUS_DATA');
        $hewan->keterangan = $this->post('KETERANGAN');

        if($id_hewan == null){
            return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        else{
            $response = $this->HewanModel->data_hapus($hewan, $id_hewan);
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
Class hewanData{
    public $id_ukuran_hewan;
    public $id_jenis_hewan;
    public $id_konsumen;
    public $nama_hewan;
    public $tgl_lahir_hewan;
    public $time_stamp;
    public $status_data;
    public $keterangan;
}