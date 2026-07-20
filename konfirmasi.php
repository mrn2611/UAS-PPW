<?php
// =============================================
// HALAMAN KONFIRMASI RESERVASI
// =============================================

session_start();

// Cek apakah ada data reservasi
if (!isset($_SESSION['reservasi'])) {
    header("Location: index.php");
    exit;
}

$reservasi = $_SESSION['reservasi'];

// Hapus session setelah ditampilkan
unset($_SESSION['reservasi']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Reservasi - Bioskop Online</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top no-print">
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
                            <i class="fas fa-check-circle"></i> Konfirmasi
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Konfirmasi -->
    <div class="container py-5">
        <div class="confirmation-box">
            <!-- Icon Sukses -->
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <h2 class="mt-3">Reservasi Berhasil! 🎉</h2>
            <p class="text-muted">
                Terima kasih telah melakukan pemesanan tiket di Bioskop Online
            </p>

            <!-- Kode Reservasi -->
            <div class="ticket-code">
                <i class="fas fa-qrcode me-2"></i>
                <?php echo $reservasi['kode']; ?>
            </div>

            <!-- Detail Tiket -->
            <div class="ticket-details">
                <h5 class="mb-3">
                    <i class="fas fa-info-circle text-primary me-2"></i>
                    Detail Pemesanan
                </h5>
                
                <div class="row-detail">
                    <span class="label"><i class="fas fa-user"></i> Nama Pemesan</span>
                    <span><?php echo htmlspecialchars($reservasi['nama']); ?></span>
                </div>
                
                <div class="row-detail">
                    <span class="label"><i class="fas fa-phone"></i> Nomor Telepon</span>
                    <span><?php echo htmlspecialchars($reservasi['telepon']); ?></span>
                </div>
                
                <div class="row-detail">
                    <span class="label"><i class="fas fa-envelope"></i> Email</span>
                    <span><?php echo htmlspecialchars($reservasi['email']); ?></span>
                </div>
                
                <div class="row-detail">
                    <span class="label"><i class="fas fa-film"></i> Judul Film</span>
                    <span><?php echo htmlspecialchars($reservasi['film']); ?></span>
                </div>
                
                <div class="row-detail">
                    <span class="label"><i class="fas fa-clock"></i> Jadwal Tayang</span>
                    <span><?php echo htmlspecialchars($reservasi['jadwal']); ?></span>
                </div>
                
                <div class="row-detail">
                    <span class="label"><i class="fas fa-ticket-alt"></i> Jumlah Tiket</span>
                    <span><?php echo $reservasi['jumlah']; ?> tiket</span>
                </div>
                
                <div class="row-detail" style="border-bottom: 2px solid #ddd; padding-bottom: 15px;">
                    <span class="label"><i class="fas fa-money-bill-wave"></i> Total Pembayaran</span>
                    <span style="font-size: 1.3rem; font-weight: 700; color: var(--success);">
                        Rp <?php echo number_format($reservasi['total'], 0, ',', '.'); ?>
                    </span>
                </div>
            </div>

            <!-- Informasi Penting -->
            <div class="alert alert-info text-start">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Informasi Penting:</strong>
                <ul class="mb-0 mt-2">
                    <li>Tunjukkan kode reservasi ini saat mengambil tiket di loket.</li>
                    <li>Pembayaran dapat dilakukan di loket bioskop sebelum pertunjukan.</li>
                    <li>Jika ada perubahan, hubungi customer service.</li>
                </ul>
            </div>

            <!-- Tombol -->
            <div class="mt-4 no-print">
                <a href="index.php" class="btn btn-primary btn-lg me-2">
                    <i class="fas fa-home me-2"></i>Beranda
                </a>
                <button onclick="window.print()" class="btn btn-success btn-lg">
                    <i class="fas fa-print me-2"></i>Cetak Tiket
                </button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer-custom no-print">
        <div class="container text-center">
            <p class="mb-0">&copy; 2024 Bioskop Online. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>