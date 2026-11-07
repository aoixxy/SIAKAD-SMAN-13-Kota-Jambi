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

	class Auth extends CI_Controller
	{

		function __construct()
		{
			parent::__construct();
			$this->load->model('model_user');
			$this->load->model('model_guru');
		}
		
		function index()
		{

			$this->load->view('auth/login');
		}

		function check_login()
        {
            if (isset($_POST['submit'])) {

                $username = $this->input->post('username');
                $password = $this->input->post('password');

                $loginGuru = $this->model_guru->login($username, $password);
                $loginUser = $this->model_user->login($username, $password);

                // 🔥 1. CEK GURU DULU (Level 3)
                if (!empty($loginGuru)) {

                    $sessionGuru = array(
                        'id_user'       => $loginGuru['id_guru'],
                        'nama_lengkap'  => $loginGuru['nama_guru'],
                        'id_level_user' => 3,
                        'id_guru'       => $loginGuru['id_guru'],
                        'foto'          => isset($loginGuru['foto']) ? $loginGuru['foto'] : '' // Menjaga jika Guru punya foto
                    );

                    $this->session->set_userdata($sessionGuru);
                    redirect('tampilan_utama');

                // 🔥 2. CEK USER LAIN / SISWA
                } elseif (!empty($loginUser)) {

                    // Cek jika yang login adalah Siswa (Level 5)
                    if ($loginUser['id_level_user'] == 5) {
                        
                        // Kuncinya di sini: satukan semua data ke dalam format template.php
                        $sessionSiswa = array(
                            'username'      => $loginUser['username'],      // NIM Siswa
                            'nama_lengkap'  => $loginUser['nama_lengkap'],  // Nama Lengkap Siswa
                            'id_level_user' => 5,
                            'foto'          => isset($loginUser['foto']) ? $loginUser['foto'] : '' // <--- MEMASTIKAN FOTO MASUK SESSION
                        );
                        
                        $this->session->set_userdata($sessionSiswa);
                        
                    } else {
                        // Jika yang login Admin (Level 1) atau Kepala Sekolah (Level 4), pakai default data loginUser
                        // Pastikan property 'foto' diset jika ada di database admin
                        if (!isset($loginUser['foto'])) {
                            $loginUser['foto'] = ''; 
                        }
                        $this->session->set_userdata($loginUser);
                    }
                    
                    redirect('tampilan_utama');

                } else {
                    redirect('auth');
                }
            } else {
                redirect('auth');
            }
        }

		function logout()
		{
			$this->session->sess_destroy();
			redirect('auth');
		}

	}

?>