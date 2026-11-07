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
                    <h3 class="box-title">Data Table Tahun Akademik</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">

                    <!-- button add -->
                    <?php
                        echo anchor('tahunakademik/add', '<button class="btn bg-navy btn-flat margin"><i class="fa fa-plus"></i> Tambah Data</button>');
                    ?>

                    <table id="mytable" class="table table-striped table-bordered table-hover table-full-width dataTable" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>TAHUN AKADEMIK</th>
                                <th>SEMESTER</th> <!-- Kolom Baru -->
                                <th>IS AKTIF</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                    </table>

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>

<!-- DataTables CSS & JS Resources -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        var t = $('#mytable').DataTable({
            "ajax": '<?php echo site_url('tahunakademik/data'); ?>',
            "order": [[1, 'desc']], // Urutkan berdasarkan Tahun Akademik
            "columns": [
                {
                    "data": null,
                    "width": "50px",
                    "class": "text-center",
                    "orderable": false,
                },
                {
                    "data": "tahun_akademik",
                    "class": "text-center"
                },
                {
                    "data": "semester", // Menampilkan data semester dari database
                    "class": "text-center",
                    "render": function(data, type, row) {
                        // Opsional: Membuat tampilan semester jadi huruf kapital di awal
                        return data.charAt(0).toUpperCase() + data.slice(1);
                    }
                },
                {
                    "data": "is_aktif",
                    "width": "100px",
                    "class": "text-center",
                    "render": function(data, type, row) {
                        // Cek data dari server (bisa 'Aktif' atau 'Tidak Aktif' karena sudah diformat di controller)
                        if (data == 'Aktif') {
                            return '<span class="label label-success">Aktif</span>';
                        } else {
                            return '<span class="label label-danger">Tidak Aktif</span>';
                        }
                    }
                },
                {
                    "data": "aksi",
                    "width": "120px",
                    "class": "text-center",
                    "orderable": false
                },
            ]
        });

        // Logika penomoran otomatis
        t.on('order.dt search.dt', function() {
            t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();
    });
</script>