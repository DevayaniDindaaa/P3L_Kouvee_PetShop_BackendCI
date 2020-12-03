<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class KonsumenModel extends CI_Model {
    private $table = 'konsumen';
    public $nama_konsumen;
    public $alamat_konsumen;
    public $tgl_lahir_konsumen;
    public $no_tlp_konsumen;
    public $status_member;
    public $time_stamp;
    public $status_data;
    public $keterangan;
    public $rule = [
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
    ];

    public function Rules() { return $this->rule; }

    public function getAll() {
        $this->db->select('id_konsumen, nama_konsumen, alamat_konsumen, tgl_lahir_konsumen, no_tlp_konsumen, status_member, time_stamp, status_data, keterangan');
        $this->db->from('konsumen');
        $this->db->order_by('nama_konsumen', 'ASC');
        $query = $this->db->get()->result_array();
    
        // Loop through the detail pengadaan array
        foreach($query as $i=>$konsumen) {
            
            $this->db->select('hewan.id_hewan, ukuran_hewan.nama_ukuran_hewan, jenis_hewan.nama_jenis_hewan, konsumen.id_konsumen, konsumen.nama_konsumen, hewan.nama_hewan, hewan.tgl_lahir_hewan, hewan.time_stamp, hewan.status_data, hewan.keterangan, konsumen.nama_konsumen, konsumen.alamat_konsumen, konsumen.tgl_lahir_konsumen, konsumen.no_tlp_konsumen, konsumen.status_member');
            $this->db->from('hewan');
            $this->db->join('ukuran_hewan', 'hewan.id_ukuran_hewan = ukuran_hewan.id_ukuran_hewan');
            $this->db->join('jenis_hewan', 'hewan.id_jenis_hewan = jenis_hewan.id_jenis_hewan');
            $this->db->join('konsumen', 'hewan.id_konsumen = konsumen.id_konsumen');
            $this->db->order_by('hewan.nama_hewan', 'ASC');
            $this->db->where('hewan.id_konsumen', $konsumen['id_konsumen']);
            $details = $this->db->get()->result_array();
        
            // Add the images array to the array entry for this detail
            $query[$i]['detail'] = $details;
    
        }
        return ['msg' => $query, 'error' => false];
    }

    public function store($request) {
        $this->nama_konsumen = $request->nama_konsumen;
        $this->alamat_konsumen = $request->alamat_konsumen;
        $this->tgl_lahir_konsumen = $request->tgl_lahir_konsumen;
        $this->no_tlp_konsumen = $request->no_tlp_konsumen;
        $this->status_member = $request->status_member;
        $this->status_data = 'created';
        $this->keterangan = $request->keterangan;
        if($this->db->insert($this->table, $this)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function update($request, $id_konsumen) {
        $updateData = [
            'NAMA_KONSUMEN' => $request->nama_konsumen,
            'ALAMAT_KONSUMEN' => $request->alamat_konsumen,
            'TGL_LAHIR_KONSUMEN' => $request->tgl_lahir_konsumen,
            'NO_TLP_KONSUMEN' => $request->no_tlp_konsumen, 
            'STATUS_MEMBER' => $request->status_member,
            'STATUS_DATA' => 'edited', 
            'KETERANGAN' => $request->keterangan
        ];
        
        if($this->db->where('ID_KONSUMEN', $id_konsumen)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function data_hapus($request, $id_konsumen) {
        $deleteData = [        
            'STATUS_DATA' => 'deleted', 
            'KETERANGAN' => $request->keterangan
        ];
        
        if($this->db->where('ID_KONSUMEN', $id_konsumen)->update($this->table, $deleteData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
}
?>