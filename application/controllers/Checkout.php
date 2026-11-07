<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 * @property CI_Loader $load
 * @property CI_Input $input
 * @property CI_Session $session
 * @property Fonnte $fonnte
 */
class Checkout extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Load library Fonnte
        $this->load->library('fonnte');
    }

    public function proses_order()
    {
        // 1. Ambil data dari form atau database
        $nama     = $this->input->post('nama');
        $no_wa    = $this->input->post('no_hp'); // contoh: 081234567890
        $no_order = 'INV-' . time();

        // 2. Susun template pesan
        $pesan  = "Halo *$nama*,\n\n";
        $pesan .= "Terima kasih telah melakukan pemesanan.\n";
        $pesan .= "Nomor Invoice: *$no_order*\n\n";
        $pesan .= "Silakan lakukan pembayaran sesuai dengan instruksi di website.";

        // 3. Kirim notifikasi WA via Fonnte Library
        $kirim = $this->fonnte->send($no_wa, $pesan);

        // 4. Cek respons kirim (Opsional)
        if (isset($kirim['status']) && $kirim['status'] == true) {
            // Berhasil terkirim
            $this->session->set_flashdata('success', 'Order berhasil dan notifikasi WA telah dikirim!');
        } else {
            // Gagal terkirim (bisa karena token salah, HP mati, dll)
            $this->session->set_flashdata('error', 'Order berhasil, tapi notifikasi WA gagal terkirim.');
        }

        redirect('checkout/sukses');
    }
}