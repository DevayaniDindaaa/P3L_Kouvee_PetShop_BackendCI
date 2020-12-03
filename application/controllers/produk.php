<?php

use Restserver \Libraries\REST_Controller;

Class produk extends REST_Controller {
    
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, ContentLength, Accept-Encoding");
        parent::__construct();
        $this->load->model('ProdukModel');
        $this->load->library('form_validation');
        $this->load->helper(array('form','url'));
    }
    
    public function index_get() {
        $response = $this->ProdukModel->getAll();
        return $this->returnData($response['msg'], $response['error']);
    }

    public function sortHarga_get() {
        $response = $this->ProdukModel->getSortHarga();
        return $this->returnData($response['msg'], $response['error']);
    }

    public function sortStok_get() {
        $response = $this->ProdukModel->getSortStok();
        return $this->returnData($response['msg'], $response['error']);
    }
    
    public function sortHarga2_get() {
        $response = $this->ProdukModel->getSortHarga2();
        return $this->returnData($response['msg'], $response['error']);
    }

    public function sortStok2_get() {
        $response = $this->ProdukModel->getSortStok2();
        return $this->returnData($response['msg'], $response['error']);
    }

    public function index_post($id_produk = null) {
        $validation = $this->form_validation;
        $rule = $this->ProdukModel->rules();
        if($id_produk == null){
            array_push($rule,
                [
                    'field' => 'NAMA_PRODUK',
                    'label' => 'NAMA_PRODUK',
                    'rules' => 'required'
                ],
                [
                    'field' => 'ID_JENIS_HEWAN',
                    'label' => 'ID_JENIS_HEWAN',
                    'rules' => 'required'
                ],
                [
                    'field' => 'STOK_PRODUK',
                    'label' => 'STOK_PRODUK',
                    'rules' => array('required', 'numeric')
                ],
                [
                    'field' => 'STOK_MINIMAL_PRODUK',
                    'label' => 'STOK_MINIMAL_PRODUK',
                    'rules' => array('required', 'numeric')
                ],
                [
                    'field' => 'HARGA_SATUAN_PRODUK',
                    'label' => 'HARGA_SATUAN_PRODUK',
                    'rules' => array('required', 'numeric')
                ],
                [
                    'field' => 'SATUAN_PRODUK',
                    'label' => 'SATUAN_PRODUK',
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
                    'field' => 'NAMA_PRODUK',
                    'label' => 'NAMA_PRODUK',
                    'rules' => 'required'
                ],
                [
                    'field' => 'ID_JENIS_HEWAN',
                    'label' => 'ID_JENIS_HEWAN',
                    'rules' => 'required'
                ],
                [
                    'field' => 'STOK_PRODUK',
                    'label' => 'STOK_PRODUK',
                    'rules' => array('required', 'numeric')
                ],
                [
                    'field' => 'STOK_MINIMAL_PRODUK',
                    'label' => 'STOK_MINIMAL_PRODUK',
                    'rules' => array('required', 'numeric')
                ],
                [
                    'field' => 'HARGA_SATUAN_PRODUK',
                    'label' => 'HARGA_SATUAN_PRODUK',
                    'rules' => array('required', 'numeric')
                ],
                [
                    'field' => 'SATUAN_PRODUK',
                    'label' => 'SATUAN_PRODUK',
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

        $produk = new produkData();
        $produk->nama_produk = $this->post('NAMA_PRODUK');
        $produk->id_jenis_hewan = $this->post('ID_JENIS_HEWAN');
        $produk->stok_produk = $this->post('STOK_PRODUK');
        $produk->stok_minimal_produk = $this->post('STOK_MINIMAL_PRODUK');
        $produk->harga_satuan_produk = $this->post('HARGA_SATUAN_PRODUK');
        $produk->satuan_produk = $this->post('SATUAN_PRODUK');      
        $produk->status_data = $this->post('STATUS_DATA');
        $produk->keterangan = $this->post('KETERANGAN');
        
        if (isset($_FILES['FOTO_PRODUK']))
        $file = $_FILES['FOTO_PRODUK'];
            else
        $file = null;

        $upload = $this->uploadImage($file);
        if ($upload === null){
        } else
            $produk->foto_produk = $upload;

        if($id_produk == null){
            $response = $this->ProdukModel->store($produk);
        }
        else{
            $response = $this->ProdukModel->update($produk,$id_produk);
        }
        return $this->returnData($response['msg'], $response['error']);
    }

    public function delete_post($id_produk = null){
        $produk = new produkData();
        $produk->status_data = $this->post('STATUS_DATA');
        $produk->keterangan = $this->post('KETERANGAN');

        if($id_produk == null){
            return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        else{
            $response = $this->ProdukModel->data_hapus($produk, $id_produk);
        }
        return $this->returnData($response['msg'], $response['error']);
    }
    
    public function returnData($msg,$error){
        $response['error'] = $error;
        $response['message'] = $msg;
        return $this->response($response);
    }

    public function Rules() { return $this->rule; }

    public function uploadImage($file)
	{
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $imageName = date("Y").date("m").date("d").date("H").date("i").date("s").rand() . '.' . $ext;
        $directory = 'upload/produk' . $imageName;

        if(move_uploaded_file($file["tmp_name"], $directory))
            return $directory;

        return null;
    }

}
Class produkData{
    public $nama_produk;
    public $id_jenis_hewan;
    public $stok_produk;
    public $stok_minimal_produk;
    public $harga_satuan_produk;
    public $satuan_produk;
    public $foto_produk;
    public $time_stamp;
    public $status_data;
    public $keterangan;
}