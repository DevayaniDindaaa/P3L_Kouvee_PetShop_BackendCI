<?php

use Restserver \Libraries\REST_Controller;

Class supplier extends REST_Controller {
    
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, ContentLength, Accept-Encoding");
        parent::__construct();
        $this->load->model('SupplierModel');
        $this->load->library('form_validation');
        $this->load->helper(array('form','url'));
    }
    
    public function index_get() {
        $response = $this->SupplierModel->getAll();
        return $this->returnData($response['msg'], $response['error']);
    }
    
    public function index_post($id_supplier = null) {
        $validation = $this->form_validation;
        $rule = $this->SupplierModel->rules();
        if($id_supplier == null){
            array_push($rule,
                [
                    'field' => 'NAMA_SUPPLIER',
                    'label' => 'NAMA_SUPPLIER',
                    'rules' => 'required'
                ],
                [
                    'field' => 'ALAMAT_SUPPLIER',
                    'label' => 'ALAMAT_SUPPLIER',
                    'rules' => 'required'
                ],
                [
                    'field' => 'KOTA_SUPPLIER',
                    'label' => 'KOTA_SUPPLIER',
                    'rules' => 'required'
                ],
                [
                    'field' => 'NO_TLP_SUPPLIER',
                    'label' => 'NO_TLP_SUPPLIER',
                    'rules' => array('required', 'regex_match[/^\+?([ -]?\d+)+|\(\d+\)([ -]\d+)$/]')
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
                    'field' => 'NAMA_SUPPLIER',
                    'label' => 'NAMA_SUPPLIER',
                    'rules' => 'required'
                ],
                [
                    'field' => 'ALAMAT_SUPPLIER',
                    'label' => 'ALAMAT_SUPPLIER',
                    'rules' => 'required'
                ],
                [
                    'field' => 'KOTA_SUPPLIER',
                    'label' => 'KOTA_SUPPLIER',
                    'rules' => 'required'
                ],
                [
                    'field' => 'NO_TLP_SUPPLIER',
                    'label' => 'NO_TLP_SUPPLIER',
                    'rules' => array('required', 'regex_match[/^\+?([ -]?\d+)+|\(\d+\)([ -]\d+)$/]')
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

        $supplier = new supplierData();
        $supplier->nama_supplier = $this->post('NAMA_SUPPLIER');
        $supplier->alamat_supplier = $this->post('ALAMAT_SUPPLIER');
        $supplier->kota_supplier = $this->post('KOTA_SUPPLIER');
        $supplier->no_tlp_supplier = $this->post('NO_TLP_SUPPLIER');
        $supplier->status_data = $this->post('STATUS_DATA');
        $supplier->keterangan = $this->post('KETERANGAN');

        if($id_supplier == null){
            $response = $this->SupplierModel->store($supplier);
        }
        else{
            $response = $this->SupplierModel->update($supplier,$id_supplier);
        }
        return $this->returnData($response['msg'], $response['error']);
    }
            
    public function delete_post($id_supplier = null){
        $supplier = new supplierData();
        $supplier->status_data = $this->post('STATUS_DATA');
        $supplier->keterangan = $this->post('KETERANGAN');

        if($id_supplier == null){
            return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        else{
            $response = $this->SupplierModel->data_hapus($supplier, $id_supplier);
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
Class supplierData{
    public $nama_supplier;
    public $alamat_supplier;
    public $kota_supplier;
    public $no_tlp_supplier;
    public $time_stamp;
    public $status_data;
    public $keterangan;
}