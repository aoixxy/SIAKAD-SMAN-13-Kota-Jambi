<?php

/**
 * @property CI_DB_query_builder $db
 * @property CI_Loader $load
 * @property CI_URI $uri
 * @property CI_Template $template
 * @property CI_Input $input
 * @property CI_Session $session
 */

class Tampilan_utama extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Pastikan user sudah login
        if (!$this->session->userdata('id_level_user')) {
            redirect('welcome');
        }
    }

    public function index()
    {
        $id_level = $this->session->userdata('id_level_user');
        $username = $this->session->userdata('username'); // NIM Siswa

        // 1. Inisialisasi variabel default agar view tidak error
        $data['hadir'] = 0;
        $data['sakit'] = 0;
        $data['izin']  = 0;
        $data['alpa']  = 0;
        $data['tahun_akademik'] = '';
        $data['semester']       = '';

        if ($id_level == 5) { 
            // --- LOGIKA KHUSUS SISWA ---
            
            // Mengambil info Tahun Akademik Aktif
            $tahun_aktif = $this->db->get_where('tbl_tahun_akademik', array('is_aktif' => 'Y'))->row_array();
            if ($tahun_aktif) {
                $data['tahun_akademik'] = $tahun_aktif['tahun_akademik'];
                $data['semester']       = $tahun_aktif['semester'];
            }

            // Mengambil jumlah data absensi berdasarkan NIM dan Status
            $data['hadir'] = $this->db->get_where('tbl_absensi', array('nim' => $username, 'status' => 'Hadir'))->num_rows();
            $data['sakit'] = $this->db->get_where('tbl_absensi', array('nim' => $username, 'status' => 'Sakit'))->num_rows();
            $data['izin']  = $this->db->get_where('tbl_absensi', array('nim' => $username, 'status' => 'Izin'))->num_rows();
            $data['alpa']  = $this->db->get_where('tbl_absensi', array('nim' => $username, 'status' => 'Alpa'))->num_rows();

        } else {
            // --- LOGIKA KHUSUS ADMIN / GURU ---
            
            $data['user']['hasil']    = $this->db->count_all_results('tbl_user');
            $data['siswa']['hasil']   = $this->db->count_all_results('tbl_siswa');
            $data['guru']['hasil']    = $this->db->count_all_results('tbl_guru');
            $data['ruangan']['hasil'] = $this->db->count_all_results('tbl_ruangan');
        }

        // Kirim semua data ke view dashboard
        $this->template->load('template', 'dashboard', $data);
    }
}