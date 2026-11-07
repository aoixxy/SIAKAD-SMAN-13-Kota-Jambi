<?php /** 
 * @var array $kelas_list
 * @var array $kelas
 * @var mixed $siswa
 * @var string $selected_kelas
 * */ 
?>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<section class="content">
    <div class="row">
        <?php if ($this->session->userdata('id_level_user') == 1 || $this->session->userdata('id_level_user') == 4): ?>
            <div class="col-xs-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Monitoring Laporan Nilai Siswa Per Kelas</h3>
                    </div>
                    <div class="box-body">
                        <?php echo form_open('laporan_nilai/index', array('method' => 'get', 'id' => 'form-filter-monitoring')); ?>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label>Pilih Kelas Terlebih Dahulu :</label>
                            <select name="filter_kelas" class="form-control" onchange="this.form.submit()">
                                <option value="">-- Pilih Kelas untuk Memantau Data Siswa --</option>
                                <?php foreach($kelas_list as $kls): ?>
                                    <option value="<?php echo $kls->kd_kelas; ?>" <?php echo (isset($selected_kelas) && $selected_kelas == $kls->kd_kelas) ? 'selected' : ''; ?>>
                                        <?php echo $kls->nama_kelas; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($kelas)): ?>
            <div class="col-xs-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <table class="table table-bordered" style="margin-bottom: 0;">
                            <tr>
                                <td width="200" style="font-weight: bold; background-color: #f9f9f9;">Tahun Akademik</td>
                                <td> : <?php echo get_tahun_akademik('tahun_akademik'); ?></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold; background-color: #f9f9f9;">Semester</td>
                                <td> : <?php echo get_tahun_akademik('semester'); ?></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold; background-color: #f9f9f9;">Jurusan &amp; Tingkatan</td>
                                <td> : <?php echo "Jurusan".' '.$kelas['nama_jurusan'].' '.$kelas['nama_tingkatan']; ?> (<?php echo $kelas['nama_kelas']; ?>)</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-users"></i> Daftar Siswa, Ekstrakurikuler & Cetak Raport</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="bg-blue">
                                    <th class="text-center" width="30">NO</th>
                                    <th class="text-center" width="80">NIM</th>
                                    <th width="200">NAMA SISWA</th>
                                    <th class="text-center" width="180">EKSTRAKURIKULER</th>
                                    <th width="250">CATATAN WALI KELAS</th>
                                    <th class="text-center" width="180">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $id_tahun_aktif = get_tahun_akademik('id_tahun_akademik');
                                if (!empty($siswa) && $siswa->num_rows() > 0):
                                    foreach ($siswa->result() as $row) {
                                        // Catatan Umum
                                        $catatan_db = $this->db->get_where('tbl_nilai_catatan', array('nim' => $row->nim))->row_array();
                                        $isi_catatan = $catatan_db ? $catatan_db['catatan'] : '';

                                        // Hitung Jumlah Ekskul Siswa
                                        $jml_ekstra = $this->db->get_where('tbl_nilai_ekstra', array('nim' => $row->nim, 'id_tahun_akademik' => $id_tahun_aktif))->num_rows();

                                        $isKepsek = ($this->session->userdata('id_level_user') == 4);
                                        $disabledAttr = $isKepsek ? "disabled='disabled'" : "";
                                        
                                        echo "<tr>
                                            <td class='text-center'>$no</td>
                                            <td class='text-center'>$row->nim</td>
                                            <td><b>".strtoupper($row->nama)."</b></td>
                                            
                                            <!-- Tombol Kelola Ekstrakurikuler -->
                                            <td class='text-center'>
                                                <button type='button' class='btn btn-sm btn-info btn-block' onclick='modalEkstra(\"$row->nim\", \"".addslashes($row->nama)."\")'>
                                                    <i class='fa fa-star'></i> Ekskul (<span id='count_ekstra_".$row->nim."'>$jml_ekstra</span>)
                                                </button>
                                            </td>

                                            <!-- Input Catatan Wali Kelas -->
                                            <td>
                                                <input type='text' id='catatan_".$row->nim."' class='form-control input-sm' $disabledAttr placeholder='Ketik catatan wali kelas...' value='$isi_catatan' onchange='updateCatatanUmum(\"$row->nim\", this.value)'>
                                            </td>

                                            <td class='text-center'>
                                                <div class='btn-group'>
                                                    <button type='button' class='btn btn-sm btn-danger' id='btn_cetak_".$row->nim."' onclick='validasiDanCetak(\"$row->nim\", \"".addslashes($row->nama)."\")' title='Cetak PDF'>
                                                        <i class='fa fa-file-pdf-o'></i> Cetak
                                                    </button>
                                                    <button type='button' class='btn btn-sm btn-success' id='btn_wa_".$row->nim."' onclick='validasiDanKirimWA(\"$row->nim\", \"".addslashes($row->nama)."\")' title='Kirim Raport PDF via WhatsApp'>
                                                        <i class='fa fa-whatsapp'></i> Kirim WA
                                                    </button>
                                                </div>
                                            </td>
                                         </tr>";
                                        $no++;
                                    }
                                else:
                                ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-danger">Belum ada data riwayat kelas/siswa pada rombel ini untuk periode aktif.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="col-xs-12">
                <div class="alert alert-warning text-center" style="margin-top: 10px;">
                    <h4><i class="icon fa fa-info-circle"></i> Pemberitahuan</h4>
                    Silakan tentukan pilihan kelas pada dropdown filter di atas untuk menampilkan daftar riwayat raport siswa.
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- MODAL INPUT & EDIT MULTI EKSTRAKURIKULER -->
<div class="modal fade" id="modal-ekstra" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-blue">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-star"></i> Kelola Ekstrakurikuler : <span id="modal-nama-siswa" style="font-weight: bold;"></span></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modal-nim-siswa">
                <input type="hidden" id="input_id_ekstra" value="0"> <!-- 0 = Mode Tambah, >0 = Mode Edit -->
                
                <?php if ($this->session->userdata('id_level_user') != 4): ?>
                    <!-- Form Input / Edit Ekskul -->
                    <div class="panel panel-default">
                        <div class="panel-heading"><b id="panel-title-ekstra">Tambah Kegiatan Ekstrakurikuler</b></div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nama Ekstrakurikuler</label>
                                        <input type="text" id="input_nama_ekstra" class="form-control" placeholder="Misal: Pramuka, PMR, Basket">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Predikat</label>
                                        <select id="input_predikat_ekstra" class="form-control">
                                            <option value="Sangat Baik">Sangat Baik</option>
                                            <option value="Baik" selected>Baik</option>
                                            <option value="Cukup">Cukup</option>
                                            <option value="Kurang">Kurang</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Keterangan Kegiatan</label>
                                        <div class="input-group">
                                            <input type="text" id="input_ket_ekstra" class="form-control" placeholder="Keterangan pencapaian...">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-primary" id="btn-simpan-ekstra" onclick="simpanEkstra()"><i class="fa fa-plus"></i> Tambah</button>
                                                <button type="button" class="btn btn-default" id="btn-batal-ekstra" onclick="resetFormEkstra()" style="display:none;"><i class="fa fa-times"></i> Batal</button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Tabel Daftar Ekskul Siswa -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="bg-gray">
                                <th class="text-center" width="40">NO</th>
                                <th>NAMA EKSTRAKURIKULER</th>
                                <th class="text-center" width="120">PREDIKAT</th>
                                <th>KETERANGAN</th>
                                <?php if ($this->session->userdata('id_level_user') != 4): ?>
                                    <th class="text-center" width="90">AKSI</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody id="list-ekstra-body">
                            <!-- Diisi via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // Data temporary untuk penampungan edit
    var globalEkstraData = [];

    // 1. Buka Modal Ekstrakurikuler
    function modalEkstra(nim, nama) {
        $('#modal-nim-siswa').val(nim);
        $('#modal-nama-siswa').text(nama);
        resetFormEkstra();
        
        loadDaftarEkstra(nim);
        $('#modal-ekstra').modal('show');
    }

    // Reset Form Input ke Mode Tambah Default
    function resetFormEkstra() {
        $('#input_id_ekstra').val('0');
        $('#input_nama_ekstra').val('');
        $('#input_ket_ekstra').val('');
        $('#input_predikat_ekstra').val('Baik');
        $('#panel-title-ekstra').text('Tambah Kegiatan Ekstrakurikuler');
        $('#btn-simpan-ekstra').removeClass('btn-success').addClass('btn-primary').html('<i class="fa fa-plus"></i> Tambah');
        $('#btn-batal-ekstra').hide();
    }

    // 2. Load Daftar Ekskul Siswa via AJAX
    function loadDaftarEkstra(nim) {
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("laporan_nilai/get_list_ekstra") ?>',
            data: { nim: nim },
            dataType: 'json',
            success: function(data) {
                globalEkstraData = data; // simpan data global
                var html = '';
                var count = data.length;
                $('#count_ekstra_' + nim).text(count);

                if (count > 0) {
                    $.each(data, function(i, item) {
                        html += '<tr>' +
                            '<td class="text-center">' + (i + 1) + '</td>' +
                            '<td><b>' + item.nama_ekstra + '</b></td>' +
                            '<td class="text-center"><span class="label label-success">' + item.predikat + '</span></td>' +
                            '<td>' + (item.keterangan ? item.keterangan : '-') + '</td>';
                            
                        <?php if ($this->session->userdata('id_level_user') != 4): ?>
                            html += '<td class="text-center">' +
                                '<div class="btn-group">' +
                                    '<button type="button" class="btn btn-xs btn-warning" onclick="editEkstra(' + item.id_ekstra + ')" title="Edit"><i class="fa fa-pencil"></i></button>' +
                                    '<button type="button" class="btn btn-xs btn-danger" onclick="hapusEkstra(' + item.id_ekstra + ')" title="Hapus"><i class="fa fa-trash"></i></button>' +
                                '</div>' +
                                '</td>';
                        <?php endif; ?>

                        html += '</tr>';
                    });
                } else {
                    html = '<tr><td colspan="5" class="text-center text-muted">Belum ada ekstrakurikuler yang ditambahkan.</td></tr>';
                }
                $('#list-ekstra-body').html(html);
            }
        });
    }

    // 3. Set Form ke Mode Edit saat Tombol Pensil Diklik
    function editEkstra(id_ekstra) {
        var item = globalEkstraData.find(x => x.id_ekstra == id_ekstra);
        if (item) {
            $('#input_id_ekstra').val(item.id_ekstra);
            $('#input_nama_ekstra').val(item.nama_ekstra);
            $('#input_predikat_ekstra').val(item.predikat);
            $('#input_ket_ekstra').val(item.keterangan);

            $('#panel-title-ekstra').text('Edit Kegiatan Ekstrakurikuler');
            $('#btn-simpan-ekstra').removeClass('btn-primary').addClass('btn-success').html('<i class="fa fa-check"></i> Update');
            $('#btn-batal-ekstra').show();
        }
    }

    // 4. Simpan / Update Ekstrakurikuler
    function simpanEkstra() {
        var nim         = $('#modal-nim-siswa').val();
        var id_ekstra   = $('#input_id_ekstra').val(); // 0 = baru, >0 = edit
        var nama_ekstra = $('#input_nama_ekstra').val();
        var predikat    = $('#input_predikat_ekstra').val();
        var keterangan  = $('#input_ket_ekstra').val();

        if (nama_ekstra.trim() === "") {
            Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Nama Ekstrakurikuler wajib diisi!' });
            return false;
        }

        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("laporan_nilai/simpan_ekstra") ?>',
            data: {
                id_ekstra: id_ekstra,
                nim: nim,
                nama_ekstra: nama_ekstra,
                predikat: predikat,
                keterangan: keterangan
            },
            success: function() {
                resetFormEkstra();
                loadDaftarEkstra(nim);

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true
                });
                Toast.fire({ icon: 'success', title: id_ekstra > 0 ? 'Data Ekskul Berhasil Diupdate' : 'Ekskul Berhasil Ditambahkan' });
            }
        });
    }

    // 5. Hapus Ekstrakurikuler
    function hapusEkstra(id_ekstra) {
        var nim = $('#modal-nim-siswa').val();
        Swal.fire({
            title: 'Hapus Ekskul ini?',
            text: 'Data yang dihapus tidak bisa dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo site_url("laporan_nilai/hapus_ekstra") ?>',
                    data: { id_ekstra: id_ekstra },
                    success: function() {
                        resetFormEkstra();
                        loadDaftarEkstra(nim);
                    }
                });
            }
        });
    }

    // 6. Function Simpan Catatan Umum
    function updateCatatanUmum(nim, catatan) {
        var level_user = "<?php echo $this->session->userdata('id_level_user'); ?>";
        if (level_user == 4) return false;

        if (catatan.trim() === "") {
            Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Catatan wali kelas wajib diisi!' });
            return false;
        }

        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("laporan_nilai/simpan_catatan") ?>',
            data: { nim: nim, catatan: catatan },
            success: function() {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true
                });
                Toast.fire({ icon: 'success', title: 'Catatan Wali Kelas Tersimpan' });
            }
        });
    }

    // 7. Validasi & Cetak PDF
    function validasiDanCetak(nim, nama) {
        var level_user = "<?php echo $this->session->userdata('id_level_user'); ?>";
        var catatan = $('#catatan_' + nim).val();

        if (level_user != 1 && level_user != 4) {
            if (catatan == undefined || catatan.trim() === "") {
                Swal.fire({ icon: 'warning', title: 'Catatan Masih Kosong!', text: 'Catatan Wali Kelas untuk ' + nama + ' belum diisi.' });
                return false;
            }
        }

        var btn = $('#btn_cetak_' + nim);
        btn.prop('disabled', true);

        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("laporan_nilai/cek_nilai_siswa") ?>',
            data: { nim: nim },
            dataType: 'json',
            success: function(response) {
                btn.prop('disabled', false);
                if (response.status === 'success') {
                    var urlCetak = "<?php echo site_url('laporan_nilai/nilai_semester/') ?>" + nim;
                    window.open(urlCetak, '_blank');
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal Mencetak', text: response.message });
                }
            },
            error: function() { btn.prop('disabled', false); }
        });
    }

    // 8. Validasi & Kirim WA
    function validasiDanKirimWA(nim, nama) {
        var level_user = "<?php echo $this->session->userdata('id_level_user'); ?>";
        var catatan = $('#catatan_' + nim).val();

        if (level_user == 4) return false;

        if (level_user != 1 && (catatan == undefined || catatan.trim() === "")) {
            Swal.fire({ icon: 'warning', title: 'Catatan Masih Kosong!', text: 'Harap isi catatan wali kelas terlebih dahulu.' });
            return false;
        }

        Swal.fire({
            title: 'Kirim Raport via WA?',
            text: 'Dokumen PDF untuk ' + nama + ' akan dikirim.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'Ya, Kirim!'
        }).then((result) => {
            if (result.isConfirmed) {
                var btn = $('#btn_wa_' + nim);
                btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Sending...');

                $.ajax({
                    type: 'POST',
                    url: '<?php echo site_url("laporan_nilai/simpan_catatan") ?>',
                    data: { nim: nim, catatan: catatan },
                    success: function() {
                        $.ajax({
                            type: 'POST',
                            url: '<?php echo site_url("laporan_nilai/kirim_pdf_wa") ?>',
                            data: { nim: nim },
                            dataType: 'json',
                            success: function(response) {
                                btn.prop('disabled', false).html('<i class="fa fa-whatsapp"></i> Kirim WA');
                                Swal.fire({
                                    icon: response.status === 'success' ? 'success' : 'error',
                                    title: response.status === 'success' ? 'Berhasil' : 'Gagal',
                                    text: response.message
                                });
                            }
                        });
                    }
                });
            }
        });
    }
</script>