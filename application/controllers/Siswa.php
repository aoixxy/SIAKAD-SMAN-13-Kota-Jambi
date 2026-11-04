<?php

/**
 * @property CI_DB_query_builder $db
 * @property CI_Loader $load
 * @property CI_URI $uri
 * @property CI_Template $template
 * @property CI_Input $input
 * @property CI_Session $session
 * @property Model_siswa $model_siswa
 * @property Model_kelas $model_kelas
 * @property Model_upload $upload
 * @var CI_Controller $ci
 */

class Siswa extends CI_Controller
{
    private $filename = "import_data"; // nama file .csv

    function __construct()
    {
        parent::__construct();
        // Memastikan user sudah login
        if (!$this->session->userdata('id_level_user')) {
            redirect('auth');
        }

        $this->load->library('ssp');
        $this->load->model('model_siswa');
    }

    // ==========================================
    // 1. FITUR PROFIL SISWA (Akses Level 5)
    // ==========================================

    function profil()
    {
        $id_level = $this->session->userdata('id_level_user');
        $username = $this->session->userdata('username'); // Username adalah NIM

        if ($id_level == 5) {
            $data['siswa'] = $this->db->get_where('tbl_siswa', array('nim' => $username))->row_array();
            $this->template->load('template', 'siswa/profil', $data);
        } else {
            redirect('siswa');
        }
    }

    function update_profil()
    {
        $nim = $this->input->post('nim');
        $uploadFoto = null;

        if (!empty($_FILES['userfile']['name'])) {
            $uploadFoto = $this->upload_foto_siswa('siswa');
            if (!$uploadFoto) {
                redirect('siswa/profil');
                return;
            }
            $data['foto'] = $uploadFoto;

            // Update session FOTO agar sisi kiri & kanan langsung berubah
            $this->session->set_userdata('foto', $uploadFoto); 
        }

        $data['nama']          = $this->input->post('nama');
        $data['tempat_lahir']  = $this->input->post('tempat_lahir');
        $data['tanggal_lahir'] = $this->input->post('tanggal_lahir');
        $data['alamat']        = $this->input->post('alamat');
        $data['no_hp']         = $this->input->post('no_hp'); // PENAMBAHAN NO HP

        // Sinkronkan dengan session 'nama_lengkap' yang dipakai di template.php
        $this->session->set_userdata('nama_lengkap', $data['nama']); 

        // 1. Update ke tabel master siswa
        $this->db->where('nim', $nim);
        $this->db->update('tbl_siswa', $data);

        // 2. Sinkronkan ke tabel akun login (tbl_user)
        $data_user['nama_lengkap'] = $data['nama'];
        if (!empty($uploadFoto)) {
            $data_user['foto'] = $uploadFoto;
        }

        $this->db->where('username', $nim); 
        $this->db->update('tbl_user', $data_user);

        $this->session->set_flashdata('pesan', 'Biodata Anda berhasil diperbarui!');
        redirect('siswa/profil');
    }

    // ==========================================
    // 2. FITUR MANAJEMEN ADMIN (DataTables & CRUD)
    // ==========================================

    function index()
    {
        $this->template->load('template', 'siswa/view');
    }

    function data()
    {
        $id_level   = $this->session->userdata('id_level_user');
        $table      = 'tbl_siswa';
        $primaryKey = 'nim';

        $filter_kelas = $this->input->get('filter_kelas');
        $whereCustom = null;

        if (!empty($filter_kelas)) {
            $whereCustom = "kd_kelas = '$filter_kelas'";
        }

        $columns = array(
            array(
                'db' => 'nim', 
                'dt' => 'no', 
                'formatter' => function($d, $row) {
                    return ''; 
                }
            ),
            array(
                'db' => 'foto',
                'dt' => 'foto', 
                'formatter' => function ($d, $row) {
                    if (!empty($d) && file_exists('./uploads/siswa/' . $d)) {
                        $src_gambar = base_url() . "uploads/siswa/" . $d;
                    } else {
                        $src_gambar = base_url() . "assets/dist/img/user icon.png";
                    }
                    return "<img width='30px' src='" . $src_gambar . "' class='img-circle'>";
                }
            ),
            array('db' => 'nim', 'dt' => 'nim'),
            array('db' => 'nama', 'dt' => 'nama'),
            array(
                'db' => 'kd_kelas',
                'dt' => 'nama_kelas',
                'formatter' => function ($d, $row) {
                    $ci = &get_instance();
                    $query = $ci->db->get_where('tbl_kelas', array('kd_kelas' => $d))->row();
                    return $query ? $query->nama_kelas : $d;
                }
            ),
            array('db' => 'tempat_lahir', 'dt' => 'tempat_lahir'),
            array('db' => 'tanggal_lahir', 'dt' => 'tanggal_lahir')
        );

        if ($id_level != 4) {
            $columns[] = array(
                'db' => 'nim',
                'dt' => 'aksi',
                'formatter' => function ($d, $row) {
                    return anchor('siswa/edit/' . $d, '<i class="fa fa-edit"></i>', 'class="btn btn-xs btn-primary" title="Edit"') . ' 
                           ' . anchor('siswa/delete/' . $d, '<i class="fa fa-times fa-white"></i>', 'class="btn btn-xs btn-danger" onclick="return confirm(\'Yakin ingin menghapus data ini?\')" title="Delete"');
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
            SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, $whereCustom)
        );
    }

    function add()
    {
        if ($this->session->userdata('id_level_user') != 1) {
            redirect('siswa');
            return;
        }

        if (isset($_POST['submit'])) {
            $uploadFoto = (!empty($_FILES['userfile']['name'])) ? $this->upload_foto_siswa('siswa') : null;
            $this->model_siswa->save($uploadFoto);
            redirect('siswa');
        } else {
            $this->template->load('template', 'siswa/add');
        }
    }

    function edit()
    {
        if ($this->session->userdata('id_level_user') != 1) {
            redirect('siswa');
            return;
        }

        if (isset($_POST['submit'])) {
            $uploadFoto = (!empty($_FILES['userfile']['name'])) ? $this->upload_foto_siswa('siswa') : $this->input->post('foto_lama');
            $this->model_siswa->update($uploadFoto);
            redirect('siswa');
        } else {
            $nim           = $this->uri->segment(3);
            $data['siswa'] = $this->db->get_where('tbl_siswa', array('nim' => $nim))->row_array();
            $this->template->load('template', 'siswa/edit', $data);
        }
    }

    function delete()
    {
        if ($this->session->userdata('id_level_user') != 1) {
            redirect('siswa');
            return;
        }

        $nim = $this->uri->segment(3);
        if (!empty($nim)) {
            $this->db->trans_start();
            $this->db->where('nim', $nim)->delete('tbl_nilai');
            $this->db->where('nim', $nim)->delete('tbl_riwayat_kelas');
            $this->db->where('nim', $nim)->delete('tbl_absensi');
            $this->db->where('nim', $nim)->delete('tbl_siswa');
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('msg', 'Gagal menghapus data!');
            } else {
                $this->session->set_flashdata('msg', 'Data berhasil dihapus!');
            }
        }
        redirect('siswa');
    }

    function siswa_aktif()
    {
        $this->template->load('template', 'siswa/siswa_aktif');
    }

    function loadDataSiswa()
    {
        $kelas    = $this->input->get('kd_kelas');
        $id_tahun = $this->input->get('id_tahun_akademik');

        echo "<table class='table table-striped table-bordered table-hover table-full-width' id='mytable'>
                <thead>
                    <tr>
                        <th width=100 class='text-center'>NIM</th>
                        <th>NAMA</th>
                        <th class='text-center'>NILAI</th>
                    </tr>
                </thead>
                <tbody>";

        $this->db->select('s.nim, s.nama');
        $this->db->from('tbl_siswa as s');
        $this->db->join('tbl_riwayat_kelas as r', 's.nim = r.nim');

        if (!empty($kelas)) { $this->db->where('r.kd_kelas', $kelas); }
        if (!empty($id_tahun)) { $this->db->where('r.id_tahun_akademik', $id_tahun); }

        $siswa = $this->db->get()->result();

        foreach ($siswa as $row) {
            echo "<tr>
                    <td class='text-center'>$row->nim</td>
                    <td>$row->nama</td>
                    <td class='text-center'>" . anchor('siswa/nilai_siswa/' . $row->nim, '<span class="btn btn-xs btn-info">Lihat</span>') . "</td>
                  </tr>";
        }
        echo "</tbody></table>
              <script> $('#mytable').DataTable(); </script>";
    }

    function nilai_siswa()
    {
        $nim = $this->uri->segment(3);
        $id_tahun_filter = $this->input->get('id_tahun_akademik');
        
        $periode_aktif = $this->db->get_where('tbl_tahun_akademik', array('is_aktif' => 'Y'))->row();
        $id_tahun_pilihan = $id_tahun_filter ? $id_tahun_filter : (isset($periode_aktif) ? $periode_aktif->id_tahun_akademik : 0);

        $data['id_tahun_pilihan'] = $id_tahun_pilihan;
        $data['nim'] = $nim;
        $data['siswa'] = $this->db->get_where('tbl_siswa', array('nim' => $nim))->row_array();
        $data['info_tahun'] = $this->db->get_where('tbl_tahun_akademik', array('id_tahun_akademik' => $id_tahun_pilihan))->row();

        $sql = "SELECT tm.nama_mapel, tn.nilai, tta.tahun_akademik, tta.semester
                FROM tbl_nilai AS tn
                JOIN tbl_jadwal AS tj ON tn.id_jadwal = tj.id_jadwal
                JOIN tbl_mapel AS tm ON tj.kd_mapel = tm.kd_mapel
                JOIN tbl_tahun_akademik AS tta ON tj.id_tahun_akademik = tta.id_tahun_akademik
                WHERE tn.nim = '$nim' AND tj.id_tahun_akademik = '$id_tahun_pilihan'";
                
        $data['nilai_siswa'] = $this->db->query($sql);
        $this->template->load('template', 'siswa/nilai', $data);
    }

    function export_excel()
    {
        $this->load->library('CPHP_excel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'NIM');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'SISWA');

        $kelas    = $this->input->post('jurusan'); 
        $id_tahun = $this->input->post('id_tahun_akademik');

        $this->db->select('s.nim, s.nama');
        $this->db->from('tbl_siswa as s');
        $this->db->join('tbl_riwayat_kelas as r', 's.nim = r.nim');
        $this->db->where('r.kd_kelas', $kelas);
        $this->db->where('r.id_tahun_akademik', $id_tahun);

        $siswa = $this->db->get()->result();

        $no = 2;
        foreach ($siswa as $row) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $no, $row->nim);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $no, $row->nama);
            $no++;
        }

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $filename = "data-siswa-" . date('Ymd') . ".xlsx";
        $objWriter->save($filename);

        $this->load->helper('download');
        force_download($filename, NULL);
    }

    function form(){
        if ($this->session->userdata('id_level_user') != 1) {
            redirect('siswa');
            return;
        }

        $data = array(); 
        if(isset($_POST['preview'])){
          $uploadcsv = $this->model_siswa->upload_csv($this->filename);
          if($uploadcsv['result'] == "success"){
            include APPPATH.'third_party/PHPExcel/PHPExcel.php';
            $csvreader = PHPExcel_IOFactory::createReader('CSV');
            $loadcsv = $csvreader->load('csv/'.$this->filename.'.csv');
            $sheet = $loadcsv->getActiveSheet()->getRowIterator();
            $data['sheet'] = $sheet; 
          }else{
            $data['upload_error'] = $uploadcsv['error'];
          }
        }
        $this->load->view('siswa/form', $data);
    }

    function import(){
        if ($this->session->userdata('id_level_user') != 1) {
            redirect('siswa');
            return;
        }

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        $csvreader = PHPExcel_IOFactory::createReader('CSV');
        $loadcsv = $csvreader->load('csv/'.$this->filename.'.csv');
        $sheet = $loadcsv->getActiveSheet()->getRowIterator();
        $data = [];
        $numrow = 1;
        foreach($sheet as $row){
          if($numrow > 1){
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            $get = array();
            foreach ($cellIterator as $cell) {
              array_push($get, $cell->getValue());
            }
            array_push($data, [
              'nim'=>$get[0],
              'nama'=>$get[1],
              'tanggal_lahir'=>$get[2],
              'tempat_lahir'=>$get[3],
            ]);
          }
          $numrow++;
        }
        $this->model_siswa->insert_multiple($data);
        redirect("Siswa");
    }

    function naik_kelas()
    {
        if ($this->session->userdata('id_level_user') != 1) {
            redirect('siswa');
            return;
        }
        $this->template->load('template', 'siswa/naik_kelas');
    }

    function Naiksiswa()
    {
        $kelas = $_GET['kd_kelas'];
        echo "<table class='table table-striped table-bordered table-hover'>
                <thead>
                    <tr>
                        <th width='50' class='text-center'><input type='checkbox' id='check-all'></th>
                        <th width=100 class='text-center'>NIM</th>
                        <th>NAMA</th>
                    </tr>
                </thead>
                <tbody>";

        $this->db->where('kd_kelas', $kelas);
        $siswa = $this->db->get('tbl_siswa');
        foreach ($siswa->result() as $row) {
            echo "<tr>
                    <td class='text-center'><input type='checkbox' name='pilih_siswa[]' value='$row->nim' class='check-item'></td>
                    <td class='text-center'>$row->nim</td>
                    <td>$row->nama</td>
                  </tr>";
        }
        echo "</tbody></table>
              <script>
                $('#check-all').click(function(){ $('.check-item').prop('checked', this.checked); });
              </script>";
    }

    function aksi_naikkelas()
    {
        if ($this->session->userdata('id_level_user') != 1) {
            redirect('siswa');
            return;
        }

        $pilih_siswa  = $this->input->post('pilih_siswa');
        $kelas_tujuan = $this->input->post('kelas_tujuan');
        $tahun_aktif  = $this->db->get_where('tbl_tahun_akademik', array('is_aktif' => 'Y'))->row();

        if (empty($pilih_siswa) || empty($kelas_tujuan)) {
            $this->session->set_flashdata('error', 'Pilih siswa dan kelas tujuan!');
            redirect('siswa/naik_kelas');
            return;
        }

        $this->db->trans_start();
        foreach ($pilih_siswa as $nim) {
            $this->db->where('nim', $nim)->update('tbl_siswa', array('kd_kelas' => $kelas_tujuan));
            $this->db->insert('tbl_riwayat_kelas', array(
                'nim'               => $nim,
                'kd_kelas'          => $kelas_tujuan,
                'id_tahun_akademik' => $tahun_aktif->id_tahun_akademik,
                'semester'          => $tahun_aktif->semester
            ));
        }
        $this->db->trans_complete();

        $this->session->set_flashdata('msg', 'Proses kenaikan kelas berhasil!');
        redirect('siswa');
    }

    function upload_foto_siswa($folder)
    {
        $config['upload_path']   = './uploads/' . $folder . '/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']      = 1024;
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('userfile')) {
            $this->session->set_flashdata('error_upload', $this->upload->display_errors('', ''));
            return false;
        } else {
            $upload = $this->upload->data();
            return $upload['file_name'];
        }
    }
}