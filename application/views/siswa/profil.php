<?php /** 
 * @var array $siswa
 */ 

?>
<style>
    /* Menghilangkan judul Dashboard dan Breadcrumb */
    .content-header {
        display: none;
    }
</style>
<section class="content">
    <div class="row">
        <div class="col-md-3">
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <img class="profile-user-img img-responsive img-circle" 
                         src="<?php echo base_url('uploads/siswa/').$siswa['foto']; ?>" 
                         onerror="this.src='<?php echo base_url('assets/dist/img/avatar5.png'); ?>'"
                         alt="User profile picture">
                    
                    <h3 class="profile-username text-center"><?php echo $siswa['nama']; ?></h3>
                    <p class="text-muted text-center">Siswa SMAN 13 Kota Jambi</p>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit Biodata Diri</h3>
                </div>
                <div class="box-body">
                    <?php if($this->session->flashdata('pesan')): ?>
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-check"></i> Berhasil!</h4>
                            <?php echo $this->session->flashdata('pesan'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if($this->session->flashdata('error_upload')): ?>
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-ban"></i> Gagal Unggah!</h4>
                            <?php echo $this->session->flashdata('error_upload'); ?> (Maksimal 1 MB)
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo site_url('siswa/update_profil') ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">NIS</label>
                            <div class="col-sm-10">
                                <input type="text" name="nim" class="form-control" value="<?php echo $siswa['nim']; ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Nama Lengkap</label>
                            <div class="col-sm-10">
                                <input type="text" name="nama" class="form-control" value="<?php echo $siswa['nama']; ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Ganti Foto</label>
                            <div class="col-sm-10">
                                <input type="file" name="userfile" class="form-control">
                                <p class="help-block" style="font-size: 11px;">*Format: JPG/PNG, Max: 1MB. Kosongkan jika tidak ganti.</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Tempat Lahir</label>
                            <div class="col-sm-10">
                                <input type="text" name="tempat_lahir" class="form-control" value="<?php echo $siswa['tempat_lahir']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Tanggal Lahir</label>
                            <div class="col-sm-10">
                                <input type="date" name="tanggal_lahir" class="form-control" value="<?php echo $siswa['tanggal_lahir']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Alamat</label>
                            <div class="col-sm-10">
                                <textarea name="alamat" class="form-control" rows="3"><?php echo $siswa['alamat']; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
