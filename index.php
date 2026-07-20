<?php
// =============================================
// HALAMAN BERANDA - BIOSKOP ONLINE
// =============================================

// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include koneksi database
include 'config/database.php';

// Ambil keyword pencarian
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Query untuk mengambil data film
$query = "SELECT * FROM films";
if (!empty($search)) {
    $search_escaped = escapeString($search);
    $query .= " WHERE judul LIKE '%$search_escaped%'";
}
$query .= " ORDER BY created_at DESC";

$result = query($query);
$films = fetchAll($result);
$total_films = countRows($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bioskop Online - Reservasi Tiket</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
<link rel="stylesheet" href="/bioskop_online/assets/css/style.css">
<style>
    /* TEST - APAKAH CSS BEKERJA? */
    body {
        background: #1a1a2e !important;
    }
    h1 {
        color: #6C3CE1 !important;
    }
</style>
</head>
<body>
    <!-- ========== NAVBAR ========== -->
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
                        <a class="nav-link active" href="index.php">
                            <i class="fas fa-home"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#films">
                            <i class="fas fa-film"></i> Film
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">
                            <i class="fas fa-info-circle"></i> Tentang
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ========== HERO SECTION ========== -->
    <section class="hero-section">
        <div class="container text-center">
            <h1>
                <i class="fas fa-ticket-alt me-3"></i>Bioskop Online
            </h1>
            <p class="lead">Pesan tiket film favorit Anda dengan mudah dan cepat</p>
            <p>Nikmati pengalaman menonton tanpa antre! 🎬</p>
        </div>
    </section>

    <!-- ========== SEARCH BOX ========== -->
    <div class="search-box">
        <form method="GET" action="index.php">
            <div class="input-group">
                <input type="text" 
                       name="search" 
                       class="form-control" 
                       placeholder="Cari film berdasarkan judul..."
                       value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-search" type="submit">
                    <i class="fas fa-search me-2"></i>Cari
                </button>
            </div>
        </form>
    </div>

    <!-- ========== DAFTAR FILM ========== -->
    <div class="container" id="films">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-film text-primary me-2"></i>Daftar Film
            </h2>
            <span class="badge bg-primary"><?php echo $total_films; ?> Film</span>
        </div>

        <?php if ($total_films > 0): ?>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php foreach ($films as $film): ?>
            <div class="col">
                <div class="film-card">
                    <img src="poster/<?php echo $film['poster']; ?>" 
                         alt="<?php echo htmlspecialchars($film['judul']); ?>"
                         onerror="this.src='poster/default.jpg'">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($film['judul']); ?></h5>
                        <div class="mb-2">
                            <span class="badge bg-primary"><?php echo htmlspecialchars($film['genre']); ?></span>
                            <span class="badge bg-secondary"><?php echo htmlspecialchars($film['durasi']); ?></span>
                        </div>
                        <div class="price">
                            Rp <?php echo number_format($film['harga'], 0, ',', '.'); ?>
                        </div>
                        <div class="mb-2">
                            <i class="fas fa-star text-warning"></i> <?php echo $film['rating']; ?>/10
                        </div>
                        <a href="detail.php?id=<?php echo $film['id']; ?>" 
                           class="btn btn-detail">
                            <i class="fas fa-eye me-2"></i>Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-search" style="font-size: 60px; color: #ccc;"></i>
            <h4 class="mt-3">Film Tidak Ditemukan</h4>
            <p>Maaf, film dengan judul "<?php echo htmlspecialchars($search); ?>" tidak tersedia.</p>
            <a href="index.php" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
        <?php endif; ?>
    </div>

    <!-- ========== ABOUT SECTION ========== -->
    <section class="container py-5" id="about">
        <div class="row">
            <div class="col-lg-6">
                <h2>Tentang Bioskop Online</h2>
                <p class="lead">Platform reservasi tiket bioskop modern dan praktis.</p>
                <p>
                    Bioskop Online hadir untuk memudahkan Anda dalam memesan tiket film 
                    favorit tanpa harus antre di lokasi.
                </p>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p><i class="fas fa-check-circle text-success me-2"></i>Reservasi Mudah</p>
                        <p><i class="fas fa-check-circle text-success me-2"></i>Informasi Lengkap</p>
                    </div>
                    <div class="col-md-6">
                        <p><i class="fas fa-check-circle text-success me-2"></i>Pembayaran Aman</p>
                        <p><i class="fas fa-check-circle text-success me-2"></i>Dukungan 24/7</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="bg-light p-4 rounded">
                    <h5><i class="fas fa-info-circle text-primary me-2"></i>Info Kontak</h5>
                    <p><i class="fas fa-map-marker-alt me-2"></i>Jl. Bioskop No. 123, Jakarta</p>
                    <p><i class="fas fa-phone me-2"></i>(021) 123-4567</p>
                    <p><i class="fas fa-envelope me-2"></i>info@bioskoponline.com</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== FOOTER ========== -->
    <footer class="footer-custom">
        <div class="container text-center">
            <p class="mb-0">&copy; 2024 M Bioskop. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>