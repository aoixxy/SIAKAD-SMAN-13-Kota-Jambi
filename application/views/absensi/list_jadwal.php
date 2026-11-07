<?php /** * @var array $jadwal
 * @var array $kelas_list
 * @var string $tahun_akademik
 * @var string $semester
 * @var string $selected_kelas
 * */ 
?>

<style>
    /* Menghilangkan judul Dashboard dan Breadcrumb */
    .content-header {
        display: none;
    }
</style>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-calendar"></i> Pilih Jadwal Pelajaran untuk Absensi</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-5">
                            <table class="table table-bordered" style="margin-bottom: 20px;">
                                <tr>
                                    <td width="150" style="font-weight: bold; background-color: #f9f9f9;">Tahun Akademik</td>
                                    <td>: <?php echo $tahun_akademik; ?></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold; background-color: #f9f9f9;">Semester</td>
                                    <td>: <?php echo ucfirst($semester); ?></td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-7">
                            <?php if ($this->session->userdata('id_level_user') == 1 || $this->session->userdata('id_level_user') == 4): ?>
                                <?php echo form_open('absensi/index', array('method' => 'get', 'id' => 'form-filter-jadwal')); ?>
                                <div class="row">
                                    <div class="col-sm-7">
                                        <div class="form-group">
                                            <label><i class="fa fa-filter"></i> Mapel Per Kelas:</label>
                                            <select name="filter_kelas" class="form-control" onchange="this.form.submit()">
                                                <option value="">-- Tampilkan Semua Jadwal Kelas --</option>
                                                <?php foreach($kelas_list as $kl): ?>
                                                    <option value="<?php echo $kl->kd_kelas; ?>" <?php echo ($selected_kelas == $kl->kd_kelas) ? 'selected' : ''; ?>>
                                                        <?php echo $kl->nama_kelas; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <hr style="margin-top: 5px;">

                    <table class="table table-bordered table-striped" id="example1">
                        <thead>
                            <tr class="bg-yellow">
                                <th width="50" class="text-center">No</th>
                                <th>Mata Pelajaran</th>
                                <th>Kelas</th>
                                <th>Hari</th>
                                <th>Jam</th>
                                <th width="150" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            if (!empty($jadwal)):
                                foreach ($jadwal as $row): 
                                    // PERUBAHAN: Menghilangkan spasi dan angka di ujung nama mapel (cth: "Fisika 1" -> "Fisika")
                                    $nama_mapel_bersih = preg_replace('/ \d+$/', '', $row->nama_mapel);
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td><?php echo $nama_mapel_bersih; ?></td>
                                    <td><span class="label label-info"><?php echo $row->nama_kelas; ?></span></td>
                                    <td><?php echo $row->hari; ?></td>
                                    <td>
                                        <?php 
                                        if(isset($row->jam_mulai)){
                                            echo $row->jam_mulai . ' - ' . $row->jam_selesai;
                                        } else {
                                            echo isset($row->jam) ? $row->jam : '-';
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($this->session->userdata('id_level_user') == 4): ?>
                                            <a href="<?php echo site_url('absensi/input/'.$row->id_jadwal); ?>" class="btn btn-info btn-sm">
                                                <i class="fa fa-eye"></i> Lihat Absensi
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo site_url('absensi/input/'.$row->id_jadwal); ?>" class="btn btn-success btn-sm">
                                                <i class="fa fa-edit"></i> Input Absensi
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; 
                            else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada jadwal pelajaran untuk periode atau filter kelas ini.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>