<?php
// =============================================
// HALAMAN RESERVASI TIKET
// =============================================

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'config/database.php';

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

// Ambil data film
$query = "SELECT * FROM films WHERE id = $id";
$result = query($query);

if (countRows($result) == 0) {
    header("Location: index.php");
    exit;
}

$film = fetchOne($result);

// Ambil jadwal tayang
$query_jadwal = "SELECT * FROM jadwal_tayang WHERE film_id = $id";
$result_jadwal = query($query_jadwal);
$jadwals = fetchAll($result_jadwal);

// Proses form
$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $nama = trim($_POST['nama'] ?? '');
    $telepon = trim($_POST['telepon'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $jadwal_id = intval($_POST['jadwal'] ?? 0);
    $jumlah = intval($_POST['jumlah'] ?? 0);
    
    // Validasi
    if (empty($nama)) {
        $error = 'Nama pemesan harus diisi!';
    } elseif (empty($telepon)) {
        $error = 'Nomor telepon harus diisi!';
    } elseif (empty($email)) {
        $error = 'Email harus diisi!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid!';
    } elseif ($jadwal_id <= 0) {
        $error = 'Pilih jadwal tayang!';
    } elseif ($jumlah < 1 || $jumlah > 10) {
        $error = 'Jumlah tiket harus 1-10!';
    }
    
    // Jika tidak ada error, simpan
    if (empty($error)) {
        // Ambil data jadwal
        $query_jd = "SELECT * FROM jadwal_tayang WHERE id = $jadwal_id";
        $result_jd = query($query_jd);
        $jadwal_detail = fetchOne($result_jd);
        
        if ($jadwal_detail) {
            // Hitung total
            $total = $film['harga'] * $jumlah;
            
            // Generate kode reservasi
            $kode = 'RES-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
            
            // Simpan ke database
            $query_insert = "INSERT INTO reservasi 
                            (kode_reservasi, nama_pemesan, nomor_telepon, email, 
                             film_id, jadwal_id, jumlah_tiket, total_harga) 
                            VALUES (
                                '$kode',
                                '" . escapeString($nama) . "',
                                '" . escapeString($telepon) . "',
                                '" . escapeString($email) . "',
                                $id,
                                $jadwal_id,
                                $jumlah,
                                $total
                            )";
            
            if (query($query_insert)) {
                // Simpan ke session
                $_SESSION['reservasi'] = [
                    'kode' => $kode,
                    'nama' => $nama,
                    'telepon' => $telepon,
                    'email' => $email,
                    'film' => $film['judul'],
                    'jadwal' => $jadwal_detail['hari'] . ' - ' . $jadwal_detail['jam'] . 
                               ' (' . $jadwal_detail['studio'] . ')',
                    'jumlah' => $jumlah,
                    'total' => $total
                ];
                
                header("Location: konfirmasi.php");
                exit;
            } else {
                $error = 'Gagal menyimpan reservasi: ' . mysqli_error($conn);
            }
        } else {
            $error = 'Jadwal tidak ditemukan!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi Tiket - Bioskop Online</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-film me-2"></i>Bioskop Online
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-home"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <i class="fas fa-ticket-alt"></i> Reservasi
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Form Reservasi -->
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="reservation-form">
                    <h3 class="mb-4">
                        <i class="fas fa-ticket-alt text-primary me-2"></i>
                        Reservasi Tiket
                    </h3>

                    <!-- Informasi Film -->
                    <div class="film-info">
                        <div class="row">
                            <div class="col-md-3">
                                <img src="poster/<?php echo $film['poster']; ?>" 
                                     class="img-fluid rounded"
                                     alt="<?php echo htmlspecialchars($film['judul']); ?>"
                                     onerror="this.src='poster/default.jpg'"
                                     style="max-height: 100px; object-fit: cover;">
                            </div>
                            <div class="col-md-9">
                                <h5><?php echo htmlspecialchars($film['judul']); ?></h5>
                                <div>
                                    <span class="badge bg-primary"><?php echo htmlspecialchars($film['genre']); ?></span>
                                    <span class="badge bg-secondary"><?php echo htmlspecialchars($film['durasi']); ?></span>
                                </div>
                                <p class="mt-2 mb-0">
                                    <strong>Harga Tiket:</strong> 
                                    <span class="text-success">Rp <?php echo number_format($film['harga'], 0, ',', '.'); ?></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Error -->
                    <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo $error; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Form -->
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Pemesan <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="nama" 
                                       class="form-control" 
                                       placeholder="Masukkan nama lengkap"
                                       value="<?php echo htmlspecialchars($_POST['nama'] ?? ''); ?>"
                                       required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                                <input type="tel" 
                                       name="telepon" 
                                       class="form-control" 
                                       placeholder="08xxxxxxxxxx"
                                       value="<?php echo htmlspecialchars($_POST['telepon'] ?? ''); ?>"
                                       required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" 
                                   name="email" 
                                   class="form-control" 
                                   placeholder="example@email.com"
                                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jadwal Tayang <span class="text-danger">*</span></label>
                            <select name="jadwal" class="form-select" required>
                                <option value="">Pilih Jadwal</option>
                                <?php foreach ($jadwals as $jadwal): ?>
                                <option value="<?php echo $jadwal['id']; ?>"
                                    <?php echo (($_POST['jadwal'] ?? '') == $jadwal['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($jadwal['hari'] . ' - ' . $jadwal['jam'] . ' (' . $jadwal['studio'] . ')'); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Jumlah Tiket <span class="text-danger">*</span></label>
                            <input type="number" 
                                   name="jumlah" 
                                   class="form-control" 
                                   min="1" 
                                   max="10" 
                                   placeholder="1-10"
                                   value="<?php echo htmlspecialchars($_POST['jumlah'] ?? 1); ?>"
                                   required>
                            <small class="text-muted">Maksimal 10 tiket per transaksi</small>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="detail.php?id=<?php echo $film['id']; ?>" 
                               class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-submit">
                                <i class="fas fa-check me-2"></i>Pesan Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer-custom">
        <div class="container text-center">
            <p class="mb-0">&copy; 2024 Bioskop Online. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>