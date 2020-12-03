<?php

use Restserver \Libraries\REST_Controller;

Class konsumen extends REST_Controller {
    
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, ContentLength, Accept-Encoding");
        parent::__construct();
        $this->load->model('KonsumenModel');
        $this->load->library('form_validation');
        $this->load->helper(array('form','url'));
    }
    
    public function index_get() {
        $response = $this->KonsumenModel->getAll();
        return $this->returnData($response['msg'], $response['error']);
    }
    
    public function index_post($id_konsumen = null) {
        $validation = $this->form_validation;
        $rule = $this->KonsumenModel->rules();
        if($id_konsumen == null){
            array_push($rule,
                [
                    'field' => 'NAMA_KONSUMEN',
                    'label' => 'NAMA_KONSUMEN',
                    'rules' => 'required'
                ],
                [
                    'field' => 'ALAMAT_KONSUMEN',
                    'label' => 'ALAMAT_KONSUMEN',
                    'rules' => 'required'
                ],
                [
                    'field' => 'TGL_LAHIR_KONSUMEN',
                    'label' => 'TGL_LAHIR_KONSUMEN',
                    'rules' => array('required', 'regex_match[/^(\d{4})-(\d{1,2})-(\d{1,2})$/]')
                ],
                [
                    'field' => 'NO_TLP_KONSUMEN',
                    'label' => 'NO_TLP_KONSUMEN',
                    'rules' => array('required', 'regex_match[/^\+?([ -]?\d+)+|\(\d+\)([ -]\d+)$/]')
                ],
                [
                    'field' => 'STATUS_MEMBER',
                    'label' => 'STATUS_MEMBER',
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
                    'field' => 'NAMA_KONSUMEN',
                    'label' => 'NAMA_KONSUMEN',
                    'rules' => 'required'
                ],
                [
                    'field' => 'ALAMAT_KONSUMEN',
                    'label' => 'ALAMAT_KONSUMEN',
                    'rules' => 'required'
                ],
                [
                    'field' => 'TGL_LAHIR_KONSUMEN',
                    'label' => 'TGL_LAHIR_KONSUMEN',
                    'rules' => array('required', 'regex_match[/^(\d{4})-(\d{1,2})-(\d{1,2})$/]')
                ],
                [
                    'field' => 'NO_TLP_KONSUMEN',
                    'label' => 'NO_TLP_KONSUMEN',
                    'rules' => array('required', 'regex_match[/^\+?([ -]?\d+)+|\(\d+\)([ -]\d+)$/]')
                ],
                [
                    'field' => 'STATUS_MEMBER',
                    'label' => 'STATUS_MEMBER',
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

        $konsumen = new konsumenData();
        $konsumen->nama_konsumen = $this->post('NAMA_KONSUMEN');
        $konsumen->alamat_konsumen = $this->post('ALAMAT_KONSUMEN');
        $konsumen->tgl_lahir_konsumen = $this->post('TGL_LAHIR_KONSUMEN');
        $konsumen->no_tlp_konsumen = $this->post('NO_TLP_KONSUMEN');
        $konsumen->status_member = $this->post('STATUS_MEMBER');
        $konsumen->keterangan = $this->post('KETERANGAN');

        if($id_konsumen == null){
            $response = $this->KonsumenModel->store($konsumen);
        }
        else{
            $response = $this->KonsumenModel->update($konsumen, $id_konsumen);
        }
        return $this->returnData($response['msg'], $response['error']);
    }
            
    public function delete_post($id_konsumen = null){
        $konsumen = new konsumenData();
        $konsumen->status_data = $this->post('STATUS_DATA');
        $konsumen->keterangan = $this->post('KETERANGAN');

        if($id_konsumen == null){
            return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        else{
            $response = $this->KonsumenModel->data_hapus($konsumen, $id_konsumen);
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
Class konsumenData{
    public $nama_konsumen;
    public $alamat_konsumen;
    public $tgl_lahir_konsumen;
    public $no_tlp_konsumen;
    public $status_member;
    public $time_stamp;
    public $status_data;
    public $keterangan;
}