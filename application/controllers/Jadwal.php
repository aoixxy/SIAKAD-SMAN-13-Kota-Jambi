<?php

/**
 * @property CI_DB_query_builder $db
 * @property CI_Loader $load
 * @property CI_URI $uri
 * @property CI_Template $template
 * @property CI_Input $input
 * @property CI_Session $session
 * @property Model_jadwal $model_jadwal
 */

class Jadwal extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('model_jadwal');
        // Proteksi login
        if (!$this->session->userdata('id_level_user')) {
            redirect('welcome');
        }
    }

    function index()
    {
        $id_level = $this->session->userdata('id_level_user');
        $username = $this->session->userdata('username');

        // --- 1. LOGIKA FILTER TAHUN AKADEMIK ---
        $data['list_tahun'] = $this->db->get('tbl_tahun_akademik')->result();
        
        $filter_tahun = $this->input->get('filter_tahun');
        if ($filter_tahun) {
            $periode_terpilih = $this->db->get_where('tbl_tahun_akademik', array('id_tahun_akademik' => $filter_tahun))->row_array();
        } else {
            $periode_terpilih = $this->db->get_where('tbl_tahun_akademik', array('is_aktif' => 'Y'))->row_array();
        }

        $id_tahun_selected = isset($periode_terpilih['id_tahun_akademik']) ? $periode_terpilih['id_tahun_akademik'] : 0;
        $data['id_tahun_selected'] = $id_tahun_selected;
        $data['tahun_akademik']    = isset($periode_terpilih['tahun_akademik']) ? $periode_terpilih['tahun_akademik'] : '-';
        $data['semester']          = isset($periode_terpilih['semester']) ? $periode_terpilih['semester'] : '-';

        // Tambahan: Mengambil ID Tahun Akademik Aktif (is_aktif = 'Y') untuk default selected view Admin
        $periode_aktif = $this->db->get_where('tbl_tahun_akademik', array('is_aktif' => 'Y'))->row();
        $data['id_tahun_aktif'] = isset($periode_aktif) ? $periode_aktif->id_tahun_akademik : $id_tahun_selected;

        // --- 2. LOGIKA BERDASARKAN LEVEL USER ---
        
        if ($id_level == 3) {
            // LEVEL GURU
            $id_guru = $this->session->userdata('id_guru');
            
            // PERBAIKAN: Membungkus tj.id_jadwal dengan MIN() agar lolos dari rule only_full_group_by
            $sql = "SELECT MIN(tj.id_jadwal) AS id_jadwal, tju.nama_jurusan, ttk.nama_tingkatan, tm.nama_mapel, 
                            MIN(tj.jam) AS jam_mulai, MAX(tj.jam) AS jam_selesai, 
                            tr.nama_ruangan, tj.hari, tj.semester, tk.nama_kelas, tta.tahun_akademik
                    FROM tbl_jadwal AS tj
                    LEFT JOIN tbl_jurusan AS tju ON tj.kd_jurusan = tju.kd_jurusan
                    LEFT JOIN tbl_ruangan AS tr ON tj.kd_ruangan = tr.kd_ruangan
                    LEFT JOIN tbl_mapel AS tm ON tj.kd_mapel = tm.kd_mapel
                    LEFT JOIN tbl_tingkatan_kelas AS ttk ON tj.kd_tingkatan = ttk.kd_tingkatan
                    LEFT JOIN tbl_kelas AS tk ON tj.kd_kelas = tk.kd_kelas
                    LEFT JOIN tbl_tahun_akademik AS tta ON tj.id_tahun_akademik = tta.id_tahun_akademik
                    WHERE tj.id_guru = ? 
                    AND tj.id_tahun_akademik = ?
                    GROUP BY tju.nama_jurusan, ttk.nama_tingkatan, tm.nama_mapel, tr.nama_ruangan, tj.hari, tj.semester, tk.nama_kelas, tta.tahun_akademik
                    ORDER BY FIELD(tj.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), jam_mulai ASC";
            
            $data['jadwal'] = $this->db->query($sql, array($id_guru, $id_tahun_selected));
            $this->template->load('template', 'jadwal/jadwal_ajar_guru', $data);

        } elseif ($id_level == 5) {
            // LEVEL SISWA
            $riwayat = $this->db->get_where('tbl_riwayat_kelas', array('nim' => $username, 'id_tahun_akademik' => $id_tahun_selected))->row();
            
            if (!$riwayat) {
                $riwayat = $this->db->order_by('id_tahun_akademik', 'DESC')->get_where('tbl_riwayat_kelas', array('nim' => $username))->row();
            }

            if ($riwayat) {
                $kd_kelas = $riwayat->kd_kelas;
                // PERUBAHAN SISWA: Menambahkan GROUP BY, MIN(jam), MAX(jam) untuk menyatukan baris jadwal siswa yang terpisah jamnya
                $sql = "SELECT tj.hari, MIN(tj.jam) AS jam_mulai, MAX(tj.jam) AS jam_selesai, 
                               tm.nama_mapel, tg.nama_guru, tr.nama_ruangan, tk.nama_kelas
                        FROM tbl_jadwal AS tj
                        LEFT JOIN tbl_mapel AS tm ON tj.kd_mapel = tm.kd_mapel
                        LEFT JOIN tbl_guru AS tg ON tj.id_guru = tg.id_guru
                        LEFT JOIN tbl_ruangan AS tr ON tj.kd_ruangan = tr.kd_ruangan
                        LEFT JOIN tbl_kelas AS tk ON tj.kd_kelas = tk.kd_kelas
                        WHERE tj.kd_kelas = ?
                        AND tj.id_tahun_akademik = ?
                        GROUP BY tj.hari, tm.kd_mapel, tm.nama_mapel, tg.nama_guru, tr.nama_ruangan, tk.nama_kelas
                        ORDER BY FIELD(tj.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), jam_mulai ASC";
                
                $data['jadwal'] = $this->db->query($sql, array($kd_kelas, $id_tahun_selected))->result();
            } else {
                $data['jadwal'] = array();
            }
            $this->template->load('template', 'jadwal/view_siswa', $data);

        } else {
            // ADMIN / KEPSEK
            $this->template->load('template', 'jadwal/view', $data);
        }
    }

    function tambah_manual()
    {
        if ($this->session->userdata('id_level_user') != 1) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger"><b>Gagal!</b> Anda tidak memiliki hak akses untuk menambah jadwal.</div>');
            redirect('jadwal');
            return;
        }

        $id_tahun  = $this->input->post('id_tahun_akademik');
        $ruangan   = $this->input->post('ruangan'); 

        $jurusan   = NULL;
        $tingkatan = NULL;
        $kelas     = NULL;
        $kd_ruangan_db = $ruangan;

        if (!empty($ruangan)) {
            // 1. Cari Kode Ruangan yang asli dari tbl_ruangan
            $cek_ruang_db = $this->db->get_where('tbl_ruangan', array('nama_ruangan' => $ruangan))->row();
            if ($cek_ruang_db) {
                $kd_ruangan_db = $cek_ruang_db->kd_ruangan; 
            }

            // 2. Ubah format kode ruangan pendek menjadi format Kode Kelas database
            $format_target_kelas = NULL;
            
            // JIKA KELAS 12
            if (substr($kd_ruangan_db, 0, 3) == 'XII' || substr($kd_ruangan_db, 0, 3) == 'xii') {
                if (preg_match('/XII-([A-Za-z0-9]+)/i', $ruangan, $matches)) {
                    $format_target_kelas = '12-' . $matches[1]; 
                } else {
                    $format_target_kelas = '12-F' . substr($kd_ruangan_db, 3); 
                }
            } 
            // JIKA KELAS 11
            elseif (substr($kd_ruangan_db, 0, 2) == 'XI' || substr($kd_ruangan_db, 0, 2) == 'xi') {
                $format_target_kelas = '11-' . substr($kd_ruangan_db, 2); 
            } 
            // JIKA KELAS 10
            elseif (substr($kd_ruangan_db, 0, 1) == 'X' || substr($kd_ruangan_db, 0, 1) == 'x') {
                $format_target_kelas = '10-' . substr($kd_ruangan_db, 1); 
            }

            // 3. Ambil data asli (Jurusan & Tingkatan) langsung dari tbl_kelas
            if (!empty($format_target_kelas)) {
                $cek_kelas_riil = $this->db->get_where('tbl_kelas', array('kd_kelas' => $format_target_kelas))->row();

                if ($cek_kelas_riil) {
                    $kelas     = $cek_kelas_riil->kd_kelas;     
                    $jurusan   = $cek_kelas_riil->kd_jurusan;   
                    $tingkatan = $cek_kelas_riil->kd_tingkatan; 
                }
            }
        }

        // Validasi pengaman
        if (empty($jurusan) || empty($id_tahun) || empty($ruangan) || empty($kelas)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger"><b>Gagal Menyimpan!</b> Sistem tidak dapat menemukan relasi Kelas yang valid untuk pilihan: ' . $ruangan . '</div>');
            redirect('jadwal');
            return;
        }

        // Cari Semester secara otomatis
        $tahun_data = $this->db->get_where('tbl_tahun_akademik', array('id_tahun_akademik' => $id_tahun))->row_array();
        $semester   = isset($tahun_data['semester']) ? $tahun_data['semester'] : 'Ganjil';

        $data = array(
            'kd_jurusan'         => $jurusan,
            'kd_tingkatan'       => $tingkatan,
            'kd_kelas'           => $kelas, 
            'kd_mapel'           => $this->input->post('mapel'),
            'id_guru'            => $this->input->post('guru'),
            'kd_ruangan'         => $kd_ruangan_db, 
            'hari'               => $this->input->post('hari'),
            'jam'                => $this->input->post('jam'),
            'id_tahun_akademik'  => $id_tahun,
            'semester'           => $semester
        );

        $insert = $this->db->insert('tbl_jadwal', $data);
        
        if($insert){
            $this->session->set_flashdata('pesan', '<div class="alert alert-success"><b>Berhasil!</b> Jadwal baru berhasil disimpan untuk Kelas ' . $kelas . '.</div>');
        } else {
            $error = $this->db->error();
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger"><b>Gagal DB:</b> ' . $error['message'] . '</div>');
        }
        
        redirect('jadwal');
    }

    function dataJadwal()
    {
        $id_level       = $this->session->userdata('id_level_user');
        $kode_jurusan   = $this->input->get('kd_jurusan');
        $kelas          = $this->input->get('kelas');
        $id_tahun       = $this->input->get('id_tahun_akademik'); 

        echo "<table class='table table-striped table-bordered table-hover table-full-width dataTable'>
                <thead>
                    <tr>
                        <th class='text-center' width='50px'>NO</th>
                        <th class='text-center'>HARI</th>
                        <th class='text-center'>JAM</th>
                        <th class='text-center'>MATA PELAJARAN</th>
                        <th class='text-center'>GURU</th>
                        <th class='text-center'>RUANGAN</th>";
        
        if ($id_level != 4) {
            echo "      <th class='text-center' width='100px'>AKSI</th>";
        }

        echo "      </tr>
                </thead>";

        // PERBAIKAN: Mengelompokkan data berdasarkan MIN dan MAX jam agar rentang jam mengajar menyatu
        $sql_datajadwal = "SELECT MIN(tj.id_jadwal) AS id_jadwal, tm.nama_mapel, tg.id_guru, tg.nama_guru, 
                                  tr.kd_ruangan, tr.nama_ruangan, tj.hari, MIN(tj.jam) AS jam_mulai, MAX(tj.jam) AS jam_selesai
                           FROM tbl_jadwal AS tj
                           LEFT JOIN tbl_mapel AS tm ON tj.kd_mapel = tm.kd_mapel
                           LEFT JOIN tbl_guru AS tg ON tj.id_guru = tg.id_guru
                           LEFT JOIN tbl_ruangan AS tr ON tj.kd_ruangan = tr.kd_ruangan
                           WHERE tj.kd_jurusan = ? 
                           AND tj.kd_kelas = ?
                           AND tj.id_tahun_akademik = ?
                           GROUP BY tm.kd_mapel, tm.nama_mapel, tg.id_guru, tg.nama_guru, tr.kd_ruangan, tr.nama_ruangan, tj.hari
                           ORDER BY FIELD(tj.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), jam_mulai ASC";
        
        $data_jadwal = $this->db->query($sql_datajadwal, array($kode_jurusan, $kelas, $id_tahun))->result();
        $no = 1;
        $jam_pelajaran = $this->model_jadwal->jamPelajaran();
        $hari_list = array('Senin'=>'Senin','Selasa'=>'Selasa','Rabu'=>'Rabu','Kamis'=>'Kamis','Jumat'=>'Jumat','Sabtu'=>'Sabtu');

        $total_kolom = ($id_level == 4) ? 6 : 7;

        if(empty($data_jadwal)){
            echo "<tr><td colspan='".$total_kolom."' class='text-center text-danger'>Data tidak ditemukan untuk periode/kelas ini.</td></tr>";
        } else {
            // Variabel bantuan untuk memantau perubahan hari
            $current_hari = "";

            foreach ($data_jadwal as $row) {
                $disabledAttr = ($id_level == 4) ? "disabled='disabled'" : "";
                
                // Atur format jam agar menyatu
                $jam_tampil = $row->jam_mulai;
                if ($row->jam_mulai != $row->jam_selesai) {
                    $jam_tampil = $row->jam_mulai . ' - ' . $row->jam_selesai;
                }

                // LOGIKA HARI TUNGGAL:
                // Jika hari pada baris ini sama dengan baris sebelumnya, kosongkan teksnya
                if ($row->hari == $current_hari) {
                    $hari_tampil = ""; 
                } else {
                    $hari_tampil = "<strong>" . $row->hari . "</strong>";
                    $current_hari = $row->hari; // Perbarui hari aktif
                }
                
                echo "<tr>
                        <td class='text-center'>$no</td>
                        <td class='text-center'>$hari_tampil</td>
                        <td class='text-center'>$jam_tampil</td>
                        <td>$row->nama_mapel</td>
                        <td>".cmb_dinamis('guru', 'tbl_guru', 'nama_guru', 'id_guru', $row->id_guru, "id='guru".$row->id_jadwal."' class='form-control' $disabledAttr onChange='updateGuru(".$row->id_jadwal.")'")."</td>
                        <td>".cmb_dinamis('ruangan', 'tbl_ruangan', 'nama_ruangan', 'kd_ruangan', $row->kd_ruangan, "id='ruangan".$row->id_jadwal."' class='form-control' $disabledAttr onChange='updateRuangan(".$row->id_jadwal.")'")."</td>";
                
                if ($id_level != 4) {
                    echo "<td class='text-center'>".anchor('jadwal/delete_dataJadwal/'.$row->id_jadwal, '<i class="fa fa-trash"></i>', 'class="btn btn-xs btn-danger" onclick="return confirm(\'Yakin ingin menghapus?\')"')."</td>";
                }

                echo "</tr>";
                $no++;
            }
        }
        echo "</table>";
    }

    function update_guru() {
        if ($this->session->userdata('id_level_user') != 1) return; 
        $id_jadwal = $this->input->get('id_jadwal');
        $id_guru   = $this->input->get('id_guru');
        $this->db->where('id_jadwal', $id_jadwal);
        $this->db->update('tbl_jadwal', array('id_guru' => $id_guru));
    }

    function cetak_jadwal()
    {
        $id_tahun  = $this->input->post('id_tahun_akademik');
        $jurusan   = $this->input->post('jurusan');
        $tingkatan = $this->input->post('tingkatan_kelas');

        $tahun_data = $this->db->get_where('tbl_tahun_akademik', array('id_tahun_akademik' => $id_tahun))->row_array();
        $nama_tahun = isset($tahun_data['tahun_akademik']) ? $tahun_data['tahun_akademik'] : '-';
        $semester   = isset($tahun_data['semester']) ? $tahun_data['semester'] : '-';

        $sql = "SELECT tj.*, tm.nama_mapel, tg.nama_guru, tr.nama_ruangan, tk.nama_kelas
                FROM tbl_jadwal AS tj
                LEFT JOIN tbl_mapel AS tm ON tj.kd_mapel = tm.kd_mapel
                LEFT JOIN tbl_guru AS tg ON tj.id_guru = tg.id_guru
                LEFT JOIN tbl_ruangan AS tr ON tj.kd_ruangan = tr.kd_ruangan
                LEFT JOIN tbl_kelas AS tk ON tj.kd_kelas = tk.kd_kelas
                WHERE tj.id_tahun_akademik = '$id_tahun'";
        
        if (!empty($jurusan)) { $sql .= " AND tj.kd_jurusan = '$jurusan'"; }
        if (!empty($tingkatan)) { $sql .= " AND tj.kd_tingkatan = '$tingkatan'"; }
        
        $sql .= " ORDER BY FIELD(tj.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), tj.jam ASC";
        $jadwal_cetak = $this->db->query($sql)->result();

        $this->load->library('CFPDF');
        $pdf = new FPDF('P','mm','A4');
        $pdf->AddPage();
        
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(190,7,'JADWAL PELAJARAN SEKOLAH',0,1,'C');
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(190,5,'TAHUN AKADEMIK: '.$nama_tahun.' ('.strtoupper($semester).')',0,1,'C');
        $pdf->Ln(5);
        $pdf->Line(10,25,200,25);
        $pdf->Ln(5);

        $w_no    = 10;
        $w_mapel = 55;
        $w_kelas = 30; 
        $w_guru  = 50;
        $w_hari  = 20;
        $w_jam   = 25;

        $pdf->SetFillColor(230, 230, 230);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell($w_no, 8, 'NO', 1, 0, 'C', true);
        $pdf->Cell($w_mapel, 8, 'MATA PELAJARAN', 1, 0, 'C', true);
        $pdf->Cell($w_kelas, 8, 'KELAS', 1, 0, 'C', true);
        $pdf->Cell($w_guru, 8, 'GURU PENGAJAR', 1, 0, 'C', true);
        $pdf->Cell($w_hari, 8, 'HARI', 1, 0, 'C', true);
        $pdf->Cell($w_jam, 8, 'JAM', 1, 1, 'C', true);
        
        $pdf->SetFont('Arial','',9);
        $no = 1;
        
        if(!empty($jadwal_cetak)){
            foreach ($jadwal_cetak as $row){
                
                $line_mapel = ceil($pdf->GetStringWidth($row->nama_mapel) / ($w_mapel - 2)); 
                $line_guru  = ceil($pdf->GetStringWidth($row->nama_guru) / ($w_guru - 2)); 
                $max_line   = max($line_mapel, $line_guru, 1);
                
                $cell_height = ($max_line > 1) ? ($max_line * 6) : 7; 

                $current_y = $pdf->GetY();
                $current_x = $pdf->GetX();

                $pdf->Cell($w_no, $cell_height, '', 1, 0, 'C');
                $pdf->Cell($w_mapel, $cell_height, '', 1, 0, 'L');
                $pdf->Cell($w_kelas, $cell_height, '', 1, 0, 'C');
                $pdf->Cell($w_guru, $cell_height, '', 1, 0, 'L');
                $pdf->Cell($w_hari, $cell_height, '', 1, 0, 'C');
                $pdf->Cell($w_jam, $cell_height, '', 1, 1, 'C');

                $pdf->SetXY($current_x, $current_y);
                $pdf->Cell($w_no, $cell_height, $no++, 0, 0, 'C');

                $pdf->SetXY($current_x + $w_no, $current_y + (($cell_height - ($line_mapel * 5)) / 2));
                $pdf->MultiCell($w_mapel, 5, $row->nama_mapel, 0, 'L');

                $pdf->SetXY($current_x + $w_no + $w_mapel, $current_y);
                $pdf->Cell($w_kelas, $cell_height, $row->nama_kelas, 0, 0, 'C');

                $pdf->SetXY($current_x + $w_no + $w_mapel + $w_kelas, $current_y + (($cell_height - ($line_guru * 5)) / 2));
                $pdf->MultiCell($w_guru, 5, $row->nama_guru, 0, 'L');

                $pdf->SetXY($current_x + $w_no + $w_mapel + $w_kelas + $w_guru, $current_y);
                $pdf->Cell($w_hari, $cell_height, $row->hari, 0, 0, 'C');

                $pdf->SetXY($current_x + $w_no + $w_mapel + $w_kelas + $w_guru + $w_hari, $current_y);
                $pdf->Cell($w_jam, $cell_height, $row->jam, 0, 0, 'C');

                $pdf->SetY($current_y + $cell_height);
            }
        } else {
            $pdf->Cell(190, 8, 'Tidak ada data jadwal pelajaran untuk filter ini.', 1, 1, 'C');
        }

        if (ob_get_length() > 0) { ob_clean(); }
        $pdf->Output('I', 'Jadwal_Pelajaran_Sekolah.pdf');
    }

    function update_ruangan() {
        if ($this->session->userdata('id_level_user') != 1) return; 
        $id_jadwal  = $this->input->get('id_jadwal');
        $kd_ruangan = $this->input->get('kd_ruangan');
        $this->db->where('id_jadwal', $id_jadwal);
        $this->db->update('tbl_jadwal', array('kd_ruangan' => $kd_ruangan));
    }

    function update_hari() {
        if ($this->session->userdata('id_level_user') != 1) return; 
        $id_jadwal = $this->input->get('id_jadwal');
        $hari      = $this->input->get('hari');
        $this->db->where('id_jadwal', $id_jadwal);
        $this->db->update('tbl_jadwal', array('hari' => $hari));
    }

    function update_jam() {
        if ($this->session->userdata('id_level_user') != 1) return; 
        $id_jadwal = $this->input->get('id_jadwal');
        $jam       = $this->input->get('jam');
        $this->db->where('id_jadwal', $id_jadwal);
        $this->db->update('tbl_jadwal', array('jam' => $jam));
    }

    function tampil_kelas()
    {
        $kd_jurusan   = $this->input->get('kd_jurusan');
        $kd_tingkatan = $this->input->get('kd_tingkatan');

        echo "<select id='kelas' name='kd_kelas' class='form-control' onChange='loadPelajaran()'>";
        echo "<option value=''>- Pilih Kelas -</option>";
        $this->db->where('kd_jurusan', $kd_jurusan);
        $this->db->where('kd_tingkatan', $kd_tingkatan);
        $kelas = $this->db->get('tbl_kelas');
        foreach ($kelas->result() as $row) {
            echo "<option value='$row->kd_kelas'>$row->nama_kelas</option>";
        }
        echo "</select>";
    }

    function delete_dataJadwal() {
        if ($this->session->userdata('id_level_user') != 1) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Akses ditolak. Hanya Admin yang dapat menghapus jadwal.</div>');
            redirect('jadwal');
            return;
        }

        $id_jadwal = $this->uri->segment(3);
        $this->db->where('id_jadwal', $id_jadwal);
        $this->db->delete('tbl_nilai');
        
        $this->db->where('id_jadwal', $id_jadwal);
        $this->db->delete('tbl_jadwal');
        
        $this->session->set_flashdata('pesan', '<div class="alert alert-success">Data Jadwal berhasil dihapus</div>');
        redirect('jadwal');
    }
}