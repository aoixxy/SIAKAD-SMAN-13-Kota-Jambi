<?php
/** 
 * @var array $siswa 
 * @var object $info_tahun 
 * @var int $id_tahun_pilihan
 * @var CI_DB_result $nilai_siswa
 * @var string $nim
 */
?>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Filter Riwayat Nilai</h3>
                </div>
                <div class="box-body">
                    <!-- Form Filter Dropdown -->
                    <form action="<?php echo site_url('siswa/nilai_siswa/'.$nim) ?>" method="get">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Pilih Tahun Akademik & Semester:</label>
                                    <select name="id_tahun_akademik" class="form-control" onchange="this.form.submit()">
                                        <?php 
                                        $list_tahun = $this->db->get('tbl_tahun_akademik')->result();
                                        foreach($list_tahun as $t){
                                            $selected = ($t->id_tahun_akademik == $id_tahun_pilihan) ? 'selected' : '';
                                            echo "<option value='$t->id_tahun_akademik' $selected>$t->tahun_akademik - $t->semester</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Data Nilai Siswa: <b><?php echo $siswa['nama']; ?></b></h3>
                    <p class="text-muted">Periode: <?php echo $info_tahun->tahun_akademik; ?> (<?php echo $info_tahun->semester; ?>)</p>
                </div>
                
                <div class="box-body">
                    <table id="mytable" class="table table-striped table-bordered table-hover" width="100%">
                        <thead>
                            <tr>
                                <th width="50px">NO</th>
                                <th>Nama Mata Pelajaran</th>
                                <th>Nilai</th>
                                <th class="text-center">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($nilai_siswa->result() as $row) {
                                // Logika Keterangan Warna
                                if($row->nilai >= 75){
                                    $ket = '<span class="label label-success">Tuntas</span>';
                                } else {
                                    $ket = '<span class="label label-danger">Tidak Tuntas</span>';
                                }

                                echo "<tr>
                                        <td>$no</td>
                                        <td>$row->nama_mapel</td>
                                        <td><b>$row->nilai</b></td>
                                        <td class='text-center'>$ket</td>
                                     </tr>";
                                $no++;
                            }
                            ?>
                        </tbody>
                    </table>

                    <?php if($nilai_siswa->num_rows() == 0): ?>
                        <div class="alert alert-info">Tidak ada data nilai untuk periode ini.</div>
                    <?php endif; ?>

                </div>
            </div>
            
            <div style="margin-bottom: 20px;">
                <?php echo anchor('siswa/siswa_aktif', '<i class="fa fa-arrow-left"></i> Kembali', array('class'=>'btn btn-danger btn-flat')); ?>
            </div>
        </div>
    </div>
</section>

<!-- DataTables Scripts -->
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

<script>
    $(document).ready(function() {
        $('#mytable').DataTable({
            "order": [[ 1, "asc" ]],
            "pageLength": 25
        });
    });
</script>