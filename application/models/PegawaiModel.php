<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class PegawaiModel extends CI_Model {
    private $table = 'pegawai';
    public $id_pegawai;
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
    public $rule = [
        [
            'field' => 'USERNAME',
            'label' => 'USERNAME',
            'rules' => 'required'
        ],
    ];

    public function Rules() { return $this->rule; }

    public function getAll() {
        $this->db->select('*');
        $this->db->from('pegawai');
        $this->db->where('STATUS_DATA', 'created');
        $this->db->or_where('STATUS_DATA', 'edited');
        return ['msg' => $this->db->get()->result(), 'error' => false];
    }

    public function store($request) {
        $this->id_role = $request->id_role;
        $this->nama_pegawai = $request->nama_pegawai;
        $this->alamat_pegawai = $request->alamat_pegawai;
        $this->tgl_lahir_pegawai = $request->tgl_lahir_pegawai;
        $this->no_tlp_pegawai = $request->no_tlp_pegawai;
        $this->username = $request->username;
        $this->password =  password_hash($request->password, PASSWORD_DEFAULT);
        $this->status_data = 'created';
        $this->keterangan = $request->keterangan;
        if($this->db->insert($this->table, $this)){
            return ['msg'=>'Berhasil Tambah Data','error'=>false];
        }
        return ['msg'=>'Gagal Tambah Data','error'=>true];
    }

    public function update($request, $id_pegawai) {
        $updateData = [
            'ID_ROLE' => $request->id_role, 
            'NAMA_PEGAWAI' => $request->nama_pegawai, 
            'ALAMAT_PEGAWAI' => $request->alamat_pegawai, 
            'TGL_LAHIR_PEGAWAI' => $request->tgl_lahir_pegawai, 
            'NO_TLP_PEGAWAI' => $request->no_tlp_pegawai, 
            'USERNAME' => $request->username, 
            'PASSWORD' => password_hash($request->password, PASSWORD_DEFAULT),
            'STATUS_DATA' => 'edited', 
            'KETERANGAN' => $request->keterangan
        ];
        
        if($this->db->where('ID_PEGAWAI', $id_pegawai)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil Ubah Data','error'=>false];
        }
        return ['msg'=>'Gagal Ubah Data','error'=>true];
    }

    public function data_hapus($request, $id_pegawai) {
        $deleteData = [        
            'STATUS_DATA' => 'deleted', 
            'KETERANGAN' => $request->keterangan
        ];
        
        if($this->db->where('ID_PEGAWAI', $id_pegawai)->update($this->table, $deleteData)){
            return ['msg'=>'Berhasil Hapus Data','error'=>false];
        }
        return ['msg'=>'Gagal Hapus Data','error'=>true];
    }

    public function verify($request){
        $this->db->where('USERNAME', $request->username);
        $this->db->where('STATUS_DATA !=', 'deleted');
        $pegawai = $this->db->get($this->table)->result_array();
        $pegawai = $pegawai[0];
        
        if(!empty($pegawai) && password_verify($request->password , $pegawai['PASSWORD'])) {
            return $pegawai;
        } else {
            return false;
        }
    }
}
?>