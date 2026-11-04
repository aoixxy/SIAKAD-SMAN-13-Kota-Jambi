<?php
/** 
 * @var int $hadir 
 * @var int $sakit 
 * @var int $izin 
 * @var int $alpa 
 * @var string $tahun_akademik
 * @var string $semester
 * @var array $user 
 * @var array $siswa 
 * @var array $guru 
 * @var array $ruangan 
 */ 
?>

<section class="content">

    <?php if ($this->session->userdata('id_level_user') == 5 && !empty($tahun_akademik)) : ?>
        <div class="row">
            <div class="col-md-12">
                <div class="info-box bg-aqua">
                    <span class="info-box-icon"><i class="fa fa-calendar"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Tahun Akademik Aktif</span>
                        <span class="info-box-number"><?php echo $tahun_akademik; ?></span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">
                            Semester: <strong><?php echo strtoupper($semester); ?></strong>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($this->session->userdata('id_level_user') == 5) : ?>
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3><?php echo $hadir; ?></h3>
                        <p>Hadir</p>
                    </div>
                    <div class="icon"><i class="fa fa-check"></i></div>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?php echo $sakit; ?></h3>
                        <p>Sakit</p>
                    </div>
                    <div class="icon"><i class="fa fa-medkit"></i></div>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3><?php echo $izin; ?></h3>
                        <p>Izin</p>
                    </div>
                    <div class="icon"><i class="fa fa-envelope"></i></div>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3><?php echo $alpa; ?></h3>
                        <p>Alpa</p>
                    </div>
                    <div class="icon"><i class="fa fa-ban"></i></div>
                </div>
            </div>
        </div>

    <?php else : ?>
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?php echo $user['hasil']; ?></h3>
                        <p>Pengguna Sistem</p>
                    </div>
                    <div class="icon"><i class="fa fa-id-badge"></i></div>
                    <a href="<?php echo site_url('user') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3><?php echo $siswa['hasil']; ?></h3>
                        <p>Siswa</p>
                    </div>
                    <div class="icon"><i class="fa fa-users"></i></div>
                    <a href="<?php echo site_url('siswa') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3><?php echo $guru['hasil']; ?></h3>
                        <p>Guru</p>
                    </div>
                    <div class="icon"><i class="fa fa-user-circle"></i></div>
                    <a href="<?php echo site_url('guru') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3><?php echo $ruangan['hasil']; ?></h3>
                        <p>Ruangan Kelas</p>
                    </div>
                    <div class="icon"><i class="fa fa-building"></i></div>
                    <a href="<?php echo site_url('ruangan') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    <?php endif; ?>

</section>