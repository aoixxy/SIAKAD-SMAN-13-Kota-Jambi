<?php

/**
 * @property CI_DB_query_builder $db
 * @property CI_Loader $load
 * @property CI_URI $uri
 * @property CI_Template $template
 * @property CI_Input $input
 * @property Model_user $model_user
 * @property Model_guru $model_guru
 * @property CI_Session $session
 */

class Absensi extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('form');
        if (!$this->session->userdata('id_level_user')) {
            redirect('auth');
        }
    }

    function index() {
        $id_level = $this->session->userdata('id_level_user');
        $id_guru  = $this->session->userdata('id_guru'); 

        // 1. Ambil Periode Aktif
        $periode_aktif = $this->db->get_where('tbl_tahun_akademik', array('is_aktif' => 'Y'))->row();
        $data['tahun_akademik'] = isset($periode_aktif) ? $periode_aktif->tahun_akademik : '-';
        $data['semester']       = isset($periode_aktif) ? $periode_aktif->semester : '-';
        $id_tahun_aktif         = isset($periode_aktif) ? $periode_aktif->id_tahun_akademik : 0;

        // Tangkap inputan filter dari browser (baik dari list_jadwal maupun view_siswa)
        $filter_mapel = $this->input->get('filter_mapel');
        $filter_kelas = $this->input->get('filter_kelas');

        // PERBAIKAN: Jika filter_kelas kosong saat pertama kali menu dibuka, paksa ke kelas '10-E1'
        if (empty($filter_kelas)) {
            $filter_kelas = '10-E1';
        }

        $data['selected_mapel'] = $filter_mapel;
        $data['selected_kelas'] = $filter_kelas;

        // Siapkan list master kelas dan mapel untuk dropdown filter Admin/Siswa
        $data['kelas_list'] = $this->db->get('tbl_kelas')->result();
        $data['mapel_list'] = $this->db->get('tbl_mapel')->result();

        // LOGIKA UNTUK ADMIN (1) DAN KEPALA SEKOLAH (4)
        if ($id_level == 1 || $id_level == 4) { 
            
            // Jika ada parameter filter_mapel=all, arahkan Admin ke halaman log rekap menyeluruh
            if ($filter_mapel == 'all' || !empty($filter_mapel)) {
                
                $this->db->select('ta.*, tm.nama_mapel, tg.nama_guru, tk.nama_kelas, ts.nama as nama_siswa');
                $this->db->from('tbl_absensi ta');
                $this->db->join('tbl_jadwal tj', 'ta.id_jadwal = tj.id_jadwal');
                $this->db->join('tbl_mapel tm', 'tj.kd_mapel = tm.kd_mapel');
                $this->db->join('tbl_guru tg', 'tj.id_guru = tg.id_guru');
                $this->db->join('tbl_kelas tk', 'tj.kd_kelas = tk.kd_kelas');
                $this->db->join('tbl_siswa ts', 'ta.nim = ts.nim');
                $this->db->where('tj.id_tahun_akademik', $id_tahun_aktif);

                if (!empty($filter_mapel) && $filter_mapel != 'all') {
                    $this->db->where('tj.kd_mapel', $filter_mapel);
                }
                if (!empty($filter_kelas)) {
                    $this->db->where('tj.kd_kelas', $filter_kelas);
                }

                $this->db->order_by('ta.tanggal', 'DESC');
                $data['riwayat'] = $this->db->get()->result();

                // Load halaman Rekap Log Absensi
                $this->template->load('template', 'absensi/view_siswa', $data);

            } else {
                // DEFAULT ADMIN & KEPSEK: Menampilkan filter Jadwal Pelajaran Utama dengan GROUP BY
                $sql = "SELECT MIN(tj.id_jadwal) AS id_jadwal, tju.nama_jurusan, ttk.nama_tingkatan, tm.nama_mapel, 
                               MIN(tj.jam) AS jam_mulai, MAX(tj.jam) AS jam_selesai, 
                               tr.nama_ruangan, tj.hari, tj.semester, tk.nama_kelas, tg.nama_guru
                        FROM tbl_jadwal AS tj
                        LEFT JOIN tbl_jurusan AS tju ON tj.kd_jurusan = tju.kd_jurusan
                        LEFT JOIN tbl_ruangan AS tr ON tj.kd_ruangan = tr.kd_ruangan
                        LEFT JOIN tbl_mapel AS tm ON tj.kd_mapel = tm.kd_mapel
                        LEFT JOIN tbl_tingkatan_kelas AS ttk ON tj.kd_tingkatan = ttk.kd_tingkatan
                        LEFT JOIN tbl_kelas AS tk ON tj.kd_kelas = tk.kd_kelas
                        LEFT JOIN tbl_guru AS tg ON tj.id_guru = tg.id_guru
                        WHERE tj.id_tahun_akademik = ? ";

                $params = array($id_tahun_aktif);

                if (!empty($filter_kelas)) {
                    $sql .= " AND tj.kd_kelas = ? ";
                    $params[] = $filter_kelas;
                }

                $sql .= " GROUP BY tju.nama_jurusan, ttk.nama_tingkatan, tm.nama_mapel, tr.nama_ruangan, tj.hari, tj.semester, tk.nama_kelas, tg.nama_guru 
                          ORDER BY FIELD(tj.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), jam_mulai ASC";

                $data['jadwal'] = $this->db->query($sql, $params)->result();
                
                // Load halaman Pilih Jadwal
                $this->template->load('template', 'absensi/list_jadwal', $data);
            }

        } elseif ($id_level == 3) {
            // LOGIKA UNTUK GURU: Hanya melihat jadwal mengajar dirinya sendiri dengan GROUP BY
            $query = "SELECT MIN(tj.id_jadwal) AS id_jadwal, tju.nama_jurusan, ttk.nama_tingkatan, tm.nama_mapel, 
                             MIN(tj.jam) AS jam_mulai, MAX(tj.jam) AS jam_selesai, 
                             tr.nama_ruangan, tj.hari, tj.semester, tk.nama_kelas, tg.nama_guru 
                      FROM tbl_jadwal tj 
                      LEFT JOIN tbl_jurusan AS tju ON tj.kd_jurusan = tju.kd_jurusan
                      LEFT JOIN tbl_ruangan AS tr ON tj.kd_ruangan = tr.kd_ruangan
                      JOIN tbl_mapel tm ON tj.kd_mapel = tm.kd_mapel
                      LEFT JOIN tbl_tingkatan_kelas AS ttk ON tj.kd_tingkatan = ttk.kd_tingkatan
                      JOIN tbl_kelas tk ON tj.kd_kelas = tk.kd_kelas
                      JOIN tbl_guru tg ON tj.id_guru = tg.id_guru
                      WHERE tj.id_tahun_akademik = '$id_tahun_aktif'
                      AND tj.id_guru = '$id_guru'
                      GROUP BY tju.nama_jurusan, ttk.nama_tingkatan, tm.nama_mapel, tr.nama_ruangan, tj.hari, tj.semester, tk.nama_kelas, tg.nama_guru 
                      ORDER BY FIELD(tj.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), jam_mulai ASC";
            
            $data['jadwal'] = $this->db->query($query)->result();
            $this->template->load('template', 'absensi/list_jadwal', $data);

        } elseif ($id_level == 5) {
            // LOGIKA UNTUK SISWA: Melihat histori absen pribadinya sendiri
            $nim = $this->session->userdata('username');

            $this->db->select('ta.*, tm.nama_mapel, tg.nama_guru, tk.nama_kelas');
            $this->db->from('tbl_absensi ta');
            $this->db->join('tbl_jadwal tj', 'ta.id_jadwal = tj.id_jadwal');
            $this->db->join('tbl_mapel tm', 'tj.kd_mapel = tm.kd_mapel');
            $this->db->join('tbl_guru tg', 'tj.id_guru = tg.id_guru');
            $this->db->join('tbl_kelas tk', 'tj.kd_kelas = tk.kd_kelas');
            $this->db->where('ta.nim', $nim);
            $this->db->where('tj.id_tahun_akademik', $id_tahun_aktif);

            if (!empty($filter_mapel)) {
                $this->db->where('tj.kd_mapel', $filter_mapel);
            }

            $this->db->order_by('ta.tanggal', 'DESC');
            $data['riwayat'] = $this->db->get()->result(); 
            
            $this->template->load('template', 'absensi/view_siswa', $data);
        }
    }

    function input($id_jadwal) {
        $id_level = $this->session->userdata('id_level_user');
        $data['siswa'] = $this->db->query("SELECT ts.* FROM tbl_siswa ts 
                                           JOIN tbl_riwayat_kelas trk ON ts.nim = trk.nim
                                           JOIN tbl_jadwal tj ON trk.kd_kelas = tj.kd_kelas
                                           WHERE tj.id_jadwal = '$id_jadwal'")->result();
        
        $data['id_jadwal'] = $id_jadwal;
        $data['read_only'] = ($id_level == 4) ? true : false;
        $this->template->load('template', 'absensi/input_absen', $data);
    }

    function simpan() {
        $id_jadwal = $this->input->post('id_jadwal');
        $nims      = $this->input->post('nim'); 
        $tanggal   = date('Y-m-d');
        $periode_aktif = $this->db->get_where('tbl_tahun_akademik', array('is_aktif' => 'Y'))->row();
        
        if (!empty($nims)) {
            foreach ($nims as $nim) {
                $status = $this->input->post('status_' . $nim);
                if (!$status) { $status = 'Alpha'; } 

                $where_criteria = array(
                    'id_jadwal' => $id_jadwal,
                    'nim'       => $nim,
                    'tanggal'   => $tanggal
                );

                $check = $this->db->get_where('tbl_absensi', $where_criteria);
                if ($check->num_rows() > 0) {
                    $this->db->where($where_criteria);
                    $this->db->update('tbl_absensi', array('status' => $status));
                } else {
                    $data_save = array(
                        'id_jadwal'      => $id_jadwal,
                        'nim'            => $nim,
                        'tanggal'        => $tanggal,
                        'status'         => $status,
                        'tahun_akademik' => $periode_aktif->tahun_akademik ?? NULL,
                        'semester'       => $periode_aktif->semester ?? NULL
                    );
                    $this->db->insert('tbl_absensi', $data_save);
                }
            }
            $this->session->set_flashdata('msg', 'Absensi Berhasil Disimpan!');
        }
        redirect('absensi');
    }
}