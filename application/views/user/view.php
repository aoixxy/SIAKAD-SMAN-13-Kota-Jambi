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
                    <h3 class="box-title">Data Table User</h3>
                </div>
                <div class="box-body">
                    <div style="margin-bottom: 15px;">
                        <?php
                            echo anchor('user/add', '<button class="btn bg-navy btn-flat">Tambah Data</button>');
                            echo anchor('user/rule', '<button class="btn btn-danger btn-flat" style="margin-left: 5px;">Rule User</button>');
                        ?>

                        <div class="pull-right">
                            <form class="form-inline">
                                <div class="form-group">
                                    <label for="filter_level">Filter Level: </label>
                                    <select id="filter_level" class="form-control input-sm" style="width: 150px; margin-left: 10px;">
                                        <option value="">Semua Level</option>
                                        <option value="Admin">Admin</option>
                                        <option value="Kepala Sekolah">Kepala Sekolah</option>
                                        <option value="WaliKelas">Wali Kelas</option>
                                        <option value="Guru">Guru</option>
                                        <option value="Siswa">Siswa</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>

                    <table id="mytable" class="table table-striped table-bordered table-hover table-full-width dataTable" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>FOTO</th>
                                <th>NAMA LENGKAP</th>
                                <th>LEVEL</th>
                                <th>AKSI</th>
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
        // Inisialisasi DataTable
        var t = $('#mytable').DataTable({
            "ajax": '<?php echo site_url('user/data'); ?>',
            "order": [[2, 'asc']], // Urutkan berdasarkan Nama Lengkap
            "columns": [
                {
                    "data": null,
                    "width": "50px",
                    "class": "text-center",
                    "orderable": false,
                },
                { 
                    "data": "foto",
                    "class": "text-center",
                    "orderable": false 
                },
                {
                    "data": "nama_lengkap",
                    "width": "200px",
                },
                { 
                    "data": "nama_level",
                    "class": "text-center"
                },
                { 
                    "data": "aksi",
                    "width": "100px",
                    "class": "text-center",
                    "orderable": false
                },
            ]
        });

        // Event listener untuk Filter Level
        $('#filter_level').on('change', function() {
            var val = $(this).val();
            // Index kolom ke-3 adalah "nama_level"
            t.column(3).search(val).draw();
        });

        // Generate nomor urut otomatis
        t.on('order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();
    });
</script>