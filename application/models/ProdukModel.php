<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ProdukModel extends CI_Model {
    private $table = 'produk';
    public $id_produk;
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
    public $rule = [
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
    ];

    public function Rules() { return $this->rule; }

    public function getAll() {
        $this->db->select('produk.id_produk, produk.nama_produk, jenis_hewan.nama_jenis_hewan, produk.stok_produk, produk.stok_minimal_produk,
                            produk.harga_satuan_produk, produk.satuan_produk, produk.foto_produk, produk.status_data, produk.time_stamp, produk.keterangan');
        $this->db->from('produk');
        $this->db->join('jenis_hewan', 'jenis_hewan.id_jenis_hewan = produk.id_jenis_hewan');
        $this->db->order_by('produk.nama_produk', 'ASC');
        return ['msg' => $this->db->get()->result(), 'error' => false];
    }

    public function getSortHarga() {
        $this->db->select('produk.id_produk, produk.nama_produk, jenis_hewan.nama_jenis_hewan, produk.stok_produk, produk.stok_minimal_produk,
                            produk.harga_satuan_produk, produk.satuan_produk, produk.foto_produk, produk.status_data, produk.time_stamp, produk.keterangan');
        $this->db->from('produk');
        $this->db->join('jenis_hewan', 'jenis_hewan.id_jenis_hewan = produk.id_jenis_hewan');
        $this->db->order_by('produk.harga_satuan_produk', 'ASC');
        return ['msg' => $this->db->get()->result(), 'error' => false];
    }

    public function getSortStok() {
        $this->db->select('produk.id_produk, produk.nama_produk, jenis_hewan.nama_jenis_hewan, produk.stok_produk, produk.stok_minimal_produk,
                            produk.harga_satuan_produk, produk.satuan_produk, produk.foto_produk, produk.status_data, produk.time_stamp, produk.keterangan');
        $this->db->from('produk');
        $this->db->join('jenis_hewan', 'jenis_hewan.id_jenis_hewan = produk.id_jenis_hewan');
        $this->db->order_by('produk.stok_produk', 'ASC');
        return ['msg' => $this->db->get()->result(), 'error' => false];
    }

    public function getSortHarga2() {
        $this->db->select('produk.id_produk, produk.nama_produk, jenis_hewan.nama_jenis_hewan, produk.stok_produk, produk.stok_minimal_produk,
                            produk.harga_satuan_produk, produk.satuan_produk, produk.foto_produk, produk.status_data, produk.time_stamp, produk.keterangan');
        $this->db->from('produk');
        $this->db->join('jenis_hewan', 'jenis_hewan.id_jenis_hewan = produk.id_jenis_hewan');
        $this->db->order_by('produk.harga_satuan_produk', 'DESC');
        return ['msg' => $this->db->get()->result(), 'error' => false];
    }

    public function getSortStok2() {
        $this->db->select('produk.id_produk, produk.nama_produk, jenis_hewan.nama_jenis_hewan, produk.stok_produk, produk.stok_minimal_produk,
                            produk.harga_satuan_produk, produk.satuan_produk, produk.foto_produk, produk.status_data, produk.time_stamp, produk.keterangan');
        $this->db->from('produk');
        $this->db->join('jenis_hewan', 'jenis_hewan.id_jenis_hewan = produk.id_jenis_hewan');
        $this->db->order_by('produk.stok_produk', 'DESC');
        return ['msg' => $this->db->get()->result(), 'error' => false];
    }

    public function store($request) {
        $this->nama_produk = $request->nama_produk;
        $this->id_jenis_hewan = $request->id_jenis_hewan;
        $this->stok_produk = $request->stok_produk;
        $this->stok_minimal_produk = $request->stok_minimal_produk;
        $this->harga_satuan_produk = $request->harga_satuan_produk;
        $this->satuan_produk = $request->satuan_produk;
        $this->foto_produk = $request->foto_produk;
        $this->status_data = 'created';
        $this->keterangan = $request->keterangan;
        if($this->db->insert($this->table, $this)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function update($request, $id_produk) {
        $updateData = [
            'NAMA_PRODUK' => $request->nama_produk,
            'ID_JENIS_HEWAN' => $request->id_jenis_hewan,
            'STOK_PRODUK' => $request->stok_produk,
            'STOK_MINIMAL_PRODUK' => $request->stok_minimal_produk,
            'HARGA_SATUAN_PRODUK' => $request->harga_satuan_produk,
            'SATUAN_PRODUK' => $request->satuan_produk,
            'STATUS_DATA' => 'edited', 
            'KETERANGAN' => $request->keterangan
        ];
        
        if($request->foto_produk != null)
            $updateData['FOTO_PRODUK'] = $request->foto_produk;
            
        if($this->db->where('ID_PRODUK', $id_produk)->update($this->table, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function data_hapus($request, $id_produk) {
        $deleteData = [        
            'STATUS_DATA' => 'deleted', 
            'KETERANGAN' => $request->keterangan
        ];
        
        if($this->db->where('ID_PRODUK', $id_produk)->update($this->table, $deleteData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
}
?>