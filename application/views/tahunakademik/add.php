<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Tambah Data Tahun Akademik</h3>
                </div>
                <div class="box-body">
                    <?php echo form_open('tahunakademik/add', 'class="form-horizontal"'); ?>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Tahun Akademik</label>
                        <div class="col-sm-9">
                            <input type="text" name="tahun_akademik" class="form-control" placeholder="Contoh: 2025/2026" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Semester</label>
                        <div class="col-sm-9">
                            <select name="semester" class="form-control" required>
                                <option value="">-- Pilih Semester --</option>
                                <option value="Ganjil">Ganjil</option>
                                <option value="Genap">Genap</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Is Aktif</label>
                        <div class="col-sm-9">
                            <?php echo form_dropdown('is_aktif', array('Y' => 'AKTIF', 'N' => 'TIDAK AKTIF'), null, array('class' => 'form-control')); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label"></label>
                        <div class="col-sm-9">
                            <button type="submit" name="submit" class="btn btn-primary btn-flat">Simpan</button>
                            <?php echo anchor('tahunakademik', 'Kembali', array('class' => 'btn btn-default btn-flat')); ?>
                        </div>
                    </div>
                    
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</section>