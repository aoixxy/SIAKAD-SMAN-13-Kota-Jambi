<?php /** * @var CI_Controller $this
 * */ 
?>

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
              <h3 class="box-title"><i class="fa fa-graduation-cap"></i> Data Table Guru</h3>
            </div>
            <div class="box-body">

            <?php if ($this->session->userdata('id_level_user') != 4): ?>
                <div style="margin-bottom: 10px;">
                    <?php echo anchor('guru/add', '<button class="btn bg-navy btn-flat"><i class="fa fa-plus"></i> Tambah Data</button>'); ?>
                </div>
            <?php endif; ?>

              <table id="mytable" class="table table-striped table-bordered table-hover table-full-width dataTable" cellspacing="0" width="100%">
                <thead>
                    <tr class="bg-blue">
                        <th width="50px" class="text-center">NO</th>
                        <th class="text-center">NIPPPK</th>
                        <th>NAMA GURU</th>
                        <th>GENDER</th>
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
            
            // Definisikan struktur kolom dasar tabel
            var columns_definition = [
                {
                    "data": null,
                    "width": "50px",
                    "class": "text-center",
                    "orderable": false,
                },
                {
                    "data": "nuptk",
                    "width": "120px",
                    "class": "text-center"
                },
                {
                    "data": "nama_guru",
                },
                {
                    "data": "gender",
                    "width": "150px"
                }
            ];

            // Jika yang login BUKAN Kepala Sekolah (Level 4), tambahkan kolom aksi
            if (id_level_user != 4) {
                columns_definition.push({
                    "data": "aksi",
                    "width": "80px",
                    "class": "text-center"
                });
            }

            var t = $('#mytable').DataTable({
                "ajax": '<?php echo site_url('guru/data'); ?>',
                "order": [[ 2, 'asc' ]], // Diubah ke index 2 (nama_guru) agar sorting default tetap rapi
                "columns": columns_definition
            });

            t.on( 'order.dt search.dt', function () {
                t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();
        } );
</script>