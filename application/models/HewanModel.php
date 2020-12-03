<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class HewanModel extends CI_Model {
    private $table = 'hewan';
    public $id_ukuran_hewan;
    public $id_jenis_hewan;
    public $id_konsumen;
    public $nama_hewan;
    public $tgl_lahir_hewan;
    public $time_stamp;
    public $status_data;
    public $keterangan;
    public $rule = [
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
    ];

    public function Rules() { return $this->rule; }

    public function getAll() {
        $this->db->select('hewan.id_hewan, ukuran_hewan.nama_ukuran_hewan, jenis_hewan.nama_jenis_hewan, konsumen.id_konsumen, konsumen.nama_konsumen, konsumen.alamat_konsumen, konsumen.tgl_lahir_konsumen, konsumen.no_tlp_konsumen, konsumen.status_member, 
                            hewan.nama_hewan, hewan.tgl_lahir_hewan, hewan.time_stamp, hewan.status_data, hewan.keterangan');
        $this->db->from('hewan');
        $this->db->join('ukuran_hewan', 'hewan.id_ukuran_hewan = ukuran_hewan.id_ukuran_hewan');
        $this->db->join('jenis_hewan', 'hewan.id_jenis_hewan = jenis_hewan.id_jenis_hewan');
        $this->db->join('konsumen', 'hewan.id_konsumen = konsumen.id_konsumen');
        $this->db->order_by('hewan.nama_hewan', 'ASC');
        return ['msg' => $this->db->get()->result(), 'error' => false];
    }

    public function store($request) {
        $this->id_ukuran_hewan = $request->id_ukuran_hewan;
        $this->id_jenis_hewan = $request->id_jenis_hewan;
        $this->id_konsumen = $request->id_konsumen;
        $this->nama_hewan = $request->nama_hewan;
        $this->tgl_lahir_hewan = $request->tgl_lahir_hewan;
        $this->status_data = 'created';
        $this->keterangan = $request->keterangan;
        if($this->db->insert($this->table, $this)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function update($request, $id_hewan) {
        $updateData = [
            'ID_UKURAN_HEWAN' => $request->id_ukuran_hewan,
            'ID_JENIS_HEWAN' => $request->id_jenis_hewan,
            'ID_KONSUMEN' => $request->id_konsumen,
            'NAMA_HEWAN' => $request->nama_hewan, 
            'TGL_LAHIR_HEWAN' => $request->tgl_lahir_hewan,
            'STATUS_DATA' => 'edited', 
            'KETERANGAN' => $request->keterangan
        ];
        
        if($this->db->where('ID_HEWAN', $id_hewan)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function data_hapus($request, $id_hewan) {
        $deleteData = [        
            'STATUS_DATA' => 'deleted', 
            'KETERANGAN' => $request->keterangan
        ];
        
        if($this->db->where('ID_HEWAN', $id_hewan)->update($this->table, $deleteData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
}
?>