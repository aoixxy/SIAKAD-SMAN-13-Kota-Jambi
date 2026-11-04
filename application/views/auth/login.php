<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SMAN 13 Kota Jambi | Log in</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/Ionicons/css/ionicons.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <style>
    body, html {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: 'Source Sans Pro', sans-serif;
      background-color: #f8fafc !important;
    }
    
    /* Container Utama Memisahkan Kiri dan Kanan */
    .split-container {
      display: flex;
      height: 100vh;
      width: 100%;
    }

    /* ================= SISI KIRI: FORM LOGIN ================= */
    .left-side {
      width: 50%;
      background: #ffffff;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 0 10%;
      box-sizing: border-box;
    }
    .brand-header {
      display: flex;
      align-items: center;
      margin-bottom: 50px;
    }
    .brand-logo-img {
      width: 65px;
      height: 65px;
      object-fit: contain;
      margin-right: 15px;
    }
    .brand-title h1 {
      margin: 0;
      font-size: 18px;
      font-weight: 700;
      color: #1e293b;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .brand-title p {
      margin: 0;
      font-size: 13px;
      color: #64748b;
    }
    .login-heading {
      font-size: 28px;
      font-weight: 800;
      color: #0f172a;
      margin-bottom: 40px;
    }

    /* Mengubah Gaya Input Box AdminLTE Menjadi Gaya Garis (Modern) */
    .custom-form-group {
      position: relative;
      border-bottom: 2px solid #e2e8f0;
      margin-bottom: 30px;
      padding-bottom: 5px;
    }
    .custom-form-group:focus-within {
      border-bottom-color: #2563eb;
    }
    .custom-form-group label {
      display: block;
      font-size: 11px;
      font-weight: 600;
      color: #94a3b8;
      text-transform: uppercase;
      margin-bottom: 5px;
    }
    .custom-form-group .form-control {
      background: transparent !important;
      border: none !important;
      box-shadow: none !important;
      padding-left: 0 !important;
      font-size: 16px;
      color: #334155;
      height: auto;
    }
    .custom-form-group .form-control-feedback {
      right: 0;
      left: auto;
      color: #94a3b8;
      line-height: 35px;
    }

    /* Tombol Login Biru Cerah & Melengkung */
    .btn-modern {
      background-color: #2563eb !important;
      border-color: #2563eb !important;
      color: #ffffff !important;
      padding: 12px 20px !important;
      font-size: 16px !important;
      font-weight: 700 !important;
      border-radius: 8px !important;
      box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3) !important;
      transition: all 0.2s;
    }
    .btn-modern:hover {
      background-color: #1d4ed8 !important;
      box-shadow: 0 10px 15px -3px rgba(29, 78, 216, 0.5) !important;
    }
    .lupa-password {
      display: block;
      text-align: right;
      margin-bottom: 25px;
      font-weight: 600;
      color: #2563eb;
      text-decoration: none;
    }

    /* ================= SISI KANAN: BACKGROUND & INFO ================= */
    .right-side {
      width: 50%;
      background-color: #f1f5f9;
      position: relative;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    /* Lengkungan Gradasi Biru Kanan Atas */
    .blue-curve {
      position: absolute;
      top: 0;
      right: 0;
      width: 80%;
      height: 35%;
      background: linear-gradient(135deg, #1e40af, #3b82f6);
      border-bottom-left-radius: 100px;
      z-index: 1;
      padding: 30px;
      box-sizing: border-box;
      display: flex;
      justify-content: flex-end;
    }
    .header-contact {
      color: rgba(255, 255, 255, 0.9);
      font-size: 13px;
      font-weight: 500;
      background: rgba(255, 255, 255, 0.15);
      padding: 8px 20px;
      border-radius: 50px;
      height: max-content;
      backdrop-filter: blur(5px);
    }
    .header-contact i { margin-right: 5px; }
    .header-contact span { margin-left: 15px; }

    /* Gambar Ilustrasi */
    .side-illustration {
      position: absolute;
      right: 0;
      top: 25%;
      width: 75%;
      z-index: 2;
      border-radius: 20px 0 0 20px;
      box-shadow: -10px 20px 25px -5px rgba(0, 0, 0, 0.1);
      border-left: 6px solid #ffffff;
    }

    /* Kotak Info Singkat */
    .info-box-container {
      position: absolute;
      bottom: 40px;
      left: 40px;
      width: 380px;
      background: #2563eb;
      color: #ffffff;
      border-radius: 12px;
      padding: 20px;
      z-index: 3;
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
      border: 1px solid #60a5fa;
    }
    .info-box-title {
      font-size: 16px;
      font-weight: 700;
      border-bottom: 1px solid rgba(255, 255, 255, 0.2);
      padding-bottom: 10px;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
    }
    .info-box-title i { margin-right: 10px; font-size: 18px; }
    .info-content-scroll {
      max-height: 160px;
      overflow-y: auto;
    }
    .info-item {
      background: rgba(255, 255, 255, 0.1);
      padding: 10px;
      border-radius: 6px;
      margin-bottom: 10px;
      font-size: 13px;
    }
    .info-date {
      font-size: 11px;
      color: #bfdbfe;
      margin-bottom: 3px;
      font-weight: 600;
    }

    /* Responsif untuk Layar HP/Tablet Kecil */
    @media (max-width: 991px) {
      .right-side { display: none; }
      .left-side { width: 100%; padding: 0 5%; }
    }
  </style>
</head>
<body>

<div class="split-container">

    <div class="left-side">
        
        <div class="brand-header">
            <img src="<?php echo base_url(); ?>assets/dist/img/channels4_profile.jpg" class="brand-logo-img" alt="Logo SMAN 13">
            <div class="brand-title">
                <h1>Sistem Informasi Akademik</h1>
                <p>SMAN 13 Kota Jambi</p>
            </div>
        </div>

        <div class="login-heading">Portal Masuk</div>

        <?php echo form_open('auth/check_login'); ?>

            <div class="custom-form-group has-feedback">
                <label>Masukkan Username / NIS</label>
                <input type="text" name="username" class="form-control" placeholder="Username Anda" required>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>

            <div class="custom-form-group has-feedback">
                <label>Masukkan Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>

            <div class="row" style="display: flex; align-items: center;">
                <div class="col-xs-7">
                     <div class="checkbox" style="margin: 0;">
                       <label style="font-weight: 600; color: #64748b;">
                         <input type="checkbox"> Remember Me
                       </label>
                     </div>
                </div>
                <div class="col-xs-5">
                    <button type="submit" name="submit" class="btn btn-primary btn-block btn-flat btn-modern">Login</button>
                </div>
            </div>

        </form>
    </div>

    <div class="right-side">
        
        <div class="blue-curve">
            <div class="header-contact">
                <span><i class="fa fa-phone"></i> 082280346126 </span>
                <span><i class="fa fa-envelope"></i> sman13kotajambi@gmail.com </span>
            </div>
        </div>

        <img src="https://images.unsplash.com/photo-1531403009284-440f080d1e12?auto=format&fit=crop&w=800&q=80" 
             class="side-illustration" alt="Ilustrasi Akademik">

    </div>

</div>

<script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>