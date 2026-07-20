<?php
// =============================================
// HALAMAN DETAIL FILM
// =============================================

error_reporting(E_ALL);
ini_set('display_errors', 1);

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
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($film['judul']); ?> - Bioskop Online</title>
    
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
                            <i class="fas fa-info-circle"></i> Detail
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Detail Content -->
    <div class="container py-4">
        <div class="detail-container">
            <div class="row g-4">
                <!-- Poster -->
                <div class="col-lg-4">
                    <img src="poster/<?php echo $film['poster']; ?>" 
                         class="detail-poster" 
                         alt="<?php echo htmlspecialchars($film['judul']); ?>"
                         onerror="this.src='poster/default.jpg'">
                </div>
                
                <!-- Info -->
                <div class="col-lg-8">
                    <h1 class="detail-title"><?php echo htmlspecialchars($film['judul']); ?></h1>
                    
                    <div class="mb-3">
                        <span class="badge bg-primary fs-6"><?php echo htmlspecialchars($film['genre']); ?></span>
                        <span class="badge bg-secondary fs-6"><?php echo htmlspecialchars($film['durasi']); ?></span>
                        <span class="badge bg-info fs-6"><?php echo htmlspecialchars($film['tahun']); ?></span>
                        <span class="badge bg-warning text-dark fs-6">
                            <i class="fas fa-star"></i> <?php echo $film['rating']; ?>/10
                        </span>
                    </div>

                    <div class="detail-info">
                        <span class="label"><i class="fas fa-tag text-primary"></i> Harga</span>
                        <strong class="text-success fs-4">
                            Rp <?php echo number_format($film['harga'], 0, ',', '.'); ?>
                        </strong>
                    </div>

                    <div class="detail-info">
                        <span class="label"><i class="fas fa-user text-primary"></i> Sutradara</span>
                        <?php echo htmlspecialchars($film['sutradara']); ?>
                    </div>

                    <div class="detail-info">
                        <span class="label"><i class="fas fa-users text-primary"></i> Pemain</span>
                        <?php echo htmlspecialchars($film['pemain']); ?>
                    </div>

                    <?php if (!empty($jadwals)): ?>
                    <div class="detail-info">
                        <span class="label"><i class="fas fa-clock text-primary"></i> Jadwal</span>
                        <div class="mt-2">
                            <?php foreach ($jadwals as $jadwal): ?>
                            <span class="badge bg-dark me-1">
                                <?php echo htmlspecialchars($jadwal['hari']); ?>
                            </span>
                            <span class="badge bg-info me-1">
                                <?php echo htmlspecialchars($jadwal['jam']); ?>
                            </span>
                            <span class="badge bg-secondary me-1">
                                <?php echo htmlspecialchars($jadwal['studio']); ?>
                            </span>
                            <br>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="detail-info">
                        <span class="label"><i class="fas fa-align-left text-primary"></i> Deskripsi</span>
                        <div class="detail-description">
                            <?php echo nl2br(htmlspecialchars($film['deskripsi'])); ?>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="reservasi.php?id=<?php echo $film['id']; ?>" 
                           class="btn btn-success btn-lg">
                            <i class="fas fa-ticket-alt me-2"></i>Pesan Tiket
                        </a>
                        <a href="index.php" class="btn btn-secondary btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
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