<style>
    /* Menghilangkan judul Dashboard dan Breadcrumb */
    .content-header {
        display: none;
    }
</style>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-users"></i> Data Table Siswa</h3>
                </div>
                <div class="box-body">
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-8">
                            <?php 
                            // Proteksi Tombol Manajemen: Hanya tampil untuk Admin (Level 1)
                            if ($this->session->userdata('id_level_user') == 1): 
                                echo anchor('siswa/add', '<button class="btn bg-navy btn-flat"><i class="fa fa-plus"></i> Tambah Data</button> ');
                                echo anchor('siswa/form', '<button class="btn btn-warning btn-flat"><i class="fa fa-upload"></i> Import Data</button> ');
                                echo anchor('siswa/naik_kelas', '<button class="btn btn-info btn-flat"><i class="fa fa-arrow-up"></i> Naik Kelas</button>');
                            endif; 
                            ?>
                        </div>
                        <div class="col-md-4">
                            <select id="filter_kelas" class="form-control">
                                <option value="">-- Semua Kelas --</option>
                                <?php 
                                // Ambil data kelas dari database
                                $kelas = $this->db->get('tbl_kelas')->result();
                                foreach ($kelas as $k) {
                                    echo "<option value='$k->kd_kelas'>$k->nama_kelas</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <table id="mytable" class="table table-striped table-bordered table-hover table-full-width dataTable" cellspacing="0" width="100%">
                        <thead>
                            <tr class="bg-blue">
                                <th width="50" class="text-center">NO</th>
                                <th class="text-center">FOTO</th>
                                <th class="text-center">NIS</th>
                                <th>NAMA</th>
                                <th>KELAS</th> 
                                <th>TEMPAT LAHIR</th>
                                <th class="text-center">TANGGAL LAHIR</th>
                                <?php if ($this->session->userdata('id_level_user') != 4): ?>
                                    <th class="text-center">AKSI</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

<script>
    $(document).ready(function() {
        var id_level_user = "<?php echo $this->session->userdata('id_level_user'); ?>";
        
        var columns_definition = [
            {
                "data": null,
                "width": "50px",
                "class": "text-center",
                "orderable": false,
                "searchable": false,
                // BENAR: Menghitung nomor secara dinamis berdasarkan halaman aktif
                "render": function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { 
                "data": "foto",
                "class": "text-center",
                "orderable": false,
                "searchable": false
                // Note: Pastikan di Controller (Siswa.php) fungsi data() kolom 'foto' 
                // sudah me-return tag HTML <img src="..." /> seperti yang dibahas sebelumnya.
            },
            {
                "data": "nim",
                "width": "120px",
                "class": "text-center"
            },
            { "data": "nama" },
            { "data": "nama_kelas" }, 
            { 
                "data": "tempat_lahir",
                "width": "150px"
            },
            { 
                "data": "tanggal_lahir", 
                "width": "120px",
                "class": "text-center"
            }
        ];

        // Jika bukan Kepala Sekolah (Level 4), masukkan definisi kolom aksi
        if (id_level_user != 4) {
            columns_definition.push({ 
                "data": "aksi",
                "width": "80px",
                "class": "text-center",
                "orderable": false,
                "searchable": false
            });
        }

        var t = $('#mytable').DataTable({
            "processing": true,
            "serverSide": true, 
            "ajax": {
                "url": "<?php echo site_url('siswa/data'); ?>",
                "type": "GET",
                "data": function (d) {
                    d.filter_kelas = $('#filter_kelas').val();
                }
            },
            "order": [[ 2, 'asc' ]], // Urutkan default berdasarkan NIS (kolom ke-3)
            "columns": columns_definition
        });

        // Jalankan filter saat dropdown berubah
        $('#filter_kelas').change(function(){
            t.draw();
        });
    });
</script>