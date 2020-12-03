<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class TambahProdukModel extends CI_Model {

    private $table1 = 'pengadaan_produk';
    private $table2 = 'produk';
    public $nomor_pemesanan;
    public $status_cetak_surat;
    public $status_kedatangan;

    public function update($request) {
        $updateData = [
            'STATUS_KEDATANGAN_PRODUK' => 'sudah datang'
        ];
        
        if($this->db->where('NOMOR_PEMESANAN', $request->nomor_pemesanan)->update($this->table1, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function addstok($post)
    {
        $this->db->select('STOK_PRODUK');
        $this->db->from('produk');
        $this->db->where('ID_PRODUK' , $post->id_produk);
        $data = $this->db->get()->result();

        $stok_saat_ini = $data[0]->STOK_PRODUK;

        $tambah = $post->jumlah_produk_dipesan;
        $updateProduk = [
            'STOK_PRODUK' => $tambah + $stok_saat_ini
        ];
        
        if($this->db->where('ID_PRODUK', $post->id_produk)->update($this->table2, $updateProduk)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
}
?>