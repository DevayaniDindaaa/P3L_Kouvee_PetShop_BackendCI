<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class LayananModel extends CI_Model {
    private $table = 'layanan';
    public $id_ukuran_hewan;
    public $id_jenis_hewan;
    public $nama_layanan;
    public $harga_satuan_layanan;
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
            'field' => 'NAMA_LAYANAN',
            'label' => 'NAMA_LAYANAN',
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
        $this->db->select('layanan.id_layanan, ukuran_hewan.nama_ukuran_hewan, jenis_hewan.nama_jenis_hewan,
                            layanan.nama_layanan, layanan.harga_satuan_layanan, layanan.time_stamp, layanan.status_data, layanan.keterangan');
        $this->db->from('layanan');
        $this->db->join('ukuran_hewan', 'layanan.id_ukuran_hewan = ukuran_hewan.id_ukuran_hewan');
        $this->db->join('jenis_hewan', 'layanan.id_jenis_hewan = jenis_hewan.id_jenis_hewan');
        $this->db->order_by('layanan.nama_layanan', 'ASC');
        return ['msg' => $this->db->get()->result(), 'error' => false];
    }

    public function getSortHarga() {
        $this->db->select('layanan.id_layanan, ukuran_hewan.nama_ukuran_hewan, jenis_hewan.nama_jenis_hewan,
                            layanan.nama_layanan, layanan.harga_satuan_layanan, layanan.time_stamp, layanan.status_data, layanan.keterangan');
        $this->db->from('layanan');
        $this->db->join('ukuran_hewan', 'layanan.id_ukuran_hewan = ukuran_hewan.id_ukuran_hewan');
        $this->db->join('jenis_hewan', 'layanan.id_jenis_hewan = jenis_hewan.id_jenis_hewan');
        $this->db->order_by('layanan.harga_satuan_layanan', 'ASC');
        return ['msg' => $this->db->get()->result(), 'error' => false];
    }

    public function getSortHarga2() {
        $this->db->select('layanan.id_layanan, ukuran_hewan.nama_ukuran_hewan, jenis_hewan.nama_jenis_hewan,
                            layanan.nama_layanan, layanan.harga_satuan_layanan, layanan.time_stamp, layanan.status_data, layanan.keterangan');
        $this->db->from('layanan');
        $this->db->join('ukuran_hewan', 'layanan.id_ukuran_hewan = ukuran_hewan.id_ukuran_hewan');
        $this->db->join('jenis_hewan', 'layanan.id_jenis_hewan = jenis_hewan.id_jenis_hewan');
        $this->db->order_by('layanan.harga_satuan_layanan', 'DESC');
        return ['msg' => $this->db->get()->result(), 'error' => false];
    }

    public function store($request) {
        $this->id_ukuran_hewan = $request->id_ukuran_hewan;
        $this->id_jenis_hewan = $request->id_jenis_hewan;
        $this->nama_layanan = $request->nama_layanan;
        $this->harga_satuan_layanan = $request->harga_satuan_layanan;
        $this->status_data = 'created';
        $this->keterangan = $request->keterangan;
        if($this->db->insert($this->table, $this)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function update($request, $id_layanan) {
        $updateData = [
            'ID_UKURAN_HEWAN' => $request->id_ukuran_hewan,
            'ID_JENIS_HEWAN' => $request->id_jenis_hewan,
            'NAMA_LAYANAN' => $request->nama_layanan, 
            'HARGA_SATUAN_LAYANAN' => $request->harga_satuan_layanan,
            'STATUS_DATA' => 'edited', 
            'KETERANGAN' => $request->keterangan
        ];
        
        if($this->db->where('ID_LAYANAN', $id_layanan)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function data_hapus($request, $id_layanan) {
        $deleteData = [        
            'STATUS_DATA' => 'deleted', 
            'KETERANGAN' => $request->keterangan
        ];
        
        if($this->db->where('ID_LAYANAN', $id_layanan)->update($this->table, $deleteData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
}
?>