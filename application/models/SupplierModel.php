<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class SupplierModel extends CI_Model {
    private $table = 'supplier';
    public $id_supplier;
    public $nama_supplier;
    public $alamat_supplier;
    public $kota_supplier;
    public $no_tlp_supplier;
    public $time_stamp;
    public $status_data;
    public $keterangan;
    public $rule = [
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
    ];

    public function Rules() { return $this->rule; }

    public function getAll() {
        $this->db->select('*');
        $this->db->from('supplier');
        $this->db->order_by('nama_supplier', 'ASC');
        return ['msg' => $this->db->get()->result(), 'error' => false];
    }

    public function store($request) {
        $this->nama_supplier = $request->nama_supplier;
        $this->alamat_supplier = $request->alamat_supplier;
        $this->kota_supplier = $request->kota_supplier;
        $this->no_tlp_supplier = $request->no_tlp_supplier;
        $this->status_data = 'created';
        $this->keterangan = $request->keterangan;
        if($this->db->insert($this->table, $this)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function update($request, $id_supplier) {
        $updateData = [
            'NAMA_SUPPLIER' => $request->nama_supplier,
            'ALAMAT_SUPPLIER' => $request->alamat_supplier,
            'KOTA_SUPPLIER' => $request->kota_supplier,
            'NO_TLP_SUPPLIER' => $request->no_tlp_supplier,
            'STATUS_DATA' => 'edited', 
            'KETERANGAN' => $request->keterangan
        ];
        
        if($this->db->where('ID_SUPPLIER', $id_supplier)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function data_hapus($request, $id_supplier) {
        $deleteData = [        
            'STATUS_DATA' => 'deleted', 
            'KETERANGAN' => $request->keterangan
        ];
        
        if($this->db->where('ID_SUPPLIER', $id_supplier)->update($this->table, $deleteData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
}
?>