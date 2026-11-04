<?php 
/** * @var int $contents
 */ 

// Ambil level user dan foto dari session
$lvl = $this->session->userdata('id_level_user');
$foto_session = $this->session->userdata('foto');

// KONDISI: Jika yang login adalah Siswa (Level 5) dan dia punya foto, tampilkan fotonya.
// Jika bukan siswa (Admin/Guru/Kepala Sekolah), otomatis kunci pakai gambar default 'user icon.png'
if ($lvl == 5 && !empty($foto_session)) {
    $gambar_profil = base_url('uploads/siswa/' . $foto_session . '?t=' . time());
} else {
    $gambar_profil = base_url('assets/dist/img/user icon.png');
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SMAN 13 Kota Jambi | Dashboard</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/Ionicons/css/ionicons.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/jvectormap/jquery-jvectormap.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/skins/_all-skins.min.css">

  <script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <a href="#" class="logo">
      <span class="logo-mini"><b>S</b>13</span>
      <span class="logo-lg"><b>SMAN 13</b> JAMBI</span>
    </a>

    <nav class="navbar navbar-static-top">
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo $gambar_profil; ?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $this->session->userdata('nama_lengkap'); ?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="user-header">
                <img src="<?php echo $gambar_profil; ?>" class="img-circle" alt="User Image">
                <p>
                  <?php echo $this->session->userdata('nama_lengkap'); ?>
                  <small>
                    <?php 
                    if($lvl == 1){ echo "Admin"; }
                    elseif($lvl == 3){ echo "Guru"; }
                    elseif($lvl == 4){ echo "Kepala Sekolah"; }
                    elseif($lvl == 5){ echo "Siswa"; }
                    else { echo "Member"; }
                    ?> 
                    SMAN 13 Kota Jambi
                  </small>
                </p>
              </li>
              <li class="user-footer">
                <div class="text-center">
                  <?php echo anchor('auth/logout', '<button class="btn btn-danger btn-flat">Sign Out</button>'); ?>
                </div>
              </li>
            </ul>
          </li>
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <aside class="main-sidebar">
    <section class="sidebar">
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo $gambar_profil; ?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $this->session->userdata('nama_lengkap'); ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        
        <li>
            <a href="<?php echo base_url('tampilan_utama') ?>">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            </a>
        </li>

        <li>
            <a href="<?php echo base_url('absensi') ?>">
                <i class="fa fa-calendar-check-o"></i> <span>Absensi Siswa</span>
            </a>
        </li>

        <?php
        $sql_menu = "SELECT * FROM `tabel_menu` WHERE id IN(SELECT id_menu FROM tbl_user_rule WHERE id_level_user = $lvl) AND is_main_menu = 0";
        $main_menu = $this->db->query($sql_menu)->result();

        foreach ($main_menu as $main) {
            $submenu = $this->db->get_where('tabel_menu', array('is_main_menu' => $main->id));
            if ($submenu->num_rows() > 0) {
              echo "<li class='treeview'>".anchor($main->link,"<i class='".$main->icon."'></i>".
                   "<span>".$main->nama_menu."</span>".
                   '<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>');
              echo "<ul class='treeview-menu'>";
              foreach ($submenu->result() as $sub) {
                echo "<li>" .anchor($sub->link,"<i class='".$sub->icon."'></i>"."<span>".$sub->nama_menu."</span>"). "</li>";
              }
              echo "</ul></li>";
            } else {
              echo "<li>" .anchor($main->link,"<i class='".$main->icon."'></i>"."<span>".$main->nama_menu."</span>"). "</li>";
            }  
        }
        ?>
      </ul>
    </section>
  </aside>

  <div class="content-wrapper">
    <?php if ($lvl != 5) : ?>
    <section class="content-header">
      <h1>Dashboard <small>SMAN 13 Kota Jambi</small></h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>
    <?php endif; ?>

    <section class="content">
        <?php echo $contents; ?>
    </section>
  </div>

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.1.0
    </div>
    <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="#">SMAN 13 Kota Jambi</a>.</strong> All rights reserved.
  </footer>

  <div class="control-sidebar-bg"></div>
</div>

<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/fastclick/lib/fastclick.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/adminlte.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/Chart.js/Chart.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/demo.js"></script>

</body>
</html>