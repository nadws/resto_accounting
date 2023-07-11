<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardKandangController extends Controller
{
    protected $tgl1, $tgl2, $period, $produk, $gudang;
    public function __construct(Request $r)
    {
        $this->produk = Produk::with('satuan')->where([['kontrol_stok', 'Y'], ['kategori_id', 3]])->get();
        $this->gudang = Gudang::where('kategori_id', 3)->get();
        if (empty($r->period)) {
            $this->tgl1 = date('Y-m-01');
            $this->tgl2 = date('Y-m-t');
        } elseif ($r->period == 'daily') {
            $this->tgl1 = date('Y-m-d');
            $this->tgl2 = date('Y-m-d');
        } elseif ($r->period == 'weekly') {
            $this->tgl1 = date('Y-m-d', strtotime("-6 days"));
            $this->tgl2 = date('Y-m-d');
        } elseif ($r->period == 'mounthly') {
            $bulan = $r->bulan;
            $tahun = $r->tahun;
            $tgl = "$tahun" . "-" . "$bulan" . "-" . "01";

            $this->tgl1 = date('Y-m-01', strtotime($tgl));
            $this->tgl2 = date('Y-m-t', strtotime($tgl));
        } elseif ($r->period == 'costume') {
            $this->tgl1 = $r->tgl1;
            $this->tgl2 = $r->tgl2;
        } elseif ($r->period == 'years') {
            $tahun = $r->tahunfilter;
            $tgl_awal = "$tahun" . "-" . "01" . "-" . "01";
            $tgl_akhir = "$tahun" . "-" . "12" . "-" . "01";

            $this->tgl1 = date('Y-m-01', strtotime($tgl_awal));
            $this->tgl2 = date('Y-m-t', strtotime($tgl_akhir));
        }
    }

    public function index()
    {
        $data = [
            'title' => 'Dashboard Kandang',
            'kandang' => DB::table('kandang')->get(),
            'telur' => DB::table('telur_produk')->get(),
            'produk' => $this->produk
        ];
        return view('dashboard_kandang.index', $data);
    }

    public function tambah_telur(Request $r)
    {
        DB::table('stok_telur')->where([['id_kandang', $r->id_kandang], ['tgl', $r->tgl]])->delete();
        DB::table('stok_telur_new')->where([['id_kandang', $r->id_kandang], ['tgl', $r->tgl]])->delete();

        for ($i = 0; $i < count($r->id_telur); $i++) {
            $ikat = $r->ikat[$i];
            $ikat_kg = $r->ikat_kg[$i];

            $rak = $r->rak[$i];
            $rak_kg = $r->rak_kg[$i];

            $pcs = $r->pcs[$i];
            $ttl_kg_pcs = $r->ttl_kg_pcs[$i];

            $ttlPcs = ($ikat * 180) + ($rak * 30) + $pcs;
            $ttlKg = $ikat_kg + $rak_kg + $ttl_kg_pcs;

            $data = [
                'id_kandang' => $r->id_kandang,
                'id_telur' => $r->id_telur[$i],
                'tgl' => $r->tgl,
                'admin' => auth()->user()->name,
                'ikat' => $ikat,
                'ikat_kg' => $ikat_kg,
                'rak' => $rak,
                'rak_kg' => $rak_kg,
                'pcs' => $pcs,
                'pcs_kg' => $r->pcs_kg[$i],
                'potongan_pcs' => $r->potongan_pcs[$i],
                'ttl_kg_pcs' => $ttl_kg_pcs,
            ];
            DB::table('stok_telur_new')->insert($data);

            $dataStok = [
                'id_kandang' => $r->id_kandang,
                'id_telur' => $r->id_telur[$i],
                'tgl' => $r->tgl,
                'pcs' => $ttlPcs,
                'kg' => $ttlKg,
                'pcs_kredit' => 0,
                'kg_kredit' => 0,
                'admin' => auth()->user()->name,
                'id_gudang' => 1,
                'nota_transfer' => '',
                'ket' => '',
            ];
            DB::table('stok_telur')->insert($dataStok);
        }

        return redirect()->route('dashboard_kandang.index')->with('sukses', 'Data Berhasil Ditambahkan');
    }

    public function load_telur($id_kandang)
    {
        $data = [
            'telur' => DB::table('telur_produk')->get(),
            'kandang' => DB::table('kandang')->where('id_kandang', $id_kandang)->first()
        ];
        return view('dashboard_kandang.modal.load_telur', $data);
    }

    public function tambah_populasi(Request $r)
    {
        DB::table('populasi')->where([['id_kandang', $r->id_kandang], ['tgl', $r->tgl]])->delete();
        DB::table('populasi')->insert([
            'id_kandang' => $r->id_kandang,
            'mati' => $r->mati,
            'jual' => $r->jual,
            'tgl' => $r->tgl,
            'admin' => auth()->user()->name
        ]);
        $pesan = $r->mati > 3 ? 'error' : 'sukses';
        return redirect()->route('dashboard_kandang.index')->with($pesan, 'Data Berhasil Ditambahkan');
    }

    public function load_populasi($id_kandang)
    {
        $data = [
            'populasi' => DB::table('populasi')->where([['id_kandang', $id_kandang], ['tgl', date('Y-m-d')]])->first(),
            'kandang' => DB::table('kandang')->where('id_kandang', $id_kandang)->first()
        ];
        return view('dashboard_kandang.modal.load_populasi', $data);
    }

    public function transfer_stok()
    {
        $tgl1 =  $this->tgl1;
        $tgl2 =  $this->tgl2;
        $transfer = DB::table('stok_telur as a')
            ->join('telur_produk as c', 'a.id_telur', 'c.id_produk_telur')
            ->where('a.id_gudang', 2)
            ->get();
        $data = [
            'title' => 'Transfer Stok',
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
            'transfer' => $transfer
        ];
        return view('dashboard_kandang.history.transfer_stok', $data);
    }

    public function add_transfer_stok(Request $r)
    {
        $data = [
            'title' => 'Transfer Stock',
            'id_gudang' => $r->id_gudang,
            'gudang' => DB::table('gudang_telur')->where('id_gudang_telur', $r->id_gudang)->first(),
            'gudang_telur' => DB::table('gudang_telur')->where('id_gudang_telur', '!=', $r->id_gudang)->get()
        ];
        return view('stok_telur.transfer', $data);
    }

    public function save_transfer(Request $r)
    {
        $cek = DB::table('stok_telur')->where('nota_transfer', '!=', '')->first();
        $nota_t = empty($cek) ? 1000 + 1 : str()->remove('TF-', $cek->nota_transfer) + 1;

        for ($x = 0; $x < count($r->id_telur); $x++) {
            $data = [
                'tgl' => $r->tgl,
                'id_telur' => $r->id_telur[$x],
                'pcs_kredit' => $r->pcs[$x],
                'kg_kredit' => $r->kg[$x],
                'admin' => auth()->user()->name,
                'nota_transfer' => 'TF-' . $nota_t,
                'id_gudang' => $r->id_gudang_dari,
                'jenis' => 'tf'
            ];
            DB::table('stok_telur')->insert($data);
            $data = [
                'tgl' => $r->tgl,
                'id_telur' => $r->id_telur[$x],
                'pcs' => $r->pcs[$x],
                'kg' => $r->kg[$x],
                'ket' => $r->ket[$x],
                'admin' => auth()->user()->name,
                'nota_transfer' => 'TF-' . $nota_t,
                'id_gudang' => $r->id_gudang,
                'jenis' => 'tf'
            ];
            DB::table('stok_telur')->insert($data);
        }
        return redirect()->route('dashboard_kandang.index')->with('sukses', 'Data berhasil di transfer');
    }

    public function penjualan_telur()
    {
        $tgl1 =  $this->tgl1;
        $tgl2 =  $this->tgl2;
        $transfer = DB::select("SELECT a.tgl, a.no_nota, a.customer,  b.nm_telur,  sum(a.total_rp) as ttl_rp , a.admin, a.admin_cek
        FROM invoice_telur as a 
        left join telur_produk as b on b.id_produk_telur = a.id_produk
        WHERE a.lokasi = 'mtd' and a.tgl between '$tgl1' and '$tgl2'
        GROUP by a.no_nota;");

        $data = [
            'title' => 'Penjualan Telur',
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
            'penjualan' => $transfer
        ];
        return view('dashboard_kandang.penjualan_telur.penjualan_telur', $data);
    }

    public function add_penjualan_telur()
    {
        $max = DB::table('invoice_telur')->latest('urutan')->first();

        if (empty($max)) {
            $nota_t = '1000';
        } else {
            $nota_t = $max->urutan + 1;
        }
        $data = [
            'title' => 'Buat Invoice',
            'produk' => DB::table('telur_produk')->get(),
            'customer' => DB::table('customer')->get(),
            'nota' => $nota_t,
            'akun' => DB::table('akun')->whereIn('id_klasifikasi', ['1', '7'])->get()
        ];

        return view('dashboard_kandang.penjualan_telur.add_penjualan_telur', $data);
    }

    public function edit_telur(Request $r)
    {
        $data =  [
            'title' => 'Edit Stok Telur',
            'telur' => DB::table('stok_telur')->where('id_stok_telur', $r->id_stok_telur)->first(),
            'kandang' => DB::table('kandang')->get(),
            'produk' => DB::table('telur_produk')->get(),
        ];
        return view('dashboard_kandang.penjualan_telur.edit', $data);
    }

    public function save_penjualan_telur(Request $r)
    {
        $max = DB::table('invoice_telur')->latest('urutan')->where('lokasi', 'mtd')->first();

        if (empty($max)) {
            $nota_t = '1000';
        } else {
            $nota_t = $max->urutan + 1;
        }

        $max_customer = DB::table('invoice_telur')->latest('urutan_customer')->where('id_customer', $r->customer)->first();

        if (empty($max_customer)) {
            $urutan_cus = '1';
        } else {
            $urutan_cus = $max_customer->urutan_customer + 1;
        }
        for ($x = 0; $x < count($r->id_produk); $x++) {

            if ($r->tipe == 'kg') {
                $data = [
                    'tgl' => $r->tgl,
                    'customer' => $r->customer,
                    'tipe' => $r->tipe,
                    'no_nota' => 'TM' . $nota_t,
                    'id_produk' => $r->id_produk[$x],
                    'pcs' => $r->pcs[$x],
                    'kg' => $r->kg[$x],
                    'kg_jual' => $r->kg_jual[$x],
                    'ikat' => $r->ikat[$x],
                    'rp_satuan' => $r->rp_satuan[$x],
                    'total_rp' => $r->total_rp[$x],
                    'admin' => auth()->user()->name,
                    'urutan' => $nota_t,
                    'urutan_customer' => $urutan_cus,
                    'driver' => '',
                    'lokasi' => 'mtd'
                ];
                DB::table('invoice_telur')->insert($data);
            } else {
                $data = [
                    'tgl' => $r->tgl,
                    'customer' => $r->customer,
                    'tipe' => $r->tipe,
                    'no_nota' => 'TM' . $nota_t,
                    'id_produk' => $r->id_produk[$x],
                    'pcs' => $r->pcs[$x],
                    'kg' => $r->kg[$x],
                    'rp_satuan' => $r->rp_satuan[$x],
                    'total_rp' => $r->total_rp[$x],
                    'admin' => auth()->user()->name,
                    'urutan' => $nota_t,
                    'urutan_customer' => $urutan_cus,
                    'driver' => '',
                    'lokasi' => 'mtd'
                ];
                DB::table('invoice_telur')->insert($data);
            }

            DB::table('stok_telur')->insert([
                'id_kandang' => 0,
                'id_telur' => $r->id_produk[$x],
                'tgl' => $r->tgl,
                'pcs_kredit' => $r->pcs[$x],
                'kg_kredit' => $r->kg[$x],
                'pcs' => 0,
                'kg' => 0,
                'admin' => auth()->user()->name,
                'id_gudang' => 1,
                'nota_transfer' => 'TM' . $nota_t,
                'ket' => '',
                'jenis' => 'penjualan',
                'check' => 'Y'
            ]);
        }

        return redirect()->route('dashboard_kandang.penjualan_telur')->with('sukses', 'Data berhasil ditambahkan');
    }

    public function save_edit_telur(Request $r)
    {
        $data = [
            'id_kandang' => $r->id_kandang,
            'id_telur' => $r->id_produk_telur,
            'tgl' => $r->tgl,
            'pcs_kredit' => $r->pcs,
            'kg_kredit' => $r->kg,
            'admin' => auth()->user()->name,
        ];
        DB::table('stok_telur')->where('id_stok_telur', $r->id_stok_telur)->update($data);
        return redirect()->route('dashboard_kandang.penjualan_telur')->with('sukses', 'Data berhasil ditambahkan');
    }

    public function penjualan_umum()
    {

        $tgl1 = $this->tgl1;
        $tgl2 = $this->tgl2;
        $id_user = auth()->user()->id;
        $penjualan = DB::select("SELECT *, sum(a.total_rp) as total, count(*) as ttl_produk  FROM `penjualan_agl` as a
        WHERE a.tgl BETWEEN '$tgl1' AND '$tgl2'
        GROUP BY a.urutan
        ORDER BY a.id_penjualan DESC");
        $data = [
            'title' => 'Penjualan Umum',
            'penjualan' => $penjualan,
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
        ];
        return view('dashboard_kandang.penjualan_umum.penjualan_umum', $data);
    }

    public function add_penjualan_umum()
    {
        $kd_produk = Produk::latest('kd_produk')->first();
        $nota = buatNota('penjualan_agl', 'urutan');
        $data = [
            'title' => 'Tambah Penjualan Umum',
            'customer' => DB::table('customer')->get(),
            'produk' => $this->produk,
            'no_nota' => $nota,
            'kd_produk' => empty($kd_produk) ? 1 : $kd_produk->kd_produk + 1,
            'satuan' => DB::table('tb_satuan')->get(),
            'gudang' => $this->gudang,
        ];
        return view('dashboard_kandang.penjualan_umum.add', $data);
    }

    public function tbh_add(Request $r)
    {
        $data = [
            'count' => $r->count,
            'produk' => $this->produk,
        ];
        return view('penjualan2.tbh_add', $data);
    }

    public function get_stok(Request $r)
    {
        $cek = DB::selectOne("SELECT f.debit,f.kredit FROM tb_produk as a
        LEFT join (
                  SELECT 
                    max(b.tgl) as tgl, 
                    b.id_produk, 
                    b.urutan, 
                    SUM(b.debit) as debit, 
                    sum(b.kredit) as kredit 
                  FROM 
                    tb_stok_produk as b 
                  where 
                    b.jenis = 'selesai'
                  group by 
                    b.id_produk
                ) as f on f.id_produk = a.id_produk 
        WHERE a.id_produk = '$r->id_telur'");
        echo json_encode($cek);
    }

    public function save_penjualan_umum(Request $r)
    {
        for ($i = 0; $i < count($r->id_produk); $i++) {
            DB::table('penjualan_agl')->insert([
                'urutan' => $r->no_nota,
                'nota_manual' => $r->nota_manual,
                'tgl' => $r->tgl,
                'kode' => 'PUM',
                'id_customer' => $r->id_customer,
                'driver' => '',
                'id_produk' => $r->id_produk[$i],
                'qty' => $r->qty[$i],
                'rp_satuan' => $r->rp_satuan[$i],
                'total_rp' => $r->total_rp[$i],
                'ket' => '',
                'id_jurnal' => 0,
                'admin' => auth()->user()->name,
                'lokasi' => 'mtd'
            ]);

            $getProduk = DB::table('tb_stok_produk')->where('id_produk', $r->id_produk[$i])->orderBy('id_stok_produk', 'DESC')->first();
            $notaProduk = buatNota('tb_stok_produk', 'urutan');
            $jml_sebelumnya = $getProduk->jml_sebelumnya ?? 0;
            $jml_sesudahnya = $jml_sebelumnya - $r->qty[$i];

            DB::table("tb_stok_produk")->insert([
                'id_produk' => $r->id_produk[$i],
                'urutan' => $notaProduk,
                'no_nota' => 'SK-' . $notaProduk,
                'tgl' => $r->tgl,
                'jenis' => 'selesai',
                'status' => 'keluar',
                'jml_sebelumnya' => $jml_sebelumnya,
                'jml_sesudahnya' => $jml_sesudahnya,
                'debit' => 0,
                'kredit' => $r->qty[$i],
                'rp_satuan' => 0,
                'ket' => 'PUM-' . $r->no_nota,
                'gudang_id' => $getProduk->gudang_id,
                'kategori_id' => 3,
                'departemen_id' => 1,
                'admin' => auth()->user()->name,
                'lokasi' => 'mtd'
            ]);
        }

        return redirect()->route('dashboard_kandang.penjualan_umum')->with('sukses', 'Data Berhasil Ditambahkan');
    }

    public function edit_penjualan(Request $r)
    {
        $penjualan = DB::selectOne("SELECT *, sum(a.total_rp) as total, count(*) as ttl_produk  FROM `penjualan_agl` as a
        WHERE a.urutan = '$r->urutan' ");
        $data = [
            'title' => 'Edit Penjualan Umum',
            'customer' => DB::table('customer')->get(),
            'produk' => $this->produk,
            'getProduk' => DB::table('penjualan_agl as a')
                ->join('tb_produk as b', 'a.id_produk', 'b.id_produk')
                ->where('urutan', $r->urutan)
                ->get(),
            'getPenjualan' => $penjualan,
            'no_nota' => $penjualan->urutan
        ];
        return view('dashboard_kandang.penjualan_umum.edit', $data);
    }

    public function update_penjualan(Request $r)
    {
        DB::table('tb_stok_produk')->where('no_nota', 'PUM-' . $r->no_nota)->delete();
        DB::table('penjualan_agl')->where('urutan', $r->no_nota)->delete();

        for ($i = 0; $i < count($r->id_produk); $i++) {
            DB::table('penjualan_agl')->insert([
                'urutan' => $r->no_nota,
                'nota_manual' => $r->nota_manual,
                'tgl' => $r->tgl,
                'id_customer' => $r->id_customer,
                'driver' => '',
                'id_produk' => $r->id_produk[$i],
                'qty' => $r->qty[$i],
                'rp_satuan' => $r->rp_satuan[$i],
                'total_rp' => $r->total_rp[$i],
                'ket' => '',
                'id_jurnal' => 0,
                'admin' => auth()->user()->name,
                'lokasi' => 'mtd'
            ]);
            $getProduk = DB::table('tb_stok_produk')->where('id_produk', $r->id_produk[$i])->orderBy('id_stok_produk', 'DESC')->first();
            $notaProduk = buatNota('tb_stok_produk', 'urutan');
            $jml_sebelumnya = $getProduk->jml_sebelumnya ?? 0;
            $jml_sesudahnya = $jml_sebelumnya - $r->qty[$i];

            DB::table("tb_stok_produk")->insert([
                'id_produk' => $r->id_produk[$i],
                'urutan' => $notaProduk,
                'no_nota' => 'SK-' . $notaProduk,
                'tgl' => $r->tgl,
                'jenis' => 'selesai',
                'status' => 'keluar',
                'jml_sebelumnya' => $jml_sebelumnya,
                'jml_sesudahnya' => $jml_sesudahnya,
                'debit' => 0,
                'kredit' => $r->qty[$i],
                'rp_satuan' => 0,
                'ket' => 'PUM-' . $r->no_nota,
                'gudang_id' => $getProduk->gudang_id,
                'kategori_id' => 3,
                'departemen_id' => 1,
                'admin' => auth()->user()->name,
                'lokasi' => 'mtd'
            ]);
        }

        return redirect()->route('dashboard_kandang.penjualan_umum')->with('sukses', 'Data Berhasil Ditambahkan');
    }

    public function detail($no_nota)
    {
        $penjualan = DB::selectOne("SELECT *, sum(a.total_rp) as total, count(*) as ttl_produk  FROM `penjualan_agl` as a
        LEFT JOIN customer as b ON a.id_customer = b.id_customer
        WHERE a.urutan = '$no_nota' ");
        $data = [
            'title' => 'Detail Penjaulan Umum',
            'head_jurnal' => $penjualan,
            'produk' => DB::table('penjualan_agl as a')
                ->join('tb_produk as b', 'a.id_produk', 'b.id_produk')
                ->where('urutan', $no_nota)
                ->get()
        ];
        return view('penjualan2.detail', $data);
    }

    public function load_detail_nota($urutan)
    {
        $urutan = explode(", ", $urutan);
        $id_produk = $urutan[count($urutan) - 1];

        $produk = DB::table('penjualan_agl as a')
            ->join('tb_produk as b', 'a.id_produk', 'b.id_produk')
            ->where('a.id_produk', $id_produk)
            ->whereIn('a.urutan', $urutan)
            ->get();
        $data = [
            'title' => 'Detail Penjaulan Umum',
            'produk' => $produk
        ];
        return view('dashboard_kandang.penjualan_umum.detail', $data);
    }

    public function load_perencanaan($id_kandang)
    {
        $pop = DB::selectOne("SELECT sum(a.mati + a.jual) as pop,b.stok_awal FROM populasi as a
                            LEFT JOIN kandang as b ON a.id_kandang = b.id_kandang
                            WHERE a.id_kandang = '$id_kandang';");
        $data = [
            'title' => 'Perencanaan',
            'id_kandang' => $id_kandang,
            'kandang' => DB::table('kandang')->where('id_kandang', $id_kandang)->first(),
            'obatAyam' => DB::table('tb_produk_perencanaan')->where('kategori', 'obat_ayam')->get(),
            'pop' => $pop
        ];
        return view('dashboard_kandang.perencanaan.index', $data);
    }

    public function load_pakan_perencanaan()
    {

        $data = [
            'title' => 'Perencanaan',
            'pakan' => DB::table('tb_produk_perencanaan')->where('kategori', 'pakan')->get(),

        ];
        return view('dashboard_kandang.perencanaan.load_pakan_perencanaan', $data);
    }
    public function tbh_pakan(Request $r)
    {
        $data = [
            'pakan' => DB::table('tb_produk_perencanaan')->where('kategori', 'pakan')->get(),
            'count' => $r->count
        ];
        return view('dashboard_kandang.perencanaan.tbh_pakan', $data);
    }
    public function save_tambah_pakan(Request $r)
    {
        $id = DB::table('tb_produk_perencanaan')->insertGetId([
            'nm_produk' => $r->nm_produk,
            'kategori' => 'pakan',
            'tgl' => date('Y-m-d'),
            'admin' => auth()->user()->name
        ]);

        DB::table('stok_produk_perencanaan')->insert([
            'id_kandang' => 0,
            'id_pakan' => $id,
            'tgl' => date('Y-m-d'),
            'pcs' => $r->stok_awal,
            'pcs_kredit' => 0,
            'admin' => auth()->user()->name,
            'cek_admin' => '',
            'total_rp' => $r->total_rp
        ]);
    }

    public function load_obat_pakan()
    {

        $data = [
            'title' => 'Perencanaan',
            'pakan' => DB::table('tb_produk_perencanaan')->where('kategori', 'obat_pakan')->get(),
        ];
        return view('dashboard_kandang.perencanaan.load_obat_pakan', $data);
    }

    public function get_stok_pakan(Request $r)
    {
        $stok = DB::selectOne("SELECT sum(pcs) as stok FROM stok_produk_perencanaan WHERE id_pakan = '$r->id_pakan'");
        echo $stok->stok;
    }
    public function tbh_obatPakan(Request $r)
    {
        $data = [
            'pakan' => DB::table('tb_produk_perencanaan')->where('kategori', 'obat_pakan')->get(),
            'count' => $r->count
        ];
        return view('dashboard_kandang.perencanaan.tbh_obatPakan', $data);
    }
    public function save_tambah_obat_pakan(Request $r)
    {
        $id = DB::table('tb_produk_perencanaan')->insertGetId([
            'nm_produk' => $r->nm_produk,
            'kategori' => 'obat_pakan',
            'tgl' => date('Y-m-d'),
            'dosis_satuan' => $r->dosis_satuan ?? '',
            'campuran_satuan' => $r->campuran_satuan ?? '',
            'admin' => auth()->user()->name
        ]);

        DB::table('stok_produk_perencanaan')->insert([
            'id_kandang' => 0,
            'id_pakan' => $id,
            'tgl' => date('Y-m-d'),
            'pcs' => $r->stok_awal,
            'pcs_kredit' => 0,
            'admin' => auth()->user()->name,
            'cek_admin' => '',
            'total_rp' => $r->total_rp
        ]);
    }
    public function get_stok_obat_pakan(Request $r)
    {
        $stok = DB::selectOne("SELECT b.nm_satuan AS dosis_satuan, c.nm_satuan AS campuran_satuan
                FROM tb_produk_perencanaan a
                LEFT JOIN tb_satuan b ON a.dosis_satuan = b.id_satuan
                LEFT JOIN tb_satuan c ON a.campuran_satuan = c.id_satuan
                WHERE a.id_produk = '$r->id_produk';");

        $data = [
            'dosis_satuan' => $stok->dosis_satuan,
            'campuran_satuan' => $stok->campuran_satuan,
        ];
        echo json_encode($data);
    }

    public function load_obat_air()
    {

        $data = [
            'title' => 'Perencanaan',
            'pakan' => DB::table('tb_produk_perencanaan')->where('kategori', 'obat_air')->get(),
        ];
        return view('dashboard_kandang.perencanaan.load_obat_air', $data);
    }
    public function tbh_obatAir(Request $r)
    {
        $data = [
            'pakan' => DB::table('tb_produk_perencanaan')->where('kategori', 'obat_air')->get(),
            'count' => $r->count
        ];
        return view('dashboard_kandang.perencanaan.tbh_obatAir', $data);
    }
    public function save_tambah_obat_air(Request $r)
    {
        $id = DB::table('tb_produk_perencanaan')->insertGetId([
            'nm_produk' => $r->nm_produk,
            'kategori' => 'obat_air',
            'tgl' => date('Y-m-d'),
            'dosis_satuan' => $r->dosis_satuan ?? '',
            'campuran_satuan' => $r->campuran_satuan ?? '',
            'admin' => auth()->user()->name
        ]);

        DB::table('stok_produk_perencanaan')->insert([
            'id_kandang' => 0,
            'id_pakan' => $id,
            'tgl' => date('Y-m-d'),
            'pcs' => $r->stok_awal,
            'pcs_kredit' => 0,
            'admin' => auth()->user()->name,
            'cek_admin' => '',
            'total_rp' => $r->total_rp
        ]);
    }
    public function get_stok_obat_air(Request $r)
    {
        $stok = DB::selectOne("SELECT b.nm_satuan AS dosis_satuan, c.nm_satuan AS campuran_satuan
                FROM tb_produk_perencanaan a
                LEFT JOIN tb_satuan b ON a.dosis_satuan = b.id_satuan
                LEFT JOIN tb_satuan c ON a.campuran_satuan = c.id_satuan
                WHERE a.id_produk = '$r->id_produk';");

        $data = [
            'dosis_satuan' => $stok->dosis_satuan,
            'campuran_satuan' => $stok->campuran_satuan,
        ];
        echo json_encode($data);
    }
    
    public function load_obat_ayam()
    {
        $data = [
            'title' => 'Perencanaan',
            'pakan' => DB::table('tb_produk_perencanaan')->where('kategori', 'obat_ayam')->get(),
        ];
        return view('dashboard_kandang.perencanaan.load_obat_ayam', $data);
    }
    public function save_tambah_obat_ayam(Request $r)
    {
        $id = DB::table('tb_produk_perencanaan')->insertGetId([
            'nm_produk' => $r->nm_produk,
            'kategori' => 'obat_ayam',
            'tgl' => date('Y-m-d'),
            'dosis_satuan' => $r->dosis_satuan ?? '',
            'campuran_satuan' => $r->campuran_satuan ?? '',
            'admin' => auth()->user()->name
        ]);

        DB::table('stok_produk_perencanaan')->insert([
            'id_kandang' => 0,
            'id_pakan' => $id,
            'tgl' => date('Y-m-d'),
            'pcs' => $r->stok_awal,
            'pcs_kredit' => 0,
            'admin' => auth()->user()->name,
            'cek_admin' => '',
            'total_rp' => $r->total_rp
        ]);
    }
    public function get_stok_obat_ayam(Request $r)
    {
        $stok = DB::selectOne("SELECT b.nm_satuan AS dosis_satuan, c.nm_satuan AS campuran_satuan
                FROM tb_produk_perencanaan a
                LEFT JOIN tb_satuan b ON a.dosis_satuan = b.id_satuan
                LEFT JOIN tb_satuan c ON a.campuran_satuan = c.id_satuan
                WHERE a.id_produk = '$r->id_produk';");

        echo $stok->dosis_satuan;
    }

    public function tambah_perencanaan(Request $r)
    {
        $tgl = $r->tgl;
        $id_kandang = $r->id_kandang;
        $kg_pakan_box = $r->kg_pakan_box;
        $populasi = $r->populasi;
        $gr_pakan_ekor = $r->gr_pakan_ekor;
        $kg_karung = $r->kg_karung;
        $kg_karung_sisa = $r->kg_karung_sisa;
        $no_nota = strtoupper(str()->random(5));
        if(!empty($r->id_pakan)) {
            for ($i=0; $i < count($r->id_pakan); $i++) { 
                $dataPakan = [
                    'id_kandang' => $id_kandang,
                    'id_produk_pakan' => $r->id_pakan[$i],
                    'tgl' => $tgl,
                    'no_nota' => $no_nota,
                    'gr' => $r->gr_pakan[$i],
                    'persen' => $r->persen_pakan[$i],
                    'admin' => auth()->user()->name
                ];
                DB::table('tb_pakan_perencanaan')->insert($dataPakan);
               
                $dataStok = [
                    'id_kandang' => $id_kandang,
                    'id_pakan' => $r->id_pakan[$i],
                    'tgl' => $tgl,
                    'pcs' => 0,
                    'total_rp' => 0,
                    'no_nota' => $no_nota,
                    'pcs_kredit' =>  ($r->gr_pakan[$i] / 1000) - $r->stok[$i],
                    'admin' => auth()->user()->name
                ];
                DB::table('stok_produk_perencanaan')->insert($dataStok);
            }
        }

        if (!empty($kg_pakan_box)) {
            $dataKarung = [
                'tgl' => $tgl,
                'id_kandang' => $id_kandang,
                'karung' => $kg_pakan_box,
                'gr' => $kg_karung,
                'gr2' => $kg_karung_sisa,
                'no_nota' => $no_nota,
            ];
            DB::table('tb_karung_perencanaan')->insert($dataKarung);
        }

        if(!empty($r->id_obat_pakan)) {
            for ($i=0; $i < count($r->id_obat_pakan); $i++) { 
                $data1 = [
                    'kategori' => 'obat_pakan',
                    'id_produk' => $r->id_obat_pakan[$i],
                    'dosis' => $r->dosis_obat_pakan[$i],
                    'campuran' => $r->campuran_obat_pakan[$i],
                    'tgl' => $tgl,
                    'no_nota' => $no_nota,
                    'id_kandang' => $id_kandang,
                    'admin' => auth()->user()->name,
                ];

                DB::table('tb_obat_perencanaan')->insert($data1);
                $id_obat_pakan = $r->id_obat_pakan[$i];
                $stok = DB::selectOne("SELECT sum(pcs) as stok FROM stok_produk_perencanaan WHERE id_pakan = '$id_obat_pakan'");
                $dataStok = [
                    'id_kandang' => $id_kandang,
                    'id_pakan' => $id_obat_pakan,
                    'tgl' => $tgl,
                    'pcs' => 0,
                    'total_rp' => 0,
                    'no_nota' => $no_nota,
                    'id_kandang' => $id_kandang,
                    'pcs_kredit' =>  ($r->dosis_obat_pakan[$i]  / 1000) - $stok->stok,
                    'admin' => auth()->user()->name
                ];
                DB::table('stok_produk_perencanaan')->insert($dataStok);
            }
        }

        if(!empty($r->id_obat_air)) {
            for ($i=0; $i < count($r->id_obat_air); $i++) { 
                $data1 = [
                    'kategori' => 'obat_air',
                    'id_produk' => $r->id_obat_air[$i],
                    'dosis' => $r->dosis_obat_air[$i],
                    'campuran' => $r->campuran_obat_air[$i],
                    'tgl' => $tgl,
                    'no_nota' => $no_nota,
                    'waktu' => $r->waktu_obat_air[$i],
                    'ket' => $r->ket_obat_air[$i],
                    'cara_pemakaian' => $r->cara_pemakaian_obat_air[$i],
                    'id_kandang' => $id_kandang,
                    'admin' => auth()->user()->name,
                ];

                DB::table('tb_obat_perencanaan')->insert($data1);

                $id_obat_air = $r->id_obat_air[$i];
                $stok = DB::selectOne("SELECT sum(pcs) as stok FROM stok_produk_perencanaan WHERE id_pakan = '$id_obat_air'");
                $dataStok = [
                    'id_kandang' => $id_kandang,
                    'id_pakan' => $id_obat_air,
                    'tgl' => $tgl,
                    'pcs' => 0,
                    'total_rp' => 0,
                    'no_nota' => $no_nota,
                    'pcs_kredit' =>  ($r->dosis_obat_pakan[$i] / 1000) - $stok->stok,
                    'admin' => auth()->user()->name
                ];
                DB::table('stok_produk_perencanaan')->insert($dataStok);
            }
        }

        if(!empty($r->id_obat_ayam)) {
            $data1 = [
                'kategori' => 'obat_air',
                'id_produk' => $r->id_obat_ayam,
                'dosis' => $r->dosis_obat,
                'campuran' => $r->campuran_obat,
                'tgl' => $tgl,
                'no_nota' => $no_nota,
                'admin' => auth()->user()->name,
            ];

            DB::table('tb_obat_perencanaan')->insert($data1);

            $id_obat_ayam = $r->id_obat_ayam;
            $stok = DB::selectOne("SELECT sum(pcs) as stok FROM stok_produk_perencanaan WHERE id_pakan = '$id_obat_ayam'");
            $dataStok = [
                'id_kandang' => $id_kandang,
                'id_pakan' => $id_obat_ayam,
                'tgl' => $tgl,
                'pcs' => 0,
                'total_rp' => 0,
                'no_nota' => $no_nota,
                'pcs_kredit' =>  ($r->dosis_obat_ayam[$i] / 1000) - $stok->stok,
                'admin' => auth()->user()->name
            ];
            DB::table('stok_produk_perencanaan')->insert($dataStok);
        }

        return redirect()->route('dashboard_kandang.index')->with('sukses', 'Data Perencanaan Berhasil ditambahkan');

    }

    public function load_detail_perencanaan($id_kandang)
    {
        $data = [
            'title' => 'Detail Perencanaan',
            'kandang' => DB::table('kandang')->where('id_kandang', $id_kandang)->first()
        ];
        return view('dashboard_kandang.modal.detail_perencanaan',$data);
    }

    public function getQueryObatPerencanaan($tgl, $id_kandang, $kategori)
    {
        return DB::table('tb_obat_perencanaan as a')
                    ->select(
                        'a.tgl',
                        'b.id_produk',
                        'b.nm_produk',
                        'a.waktu',
                        'a.cara_pemakaian as cara',
                        'a.id_kandang',
                        'a.ket',
                        'a.dosis',
                        'a.campuran',
                        'c.nm_satuan as satuan', 
                        'd.nm_satuan as satuan2'
                    )
                    ->leftJoin('tb_produk_perencanaan as b', 'a.id_produk', 'b.id_produk')
                    ->leftJoin('tb_satuan as c', 'b.dosis_satuan', 'c.id_satuan')
                    ->leftJoin('tb_satuan as d', 'b.campuran_satuan', 'd.id_satuan')
                    ->where([['a.tgl', $tgl], ['a.id_kandang', $id_kandang], ['a.kategori', $kategori]])
                    ->get();
    }

    public function viewHistoryPerencanaan(Request $r)
    {
        $id_kandang = $r->id_kandang;
        $tgl = $r->tgl;

        $tgl1 = date('Y-m-d', strtotime('-1 days', strtotime($tgl)));

        $pop = DB::selectOne("SELECT sum(a.mati + a.jual) as pop,b.stok_awal FROM populasi as a
                            LEFT JOIN kandang as b ON a.id_kandang = b.id_kandang
                            WHERE a.id_kandang = '$id_kandang'");
        $populasi = $pop->stok_awal - $pop->pop;

        $kandang = DB::table('kandang')->where('id_kandang', $id_kandang)->first();
        $pakan = DB::selectOne("SELECT *,sum(gr) as total FROM tb_pakan_perencanaan as a 
                    WHERE a.tgl = '$tgl' AND a.id_kandang = '$id_kandang' 
                    GROUP BY a.id_kandang");

        $umur = DB::selectOne("SELECT TIMESTAMPDIFF(WEEK, a.chick_in, '$tgl') as mgg FROM kandang as a 
        WHERE a.id_kandang = '$id_kandang'");

        $pakan1 = DB::table('tb_karung_perencanaan')->where([['id_kandang', $id_kandang], ['tgl', $tgl]])->first();

        $pakan2 = DB::select("SELECT  a.tgl, b.nm_produk as nm_pakan, a.persen, a.gr as gr_pakan
        FROM tb_pakan_perencanaan as a 
        left join tb_produk_perencanaan as b on a.id_produk_pakan = b.id_produk 
        where a.id_kandang = '$id_kandang' AND  a.tgl = '$tgl'");

        $obat_pakan = $this->getQueryObatPerencanaan($tgl, $id_kandang, 'obat_pakan');

        $obat_air = $this->getQueryObatPerencanaan($tgl, $id_kandang, 'obat_air');
        $obat_ayam = $this->getQueryObatPerencanaan($tgl, $id_kandang, 'obat_ayam');
        $data = [
            'tgl_per' => $tgl,
            'id_kandang' => $id_kandang,
            'kandang' => $kandang,
            'pakan' => $pakan,
            'populasi' => $populasi,
            'umur' => $umur,
            'pakan1' => $pakan1,
            'pakan2' => $pakan2,
            'obat_pakan' => $obat_pakan,
            'obat_air' => $obat_air,
            'obat_ayam' => $obat_ayam,
        ];
        return view("dashboard_kandang.history.hasilPerencanaan", $data);
    }

    public function viewHistoryEditPerencanaan(Request $r)
    {
        $tgl = $r->tgl;
        $tgl1 = date('Y-m-d', strtotime('-1 days', strtotime($tgl)));
        $id_kandang = $r->id_kandang;
        $pakan_id = DB::select("SELECT a.no_nota, a.id_pakan_perencanaan,a.tgl,b.id_produk, b.nm_produk as nm_pakan, a.persen, a.gr as gr_pakan
                    FROM tb_pakan_perencanaan as a 
                    left join tb_produk_perencanaan as b on a.id_produk_pakan = b.id_produk 
                    where a.id_kandang = '$id_kandang' AND  a.tgl = '$tgl'");
        $pakan = DB::table('tb_produk_perencanaan')->where('kategori', 'pakan')->get();
        $obat = DB::table('tb_produk_perencanaan')->where('kategori', 'obat_pakan')->get();
        $obat_air2 = DB::table('tb_produk_perencanaan')->where('kategori', 'obat_air')->get();
        $obat_ayam = DB::table('tb_produk_perencanaan')->where('kategori', 'obat_ayam')->get();
        $karung = DB::table('tb_karung_perencanaan')->where([['id_kandang', $id_kandang], ['tgl', $tgl]])->first();
        $kandang = DB::table('kandang')->get();
        $pop = DB::selectOne("SELECT sum(a.mati + a.jual) as pop,b.stok_awal FROM populasi as a
                            LEFT JOIN kandang as b ON a.id_kandang = b.id_kandang
                            WHERE a.id_kandang = '$id_kandang'");
        $populasi = $pop->stok_awal - $pop->pop;
        $gr_pakan = DB::selectOne("SELECT sum(a.gr) as ttl, a.no_nota FROM tb_pakan_perencanaan as a where a.id_kandang = '$id_kandang' and a.tgl = '$tgl' group by a.id_kandang");
        $obat_pakan = $this->getQueryObatPerencanaan($tgl, $id_kandang, 'obat_pakan');
        $obat_air = $this->getQueryObatPerencanaan($tgl, $id_kandang, 'obat_air');
        $obat_aym = $this->getQueryObatPerencanaan($tgl, $id_kandang, 'obat_ayam');

        $data = [
            'tgl' => $tgl,
            'id_kandang' => $id_kandang,
            'pakan_id' => $pakan_id,
            'pakan' => $pakan,
            'karung' => $karung,
            'kandang' => $kandang,
            'populasi' => $populasi,
            'gr_pakan' => $gr_pakan,
            'obat_pakan' => $obat_pakan,
            'obat_air' => $obat_air,
            'obat' => $obat,
            'obat_air2' => $obat_air2,
            'obat_ayam' => $obat_ayam,
            'obat_aym' => $obat_aym,
        ];
        return view('dashboard_kandang.history.editPerencanaan',$data);
    }

    public function edit_perencanaan(Request $r)
    {
        $no_nota = $r->no_nota;
        $id_kandang = $r->id_kandang;
        $tgl = $r->tgl;
        $kg_pakan_box = $r->kg_pakan_box;
        $populasi = $r->populasi;
        $gr_pakan_ekor = $r->gr_pakan_ekor;
        $kg_karung = $r->kg_karung;
        $kg_karung_sisa = $r->kg_karung_sisa;

        DB::table('stok_produk_perencanaan')->where('no_nota', $no_nota)->delete();
        DB::table('tb_karung_perencanaan')->where('no_nota', $no_nota)->delete();
        DB::table('tb_obat_perencanaan')->where('no_nota', $no_nota)->delete();
        DB::table('tb_pakan_perencanaan')->where('no_nota', $no_nota)->delete();

        $no_nota = strtoupper(str()->random(5));

        if(!empty($r->id_pakan)) {
            for ($i=0; $i < count($r->id_pakan); $i++) { 
                $dataPakan = [
                    'id_kandang' => $id_kandang,
                    'id_produk_pakan' => $r->id_pakan[$i],
                    'tgl' => $tgl,
                    'no_nota' => $no_nota,
                    'gr' => $r->gr_pakan[$i],
                    'persen' => $r->persen_pakan[$i],
                    'admin' => auth()->user()->name
                ];
                DB::table('tb_pakan_perencanaan')->insert($dataPakan);
                $id_pakan = $r->id_pakan[$i];
                $stok = DB::selectOne("SELECT sum(pcs) as stok FROM stok_produk_perencanaan WHERE id_pakan = '$id_pakan'");
                $dataStok = [
                    'id_kandang' => $id_kandang,
                    'id_pakan' => $r->id_pakan[$i],
                    'tgl' => $tgl,
                    'pcs' => 0,
                    'total_rp' => 0,
                    'no_nota' => $no_nota,
                    'pcs_kredit' =>  ($r->gr_pakan[$i] / 1000) - $stok->stok,
                    'admin' => auth()->user()->name
                ];
                DB::table('stok_produk_perencanaan')->insert($dataStok);
            }
        }

        if (!empty($kg_pakan_box)) {
            $dataKarung = [
                'tgl' => $tgl,
                'id_kandang' => $id_kandang,
                'karung' => $kg_pakan_box,
                'gr' => $kg_karung,
                'gr2' => $kg_karung_sisa,
                'no_nota' => $no_nota,
            ];
            DB::table('tb_karung_perencanaan')->insert($dataKarung);
        }

        if(!empty($r->id_obat_pakan)) {
            for ($i=0; $i < count($r->id_obat_pakan); $i++) { 
                $data1 = [
                    'kategori' => 'obat_pakan',
                    'id_produk' => $r->id_obat_pakan[$i],
                    'dosis' => $r->dosis_obat_pakan[$i],
                    'campuran' => $r->campuran_obat_pakan[$i],
                    'tgl' => $tgl,
                    'no_nota' => $no_nota,
                    'id_kandang' => $id_kandang,
                    'admin' => auth()->user()->name,
                ];

                DB::table('tb_obat_perencanaan')->insert($data1);
                $id_obat_pakan = $r->id_obat_pakan[$i];
                $stok = DB::selectOne("SELECT sum(pcs) as stok FROM stok_produk_perencanaan WHERE id_pakan = '$id_obat_pakan'");
                $dataStok = [
                    'id_kandang' => $id_kandang,
                    'id_pakan' => $id_obat_pakan,
                    'tgl' => $tgl,
                    'pcs' => 0,
                    'total_rp' => 0,
                    'no_nota' => $no_nota,
                    'id_kandang' => $id_kandang,
                    'pcs_kredit' =>  ($r->dosis_obat_pakan[$i]  / 1000) - $stok->stok,
                    'admin' => auth()->user()->name
                ];
                DB::table('stok_produk_perencanaan')->insert($dataStok);
            }
        }

        if(!empty($r->id_obat_air)) {
            for ($i=0; $i < count($r->id_obat_air); $i++) { 
                $data1 = [
                    'kategori' => 'obat_air',
                    'id_produk' => $r->id_obat_air[$i],
                    'dosis' => $r->dosis_obat_air[$i],
                    'campuran' => $r->campuran_obat_air[$i],
                    'tgl' => $tgl,
                    'no_nota' => $no_nota,
                    'waktu' => $r->waktu_obat_air[$i],
                    'ket' => $r->ket_obat_air[$i],
                    'cara_pemakaian' => $r->cara_pemakaian_obat_air[$i],
                    'id_kandang' => $id_kandang,
                    'admin' => auth()->user()->name,
                ];

                DB::table('tb_obat_perencanaan')->insert($data1);

                $id_obat_air = $r->id_obat_air[$i];
                $stok = DB::selectOne("SELECT sum(pcs) as stok FROM stok_produk_perencanaan WHERE id_pakan = '$id_obat_air'");
                $dataStok = [
                    'id_kandang' => $id_kandang,
                    'id_pakan' => $id_obat_air,
                    'tgl' => $tgl,
                    'pcs' => 0,
                    'total_rp' => 0,
                    'no_nota' => $no_nota,
                    'pcs_kredit' =>  ($r->dosis_obat_pakan[$i] / 1000) - $stok->stok,
                    'admin' => auth()->user()->name
                ];
                DB::table('stok_produk_perencanaan')->insert($dataStok);
            }
        }

        if(!empty($r->id_obat_ayam)) {
            $data1 = [
                'kategori' => 'obat_air',
                'id_produk' => $r->id_obat_ayam,
                'dosis' => $r->dosis_obat,
                'campuran' => $r->campuran_obat,
                'tgl' => $tgl,
                'no_nota' => $no_nota,
                'admin' => auth()->user()->name,
            ];

            DB::table('tb_obat_perencanaan')->insert($data1);

            $id_obat_ayam = $r->id_obat_ayam;
            $stok = DB::selectOne("SELECT sum(pcs) as stok FROM stok_produk_perencanaan WHERE id_pakan = '$id_obat_ayam'");
            $dataStok = [
                'id_kandang' => $id_kandang,
                'id_pakan' => $id_obat_ayam,
                'tgl' => $tgl,
                'pcs' => 0,
                'total_rp' => 0,
                'no_nota' => $no_nota,
                'pcs_kredit' =>  ($r->dosis_obat_ayam[$i] / 1000) - $stok->stok,
                'admin' => auth()->user()->name
            ];
            DB::table('stok_produk_perencanaan')->insert($dataStok);
        }

        return redirect()->route('dashboard_kandang.index')->with('sukses', 'Data Perencanaan Berhasil didedit');
    }

    public function hasilLayer(Request $r)
    {
        $data = [
            'title' => 'Layer',
            'kandang' => DB::table('kandang')->get(),
            'tgl' => $r->tgl
        ];
        return view('dashboard_kandang.history.layer',$data);
    }
}