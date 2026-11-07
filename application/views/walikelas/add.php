<section class="content">
    <div class="row">
        <div class="col-xs-12">

          <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Form Tambah Wali Kelas</h3>
            </div>
            <?php
                echo form_open('walikelas/post', 'role="form" class="form-horizontal"');
            ?>

                <div class="box-body">

                  <div class="form-group">
                      <label class="col-sm-2 control-label">Pilih Kelas</label>
                      <div class="col-sm-9">
                        <?php 
                          // Mengambil data dari tbl_kelas, menampilkan nama_kelas, mengirim kd_kelas
                          echo cmb_dinamis('kd_kelas', 'tbl_kelas', 'nama_kelas', 'kd_kelas'); 
                        ?>
                      </div>
                  </div>

                  <div class="form-group">
                      <label class="col-sm-2 control-label">Pilih Guru</label>
                      <div class="col-sm-9">
                        <?php 
                          // Mengambil data dari tbl_guru, menampilkan nama_guru, mengirim id_guru
                          echo cmb_dinamis('id_guru', 'tbl_guru', 'nama_guru', 'id_guru'); 
                        ?>
                      </div>
                  </div>

                  <div class="form-group">
                      <label class="col-sm-2 control-label"></label>

                      <div class="col-sm-1">
                        <button type="submit" name="submit" class="btn btn-primary btn-flat">Simpan</button>
                      </div>

                      <div class="col-sm-1">
                        <?php
                          echo anchor('walikelas', 'Kembali', array('class'=>'btn btn-danger btn-flat'));
                        ?>
                      </div>
                  </div>

                </div>
                </form>
          </div>
          </div>
        </div>
    </section>