<section class="content">
    <div class="row">

        <!-- filter -->
        <div class="col-xs-4">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Filter Data</h3>
            </div>
            <div class="box-body">
                
                <?php echo form_open('siswa/export_excel'); ?>

                <table class="table table-bordered">
                    <tr>
                        <td>Periode Akademik</td>
                        <td>
                            <select name="id_tahun_akademik" id="filter_periode" class="form-control" onChange="loadSiswa()">
                                <option value="">-- Pilih Periode --</option>
                                <?php
                                // Mengambil data tahun akademik dan semester sekaligus
                                $periode = $this->db->get('tbl_tahun_akademik')->result();
                                foreach ($periode as $p) {
                                    $selected = ($p->is_aktif == 'Y') ? 'selected' : '';
                                    echo "<option value='$p->id_tahun_akademik' $selected>$p->tahun_akademik - $p->semester</option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Jurusan</td>
                        <td>
                            <?php echo cmb_dinamis('jurusan', 'tbl_jurusan', 'nama_jurusan', 'kd_jurusan', null, "id='filter_jurusan' onChange='loadKelas()'") ?>        
                        </td>
                    </tr>
                    <tr>
                        <td>Kelas</td>
                        <td>    
                            <div id="kelas"></div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                           <button type="submit" name="export_jadwal" class="btn btn-success btn-sm btn-block"><i class="fa fa-print" aria-hidden="true"></i> Export Data Excel</button>
                        </td>
                    </tr>
                </table>

                </form>

            </div>
          </div>
        </div>

        <!-- Tabel Data -->
        <div class="col-xs-8">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Data Table Siswa</h3>
            </div>
            <div class="box-body">
                <div id="dataSiswa">
                    <div class="alert alert-info">Silakan pilih filter untuk menampilkan data.</div>
                </div>
            </div>
          </div>
        </div>

    </div>
</section>

<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

<script type="text/javascript">
    $(document).ready(function(){
        loadKelas(); 
    });

    function loadKelas() {
        var jurusan = $("#filter_jurusan").val();
        $.ajax({
            type    : 'GET',
            url     : '<?php echo base_url() ?>index.php/kelas/combobox_kelas',
            data    : 'kd_jurusan='+jurusan,
            success : function(html) {
                $("#kelas").html(html);
                
                $("#kelas select").attr('id', 'cbkelas');
                $("#kelas select").attr('class', 'form-control');
                $("#kelas select").attr('onChange', 'loadSiswa()'); 
                
                loadSiswa(); 
            }
        });
    }

    function loadSiswa() {   
        var kelas    = $("#cbkelas").val();
        var id_tahun = $("#filter_periode").val(); // Mengambil ID gabungan

        if(kelas && id_tahun) {
            $.ajax({
                type    : 'GET',
                url     : '<?php echo base_url() ?>index.php/siswa/loadDataSiswa',
                data    : {
                    kd_kelas: kelas,
                    id_tahun_akademik: id_tahun
                },
                success : function(html) {
                    $("#dataSiswa").html(html);
                }
            });
        }
    }
</script>