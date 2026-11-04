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
              <h3 class="box-title">Daftar Jadwal Mengajar</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

              <table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Mata Pelajaran</th>
            <th>Kelas</th>
            <th>Hari / Jam</th>
            <th>Ruang</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1;
        foreach ($jadwal->result() as $row) { 
            // 1. Menggabungkan jam mulai dan jam selesai hasil GROUP BY dari Controller
            $jam_tampil = $row->jam_mulai . ' - ' . $row->jam_selesai;

            // 2. Menghilangkan spasi dan angka di ujung nama mapel (cth: "Fisika 1" -> "Fisika")
            $nama_mapel_bersih = preg_replace('/ \d+$/', '', $row->nama_mapel);
        ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $nama_mapel_bersih; ?></td>
            <td><?php echo $row->nama_kelas; ?></td>
            <td><?php echo $row->hari . ' / ' . $jam_tampil; ?></td>
            <td><?php echo $row->nama_ruangan; ?></td>
        </tr>
        <?php } ?>
    </tbody>
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