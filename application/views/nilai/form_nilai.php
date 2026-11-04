<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <table class="table table-bordered">
                        <tr>
                            <td width="200">Tahun Akademik</td>
                            <td> : <?php echo get_tahun_akademik('tahun_akademik'); ?></td>
                        </tr>
                        <tr>
                            <td>Semester</td>
                            <td> : <?php echo get_tahun_akademik('semester'); ?></td>
                        </tr>
                        <tr>
                            <td>Jurusan &amp; Tingkatan</td>
                            <td> : <?php echo "Jurusan".' '.$kelas['nama_jurusan'].' '.$kelas['nama_tingkatan']; ?> (<?php echo $kelas['nama_kelas']; ?>)</td>
                        </tr>
                        <tr>
                            <td>Mata Pelajaran</td>
                            <td> : <?php echo $kelas['nama_mapel']?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Daftar Siswa & Input Nilai</h3>
                </div>
                <div class="box-body">
                    <table id="mytable" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center" width="100">NIM</th>
                                <th>NAMA SISWA</th>
                                <th class="text-center" width="100">NILAI</th>
                                <th class="text-center">KETERANGAN KOMPETENSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($siswa as $row) {
                                // Mengambil id_jadwal dari segmen URL ke-3
                                $id_jadwal = $this->uri->segment(3);
                                
                                // Ambil data nilai & keterangan yang sudah ada di database
                                $res = $this->db->get_where('tbl_nilai', array(
                                    'nim' => $row->nim, 
                                    'id_jadwal' => $id_jadwal
                                ))->row_array();
                                
                                $nilai_siswa = $res ? $res['nilai'] : 0;
                                $ket_siswa   = $res ? $res['keterangan'] : '';

                                echo "<tr>
                                        <td class='text-center'>$row->nim</td>
                                        <td>".strtoupper($row->nama)."</td>
                                        <td>
                                            <input type='number' 
                                                   id='nilai".$row->nim."' 
                                                   value='".$nilai_siswa."' 
                                                   onKeyUp='updateNilai(\"$row->nim\")' 
                                                   class='form-control text-center'>
                                        </td>
                                        <td>
                                            <input type='text' 
                                                   id='ket".$row->nim."' 
                                                   value='".$ket_siswa."' 
                                                   placeholder='Ketik capaian kompetensi di sini...' 
                                                   onBlur='updateKeterangan(\"$row->nim\")' 
                                                   class='form-control'>
                                        </td>
                                     </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    <p class="text-muted"><i class="fa fa-info-circle"></i> <b>Info:</b> Nilai tersimpan saat mengetik (KeyUp), Keterangan tersimpan saat Anda klik di luar kotak (Blur).</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    // Fungsi Update Nilai Angka
    function updateNilai(nim) {
        var nilai = $("#nilai"+nim).val();
        var id_jadwal = "<?php echo $this->uri->segment(3); ?>";
        $.ajax({
            type    : 'GET',
            url     : '<?php echo base_url(); ?>nilai/update_nilai',
            data    : { nim: nim, id_jadwal: id_jadwal, nilai: nilai },
            success : function(html) {
                console.log('Nilai updated');
            }
        });
    }

    // Fungsi Update Teks Keterangan
    function updateKeterangan(nim) {
        var keterangan = $("#ket"+nim).val();
        var id_jadwal  = "<?php echo $this->uri->segment(3); ?>";
        $.ajax({
            type    : 'POST',
            url     : '<?php echo site_url("laporan_nilai/simpan_keterangan_mapel") ?>',
            data    : { 
                nim: nim, 
                id_jadwal: id_jadwal, 
                keterangan: keterangan 
            },
            success : function(response) {
                console.log('Keterangan updated');
            }
        });
    }
</script>