<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fonnte {

    protected $ci;
    private $token = 'CjEPYbxRVzgiPHumErgi'; // Token Fonnte kamu

    public function __construct()
    {
        $this->ci =& get_instance();
    }

    /**
     * Kirim Pesan / File Dokumen via Fonnte
     */
    public function send($target, $message, $file_url = '', $filename = '')
    {
        $postData = array(
            'target'      => $target,
            'message'     => $message,
            'countryCode' => '62',
        );

        // Jika ada attachment file PDF
        if (!empty($file_url)) {
            $postData['url'] = $file_url;
            if (!empty($filename)) {
                $postData['filename'] = $filename;
            }
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => array(
                "Authorization: " . $this->token,
                "ngrok-skip-browser-warning: true" // Solusi 1: Bypass halaman peringatan Ngrok
            ),
        ));

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        if ($error) {
            return array('status' => false, 'message' => $error);
        }

        return json_decode($response, true);
    }
}