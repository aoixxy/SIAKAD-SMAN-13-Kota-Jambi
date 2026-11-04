<section class="content-header">
    <h1>Dashboard <small>Ringkasan Aktivitas Siswa</small></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
                <div class="inner"><h3><?php echo $hadir; ?></h3><p>Hadir</p></div>
                <div class="icon"><i class="fa fa-check"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
                <div class="inner"><h3><?php echo $sakit; ?></h3><p>Sakit</p></div>
                <div class="icon"><i class="fa fa-medkit"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
                <div class="inner"><h3><?php echo $izin; ?></h3><p>Izin</p></div>
                <div class="icon"><i class="fa fa-envelope"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-red">
                <div class="inner"><h3><?php echo $alpa; ?></h3><p>Alpa</p></div>
                <div class="icon"><i class="fa fa-ban"></i></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="box box-solid box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-bullhorn"></i> Pengumuman Sekolah</h3>
                </div>
                <div class="box-body">
                    <?php if(!empty($pengumuman)): ?>
                        <?php foreach($pengumuman as $p): ?>
                            <div class="callout callout-info">
                                <h4><?php echo $p->judul; ?></h4>
                                <p><?php echo $p->isi_pengumuman; ?></p>
                                <small class="pull-right"><i>Diposting: <?php echo date('d M Y', strtotime($p->tanggal)); ?></i></small>
                                <div style="clear:both;"></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-default">Belum ada pengumuman terbaru hari ini.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-info-circle"></i> Status Semester</h3>
                </div>
                <div class="box-body">
                    <table class="table">
                        <tr><th>Semester</th><td>: Ganjil</td></tr>
                        <tr><th>Tahun Ajaran</th><td>: 2025/2026</td></tr>
                        <tr><th>Sekolah</th><td>: SMAN 13 Kota Jambi</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>