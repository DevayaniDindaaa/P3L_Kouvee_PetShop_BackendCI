<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class UkuranHewanModel extends CI_Model {
    private $table = 'ukuran_hewan';
    public $id_ukuran_hewan;
    public $nama_ukuran_hewan;
    public $time_stamp;
    public $status_data;
    public $keterangan;
    public $rule = [
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
    ];

    public function Rules() { return $this->rule; }

    public function getAll() {
        $this->db->select('*');
        $this->db->from('ukuran_hewan');
        $this->db->order_by('nama_ukuran_hewan', 'ASC');
        return ['msg' => $this->db->get()->result(), 'error' => false];
    }

    public function store($request) {
        $this->nama_ukuran_hewan = $request->nama_ukuran_hewan;
        $this->status_data = 'created';
        $this->keterangan = $request->keterangan;
        if($this->db->insert($this->table, $this)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function update($request, $id_ukuran_hewan) {
        $updateData = [
            'NAMA_UKURAN_HEWAN' => $request->nama_ukuran_hewan, 
            'STATUS_DATA' => 'edited', 
            'KETERANGAN' => $request->keterangan
        ];
        
        if($this->db->where('ID_UKURAN_HEWAN', $id_ukuran_hewan)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function data_hapus($request, $id_ukuran_hewan) {
        $deleteData = [        
            'STATUS_DATA' => 'deleted', 
            'KETERANGAN' => $request->keterangan
        ];
        
        if($this->db->where('ID_UKURAN_HEWAN', $id_ukuran_hewan)->update($this->table, $deleteData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
}
?>