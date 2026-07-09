<?php
// Ambil parameter NIM dan Nama dari kiriman FormData JavaScript
$nim  = isset($_POST['nim']) ? trim($_POST['nim']) : '60900124062';
$nama = isset($_POST['nama']) ? trim($_POST['nama']) : 'Aulia Wulandari';

if (empty($nim) || empty($nama)) {
    die("Gagal: Parameter identitas mahasiswa tidak lengkap.");
}

// ========================================================
// KUNCI UTAMA: Pembuatan Subfolder Otomatis Sesuai Aturan Anda
// ========================================================
$direktori_utama = "uploads/";
// Menghilangkan spasi dan karakter aneh pada nama agar folder terbentuk dengan rapi (Contoh: 60900124062_AuliaWulandari)
$nama_folder_mhs = $nim . "_" . preg_replace("/[^a-zA-Z0-9]/", "", $nama); 
$jalur_subfolder = $direktori_utama . $nama_folder_mhs . "/";

// Jika subfolder khusus mahasiswa tersebut belum ada di komputer, otomatis buat di sini
if (!file_exists($jalur_subfolder)) {
    mkdir($jalur_subfolder, 0777, true);
}

// ========================================================
// PROSES PENANGANAN MULTI-FILE UPLOAD (ARRAY)
// ========================================================
if (isset($_FILES['berkas_sidang'])) {
    $files = $_FILES['berkas_sidang'];
    
    // Menghitung jumlah berkas yang dikirim dari form dinamis mahasiswa
    $jumlah_berkas = count($files['name']);
    
    // Looping untuk memindahkan semua berkas satu per satu ke dalam subfolder khusus mahasiswa
    for ($i = 0; $i < $jumlah_berkas; $i++) {
        // Pastikan tidak ada eror saat proses upload berkas tersebut
        if ($files['error'][$i] === UPLOAD_ERR_OK) {
            $nama_file_asli  = $files['name'][$i];
            $lokasi_sementara = $files['tmp_name'][$i];
            
            // Mengunci tujuan akhir berkas ke dalam subfolder mahasiswa
            $tujuan_akhir = $jalur_subfolder . $nama_file_asli;
            
            // Pindahkan file secara otomatis
            move_uploaded_file($lokasi_sementara, $tujuan_akhir);
        }
    }
    echo "Semua_Berkas_Sukses_Masuk_Subfolder";
} else {
    echo "Tidak_Ada_Berkas_Diterima";
}
?>