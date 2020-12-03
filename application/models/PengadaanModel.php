<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class PengadaanModel extends CI_Model {

    private $table1 = 'pengadaan_produk';
    private $table2 = 'detail_pengadaan_produk';
    public $nomor_pemesanan;
    public $id_supplier;
    public $tgl_pemesanan;
    public $status_cetak_surat;

    public function getAll(){
        $this->db->select('pengadaan_produk.nomor_pemesanan, supplier.id_supplier, supplier.nama_supplier, supplier.alamat_supplier, supplier.kota_supplier, supplier.no_tlp_supplier,
                            pengadaan_produk.tgl_pemesanan, pengadaan_produk.tgl_cetak_surat_pemesanan, pengadaan_produk.status_cetak_surat, pengadaan_produk.status_kedatangan_produk');
        $this->db->from('pengadaan_produk');
        $this->db->join('supplier', 'pengadaan_produk.id_supplier = supplier.id_supplier');
        $this->db->order_by('pengadaan_produk.nomor_pemesanan', 'ASC');
        $query = $this->db->get()->result_array();
    
        // Loop through the detail pengadaan array
        foreach($query as $i=>$pengadaan) {
            
            $this->db->select('detail_pengadaan_produk.id_detail_pengadaan, detail_pengadaan_produk.id_produk, produk.nama_produk, produk.satuan_produk, detail_pengadaan_produk.jumlah_produk_dipesan,
                                detail_pengadaan_produk.status_data, detail_pengadaan_produk.time_stamp, detail_pengadaan_produk.keterangan');
            $this->db->from('detail_pengadaan_produk');
            $this->db->join('produk', 'detail_pengadaan_produk.id_produk = produk.id_produk');
            $this->db->order_by('produk.nama_produk', 'ASC');
            $this->db->where('nomor_pemesanan', $pengadaan['nomor_pemesanan']);
            $details = $this->db->get()->result_array();
        
            // Add the images array to the array entry for this detail
            $query[$i]['detail'] = $details;
    
        }
        return ['msg' => $query, 'error' => false];
    }

    public function store($request) {
        $nomor_pemesanan = $this->getNomorPemesanan();
        $this->nomor_pemesanan = $nomor_pemesanan;
        $this->id_supplier = $request->id_supplier;
        $this->tgl_pemesanan = date('Y-m-d H:i:s');;
        $this->status_cetak_surat = 'belum dicetak';
    
        if($this->db->insert($this->table1, $this)){
            return ['msg'=>$nomor_pemesanan,'error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function tambahDetail($details) {
        $params->nomor_pemesanan = $details->nomor_pemesanan;
        $params->status_data = 'created';
        $params->keterangan = 'owner';
        $params->id_produk = $details->id_produk;
        $params->jumlah_produk_dipesan = $details->jumlah_produk_dipesan;
    
        if($this->db->insert($this->table2, $params)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function getNomorPemesanan() {
        $temp = "PO-" . date("Y") . "-" . date("m") . "-" . date("d") . "-";

        $this->db->select('NOMOR_PEMESANAN');
        $this->db->from($this->table1);
        $this->db->like('NOMOR_PEMESANAN',$temp);
        $data = $this->db->get()->result();

        if($data == null)
        {
            return $temp . "0" . "1";
        }
        else if(count($data) >= 9){
            $i = count($data) + 1;
            return $temp . $i;
        }
        else
        {
            $i = count($data) + 1;
            return $temp . "0" . $i;
        }

    }

    public function updatePengadaan($parameter, $no_pesan)
    {
        $updateData = [
            'ID_SUPPLIER' => $parameter->id_supplier
        ];
        
        if($this->db->where('NOMOR_PEMESANAN', $no_pesan)->update($this->table1, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function updateDetail($detailku, $id_detail)
    {
        $updateData = [
            'JUMLAH_PRODUK_DIPESAN' => $detailku->jumlah_produk_dipesan,
            'STATUS_DATA' => 'edited',
            'KETERANGAN' => 'owner'
        ];
        
        if($this->db->where('ID_DETAIL_PENGADAAN', $id_detail)->update($this->table2, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function destroy($id_detail_pengadaan){
        if (empty($this->db->select('*')->where(array('id_detail_pengadaan' => $id_detail_pengadaan->id_detail_pengadaan))->get($this->table2)->row()))
            return ['msg'=>'ID Tidak Ditemukan','error'=>true];

        if($this->db->delete($this->table2, array('id_detail_pengadaan' => $id_detail_pengadaan->id_detail_pengadaan))){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

}
?>