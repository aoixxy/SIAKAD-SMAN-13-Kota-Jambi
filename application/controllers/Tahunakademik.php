<?php

/**
    * @property CI_DB_builder $db
    * @property CI_Loader $load
    * @property CI_URI $uri
    * @property CI_Template $template
    * @property CI_Input $input
    * @property CI_Session $session
    * @property Model_tahunakademik $model_tahunakademik
    * @property Model_walikelas $model_walikelas
 */

    class Tahunakademik extends CI_Controller
    {

        function __construct()
        {
            parent::__construct();
            //checkAksesModule();
            $this->load->library('ssp');
            $this->load->model('model_tahunakademik');
        }

        function data()
        {
            // nama table
            $table      = 'tbl_tahun_akademik';
            // nama PK
            $primaryKey = 'id_tahun_akademik';
            // list field yang mau ditampilkan
            $columns    = array(
                array('db' => 'id_tahun_akademik', 'dt' => 'id_tahun_akademik'),
                array('db' => 'tahun_akademik', 'dt' => 'tahun_akademik'),
                // Menampilkan kolom semester di datatable
                array('db' => 'semester', 'dt' => 'semester'),
                array(
                    'db' => 'is_aktif',
                    'dt' => 'is_aktif',
                    'formatter' => function($d) {
                        // strtoupper & trim digunakan agar sinkron dengan database (case-insensitive)
                        return strtoupper(trim($d)) == 'Y' ? 'Aktif' : 'Tidak Aktif';
                    }
                ),
                //untuk menampilkan aksi(edit/delete dengan parameter kode tahunakademik)
                array(
                      'db' => 'id_tahun_akademik',
                      'dt' => 'aksi',
                      'formatter' => function($d) {
                            return anchor('tahunakademik/aktif/'.$d, 'Aktifkan', 'class="btn btn-xs bg-orange" data-placement="top" title="Aktif"').'
                            '.anchor('tahunakademik/edit/'.$d, '<i class="fa fa-edit"></i>', 'class="btn btn-xs btn-primary" data-placement="top" title="Edit"').'
                            '.anchor('tahunakademik/delete/'.$d, '<i class="fa fa-times fa fa-white"></i>', 'class="btn btn-xs btn-danger" data-placement="top" title="Delete"');
                    }
                )
            );

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

        function index()
        {
            $this->template->load('template', 'tahunakademik/view');
        }

        function add()
        {
            if (isset($_POST['submit'])) {
                // Memanggil fungsi save yang ada di Model_tahunakademik
                $this->model_tahunakademik->save();

                // Mengambil id tahun akademik terakhir yang diinputkan
                $idTahunAkademik = $this->db->insert_id();

                $this->load->model('model_walikelas');
                // Insert data dummy walikelas secara otomatis
                $this->model_walikelas->insert_walikelas($idTahunAkademik);
                redirect('tahunakademik');
            } else {
                $this->template->load('template', 'tahunakademik/add');
            }
        }

        function edit()
        {
            if (isset($_POST['submit'])) {
                    $this->model_tahunakademik->update();
                    redirect('tahunakademik');
                } else {
                    $id_tahunakademik       = $this->uri->segment(3);
                    $data['tahunakademik']  = $this->db->get_where('tbl_tahun_akademik', array('id_tahun_akademik' => $id_tahunakademik))->row_array();
                    $this->template->load('template', 'tahunakademik/edit', $data);
                }
            }

        function delete()
		{
			$id_tahunakademik = $this->uri->segment(3);
			if (!empty($id_tahunakademik)) {
				// 1. Hapus dulu data di tabel anak (tbl_walikelas) yang menggunakan id ini
				$this->db->where('id_tahun_akademik', $id_tahunakademik);
				$this->db->delete('tbl_walikelas');

				// 2. Baru hapus data di tabel utama (tbl_tahun_akademik)
				$this->db->where('id_tahun_akademik', $id_tahunakademik);
				$this->db->delete('tbl_tahun_akademik');
			}
			redirect('tahunakademik');
		}

        function aktif()
        {
            $id_tahunakademik = $this->uri->segment(3);
            
            // Set semua tahun akademik menjadi tidak aktif dulu
            $off_all = "UPDATE tbl_tahun_akademik SET is_aktif = 'N' WHERE is_aktif = 'Y'";
            $this->db->query($off_all);
            
            // Set tahun akademik yang dipilih menjadi aktif
            $on = "UPDATE tbl_tahun_akademik SET is_aktif = 'Y' WHERE id_tahun_akademik = '$id_tahunakademik'";
            $this->db->query($on);
            
            redirect('tahunakademik');
        }

    }

?>