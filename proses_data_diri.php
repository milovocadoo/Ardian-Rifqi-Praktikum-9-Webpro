<?php
// Konfigurasi Koneksi Database Laragon
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_data_diri";

// Menghubungkan ke MySQL
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Ambil dan amankan data nama
    $nama = htmlspecialchars($_POST['nama']);

    // 2. Olah Tanggal Lahir (Format awal: YYYY-MM-DD menjadi format Indonesia)[cite: 1, 2]
    $tgl_input = $_POST['tgl_lahir'];
    $pecah_tgl = explode('-', $tgl_input); 

    $daftar_bulan = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
        '04' => 'April', '05' => 'Mei', '06' => 'Juni',
        '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
        '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    ];

    $tahun = $pecah_tgl[0];
    $bulan = $daftar_bulan[$pecah_tgl[1]];
    $tanggal = (int)$pecah_tgl[2]; // Menghilangkan angka 0 di depan, misal 07 menjadi 7[cite: 1, 2]

    $tgl_lahir_indo = $tanggal . " " . $bulan . " " . $tahun;

    // 3. Olah Hobi (Gabungkan dengan koma atau tampilkan pesan jika kosong)[cite: 1]
    if (isset($_POST['hobi']) && !empty($_POST['hobi'])) {
        $hobi_tampil = implode(", ", $_POST['hobi']);
    } else {
        $hobi_tampil = "Tidak ada hobi yang dipilih";
    }

    // 4. SIMPAN DATA KE DATABASE MYSQL
    $query = "INSERT INTO tabel_mahasiswa (nama_lengkap, tanggal_lahir, hobi) VALUES ('$nama', '$tgl_input', '$hobi_tampil')";
    mysqli_query($koneksi, $query);

} else {
    header("Location: form_data_diri.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tampilan Data Anda</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9f9f9; margin: 40px; color: #222; }
        .card { max-width: 480px; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); }
        h1 { font-size: 24px; margin-top: 0; padding-bottom: 15px; border-bottom: 1px solid #eee; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 25px; }
        td { padding: 10px 0; vertical-align: top; font-size: 15px; }
        td.label { font-weight: bold; width: 140px; }
        a.back-link { color: #007bff; text-decoration: none; font-size: 14px; }
        a.back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="card">
    <h1>Tampilan Data Anda</h1>
    <table>
        <tr>
            <td class="label">Nama Lengkap:</td>
            <td><?php echo $nama; ?></td>
        </tr>
        <tr>
            <td class="label">Tanggal Lahir:</td>
            <td><?php echo $tgl_lahir_indo; ?></td>
        </tr>
        <tr>
            <td class="label">Hobi:</td>
            <td><?php echo htmlspecialchars($hobi_tampil); ?></td>
        </tr>
    </table>
    <p style="color: green; font-size: 13px; margin-bottom: 20px;">✓ Data berhasil disimpan ke database!</p>
    <a href="form_data_diri.html" class="back-link">&laquo; Kembali ke Form</a>
</div>

</body>
</html>