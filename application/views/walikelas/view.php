<style>
    /* Menghilangkan judul Dashboard dan Breadcrumb */
    .content-header {
        display: none;
    }
</style>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <table class="table table-bordered" style="margin-bottom: 0px;">
                        <tr>
                            <td width="200" style="font-weight: bold; background-color: #f9f9f9;">Tahun Akademik</td>
                            <td> : <?php echo get_tahun_akademik('tahun_akademik'); ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold; background-color: #f9f9f9;">Semester</td>
                            <td> : <?php echo get_tahun_akademik('semester'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-user"></i> Data Table Walikelas</h3>
                    <a href="<?php echo site_url('walikelas/generate_walikelas'); ?>" 
                       class="btn btn-primary btn-sm pull-right" 
                       onclick="return confirm('Generate data walikelas untuk tahun ajaran ini?')">
                       <i class="fa fa-refresh"></i> Generate Data Walikelas
                    </a>
                </div>
                
                <div class="box-body">
                    <?php if($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-check"></i> Sukses!</h4>
                            <?php echo $this->session->flashdata('success'); ?>
                        </div>
                    <?php endif; ?>

                    <div id="ajax-alert-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999; display: none;">
                        <div class="alert alert-success" style="box-shadow: 0px 4px 8px rgba(0,0,0,0.1);">
                            <i class="icon fa fa-check-circle"></i> <span id="ajax-alert-message">Perubahan Walikelas Berhasil Disimpan!</span>
                        </div>
                    </div>

                    <table id="mytable" class="table table-striped table-bordered table-hover table-full-width dataTable" cellspacing="0" width="100%">
                        <thead>
                            <tr class="bg-blue">
                                <th width="50px" class="text-center">NO</th>
                                <th>KELAS</th>
                                <th class="text-center">NAMA JURUSAN</th>
                                <th class="text-center">TINGKATAN</th>
                                <th>NAMA WALIKELAS</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-info-circle"></i> Tips Manajemen!</h4>
                Cukup pilih nama guru pada kolom <b>Nama Walikelas</b>, sistem akan langsung menguji dan menyimpan perubahan secara otomatis ke server tanpa memuat ulang halaman.
            </div>
        </div>
    </div>
</section>

<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

<script type="text/javascript">
    // Fungsi Update Otomatis via AJAX dengan Efek Notifikasi Sukses Pop-up
    function updateWalikelas(idwalikelas){
        var id_guru = $("#guru"+idwalikelas).val();
        $.ajax({
            type    : 'GET',
            url     : '<?php echo base_url() ?>walikelas/update_walikelas',
            data    : 'id_walikelas='+idwalikelas+'&id_guru='+id_guru,
            success : function(html) {
                // Tampilkan notifikasi pop-up kecil di pojok kanan atas
                $("#ajax-alert-container").fadeIn();
                
                // Sembunyikan kembali otomatis setelah 2.5 detik
                setTimeout(function(){
                    $("#ajax-alert-container").fadeOut();
                }, 2500);
            },
            error : function() {
                alert('Gagal menyimpan perubahan. Periksa koneksi jaringan Anda.');
            }
        });
    }
</script>

<script>
    $(document).ready(function() {
        var t = $('#mytable').DataTable({
            "ajax": '<?php echo site_url('walikelas/data'); ?>',
            "order": [[ 1, 'asc' ]],
            "columns": [
                {
                    "data": null,
                    "width": "50px",
                    "class": "text-center",
                    "orderable": false,
                },
                { "data": "nama_kelas", "width": "150px" },
                { "data": "nama_jurusan", "width": "150px", "class": "text-center" },
                { "data": "nama_tingkatan", "width": "100px", "class": "text-center" },
                { "data": "nama_guru" },
            ]
        });
           
        t.on('order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each(function (cell, i) {
                cell.innerHTML = i+1;
            });
        }).draw();
    });
</script>