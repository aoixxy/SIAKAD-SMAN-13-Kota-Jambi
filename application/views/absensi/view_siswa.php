<?php /** 
 * @var array $riwayat 
 * @var array $mapel_list
 * @var string $tahun_akademik
 * @var string $semester
 * @var string $selected_mapel
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
            <!-- BOX INFORMASI PERIODE -->
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table" style="border: none; margin-bottom: 0;">
                                <tr>
                                    <td width="150px" style="border: none; font-weight: bold;">Tahun Akademik</td>
                                    <td style="border: none;">: <?php echo $tahun_akademik; ?></td>
                                </tr>
                                <tr>
                                    <td style="border: none; font-weight: bold;">Semester</td>
                                    <td style="border: none;">: <?php echo ucfirst($semester); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <!-- FILTER MATA PELAJARAN -->
                            <?php echo form_open('absensi/index', array('method' => 'get', 'id' => 'form-filter')); ?>
                            <div class="form-group" style="margin-top: 10px;">
                                <label>Absen Sesuai Mata Pelajaran:</label>
                                <select name="filter_mapel" class="form-control" onchange="this.form.submit()">
                                    <option value="">-- Tampilkan Semua --</option>
                                    <?php 
                                    $tampil_mapel = array(); // Variabel bantuan untuk melacak nama mapel unik
                                    foreach($mapel_list as $ml): 
                                        // Hilangkan angka di ujung nama mapel
                                        $nama_ml_bersih = preg_replace('/ \d+$/', '', $ml->nama_mapel); 

                                        // JIKA NAMA MAPEL SUDAH PERNAH DIMASUKKAN KE DROPDOWN, SKIP/JANGAN TAMPILKAN LAGI
                                        if (in_array($nama_ml_bersih, $tampil_mapel)) {
                                            continue;
                                        }

                                        // Catat nama mapel yang sudah lolos seleksi pertama kali
                                        $tampil_mapel[] = $nama_ml_bersih;
                                    ?>
                                        <option value="<?php echo $ml->kd_mapel; ?>" <?php echo ($selected_mapel == $ml->kd_mapel) ? 'selected' : ''; ?>>
                                            <?php echo $nama_ml_bersih; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BOX TABEL ABSENSI -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-check-square-o"></i> Rekap Kehadiran Saya</h3>
                </div>
                <div class="box-body">
                    <table class="table table-bordered table-striped" id="example1">
                        <thead>
                            <tr class="bg-yellow">
                                <th width="30px" class="text-center">NO</th>
                                <th class="text-center">TANGGAL</th>
                                <th>MATA PELAJARAN</th>
                                <th>GURU</th>
                                <th width="100px" class="text-center">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no=1; 
                            if (!empty($riwayat)) :
                                foreach ($riwayat as $a) : 
                                    $nama_mapel_bersih = preg_replace('/ \d+$/', '', $a->nama_mapel);
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $no++; ?></td>
                                <td class="text-center"><?php echo date('d-m-Y', strtotime($a->tanggal)); ?></td>
                                <td><?php echo $nama_mapel_bersih; ?></td>
                                <td><?php echo $a->nama_guru; ?></td>
                                <td class="text-center">
                                    <?php 
                                    if(strtolower($a->status) == 'hadir') {
                                        echo '<span class="label label-success">Hadir</span>';
                                    } elseif(strtolower($a->status) == 'sakit') {
                                        echo '<span class="label label-primary">Sakit</span>';
                                    } elseif(strtolower($a->status) == 'izin') {
                                        echo '<span class="label label-warning">Izin</span>';
                                    } else {
                                        echo '<span class="label label-danger">Alpa</span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php 
                                endforeach; 
                            else: 
                            ?>
                            <tr>
                                <td colspan="5" class="text-center">Belum ada data kehadiran untuk periode ini.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>