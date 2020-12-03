<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class TransaksiLayananModel extends CI_Model {

    private $table1 = 'transaksi_layanan';
    private $table2 = 'detail_transaksi_layanan';
    private $table3 = 'layanan';
    public $no_transaksi_layanan;
    public $id_customer_service;
    public $waktu_transaksi_layanan;
    public $status_pembayaran_layanan;
    public $status_pengerjaan_layanan;
    public $diskon_layanan;

    public function getAll(){
        $this->db->select('transaksi_layanan.no_transaksi_layanan, hewan.nama_hewan,  jenis_hewan.nama_jenis_hewan, ukuran_hewan.nama_ukuran_hewan, konsumen.nama_konsumen, konsumen.status_member, konsumen.no_tlp_konsumen, pegawai1.username AS nama_cs, pegawai2.username AS nama_kasir, transaksi_layanan.waktu_transaksi_layanan, transaksi_layanan.status_pembayaran_layanan,
                            transaksi_layanan.status_pengerjaan_layanan, transaksi_layanan.sub_total_layanan, transaksi_layanan.diskon_layanan, transaksi_layanan.total_pembayaran_layanan');
        $this->db->from('transaksi_layanan');
        $this->db->join('hewan', 'transaksi_layanan.id_hewan = hewan.id_hewan');
        $this->db->join('ukuran_hewan', 'hewan.id_ukuran_hewan = ukuran_hewan.id_ukuran_hewan');
        $this->db->join('jenis_hewan', 'hewan.id_jenis_hewan = jenis_hewan.id_jenis_hewan');
        $this->db->join('konsumen', 'hewan.id_konsumen = konsumen.id_konsumen');
        $this->db->join('pegawai AS pegawai1', 'pegawai1.id_pegawai = transaksi_layanan.id_customer_service');
        $this->db->join('pegawai AS pegawai2', 'pegawai2.id_pegawai = transaksi_layanan.id_kasir');
        $this->db->order_by('transaksi_layanan.waktu_transaksi_layanan', 'ASC');
        $query = $this->db->get()->result_array();
    
        // Loop through the detail pengadaan array
        foreach($query as $i=>$transaksilayanan) {
            
            $this->db->select('detail_transaksi_layanan.id_detail_trans_layanan, detail_transaksi_layanan.id_layanan, layanan.nama_layanan, jenis_hewan.nama_jenis_hewan, ukuran_hewan.nama_ukuran_hewan,  layanan.harga_satuan_layanan, detail_transaksi_layanan.jumlah_layanan, detail_transaksi_layanan.jumlah_harga_layanan,
                                detail_transaksi_layanan.status_data, detail_transaksi_layanan.time_stamp, detail_transaksi_layanan.keterangan');
            $this->db->from('detail_transaksi_layanan');
            $this->db->join('layanan', 'detail_transaksi_layanan.id_layanan = layanan.id_layanan');
            $this->db->join('ukuran_hewan', 'layanan.id_ukuran_hewan = ukuran_hewan.id_ukuran_hewan');
            $this->db->join('jenis_hewan', 'layanan.id_jenis_hewan = jenis_hewan.id_jenis_hewan');
            $this->db->order_by('layanan.nama_layanan', 'ASC');
            $this->db->where('detail_transaksi_layanan.no_transaksi_layanan', $transaksilayanan['no_transaksi_layanan']);
            $details = $this->db->get()->result_array();
        
            // Add the images array to the array entry for this detail
            $query[$i]['detail'] = $details;
    
        }
        return ['msg' => $query, 'error' => false];
    }

    public function store($request){     
        date_default_timezone_set("Asia/Jakarta");
        $no_transaksi_layanan = $this->getNoTransaksi();
        $this->no_transaksi_layanan = $no_transaksi_layanan;
        $this->id_hewan = $request->id_hewan;
        $this->id_customer_service = $request->id_customer_service;
        $this->waktu_transaksi_layanan = date('Y-m-d H:i:s');
        $this->status_pembayaran_layanan = 'Belum Lunas';
        $this->status_pengerjaan_layanan = 'Belum Selesai';
        $this->diskon_layanan = 0;

        if($this->db->insert($this->table1, $this)){
            return ['msg'=>$no_transaksi_layanan,'error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function tambahDetail($details) {
        $params->no_transaksi_layanan = $details->no_transaksi_layanan;
        $params->id_layanan = $details->id_layanan;
        $params->jumlah_layanan = $details->jumlah_layanan;
        $params->jumlah_harga_layanan = $this->getSubTotalProduk($params->id_layanan, $params->jumlah_layanan);
        $params->status_data = 'created';
        $params->keterangan = $details->keterangan;
        
        if($this->db->insert($this->table2, $params)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function updateHarga($noTrans)
    {
        $this->db->select('diskon_layanan');
        $this->db->from($this->table1);
        $this->db->where('no_transaksi_layanan', $noTrans);
        $diskon = $this->db->get()->result();

        $diskonharga = $diskon[0]->diskon_layanan;

        $totalbayar = $this->getTotalBayar($noTrans);

        $updateData = [
            'SUB_TOTAL_LAYANAN' => $totalbayar, 
            'TOTAL_PEMBAYARAN_LAYANAN' => $totalbayar - $diskonharga
        ];
        
        if($this->db->where('NO_TRANSAKSI_LAYANAN', $noTrans)->update($this->table1, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function getNoTransaksi() {
		date_default_timezone_set("Asia/Jakarta");
		$temp = "LY-" . date("d") . "" . date("m") . "" . date("y") . "-";
		$this->db->select('NO_TRANSAKSI_LAYANAN');
		$this->db->from($this->table1);
		$this->db->like('NO_TRANSAKSI_LAYANAN',$temp);
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
        $this->db->select_sum('jumlah_harga_layanan');
        $this->db->from($this->table2);
        $this->db->where('no_transaksi_layanan', $noTrans);
        $totalbayar = $this->db->get()->result();

        $bayar = $totalbayar[0]->jumlah_harga_layanan;

        return $bayar;
    }

    public function getSubTotalProduk($id_layanan, $jumlah_layanan) {
        $this->db->select('harga_satuan_layanan');
        $this->db->from($this->table3);
        $this->db->where('id_layanan', $id_layanan);
        $harga = $this->db->get()->result();

        $hargalayanan = $harga[0]->harga_satuan_layanan;
        
        $jumlahharga = $jumlah_layanan * $hargalayanan;

        return $jumlahharga;
    }

    public function updateStatus($noTrans)
    {
        $updateData = [
            'STATUS_PENGERJAAN_LAYANAN' => 'Selesai'
        ];
        
        if($this->db->where('NO_TRANSAKSI_LAYANAN', $noTrans)->update($this->table1, $updateData)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }

    public function destroy($id_detail){
        if (empty($this->db->select('*')->where(array('id_detail_trans_layanan' => $id_detail->id_detail_trans_layanan))->get($this->table2)->row()))
            return ['msg'=>'ID Tidak Ditemukan','error'=>true];

        if($this->db->delete($this->table2, array('id_detail_trans_layanan' => $id_detail->id_detail_trans_layanan))){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
}
?>