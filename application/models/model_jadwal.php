<?php
 
	class Model_jadwal extends CI_Model
	{
		
		function jamPelajaran() {
	 		 $jam_pelajaran	= array(
            	'07.00 - 07.45' => '07.00 - 07.45',
				'07.45 - 08.30' => '07.45 - 08.30',
            	'08.30 - 09.15' => '08.30 - 09.15',
            	'09.15 - 10.00' => '09.15 - 10.00',
            	'10.00 - 10.20' => '10.00 - 10.20',
            	'10.20 - 11.05' => '10.20 - 11.05',
            	'11.05 - 11.50' => '11.05 - 11.50',
            	'11.50 - 12.35' => '11.50 - 12.35',
            	'12.35 - 13.00' => '12.35 - 13.00',
            	'13.00 - 13.45' => '13.00 - 13.45',
            	'13.45 - 14.30' => '13.45 - 14.30',
            	'14.30 - 15.15' => '14.30 - 15.15',
            );
	 		 return $jam_pelajaran;
	 	}

	 	function generateJadwal()
	 	{
	 		$idkurikulum	 = $this->input->post('kurikulum');
			$semester		 = $this->input->post('semester');

			// Mengambil detail data dari kurikulum yang dipilih (tbl_kurikulum_detail)
			$kurikulumDetail = $this->db->get_where('tbl_kurikulum_detail', array('id_kurikulum' => $idkurikulum));

			// Ambil tahun akademik yang aktif
			$tahunakademik 	 = $this->db->get_where('tbl_tahun_akademik', array('is_aktif' => 'Y'))->row_array();

			foreach ($kurikulumDetail->result() as $row) {

				// ambil kelas berdasarkan tingkatan dan jurusan
				$kelasnya = $this->db->get_where('tbl_kelas', array('kd_jurusan' => $row->kd_jurusan, 'kd_tingkatan' => $row->kd_tingkatan));

				foreach ($kelasnya->result() as $row_kelas) {
					$data = array(
						'id_tahun_akademik' => $tahunakademik['id_tahun_akademik'], 
						'semester'			=> $semester,
						'kd_jurusan'		=> $row->kd_jurusan, 
						'kd_tingkatan'		=> $row->kd_tingkatan, //sama seperti kelas di akademik
						'kd_kelas'			=> $row_kelas->kd_kelas, //sama seperti rombel di akademik
						'kd_mapel'			=> $row->kd_mapel, 
						'id_guru'			=> 0, 
						'jam'				=> '', 
						'kd_ruangan'		=> '000', 
						'hari'				=> ''
					);
					$this->db->insert('tbl_jadwal', $data);
				}

			}
	 	}

	}

?>