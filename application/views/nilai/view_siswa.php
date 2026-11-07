<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-graduation-cap"></i> Transkrip Nilai Saya</h3>
                </div>
                <div class="box-body">
                    <!-- Form Filter untuk Siswa -->
                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-md-4">
                            <form action="<?php echo site_url('nilai') ?>" method="get">
                                <label>Lihat Tahun Tahun Akademik & Semester Lain:</label>
                                <select name="id_tahun_akademik" class="form-control" onchange="this.form.submit()">
                                    <?php 
                                    $all_tahun = $this->db->get('tbl_tahun_akademik')->result();
                                    foreach($all_tahun as $t){
                                        $selected = ($t->id_tahun_akademik == $id_tahun_pilihan) ? 'selected' : '';
                                        echo "<option value='$t->id_tahun_akademik' $selected>$t->tahun_akademik - $t->semester</option>";
                                    }
                                    ?>
                                </select>
                            </form>
                        </div>
                    </div>

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="bg-blue">
                                <th width="50">NO</th>
                                <th>MATA PELAJARAN</th>
                                <th>GURU</th>
                                <th width="100" class="text-center">NILAI</th>
                                <th width="150" class="text-center">STATUS KELULUSAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($nilai as $row) {
                                $status = $row->nilai >= 75 ? 
                                    '<span class="label label-success">Tuntas</span>' : 
                                    '<span class="label label-danger">Tidak Tuntas</span>';
                                
                                // PERBAIKAN: Menghilangkan spasi dan angka di ujung nama mapel (cth: "Fisika 1" -> "Fisika")
                                $nama_mapel_bersih = preg_replace('/ \d+$/', '', $row->nama_mapel);
                                
                                echo "<tr>
                                        <td>$no</td>
                                        <td>$nama_mapel_bersih</td>
                                        <td>$row->nama_guru</td>
                                        <td class='text-center'><b>$row->nilai</b></td>
                                        <td class='text-center'>$status</td>
                                      </tr>";
                                $no++;
                            }
                            ?>
                        </tbody>
                    </table>
                    
                    <?php if(empty($nilai)): ?>
                        <div class="alert alert-warning text-center">
                            Belum ada data nilai untuk periode ini.
                        </div>
                    <?php endif; ?>

                    <p class="text-muted" style="margin-top: 10px;">
                        * Jika ada kesalahan nilai, silakan hubungi guru mata pelajaran yang bersangkutan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>