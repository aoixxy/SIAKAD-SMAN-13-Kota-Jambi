<?php

/**
 * @property CI_DB_query_builder $db
 * @property CI_Loader $load
 * @property CI_URI $uri
 * @property CI_Template $template
 * @property CI_Input $input
 * @property CI_Session $session
 * @property Model_walikelas $model_walikelas
 */

class Walikelas extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('ssp');
        // Proteksi login
        if (!$this->session->userdata('id_level_user')) {
            redirect('auth');
        }
    }

    function index()
    {
        $this->template->load('template', 'walikelas/view');
    }

    function data()
    {
        $table      = 'view_walikelas';
        $primaryKey = 'id_walikelas';

        $columns    = array(
            array('db' => 'nama_kelas', 'dt' => 'nama_kelas'),
            array('db' => 'nama_jurusan', 'dt' => 'nama_jurusan'),
            array('db' => 'nama_tingkatan', 'dt' => 'nama_tingkatan'),
            array(
                'db' => 'id_walikelas',
                'dt' => 'nama_guru',
                'formatter' => function ($d) {
                    $walikelas = $this->db->get_where(
                        'tbl_walikelas',
                        array('id_walikelas' => $d)
                    )->row_array();

                    $id_guru_selected = isset($walikelas['id_guru']) ? $walikelas['id_guru'] : 0;

                    return cmb_dinamis(
                        'guru',
                        'tbl_guru',
                        'nama_guru',
                        'id_guru',
                        $id_guru_selected,
                        "id='guru$d' class='form-control select2' onchange='updateWalikelas($d)'"
                    );
                }
            ),
        );

        $sql_details = array(
            'user' => $this->db->username,
            'pass' => $this->db->password,
            'db'   => $this->db->database,
            'host' => $this->db->hostname
        );

        $tahun_aktif = get_tahun_akademik('tahun_akademik');
        $where = "tahun_akademik='$tahun_aktif'";

        echo json_encode(
            SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, $where)
        );
    }

    function update_walikelas()
    {
        $id_walikelas = $this->input->get('id_walikelas');
        $id_guru      = $this->input->get('id_guru');

        $this->db->where('id_walikelas', $id_walikelas);
        $this->db->update('tbl_walikelas', array('id_guru' => $id_guru));
    }

    function generate_walikelas()
    {
        $kelas = $this->db->get('tbl_kelas')->result();
        $id_tahun = get_tahun_akademik('id_tahun_akademik');

        foreach ($kelas as $k) {
            $cek = $this->db->get_where('tbl_walikelas', [
                'kd_kelas' => $k->kd_kelas,
                'id_tahun_akademik' => $id_tahun
            ]);

            if ($cek->num_rows() == 0) {
                $this->db->insert('tbl_walikelas', [
                    'kd_kelas'          => $k->kd_kelas,
                    'id_tahun_akademik' => $id_tahun,
                    'id_guru'           => 0 
                ]);
            }
        }

        $this->session->set_flashdata('success', 'Data seluruh walikelas berhasil digenerate otomatis untuk tahun ajaran aktif.');
        redirect('walikelas');
    }
}