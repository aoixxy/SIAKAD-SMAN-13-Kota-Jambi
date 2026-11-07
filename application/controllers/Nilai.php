<?php

/**
 * @property CI_DB_query_builder $db
 * @property CI_Loader $load
 * @property CI_URI $uri
 * @property CI_Template $template
 * @property CI_Input $input
 * @property CI_Session $session
 */

class Nilai extends CI_Controller
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
        $username = $this->session->userdata('username'); // NIM Siswa

        // 1. Ambil ID Tahun dari Filter GET
        $id_tahun_filter = $this->input->get('id_tahun_akademik');
        $periode_aktif = $this->db->get_where('tbl_tahun_akademik', array('is_aktif' => 'Y'))->row();
        
        // Jika tidak ada filter, gunakan tahun akademik yang sedang aktif
        $id_tahun_pilihan = $id_tahun_filter ? $id_tahun_filter : (isset($periode_aktif) ? $periode_aktif->id_tahun_akademik : 0);
        
        $data['id_tahun_pilihan'] = $id_tahun_pilihan;

        if ($id_level == 5) {
            // Logika untuk Siswa: Filter nilai berdasarkan NIM dan Tahun Akademik pilihan
            $sql = "SELECT tn.nilai, tm.nama_mapel, tg.nama_guru, tta.tahun_akademik, tta.semester
                    FROM tbl_nilai AS tn
                    JOIN tbl_jadwal AS tj ON tn.id_jadwal = tj.id_jadwal
                    JOIN tbl_mapel AS tm ON tj.kd_mapel = tm.kd_mapel
                    JOIN tbl_guru AS tg ON tj.id_guru = tg.id_guru
                    JOIN tbl_tahun_akademik AS tta ON tj.id_tahun_akademik = tta.id_tahun_akademik
                    WHERE tn.nim = '$username' AND tj.id_tahun_akademik = '$id_tahun_pilihan'";
            
            $data['nilai'] = $this->db->query($sql)->result();
            $this->template->load('template', 'nilai/view_siswa', $data);
        } else {
            // Perbaikan untuk Guru/Admin: Kelompokkan jadwal agar tombol input nilai hanya muncul 1 per mapel per kelas
            $filter_guru = ($id_level == 1 || $id_level == 4) ? "" : " AND tj.id_guru = '$id_guru'";
            
            // Menggunakan GROUP BY dan MIN(tj.id_jadwal) untuk mengambil 1 ID sebagai perwakilan input nilai
            $sql = "SELECT tj.kd_kelas, MIN(tj.id_jadwal) AS id_jadwal, tju.nama_jurusan, ttk.nama_tingkatan, tm.nama_mapel, 
                           MIN(tj.jam) AS jam_mulai, MAX(tj.jam) AS jam_selesai, 
                           tr.nama_ruangan, tj.hari, tj.semester 
                    FROM tbl_jadwal AS tj
                    JOIN tbl_jurusan AS tju ON tj.kd_jurusan = tju.kd_jurusan
                    JOIN tbl_ruangan AS tr ON tj.kd_ruangan = tr.kd_ruangan
                    JOIN tbl_mapel AS tm ON tj.kd_mapel = tm.kd_mapel
                    JOIN tbl_tingkatan_kelas AS ttk ON tj.kd_tingkatan = ttk.kd_tingkatan
                    WHERE tj.id_tahun_akademik = '$id_tahun_pilihan' $filter_guru
                    GROUP BY tj.kd_kelas, tju.nama_jurusan, ttk.nama_tingkatan, tm.nama_mapel, tr.nama_ruangan, tj.hari, tj.semester";

            $data['jadwal'] = $this->db->query($sql);
            $this->template->load('template', 'nilai/list_kelas', $data);
        }
    }

    function kelas()
    {
        $id_level = $this->session->userdata('id_level_user');
        if ($id_level == 5) { redirect('nilai'); }

        $id_jadwal = $this->uri->segment(3);
        $jadwal    = $this->db->get_where('tbl_jadwal', array('id_jadwal' => $id_jadwal))->row_array();
        $kd_kelas  = $jadwal['kd_kelas'];
        $id_tahun  = $jadwal['id_tahun_akademik'];

        // Info Header
        $sql_header = "SELECT tk.nama_kelas, tju.nama_jurusan, tm.nama_mapel, ttk.nama_tingkatan 
                       FROM tbl_jadwal AS tj
                       JOIN tbl_jurusan AS tju ON tj.kd_jurusan = tju.kd_jurusan 
                       JOIN tbl_kelas AS tk ON tj.kd_kelas = tk.kd_kelas 
                       JOIN tbl_mapel AS tm ON tj.kd_mapel = tm.kd_mapel 
                       JOIN tbl_tingkatan_kelas AS ttk ON tj.kd_tingkatan = ttk.kd_tingkatan 
                       WHERE tj.id_jadwal = $id_jadwal";

        // Query Utama: Mencari siswa di riwayat kelas periode aktif
        $sql_siswa = "SELECT ts.nim, ts.nama, tn.nilai
                      FROM tbl_riwayat_kelas AS trk
                      JOIN tbl_siswa AS ts ON trk.nim = ts.nim
                      LEFT JOIN tbl_nilai AS tn ON tn.nim = ts.nim AND tn.id_jadwal = $id_jadwal
                      WHERE trk.kd_kelas = '$kd_kelas' AND trk.id_tahun_akademik = '$id_tahun'";
        
        $siswa_result = $this->db->query($sql_siswa)->result();

        // Query Fallback: Jika riwayat tahun tersebut masih kosong, ambil dari list siswa di kelas tersebut secara umum
        if (empty($siswa_result)) {
            $sql_fallback = "SELECT DISTINCT ts.nim, ts.nama, 
                               (SELECT nilai FROM tbl_nilai WHERE nim = ts.nim AND id_jadwal = $id_jadwal LIMIT 1) as nilai
                               FROM tbl_siswa AS ts
                               JOIN tbl_riwayat_kelas AS trk ON ts.nim = trk.nim
                               WHERE trk.kd_kelas = '$kd_kelas'";
            $siswa_result = $this->db->query($sql_fallback)->result();
        }

        $data['kelas'] = $this->db->query($sql_header)->row_array();
        $data['siswa'] = $siswa_result;
        $data['id_jadwal'] = $id_jadwal;

        $this->template->load('template', 'nilai/form_nilai', $data);
    }

    function update_nilai()
    {
        $nim      = $this->input->get('nim');
        $idjadwal = $this->input->get('id_jadwal');
        $nilai    = $this->input->get('nilai');

        $where = array('nim' => $nim, 'id_jadwal' => $idjadwal);
        $check = $this->db->get_where('tbl_nilai', $where);

        if ($check->num_rows() > 0) {
            $this->db->where($where);
            $this->db->update('tbl_nilai', array('nilai' => $nilai));
            echo "Data Berhasil Diupdate";
        } else {
            $this->db->insert('tbl_nilai', array('nim' => $nim, 'id_jadwal' => $idjadwal, 'nilai' => $nilai));
            echo "Data Berhasil Disimpan";
        }
    }
}