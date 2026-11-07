<section class="content">
    <div class="row">

        <div class="col-xs-12">
            <div class="box box-info">
                <div class="box-header  with-border">
                <table class="table table-bordered">
                <tr>
                    <td width="200">Tahun Akademik</td>
                    <td> : <?php echo get_tahun_akademik('tahun_akademik'); ?></td>
                </tr>
                <tr>
                    <td>Semester</td>
                    <td> : <?php echo get_tahun_akademik('semester'); ?></td>
                </tr>
                </table>
            </div>
            </div>
        </div>

        <div class="col-xs-12">

          <div class="box box-primary">
            <div class="box-header  with-border">
              <h3 class="box-title">Daftar Kelas yang Diajar</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

              <table id="mytable" class="table table-striped table-bordered table-hover table-full-width dataTable" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Jurusan &amp; Tingkatan</th>
                        <th>MATA PELAJARAN</th>
                        <th>HARI</th>
                        <th>JAM</th>
                        <th>RUANG</th>
                        <th></th>
                    </tr>
                </thead>

                <?php
                    $no = 1;
                    foreach ($jadwal->result() as $row) {
                       $jam_tampil = $row->jam_mulai . ' - ' . $row->jam_selesai;

                       // PERUBAHAN: Menghilangkan angka di akhir nama mapel
                       $nama_mapel_bersih = preg_replace('/ \d+$/', '', $row->nama_mapel);

                       echo "<tr>
                                <td>$no</td>
                                <td>Jurusan $row->nama_jurusan $row->nama_tingkatan</td>
                                <td>$nama_mapel_bersih</td> <!-- Menggunakan nama yang sudah bersih -->
                                <td>$row->hari</td>
                                <td>$jam_tampil</td>
                                <td>$row->nama_ruangan</td>
                                <td>".anchor('nilai/kelas/'.$row->id_jadwal, '<span class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i> Input Nilai</span>')."</td>
                            </tr>";
                        $no++;
                    }
                ?>

              </table>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>