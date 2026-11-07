<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_DB_query_builder $db
 * @property CI_Loader $load
 * @property CI_URI $uri
 * @property CI_Template $template
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_Output $output
 * @property Fonnte $fonnte
 */
class Laporan_nilai extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('id_level_user')) {
            redirect('auth');
        }
    }

    function index()
    {
        $id_level = $this->session->userdata('id_level_user');
        $id_guru  = $this->session->userdata('id_guru');
        $id_tahun_aktif = get_tahun_akademik('id_tahun_akademik');

        $data['kelas_list'] = $this->db->get('tbl_kelas')->result();

        if ($id_level == 1 || $id_level == 4) {
            $kd_kelas = $this->input->get('filter_kelas');
            $data['selected_kelas'] = $kd_kelas;

            if (!empty($kd_kelas)) {
                $kelas = "SELECT tk.nama_kelas, tju.nama_jurusan, ttk.nama_tingkatan 
                          FROM tbl_kelas AS tk
                          JOIN tbl_jurusan AS tju ON tk.kd_jurusan = tju.kd_jurusan
                          JOIN tbl_tingkatan_kelas AS ttk ON tk.kd_tingkatan = ttk.kd_tingkatan
                          WHERE tk.kd_kelas = '$kd_kelas'";
                
                $siswa = "SELECT ts.nim, ts.nama, ts.no_hp
                          FROM tbl_riwayat_kelas AS trk
                          JOIN tbl_siswa AS ts ON trk.nim = ts.nim
                          WHERE trk.kd_kelas = '$kd_kelas' 
                          AND trk.id_tahun_akademik = '$id_tahun_aktif'";

                $data['kelas'] = $this->db->query($kelas)->row_array();
                $data['siswa'] = $this->db->query($siswa);
            } else {
                $data['kelas'] = null;
                $data['siswa'] = null;
            }

        } else {
            $walikelas = $this->db->get_where('tbl_walikelas', array('id_guru' => $id_guru, 'id_tahun_akademik' => $id_tahun_aktif))->row_array();

            if (!$walikelas) {
                echo "<script>alert('Anda bukan wali kelas pada periode aktif ini, tidak dapat mengakses laporan nilai.'); history.go(-1);</script>";
                return;
            }

            $kd_kelas = $walikelas['kd_kelas'];
            $data['selected_kelas'] = $kd_kelas;

            $kelas = "SELECT tk.nama_kelas, tju.nama_jurusan, ttk.nama_tingkatan 
                      FROM tbl_kelas AS tk
                      JOIN tbl_jurusan AS tju ON tk.kd_jurusan = tju.kd_jurusan
                      JOIN tbl_tingkatan_kelas AS ttk ON tk.kd_tingkatan = ttk.kd_tingkatan
                      WHERE tk.kd_kelas = '$kd_kelas'";
            
            $siswa = "SELECT ts.nim, ts.nama, ts.no_hp
                      FROM tbl_riwayat_kelas AS trk
                      JOIN tbl_siswa AS ts ON trk.nim = ts.nim
                      WHERE trk.kd_kelas = '$kd_kelas' 
                      AND trk.id_tahun_akademik = '$id_tahun_aktif'";

            $data['kelas'] = $this->db->query($kelas)->row_array();
            $data['siswa'] = $this->db->query($siswa);
        }
        
        $this->template->load('template', 'Laporan_nilai/list_siswa', $data);
    }

    public function cek_nilai_siswa()
    {
        $nim = $this->input->post('nim');
        $id_tahun_aktif = get_tahun_akademik('id_tahun_akademik');
        $min_mapel_terisi = 6; 

        $sqlCekNilai = "SELECT COUNT(tn.nilai) as total_terisi 
                        FROM tbl_jadwal tj
                        LEFT JOIN tbl_nilai tn ON tn.id_jadwal = tj.id_jadwal AND tn.nim = '$nim'
                        WHERE tj.id_tahun_akademik = '$id_tahun_aktif' 
                        AND tn.nilai > 0";
        $cek_nilai = $this->db->query($sqlCekNilai)->row_array();
        $total_terisi = $cek_nilai ? (int)$cek_nilai['total_terisi'] : 0;

        if ($total_terisi >= $min_mapel_terisi) {
            echo json_encode(array('status' => 'success', 'total_terisi' => $total_terisi));
        } else {
            echo json_encode(array(
                'status' => 'error', 
                'message' => 'Raport belum dapat dicetak/dikirim! Nilai siswa baru terisi ' . $total_terisi . ' mata pelajaran. Minimal harus terisi ' . $min_mapel_terisi . ' mata pelajaran!'
            ));
        }
    }

    // FUNGSI AJAX: Ambil List Ekstrakurikuler Siswa
    function get_list_ekstra()
    {
        $nim = $this->input->post('nim');
        $id_tahun_aktif = get_tahun_akademik('id_tahun_akademik');

        $list = $this->db->get_where('tbl_nilai_ekstra', array(
            'nim' => $nim,
            'id_tahun_akademik' => $id_tahun_aktif
        ))->result();

        echo json_encode($list);
    }

    // FUNGSI AJAX: Simpan Baru ATAU Update Ekstrakurikuler Siswa
    function simpan_ekstra()
    {
        if ($this->session->userdata('id_level_user') == 4) return;

        $id_ekstra      = $this->input->post('id_ekstra'); // 0 jika Baru, >0 jika Update
        $nim            = $this->input->post('nim');
        $nama_ekstra    = trim($this->input->post('nama_ekstra'));
        $predikat       = $this->input->post('predikat');
        $keterangan     = trim($this->input->post('keterangan'));
        $id_tahun_aktif = get_tahun_akademik('id_tahun_akademik');

        $data_save = array(
            'nim'               => $nim,
            'nama_ekstra'       => $nama_ekstra,
            'predikat'          => $predikat,
            'keterangan'        => $keterangan,
            'id_tahun_akademik' => $id_tahun_aktif
        );

        if (!empty($id_ekstra) && $id_ekstra > 0) {
            // PROSES UPDATE
            $this->db->where('id_ekstra', $id_ekstra);
            $this->db->update('tbl_nilai_ekstra', $data_save);
        } else {
            // PROSES INSERT BARU
            $this->db->insert('tbl_nilai_ekstra', $data_save);
        }

        echo json_encode(array('status' => 'success'));
    }

    // FUNGSI AJAX: Hapus Ekstrakurikuler
    function hapus_ekstra()
    {
        if ($this->session->userdata('id_level_user') == 4) return;

        $id_ekstra = $this->input->post('id_ekstra');
        $this->db->delete('tbl_nilai_ekstra', array('id_ekstra' => $id_ekstra));
        echo json_encode(array('status' => 'success'));
    }

    function simpan_catatan() 
    {
        if ($this->session->userdata('id_level_user') == 4) return;

        $nim     = $this->input->post('nim');
        $catatan = trim($this->input->post('catatan')); 

        if (empty($catatan)) {
            $this->output->set_status_header(400);
            echo json_encode(array('status' => 'error', 'message' => 'Catatan wali kelas wajib diisi!'));
            return;
        }

        $cek = $this->db->get_where('tbl_nilai_catatan', array('nim' => $nim));
        
        if($cek->num_rows() > 0) {
            $this->db->where('nim', $nim);
            $this->db->update('tbl_nilai_catatan', array('catatan' => $catatan));
        } else {
            $this->db->insert('tbl_nilai_catatan', array('nim' => $nim, 'catatan' => $catatan));
        }

        echo json_encode(array('status' => 'success'));
    }

    function simpan_keterangan_mapel() 
    {
        if ($this->session->userdata('id_level_user') == 4) return;

        $nim        = $this->input->post('nim');
        $id_jadwal  = $this->input->post('id_jadwal');
        $keterangan = $this->input->post('keterangan');
        
        $cek = $this->db->get_where('tbl_nilai', array('nim' => $nim, 'id_jadwal' => $id_jadwal));
        
        if($cek->num_rows() > 0) {
            $this->db->where(array('nim' => $nim, 'id_jadwal' => $id_jadwal));
            $this->db->update('tbl_nilai', array('keterangan' => $keterangan));
        } else {
            $this->db->insert('tbl_nilai', array(
                'nim' => $nim, 
                'id_jadwal' => $id_jadwal, 
                'keterangan' => $keterangan,
                'nilai' => 0 
            ));
        }
    }

    function nilai_semester()
    {
        $id_level = $this->session->userdata('id_level_user');
        $nim = $this->uri->segment(3);

        if ($id_level != 1 && $id_level != 4) {
            $catatan_db = $this->db->get_where('tbl_nilai_catatan', array('nim' => $nim))->row_array();
            if (!$catatan_db || empty(trim($catatan_db['catatan']))) {
                die("Akses ditolak: Catatan wali kelas masih kosong. Harap lengkapi catatan terlebih dahulu.");
            }
        }

        $this->_generate_pdf_file($nim, 'I');
    }

    public function kirim_pdf_wa()
    {
        if ($this->session->userdata('id_level_user') == 4) {
            echo json_encode(array('status' => 'error', 'message' => 'Kepala Sekolah tidak memiliki akses pengiriman.'));
            return;
        }

        $nim = $this->input->post('nim');
        $id_tahun_aktif = get_tahun_akademik('id_tahun_akademik');
        $min_mapel_terisi = 6;

        $sqlCekNilai = "SELECT COUNT(tn.nilai) as total_terisi 
                        FROM tbl_jadwal tj
                        LEFT JOIN tbl_nilai tn ON tn.id_jadwal = tj.id_jadwal AND tn.nim = '$nim'
                        WHERE tj.id_tahun_akademik = '$id_tahun_aktif' 
                        AND tn.nilai > 0";
        $cek_nilai = $this->db->query($sqlCekNilai)->row_array();
        $total_terisi = $cek_nilai ? (int)$cek_nilai['total_terisi'] : 0;

        if ($total_terisi < $min_mapel_terisi) {
            echo json_encode(array(
                'status' => 'error', 
                'message' => 'Raport gagal dikirim! Nilai siswa baru terisi ' . $total_terisi . ' mata pelajaran. Minimal harus terisi ' . $min_mapel_terisi . ' mata pelajaran!'
            ));
            return;
        }

        $sqlSiswa = "SELECT ts.nama as nama_siswa, ts.nim, ts.no_hp, tk.nama_kelas
                     FROM tbl_riwayat_kelas as trk
                     JOIN tbl_siswa as ts ON ts.nim = trk.nim
                     JOIN tbl_kelas as tk ON tk.kd_kelas = trk.kd_kelas
                     WHERE trk.nim = '$nim' 
                     AND trk.id_tahun_akademik = '$id_tahun_aktif'";
        
        $siswa = $this->db->query($sqlSiswa)->row_array();

        if (!$siswa || empty($siswa['no_hp'])) {
            echo json_encode(array('status' => 'error', 'message' => 'Data siswa atau nomor WhatsApp belum terisi di database.'));
            return;
        }

        $folder_path = FCPATH . 'uploads/pdf_raport/';
        if (!is_dir($folder_path)) {
            mkdir($folder_path, 0777, true);
        }

        $filename = 'Raport_' . $siswa['nim'] . '_' . time() . '.pdf';
        $full_filepath = $folder_path . $filename;

        $this->_generate_pdf_file($nim, 'F', $full_filepath);

        $public_pdf_url = base_url('uploads/pdf_raport/' . $filename);

        $pesan  = "Yth. Orang Tua/Wali dari *" . $siswa['nama_siswa'] . "*,\n\n";
        $pesan .= "Berikut kami sampaikan Laporan Hasil Belajar (Raport) Semester ini.\n\n";
        $pesan .= "📄 *Silakan unduh dokumen Raport PDF melalui tautan di bawah ini:*\n";
        $pesan .= $public_pdf_url . "\n\n";
        $pesan .= "Terima Kasih.\n*SMA Negeri 13 Kota Jambi*";

        $this->load->library('fonnte');
        $response = $this->fonnte->send($siswa['no_hp'], $pesan);

        if (isset($response['status']) && $response['status'] == true) {
            echo json_encode(array('status' => 'success', 'message' => 'Notifikasi dan Link Raport PDF berhasil dikirim ke WhatsApp!'));
        } else {
            $err_msg = isset($response['reason']) ? $response['reason'] : 'Gagal mengirim pesan ke WhatsApp.';
            echo json_encode(array('status' => 'error', 'message' => $err_msg));
        }
    }

    private function _generate_pdf_file($nim, $mode = 'I', $filepath = '')
    {
        $id_tahun_aktif = get_tahun_akademik('id_tahun_akademik');

        if (!$id_tahun_aktif) {
            die("Error: Tahun akademik aktif tidak ditemukan.");
        }

        // Ambil data catatan wali kelas
        $catatan_db = $this->db->get_where('tbl_nilai_catatan', array('nim' => $nim))->row_array();
        $catatan_raport = ($catatan_db && !empty(trim($catatan_db['catatan']))) ? $catatan_db['catatan'] : 'Tingkatkan lagi prestasi belajar dan semangat selalu.';

        // Query Detail Data Siswa & Wali Kelas
        $sqlSiswa = "SELECT ts.nama as nama_siswa, ts.nim, tj.nama_jurusan, tk.nama_kelas, tk.kd_tingkatan, trk.kd_kelas,
                            tg.nama_guru as nama_walikelas
                     FROM tbl_riwayat_kelas as trk
                     JOIN tbl_siswa as ts ON ts.nim = trk.nim
                     JOIN tbl_kelas as tk ON tk.kd_kelas = trk.kd_kelas
                     JOIN tbl_jurusan as tj ON tk.kd_jurusan = tj.kd_jurusan 
                     LEFT JOIN tbl_walikelas as tw ON tw.kd_kelas = tk.kd_kelas AND tw.id_tahun_akademik = '$id_tahun_aktif'
                     LEFT JOIN tbl_guru as tg ON tw.id_guru = tg.id_guru
                     WHERE trk.nim = '$nim' 
                     AND trk.id_tahun_akademik = '$id_tahun_aktif'";
        
        $siswa = $this->db->query($sqlSiswa)->row_array();

        if (!$siswa) {
            die("Data siswa tidak ditemukan.");
        }

        // Data Kepala Sekolah
        $nama_kepsek = 'Ika Kartikasari, S.Si, M.Pd';
        $nip_kepsek  = '196601151988111001';

        $this->load->library('CFPDF');
        
        // UKURAN KERTAS F4 / FOLIO (215 mm x 330 mm)
        $pdf = new FPDF('P', 'mm', array(215, 330));
        $pdf->SetMargins(10, 8, 10);
        $pdf->SetAutoPageBreak(false);
        $pdf->AddPage();
        
        // --- HEADER INFORMASI SISWA ---
        $pdf->SetFont('Arial','',8.5);
        
        $pdf->Cell(25, 3.8, 'Nama', 0, 0, 'L');
        $pdf->Cell(70, 3.8, ': ' . strtoupper($siswa['nama_siswa']), 0, 0, 'L');
        $pdf->Cell(28, 3.8, 'Kelas', 0, 0, 'L');
        $pdf->Cell(67, 3.8, ': ' . $siswa['nama_kelas'], 0, 1, 'L');
        
        $pdf->Cell(25, 3.8, 'NIS/NISN', 0, 0, 'L');
        $pdf->Cell(70, 3.8, ': ' . $siswa['nim'], 0, 0, 'L');
        $pdf->Cell(28, 3.8, 'Fase', 0, 0, 'L');
        $pdf->Cell(67, 3.8, ': E', 0, 1, 'L');
        
        $pdf->Cell(25, 3.8, 'Nama Sekolah', 0, 0, 'L');
        $pdf->Cell(70, 3.8, ': SMAN 13 KOTA JAMBI', 0, 0, 'L');
        $pdf->Cell(28, 3.8, 'Semester', 0, 0, 'L');
        $pdf->Cell(67, 3.8, ': ' . get_tahun_akademik('semester'), 0, 1, 'L');
        
        $pdf->Cell(25, 3.8, 'Alamat', 0, 0, 'L');
        $pdf->Cell(70, 3.8, ': Jln. Sersan Udara Syawal Rt.03 No.104', 0, 0, 'L');
        $pdf->Cell(28, 3.8, 'Tahun Pelajaran', 0, 0, 'L');
        $pdf->Cell(67, 3.8, ': ' . get_tahun_akademik('tahun_akademik'), 0, 1, 'L');
        
        $pdf->Cell(25, 3.8, '', 0, 0, 'L');
        $pdf->Cell(70, 3.8, '  Talang Bakung', 0, 1, 'L');

        // --- TAMBAHAN: GARIS PEMBATAS ATAS LAPORAN ---
        $pdf->Ln(3);
        $pdf->SetLineWidth(0.3); // Ketebalan garis
        $pdf->Line(10, $pdf->GetY(), 205, $pdf->GetY()); // Garis horizontal dari kiri ke kanan (195mm)
        $pdf->Ln(2);

        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(195, 5, 'LAPORAN HASIL BELAJAR', 0, 1, 'C');
        $pdf->Ln(1.5);

        // --- TABEL NILAI UTAMA (195mm) ---
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetFont('Arial','B',8);
        
        $w_no = 8;
        $w_mapel = 48;
        $w_nilai = 18;
        $w_capaian = 121;

        $pdf->Cell($w_no, 5.5, 'No', 1, 0, 'C', true);
        $pdf->Cell($w_mapel, 5.5, 'Mata Pelajaran', 1, 0, 'C', true);
        $pdf->Cell($w_nilai, 5.5, 'Nilai Akhir', 1, 0, 'C', true);
        $pdf->Cell($w_capaian, 5.5, 'Capaian Kompetensi', 1, 1, 'C', true);

        $pdf->SetFont('Arial','',7.5);
        
        $kd_kelas_siswa = $siswa['kd_kelas'];
        $sqlMapel = "SELECT tm.kd_mapel, tm.nama_mapel, 
                            MAX(tn.nilai) AS nilai_angka,
                            MAX(tn.keterangan) AS ket_mapel
                     FROM tbl_jadwal as tj
                     JOIN tbl_mapel as tm ON tj.kd_mapel = tm.kd_mapel
                     LEFT JOIN tbl_nilai as tn ON tn.id_jadwal = tj.id_jadwal AND tn.nim = '$nim'
                     WHERE tj.kd_kelas = '$kd_kelas_siswa'
                     AND tj.id_tahun_akademik = '$id_tahun_aktif'
                     GROUP BY tm.kd_mapel, tm.nama_mapel";
        
        $mapel = $this->db->query($sqlMapel)->result();
        
        $no = 1;
        foreach ($mapel as $m) {
            $nilai = !empty($m->nilai_angka) ? $m->nilai_angka : 0;
            $nama_mapel_bersih = preg_replace('/ \d+$/', '', $m->nama_mapel);
            
            if (!empty($m->ket_mapel) && $m->ket_mapel != '-') {
                $capaian = $m->ket_mapel;
            } else {
                if ($nilai >= 85) {
                    $capaian = "Mencapai Kompetensi dengan sangat baik dalam memahami materi " . $nama_mapel_bersih . " secara menyeluruh.";
                } elseif ($nilai >= 75) {
                    $capaian = "Mencapai Kompetensi dengan baik dalam memahami materi dan tugas " . $nama_mapel_bersih . ".";
                } else {
                    $capaian = "Perlu peningkatan dan bimbingan lebih lanjut pada mata pelajaran " . $nama_mapel_bersih . ".";
                }
            }

            $cellWidth = $w_capaian - 2;
            $nb = $pdf->GetStringWidth($capaian);
            $lines = ceil($nb / $cellWidth);
            if ($lines == 0) $lines = 1;
            $h = max(5, $lines * 3.4);

            $startX = $pdf->GetX();
            $startY = $pdf->GetY();

            $pdf->Cell($w_no, $h, $no++, 1, 0, 'C');
            $pdf->Cell($w_mapel, $h, ' ' . $nama_mapel_bersih, 1, 0, 'L');
            $pdf->Cell($w_nilai, $h, $nilai, 1, 0, 'C');

            $x_multicell = $pdf->GetX();
            $y_multicell = $pdf->GetY();
            
            $pdf->MultiCell($w_capaian, 3.4, $capaian, 0, 'L');
            
            $pdf->SetXY($x_multicell, $y_multicell);
            $pdf->Cell($w_capaian, $h, '', 1, 0);

            $pdf->SetXY($startX, $startY + $h);
        }

        // --- EKSTRAKURIKULER ---
        $pdf->Ln(2);
        $pdf->SetFont('Arial','B',8);
        
        $pdf->Cell(8, 4.8, 'No', 1, 0, 'C', true);
        $pdf->Cell(52, 4.8, 'Kegiatan Ekstrakurikuler', 1, 0, 'C', true);
        $pdf->Cell(25, 4.8, 'Predikat', 1, 0, 'C', true);
        $pdf->Cell(110, 4.8, 'Keterangan', 1, 1, 'C', true);

        $pdf->SetFont('Arial','',7.5);

        $sqlEkstra = "SELECT nama_ekstra, predikat, keterangan 
                      FROM tbl_nilai_ekstra 
                      WHERE nim = '$nim' AND id_tahun_akademik = '$id_tahun_aktif'";
        $ekstra = $this->db->query($sqlEkstra)->result();

        if (!empty($ekstra)) {
            $no_e = 1;
            foreach ($ekstra as $e) {
                $pdf->Cell(8, 4.2, $no_e++, 1, 0, 'C');
                $pdf->Cell(52, 4.2, ' ' . $e->nama_ekstra, 1, 0, 'L');
                $pdf->Cell(25, 4.2, $e->predikat, 1, 0, 'C');
                $pdf->Cell(110, 4.2, ' ' . ($e->keterangan ? $e->keterangan : '-'), 1, 1, 'L');
            }
        } else {
            $pdf->Cell(8, 4.2, '-', 1, 0, 'C');
            $pdf->Cell(52, 4.2, ' -', 1, 0, 'L');
            $pdf->Cell(25, 4.2, '-', 1, 0, 'C');
            $pdf->Cell(110, 4.2, ' -', 1, 1, 'L');
        }

        // --- TABEL KETIDAKHADIRAN (ABSENSI) ---
        $sqlSakit = "SELECT COUNT(*) as total FROM tbl_absensi ta JOIN tbl_jadwal tj ON tj.id_jadwal = ta.id_jadwal WHERE ta.nim = '$nim' AND ta.status = 'Sakit' AND tj.id_tahun_akademik = '$id_tahun_aktif'";
        $sqlIzin  = "SELECT COUNT(*) as total FROM tbl_absensi ta JOIN tbl_jadwal tj ON tj.id_jadwal = ta.id_jadwal WHERE ta.nim = '$nim' AND ta.status = 'Izin' AND tj.id_tahun_akademik = '$id_tahun_aktif'";
        $sqlAlpa  = "SELECT COUNT(*) as total FROM tbl_absensi ta JOIN tbl_jadwal tj ON tj.id_jadwal = ta.id_jadwal WHERE ta.nim = '$nim' AND ta.status = 'Alpa' AND tj.id_tahun_akademik = '$id_tahun_aktif'";

        $sakit = $this->db->query($sqlSakit)->row()->total;
        $izin  = $this->db->query($sqlIzin)->row()->total;
        $alpa  = $this->db->query($sqlAlpa)->row()->total;

        $pdf->Ln(2);
        $pdf->SetFont('Arial','',7.5);
        $pdf->Cell(45, 4, 'Sakit', 1, 0, 'L');
        $pdf->Cell(35, 4, ': ' . $sakit . ' hari', 1, 1, 'L');
        
        $pdf->Cell(45, 4, 'Izin', 1, 0, 'L');
        $pdf->Cell(35, 4, ': ' . $izin . ' hari', 1, 1, 'L');
        
        $pdf->Cell(45, 4, 'Tanpa Keterangan', 1, 0, 'L');
        $pdf->Cell(35, 4, ': ' . $alpa . ' hari', 1, 1, 'L');

        // --- CATATAN WALI KELAS ---
        $pdf->Ln(2);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(195, 4, 'Catatan Wali Kelas', 0, 1, 'L');
        $pdf->SetFont('Arial','I',7.5);
        $pdf->MultiCell(195, 4, $catatan_raport, 1, 'L');

        // --- KETERANGAN KENAIKAN KELAS ---
        $semester_aktif = get_tahun_akademik('semester');
        if (strtolower($semester_aktif) == 'genap' || $semester_aktif == '2') {
            $pdf->Ln(2);
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(195, 5, 'Keterangan Kenaikan Kelas : Naik ke kelas XI', 1, 1, 'C', true);
        }

        // --- TANDA TANGAN 3 KOLOM ---
        $pdf->Ln(3);
        $pdf->SetFont('Arial','',8);

        $pdf->Cell(125);
        $pdf->Cell(70, 3.5, 'Jambi, ' . date('d F Y'), 0, 1, 'C');

        $pdf->Cell(60, 3.5, 'Mengetahui', 0, 0, 'C');
        $pdf->Cell(65);
        $pdf->Cell(70, 3.5, 'Wali Kelas,', 0, 1, 'C');
        
        $pdf->Cell(60, 3.5, 'Orang Tua/Wali,', 0, 1, 'C');

        $pdf->Ln(10);

        $nama_walikelas = !empty($siswa['nama_walikelas']) ? $siswa['nama_walikelas'] : 'Marcellya, S.Pd';

        $pdf->Cell(60, 3.5, '( ..................................... )', 0, 0, 'C');
        $pdf->Cell(65);
        $pdf->SetFont('Arial','BU',8);
        $pdf->Cell(70, 3.5, $nama_walikelas, 0, 1, 'C');
        
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(125);
        $pdf->Cell(70, 3.5, 'NIP. -', 0, 1, 'C');

        $pdf->Ln(1.5);
        $pdf->Cell(67.5);
        $pdf->Cell(60, 3.5, 'Mengetahui', 0, 1, 'C');
        $pdf->Cell(67.5);
        $pdf->Cell(60, 3.5, 'Kepala Sekolah,', 0, 1, 'C');

        $pdf->Ln(10);

        $pdf->Cell(67.5);
        $pdf->SetFont('Arial','BU',8);
        $pdf->Cell(60, 3.5, $nama_kepsek, 0, 1, 'C');
        
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(67.5);
        $pdf->Cell(60, 3.5, 'NIP. ' . $nip_kepsek, 0, 1, 'C');

        // --- FOOTER DOKUMEN DENGAN GARIS PEMBATAS ---
        $pdf->SetY(-10);
        $pdf->SetLineWidth(0.2);
        $pdf->Line(10, $pdf->GetY(), 205, $pdf->GetY()); // Garis tipis pembatas footer
        $pdf->SetY(-9);
        $pdf->SetFont('Arial','I',7);
        $pdf->Cell(135, 3.5, 'Fase E | ' . strtoupper($siswa['nama_siswa']) . ' | ' . $siswa['nim'], 0, 0, 'L');
        $pdf->Cell(60, 3.5, 'Halaman : 1', 0, 0, 'R');

        if (ob_get_length() > 0) {
            ob_clean(); 
        }
        
        if ($mode == 'F') {
            $pdf->Output('F', $filepath);
            return true;
        } else {
            $pdf->Output('I', 'Laporan_Nilai_' . $siswa['nim'] . '.pdf');
        }
    }
}