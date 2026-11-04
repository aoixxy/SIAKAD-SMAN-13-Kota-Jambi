<section class="content">
    <div class="row">
        <form action="<?php echo site_url('siswa/aksi_naikkelas'); ?>" method="post">
            
            <div class="col-xs-4">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Filter Data Asal</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered">
                            <!-- Informasi Periode Aktif -->
                            <tr style="background: #f4f4f4;">
                                <td><b>Periode Aktif</b></td>
                                <td>
                                    <?php 
                                        $tahun = $this->db->get_where('tbl_tahun_akademik', array('is_aktif' => 'Y'))->row();
                                        echo "<b>". ($tahun ? $tahun->tahun_akademik : 'Belum Atur') ."</b>";
                                        echo " (Semester ". ($tahun ? $tahun->semester : '-') .")";
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Jurusan</td>
                                <td>
                                    <?php echo cmb_dinamis('jurusan', 'tbl_jurusan', 'nama_jurusan', 'kd_jurusan', null, "id='filter_jurusan' onChange='loadKelas()'") ?>        
                                </td>
                            </tr>
                            <tr>
                                <td>Kelas Asal</td>
                                <td>    
                                    <div id="kelas"></div>
                                </td>
                            </tr>
                            <tr>
                                <td>Ke Kelas Tujuan</td>
                                <td>
                                    <select name="kelas_tujuan" class="form-control" required>
                                        <option value="">-- Pilih Tujuan --</option>
                                        <?php 
                                        $semua_kelas = $this->db->get('tbl_kelas')->result();
                                        foreach($semua_kelas as $k) {
                                            echo "<option value='$k->kd_kelas'>$k->nama_kelas</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-info btn-block btn-flat" onclick="return confirm('Yakin ingin memproses kenaikan kelas pada periode ini?')">
                            <i class="fa fa-arrow-up"></i> Proses Naik Kelas
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-xs-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Daftar Siswa yang Akan Naik</h3>
                    </div>
                    <div class="box-body">
                        <div id="dataSiswa">
                            <div class="alert alert-info">Pilih jurusan dan kelas terlebih dahulu.</div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</section>

<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

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
                $("#kelas select").attr('name', 'kd_kelas'); 
                $("#kelas select").attr('class', 'form-control');
                $("#kelas select").attr('onChange', 'loadSiswa()'); 
                
                loadSiswa();
            }
        });
    }

    function loadSiswa() {   
        var kelas = $("#cbkelas").val(); 
        if(kelas) {
            $.ajax({
                type    : 'GET',
                url     : '<?php echo base_url() ?>index.php/siswa/naiksiswa',
                data    : 'kd_kelas='+kelas,
                success : function(html) {
                    $("#dataSiswa").html(html);
                }
            });
        }
    }
</script>