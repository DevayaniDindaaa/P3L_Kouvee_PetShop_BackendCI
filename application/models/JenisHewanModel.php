<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class JenisHewanModel extends CI_Model {
    private $table = 'jenis_hewan';
    public $id_jenis_hewan;
    public $nama_jenis_hewan;
    public $time_stamp;
    public $status_data;
    public $keterangan;
    public $rule = [
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
    ];

    public function Rules() { return $this->rule; }

    public function getAll() {
        $this->db->select('*');
        $this->db->from('jenis_hewan');
        $this->db->order_by('nama_jenis_hewan', 'ASC');
        return ['msg' => $this->db->get()->result(), 'error' => false];
    }

    public function store($request) {
        $this->nama_jenis_hewan = $request->nama_jenis_hewan;
        $this->status_data = 'created';
        $this->keterangan = $request->keterangan;
        if($this->db->insert($this->table, $this)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function update($request, $id_jenis_hewan) {
        $updateData = [
            'NAMA_JENIS_HEWAN' => $request->nama_jenis_hewan, 
            'STATUS_DATA' => 'edited', 
            'KETERANGAN' => $request->keterangan
        ];
        
        if($this->db->where('ID_JENIS_HEWAN', $id_jenis_hewan)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function data_hapus($request, $id_jenis_hewan) {
        $deleteData = [        
            'STATUS_DATA' => 'deleted', 
            'KETERANGAN' => $request->keterangan
        ];
        
        if($this->db->where('ID_JENIS_HEWAN', $id_jenis_hewan)->update($this->table, $deleteData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
}
?>