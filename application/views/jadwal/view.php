<?php /** * @var string $tahun_akademik
 * @var string $semester
 * @var string $id_tahun_aktif 
 * */ 
?>

<style>
    /* Menghilangkan judul Dashboard dan Breadcrumb agar tampilan lebih luas */
    .content-header {
        display: none;
    }
    /* Mempercantik tampilan dropdown di tabel */
    .table select.form-control {
        height: 30px;
        padding: 2px 5px;
        font-size: 12px;
    }
</style>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-filter"></i> Filter & Kelola Jadwal</h3>
                </div>
                <div class="box-body">
                    <?php echo form_open('jadwal/cetak_jadwal'); ?>
                    <table class="table table-bordered">
                        <tr>
                            <td width="200">Tahun Academic & Semester</td>
                            <td>
                                <?php 
                                $queryTahun = "SELECT id_tahun_akademik, CONCAT(tahun_akademik, ' - ', semester) as tahun_semester FROM tbl_tahun_akademik ORDER BY tahun_akademik DESC";
                                echo cmb_dinamis('id_tahun_akademik', 'tbl_tahun_akademik', 'tahun_semester', 'id_tahun_akademik', $id_tahun_aktif, "id='filter_tahun' onChange='loadPelajaran()'", $queryTahun) 
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
                            <td>Tingkatan Kelas</td>
                            <td>
                                <?php echo cmb_dinamis('tingkatan_kelas', 'tbl_tingkatan_kelas', 'nama_tingkatan', 'kd_tingkatan', null, "id='filter_tingkatan' onchange='loadKelas()'") ?>        
                            </td>
                        </tr>
                        <tr>
                            <td>Kelas</td>
                            <td>
                                <div id="tampilKelas"><i>Memuat kelas...</i></div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <?php if ($this->session->userdata('id_level_user') == 1): ?>
                                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalTambah">
                                        <i class="fa fa-plus"></i> Tambah Jadwal Manual
                                    </button>
                                <?php endif; ?>
                                
                                <button type="submit" name="export_jadwal" class="btn btn-danger btn-sm">
                                    <i class="fa fa-print"></i> Cetak PDF
                                </button>
                            </td>
                        </tr>
                    </table>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-list"></i> Data Daftar Pelajaran</h3>
                </div>
                <div class="box-body">
                    <div id="table_daftarpelajaran">
                        <div class="alert alert-warning">
                            <h4><i class="icon fa fa-info"></i> Pilih Filter</h4>
                            <p>Silakan pilih Tahun, Jurusan, Tingkatan, dan Kelas untuk menampilkan data.</p>
                        </div>
                    </div> 
                </div>
            </div>

            <?php if ($this->session->userdata('id_level_user') == 1): ?>
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-info-circle"></i> Tips Update Cepat!</h4>
                    Cukup ganti pilihan (Guru/Ruang/Hari/Jam) pada tabel di atas, sistem akan otomatis menyimpan perubahan via AJAX tanpa perlu klik tombol simpan lagi.
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if ($this->session->userdata('id_level_user') == 1): ?>
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="labelTambah">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php echo form_open('jadwal/tambah_manual', 'role="form" class="form-horizontal"'); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="labelTambah">Tambah Data Jadwal</h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <input type="hidden" name="jurusan" id="input_jurusan">
                    <input type="hidden" name="tingkatan" id="input_tingkatan">
                    <input type="hidden" name="kelas" id="input_kelas">
                    <input type="hidden" name="id_tahun_akademik" id="input_tahun">
                    
                    <tr>
                        <td>Mata Pelajaran</td>
                        <td><?php echo cmb_dinamis('mapel', 'tbl_mapel', 'nama_mapel', 'kd_mapel') ?></td>
                    </tr>
                    <tr>
                        <td>Guru Pengajar</td>
                        <td><?php echo cmb_dinamis('guru', 'tbl_guru', 'nama_guru', 'id_guru') ?></td>
                    </tr>
                    <tr>
                        <td>Ruangan</td>
                        <td><?php echo cmb_dinamis('ruangan', 'tbl_ruangan', 'nama_ruangan', 'kd_ruangan') ?></td>
                    </tr>
                    <tr>
                        <td>Hari & Jam</td>
                        <td>
                            <div class="row">
                                <div class="col-md-6">
                                    <?php 
                                    $hari = array('Senin'=>'Senin','Selasa'=>'Selasa','Rabu'=>'Rabu','Kamis'=>'Kamis','Jumat'=>'Jumat','Sabtu'=>'Sabtu');
                                    echo form_dropdown('hari', $hari, null, "class='form-control'"); 
                                    ?>
                                </div>
                                <div class="col-md-6">
                                    <?php echo form_dropdown('jam', $this->model_jadwal->jamPelajaran(), null, "class='form-control'"); ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
                <p class="text-danger">* Jadwal akan otomatis tersimpan pada <b>Tahun Akademik</b> yang terpilih di filter.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" name="submit" class="btn btn-primary">Simpan Jadwal</button>
            </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<script type="text/javascript">
    $(document).ready(function(){
        loadKelas();
    });

    function loadKelas() {
        var tingkatan_kelas = $("#filter_tingkatan").val();
        var jurusan = $("#filter_jurusan").val();
        
        // Sinkronisasi data awal ke modal
        $("#input_jurusan").val(jurusan);
        $("#input_tingkatan").val(tingkatan_kelas);

        $.ajax({
            type: 'GET',
            url: '<?php echo base_url() ?>jadwal/tampil_kelas',
            data: 'kd_jurusan='+jurusan+'&kd_tingkatan='+tingkatan_kelas,
            success: function(html) {
                $("#tampilKelas").html(html);
                loadPelajaran(); // Load tabel setelah kelas muncul
            }
        });
    }

    function loadPelajaran() {
        var tingkatan_kelas = $("#filter_tingkatan").val();
        var jurusan = $("#filter_jurusan").val();
        var kelas = $("#kelas").val(); 
        var tahun = $("#filter_tahun").val(); 
        
        // Update input hidden di modal setiap kali filter berubah
        $("#input_kelas").val(kelas);
        $("#input_jurusan").val(jurusan);
        $("#input_tingkatan").val(tingkatan_kelas);
        $("#input_tahun").val(tahun);

        if (!kelas || kelas == "") { 
            $("#table_daftarpelajaran").html('<div class="alert alert-warning">Silakan pilih kelas.</div>');
            return; 
        }

        $.ajax({
            type: 'GET',
            url: '<?php echo base_url() ?>jadwal/dataJadwal',
            data: {
                kd_jurusan: jurusan,
                kd_tingkatan: tingkatan_kelas,
                kelas: kelas,
                id_tahun_akademik: tahun 
            },
            beforeSend: function() {
                $("#table_daftarpelajaran").html('<div class="text-center"><i class="fa fa-refresh fa-spin"></i> Menarik data...</div>');
            },
            success: function(html) {
                $("#table_daftarpelajaran").html(html);
            }
        });
    }

    // Fungsi Update Otomatis via AJAX (Inline Editing)
    function updateGuru(id) {
        var guru = $("#guru"+id).val();
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url() ?>jadwal/update_guru',
            data: 'id_guru='+guru+'&id_jadwal='+id
        });
    }

    function updateRuangan(id) {
        var ruangan = $("#ruangan"+id).val();
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url() ?>jadwal/update_ruangan',
            data: 'kd_ruangan='+ruangan+'&id_jadwal='+id
        });
    }

    // Perubahan Hak Akses via AJAX (Bila Kepsek mencoba meretas element front-end, request akan diabaikan/error)
    function updateHari(id) {
        var hari = $("#hari"+id).val();
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url() ?>jadwal/update_hari',
            data: 'hari='+hari+'&id_jadwal='+id
        });
    }

    function updateJam(id) {
        var jam = $("#jam"+id).val();
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url() ?>jadwal/update_jam',
            data: 'jam='+jam+'&id_jadwal='+id
        });
    }
</script>