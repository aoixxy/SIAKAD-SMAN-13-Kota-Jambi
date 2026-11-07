<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-filter"></i> Filter Periode</h3>
                </div>
                <div class="box-body">
                    <form action="<?php echo site_url('jadwal'); ?>" method="get" class="form-inline">
                        <div class="form-group">
                            <label>Pilih Tahun Akademik & Semester: </label>
                            <select name="filter_tahun" class="form-control" style="min-width: 300px;">
                                <?php foreach ($list_tahun as $lt) : ?>
                                    <option value="<?php echo $lt->id_tahun_akademik; ?>" <?php echo ($id_tahun_selected == $lt->id_tahun_akademik) ? 'selected' : ''; ?>>
                                        <?php echo $lt->tahun_akademik; ?> - <?php echo ucfirst($lt->semester); ?> 
                                        <?php echo ($lt->is_aktif == 'Y') ? '(Aktif Sekarang)' : ''; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Tampilkan Jadwal</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                    <table class="table" style="margin-bottom: 0; border: none;">
                        <tr>
                            <td width="180px" style="border: none;"><strong>Tahun Akademik</strong></td>
                            <td style="border: none;">: <?php echo $tahun_akademik; ?></td>
                        </tr>
                        <tr>
                            <td style="border: none;"><strong>Semester</strong></td>
                            <td style="border: none;">: <?php echo ucfirst($semester); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-calendar"></i> Daftar Pelajaran</h3>
                </div>
                <div class="box-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="bg-green">
                                <th width="30px" class="text-center">NO</th>
                                <th>HARI</th>
                                <th>JAM</th>
                                <th>MATA PELAJARAN</th>
                                <th>GURU</th>
                                <th>RUANGAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if (!empty($jadwal)) : 
                                $no = 1; 
                                $current_hari = ""; // Variabel penanda untuk memantau perubahan hari
                                
                                foreach ($jadwal as $j) : 
                                    // 1. Menggabungkan jam mulai dan jam selesai
                                    $jam_tampil = $j->jam_mulai;
                                    if ($j->jam_mulai != $j->jam_selesai) {
                                        $jam_tampil = $j->jam_mulai . ' - ' . $j->jam_selesai;
                                    }

                                    // 2. Menghilangkan spasi dan angka di ujung nama mapel
                                    $nama_mapel_bersih = preg_replace('/ \d+$/', '', $j->nama_mapel);

                                    // 3. LOGIKA HARI TUNGGAL
                                    // Jika hari di baris ini sama dengan baris sebelumnya, kosongkan teksnya
                                    if (trim($j->hari) == trim($current_hari)) {
                                        $hari_tampil = "";
                                    } else {
                                        $hari_tampil = "<strong>" . $j->hari . "</strong>";
                                        $current_hari = $j->hari; // Update penanda hari aktif
                                    }
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $no++; ?></td>
                                <td><?php echo $hari_tampil; ?></td>
                                <td><?php echo $jam_tampil; ?></td>
                                <td><?php echo $nama_mapel_bersih; ?></td>
                                <td><?php echo $j->nama_guru; ?></td>
                                <td><span class="label label-primary"><?php echo $j->nama_ruangan; ?></span></td>
                            </tr>
                            <?php 
                                endforeach; 
                            else : 
                            ?>
                            <tr>
                                <td colspan="6" class="text-center">Data jadwal tidak ditemukan untuk periode ini.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>