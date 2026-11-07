<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-pencil-square-o"></i> 
                        <?php echo ($read_only) ? 'Detail Absensi Siswa (View Only)' : 'Input Absensi Siswa'; ?>
                    </h3>
                </div>
                
                <div class="box-body">
                    <?php echo form_open('absensi/simpan', array('id' => 'form-absen')); ?>
                    
                    <input type="hidden" name="id_jadwal" value="<?php echo $id_jadwal; ?>">
                    
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="bg-blue">
                                <th width="50px" class="text-center">NO</th>
                                <th width="150px">NIM</th>
                                <th>NAMA SISWA</th>
                                <th class="text-center">STATUS ABSENSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach ($siswa as $s) : ?>
                            <tr>
                                <td class="text-center"><?php echo $no++; ?></td>
                                <td><?php echo $s->nim; ?></td>
                                <td><?php echo $s->nama; ?></td>
                                <td class="text-center">
                                    <input type="hidden" name="nim[]" value="<?php echo trim($s->nim); ?>">
                                    
                                    <?php $dis = ($read_only) ? 'disabled' : ''; ?>

                                    <label class="radio-inline">
                                        <input type="radio" name="status_<?php echo trim($s->nim); ?>" value="Hadir" <?php echo $dis; ?> checked> 
                                        <span class="label label-success">Hadir</span>
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="status_<?php echo trim($s->nim); ?>" value="Sakit" <?php echo $dis; ?>> 
                                        <span class="label label-primary">Sakit</span>
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="status_<?php echo trim($s->nim); ?>" value="Izin" <?php echo $dis; ?>> 
                                        <span class="label label-warning">Izin</span>
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="status_<?php echo trim($s->nim); ?>" value="Alpha" <?php echo $dis; ?>> 
                                        <span class="label label-danger">Alpha</span>
                                    </label>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div style="margin-top: 20px;">
                        <?php if (!$read_only) : ?>
                            <button type="submit" name="submit" class="btn btn-primary btn-lg btn-flat">
                                <i class="fa fa-save"></i> SIMPAN ABSENSI SEKARANG
                            </button>
                        <?php endif; ?>

                        <a href="<?php echo site_url('absensi') ?>" class="btn btn-default btn-lg btn-flat">Kembali</a>
                    </div>
                    
                    <?php echo form_close(); ?>
                </div>
                
                <div class="box-footer">
                    <p class="text-muted small">
                        <?php if ($read_only) : ?>
                            <i class="fa fa-info-circle"></i> Anda masuk sebagai Kepala Sekolah. Anda hanya dapat melihat data tanpa merubahnya.
                        <?php else : ?>
                            *Pilih salah satu status (Hadir, Sakit, Izin, atau Alpha) lalu tekan tombol Simpan.
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>