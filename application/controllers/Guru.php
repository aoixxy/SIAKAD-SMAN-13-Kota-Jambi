<?php

/**
 * @property CI_DB_query_builder $db
 * @property CI_Loader $load
 * @property CI_URI $uri
 * @property CI_Template $template
 * @property CI_Input $input
 * @property CI_Session $session
 * @property Model_user $model_user
 * @property Model_guru $model_guru
 */

class Guru extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    // Memastikan user sudah login sebelum mengakses menu ini
    if (!$this->session->userdata('id_level_user')) {
        redirect('auth');
    }
    
    $this->load->library('ssp');
    $this->load->model('model_guru');
  }

  function index()
  {
    $this->template->load('template', 'guru/view');
  }

  function data()
  {
    $id_level   = $this->session->userdata('id_level_user');
    $table      = 'tbl_guru';
    $primaryKey = 'id_guru';
    
    // Mapping kolom utama dasar
    $columns    = array(
      array('db' => 'id_guru', 'dt' => 'id_guru'),
      array('db' => 'nuptk', 'dt' => 'nuptk'),
      array('db' => 'nama_guru', 'dt' => 'nama_guru'),
      array(
        'db' => 'gender',
        'dt' => 'gender',
        'formatter' => function ($d) {
          return $d == 'P' ? 'Pria' : 'Wanita';
        }
      )
    );

    // Kunci DataTables: Kolom aksi hanya dibuat jika user BUKAN Kepala Sekolah (Level 4)
    if ($id_level != 4) {
        $columns[] = array(
          'db' => 'id_guru',
          'dt' => 'aksi',
          'formatter' => function ($d) {
            return anchor('guru/edit/' . $d, '<i class="fa fa-edit"></i>', 'class="btn btn-xs btn-primary" data-placement="top" title="Edit"') . ' 
                    ' . anchor('guru/delete/' . $d, '<i class="fa fa-times fa fa-white"></i>', 'class="btn btn-xs btn-danger" data-placement="top" title="Delete" onclick="return confirm(\'Yakin ingin menghapus data ini? Semua relasi jadwal akan dikosongkan.\')"');
          }
        );
    }

    $sql_details = array(
      'user' => $this->db->username,
      'pass' => $this->db->password,
      'db'   => $this->db->database,
      'host' => $this->db->hostname
    );

    echo json_encode(
      SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns)
    );
  }

  function add()
  {
    // Proteksi URL back-end: Selain Admin (Level 1), dilarang menambah data
    if ($this->session->userdata('id_level_user') != 1) {
        redirect('guru');
        return;
    }

    if (isset($_POST['submit'])) {
      $this->model_guru->save();
      redirect('guru');
    } else {
      $this->template->load('template', 'guru/add');
    }
  }

  function edit()
  {
    // Proteksi URL back-end: Selain Admin (Level 1), dilarang mengubah data
    if ($this->session->userdata('id_level_user') != 1) {
        redirect('guru');
        return;
    }

    if (isset($_POST['submit'])) {
      $this->model_guru->update();
      redirect('guru');
    } else {
      $id_guru     = $this->uri->segment(3);
      $data['guru']  = $this->db->get_where('tbl_guru', array('id_guru' => $id_guru))->row_array();
      $this->template->load('template', 'guru/edit', $data);
    }
  }

  function delete()
  {
    // Proteksi URL back-end: Selain Admin (Level 1), dilarang menghapus data
    if ($this->session->userdata('id_level_user') != 1) {
        redirect('guru');
        return;
    }

    $id_guru = $this->uri->segment(3);
    if (!empty($id_guru)) {
      // 1. Putuskan relasi di tbl_jadwal (Set id_guru menjadi 0 agar jadwal tidak hilang tapi gurunya kosong)
      $this->db->where('id_guru', $id_guru);
      $this->db->update('tbl_jadwal', array('id_guru' => 0));

      // 2. Putuskan relasi di tbl_walikelas (Set id_guru menjadi 0)
      $this->db->where('id_guru', $id_guru);
      $this->db->update('tbl_walikelas', array('id_guru' => 0));

      // 3. Setelah relasi aman, baru hapus data di tbl_guru
      $this->db->where('id_guru', $id_guru);
      $this->db->delete('tbl_guru');
    }
    redirect('guru');
  }
}