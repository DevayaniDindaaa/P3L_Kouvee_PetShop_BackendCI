<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class TransaksiProdukModel extends CI_Model {

    private $table1 = 'transaksi_produk';
    private $table2 = 'detail_transaksi_produk';
    private $table3 = 'produk';
    public $no_transaksi_produk;
    public $id_customer_service;
    public $waktu_transaksi_produk;
    public $status_pembayaran_produk;
    public $diskon_produk;

    public function getAll(){
        $this->db->select('transaksi_produk.no_transaksi_produk, hewan.nama_hewan, jenis_hewan.nama_jenis_hewan, konsumen.nama_konsumen,  konsumen.status_member, pegawai1.username AS nama_cs, pegawai2.username AS nama_kasir,transaksi_produk.waktu_transaksi_produk, transaksi_produk.status_pembayaran_produk,
                            transaksi_produk.sub_total_produk, transaksi_produk.diskon_produk, transaksi_produk.total_pembayaran_produk');
        $this->db->from('transaksi_produk');
        $this->db->join('hewan', 'transaksi_produk.id_hewan = hewan.id_hewan');
        $this->db->join('konsumen', 'hewan.id_konsumen = konsumen.id_konsumen');
        $this->db->join('jenis_hewan', 'hewan.id_jenis_hewan = jenis_hewan.id_jenis_hewan');
        $this->db->join('pegawai AS pegawai1', 'pegawai1.id_pegawai = transaksi_produk.id_customer_service');
        $this->db->join('pegawai AS pegawai2', 'pegawai2.id_pegawai = transaksi_produk.id_kasir');
        $this->db->order_by('transaksi_produk.waktu_transaksi_produk', 'ASC');
        $query = $this->db->get()->result_array();
    

        // Loop through the detail pengadaan array
        foreach($query as $i=>$transaksiproduk) {
            
            $this->db->select('detail_transaksi_produk.id_detail_trans_produk, detail_transaksi_produk.id_produk, jenis_hewan.nama_jenis_hewan, produk.nama_produk, produk.satuan_produk, produk.harga_satuan_produk, detail_transaksi_produk.jumlah_produk, detail_transaksi_produk.jumlah_harga_produk,
                                detail_transaksi_produk.status_data, detail_transaksi_produk.time_stamp, detail_transaksi_produk.keterangan');
            $this->db->from('detail_transaksi_produk');
            $this->db->join('produk', 'detail_transaksi_produk.id_produk = produk.id_produk');
            $this->db->join('jenis_hewan', 'produk.id_jenis_hewan = jenis_hewan.id_jenis_hewan');
            $this->db->order_by('produk.nama_produk', 'ASC');
            $this->db->where('detail_transaksi_produk.no_transaksi_produk', $transaksiproduk['no_transaksi_produk']);
            $details = $this->db->get()->result_array();
        
            // Add the images array to the array entry for this detail
            $query[$i]['detail'] = $details;
    
        }
        return ['msg' => $query, 'error' => false];
    }

    public function store($request){     
        date_default_timezone_set("Asia/Jakarta");
        $no_transaksi_produk = $this->getNoTransaksi();
        $this->no_transaksi_produk = $no_transaksi_produk;
        $this->id_hewan = $request->id_hewan;
        $this->id_customer_service = $request->id_customer_service;
        $this->waktu_transaksi_produk = date('Y-m-d H:i:s');
        $this->status_pembayaran_produk = 'Belum Lunas';
        $this->diskon_produk = 0;

        if($this->db->insert($this->table1, $this)){
            return ['msg'=>$no_transaksi_produk,'error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function tambahDetail($details) {
        $params->no_transaksi_produk = $details->no_transaksi_produk;
        $params->id_produk = $details->id_produk;
        $params->jumlah_produk = $details->jumlah_produk;
        $params->jumlah_harga_produk = $this->getSubTotalProduk($params->id_produk, $params->jumlah_produk);
        $params->status_data = 'created';
        $params->keterangan = $details->keterangan;
        
        if($this->db->insert($this->table2, $params)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function updateHarga($noTrans)
    {
        $this->db->select('diskon_produk');
        $this->db->from($this->table1);
        $this->db->where('no_transaksi_produk', $noTrans);
        $diskon = $this->db->get()->result();

        $diskonharga = $diskon[0]->diskon_produk;

        $totalbayar = $this->getTotalBayar($noTrans);

        $updateData = [
            'SUB_TOTAL_PRODUK' => $totalbayar, 
            'TOTAL_PEMBAYARAN_PRODUK' => $totalbayar - $diskonharga
        ];
        
        if($this->db->where('NO_TRANSAKSI_PRODUK', $noTrans)->update($this->table1, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function getNoTransaksi() {
		date_default_timezone_set("Asia/Jakarta");
		$temp = "PR-" . date("d") . "" . date("m") . "" . date("y") . "-";
		$this->db->select('NO_TRANSAKSI_PRODUK');
		$this->db->from($this->table1);
		$this->db->like('NO_TRANSAKSI_PRODUK',$temp);
		$data = $this->db->get()->result();

		if($data == null)
		{
			return $temp . "01";
		}
		else
		{
			$i = count($data) + 1;
			if($i<10)
				return $temp . "0" . $i;
			else
				return $temp . $i;
		}
    }

    public function getTotalBayar($noTrans){
        $this->db->select_sum('jumlah_harga_produk');
        $this->db->from($this->table2);
        $this->db->where('no_transaksi_produk', $noTrans);
        $totalbayar = $this->db->get()->result();

        $bayar = $totalbayar[0]->jumlah_harga_produk;

        return $bayar;
    }

    public function getSubTotalProduk($id_produk, $jumlah_produk) {
        $this->db->select('harga_satuan_produk');
        $this->db->from($this->table3);
        $this->db->where('id_produk', $id_produk);
        $harga = $this->db->get()->result();

        $hargaproduk = $harga[0]->harga_satuan_produk;
        
        $jumlahharga = $jumlah_produk * $hargaproduk;

        return $jumlahharga;
    }

    public function updateDetail($detailku, $id_detail) {
        $updateData = [
            'JUMLAH_PRODUK' => $detailku->jumlah_produk,
            'JUMLAH_HARGA_PRODUK' => $this->getSubTotalProduk($detailku->id_produk, $detailku->jumlah_produk),
            'STATUS_DATA' => 'edited',
            'KETERANGAN' => $detailku->keterangan
        ];
        
        if($this->db->where('ID_DETAIL_TRANS_PRODUK', $id_detail)->update($this->table2, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function destroy($id_detail){
        if (empty($this->db->select('*')->where(array('id_detail_trans_produk' => $id_detail->id_detail_trans_produk))->get($this->table2)->row()))
            return ['msg'=>'ID Tidak Ditemukan','error'=>true];

        if($this->db->delete($this->table2, array('id_detail_trans_produk' => $id_detail->id_detail_trans_produk))){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

}
?>