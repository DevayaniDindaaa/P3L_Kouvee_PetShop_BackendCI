<?php

use Restserver \Libraries\REST_Controller;

Class pegawai extends REST_Controller {
    
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, ContentLength, Accept-Encoding");
        parent::__construct();
        $this->load->model('PegawaiModel');
        $this->load->library('form_validation');
        $this->load->helper(array('form','url'));
    }
    
    public function index_get() {
        $response = $this->PegawaiModel->getAll();
        return $this->returnData($response['msg'], $response['error']);
    }
    
    public function index_post($id_pegawai = null) {
        $validation = $this->form_validation;
        $rule = $this->PegawaiModel->rules();
        if($id_pegawai == null){
            array_push($rule,[
                    'field' => 'ID_ROLE',
                    'label' => 'ID_ROLE',
                    'rules' => 'required'
                ],
                [
                    'field' => 'NAMA_PEGAWAI',
                    'label' => 'NAMA_PEGAWAI',
                    'rules' => 'required'
                ],
                [
                    'field' => 'ALAMAT_PEGAWAI',
                    'label' => 'ALAMAT_PEGAWAI',
                    'rules' => 'required'
                ],
                [
                    'field' => 'TGL_LAHIR_PEGAWAI',
                    'label' => 'TGL_LAHIR_PEGAWAI',
                    'rules' => 'required'
                ],
                [
                    'field' => 'NO_TLP_PEGAWAI',
                    'label' => 'NO_TLP_PEGAWAI',
                    'rules' => 'required'
                ],
                [
                    'field' => 'USERNAME',
                    'label' => 'USERNAME',
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
                    'field' => 'NAMA_PEGAWAI',
                    'label' => 'NAMA_PEGAWAI',
                    'rules' => 'required'
                ]
            );
        }
    
        $validation->set_rules($rule);
        
        if (!$validation->run()) {
            return $this->returnData($this->form_validation->error_array(), true);
        }

        $pegawai = new pegawaiData();
        $pegawai->id_role = $this->post('ID_ROLE');
        $pegawai->nama_pegawai = $this->post('NAMA_PEGAWAI');
        $pegawai->alamat_pegawai = $this->post('ALAMAT_PEGAWAI');
        $pegawai->tgl_lahir_pegawai = $this->post('TGL_LAHIR_PEGAWAI');
        $pegawai->no_tlp_pegawai = $this->post('NO_TLP_PEGAWAI');
        $pegawai->username = $this->post('USERNAME');
        $pegawai->password = $this->post('PASSWORD');
        $pegawai->status_data = $this->post('STATUS_DATA');
        $pegawai->keterangan = $this->post('KETERANGAN');

        if($id_pegawai == null){
            $response = $this->PegawaiModel->store($pegawai);
        }
        else{
            $response = $this->PegawaiModel->update($pegawai,$id_pegawai);
        }
        return $this->returnData($response['msg'], $response['error']);
    }
            
    public function delete_post($id_pegawai = null){
        $pegawai = new pegawaiData();
        $pegawai->status_data = $this->post('STATUS_DATA');
        $pegawai->keterangan = $this->post('KETERANGAN');

        if($id_pegawai == null){
            return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        else{
            $response = $this->PegawaiModel->data_hapus($pegawai, $id_pegawai);
        }
        return $this->returnData($response['msg'], $response['error']);
    }
    
    public function returnData($msg,$error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }

    public function Rules() { return $this->rule; }

    public function login_post()
    {
        $validation = $this->form_validation;
        $rule = $this->PegawaiModel->rules();
        $validation->set_rules($rule);

        if (!$validation->run()) {
            return $this->returnData($this->form_validation->error_array(), true);
        }

        $pegawai = new pegawaiData();
        $pegawai->username = $this->post('USERNAME');
        $pegawai->password = $this->post('PASSWORD');
        
        $result = $this->PegawaiModel->verify($pegawai);
        if ($result != false) {
            if($result['ID_ROLE'] == 1)
            {
                $data = [
                    'ID_PEGAWAI' => $result['ID_PEGAWAI'],
                    'ID_ROLE' => $result['ID_ROLE'],
                    'NAMA_PEGAWAI' => $result['NAMA_PEGAWAI'],
                    'ALAMAT_PEGAWAI' => $result['ALAMAT_PEGAWAI'],
                    'TGL_LAHIR_PEGAWAI' => $result['TGL_LAHIR_PEGAWAI'],
                    'NO_TLP_PEGAWAI' => $result['NO_TLP_PEGAWAI'],
                    'USERNAME' => $result['USERNAME'],
                    'PASSWORD' => $result['PASSWORD'],
                    'TIME_STAMP' => $result['TIME_STAMP'],
                    'STATUS_DATA' => $result['STATUS_DATA'],
                    'KETERANGAN' => $result['KETERANGAN']
                ];
                $message = 'owner';
                $error = false;
                
                $response = ['message' => $message, 'data' => $data, 'error' => $error];
                return $this->response($response);
            }
            else if($result['ID_ROLE'] == 2)
            {
                $data = [
                    'ID_PEGAWAI' => $result['ID_PEGAWAI'],
                    'ID_ROLE' => $result['ID_ROLE'],
                    'NAMA_PEGAWAI' => $result['NAMA_PEGAWAI'],
                    'ALAMAT_PEGAWAI' => $result['ALAMAT_PEGAWAI'],
                    'TGL_LAHIR_PEGAWAI' => $result['TGL_LAHIR_PEGAWAI'],
                    'NO_TLP_PEGAWAI' => $result['NO_TLP_PEGAWAI'],
                    'USERNAME' => $result['USERNAME'],
                    'PASSWORD' => $result['PASSWORD'],
                    'TIME_STAMP' => $result['TIME_STAMP'],
                    'STATUS_DATA' => $result['STATUS_DATA'],
                    'KETERANGAN' => $result['KETERANGAN']
                ];
                $message = 'customer service';
                $error = false;
                
                $response = ['message' => $message, 'data' => $data, 'error' => $error];
                return $this->response($response);
            }
            else if($result['ID_ROLE'] == 3)
            {
                $data = [
                    'ID_PEGAWAI' => $result['ID_PEGAWAI'],
                    'ID_ROLE' => $result['ID_ROLE'],
                    'NAMA_PEGAWAI' => $result['NAMA_PEGAWAI'],
                    'ALAMAT_PEGAWAI' => $result['ALAMAT_PEGAWAI'],
                    'TGL_LAHIR_PEGAWAI' => $result['TGL_LAHIR_PEGAWAI'],
                    'NO_TLP_PEGAWAI' => $result['NO_TLP_PEGAWAI'],
                    'USERNAME' => $result['USERNAME'],
                    'PASSWORD' => $result['PASSWORD'],
                    'TIME_STAMP' => $result['TIME_STAMP'],
                    'STATUS_DATA' => $result['STATUS_DATA'],
                    'KETERANGAN' => $result['KETERANGAN']
                ];
                $message = 'kasir';
                $error = false;
                
                $response = ['message' => $message, 'data' => $data, 'error' => $error];
                return $this->response($response);
            }
        } else {
            $response = ['message' => 'kesalahan koneksi', 'error' => true];
            return $this->response($response);
        }
    }
}
Class pegawaiData{
    public $id_role;
    public $nama_pegawai;
    public $alamat_pegawai;
    public $tgl_lahir_pegawai;
    public $no_tlp_pegawai;
    public $username;
    public $password;
    public $time_stamp;
    public $status_data;
    public $keterangan;
}