<?php
session_start();

$host = 'localhost';
$dbname = 'db_voting_kakang_senduk';
$username = 'root';
$password = ''; // Isi jika MySQL kamu menggunakan password

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function sudahLogin() {
    return isset($_SESSION['user_id']);
}

function adminLogin() {
    return sudahLogin() && ($_SESSION['role'] ?? '') === 'admin';
}

function wajibLogin() {
    if (!sudahLogin()) {
        header('Location: index.php?page=login');
        exit;
    }
}

function wajibAdmin() {
    if (!adminLogin()) {
        header('Location: index.php?page=login');
        exit;
    }
}

// Membuat akun admin awal jika belum tersedia.
// Username: admin
// Password: admin123
$cekAdmin = $pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn();

if ((int)$cekAdmin === 0) {
    $hash = password_hash('admin123', PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("
        INSERT INTO users (nama, email, username, password, role)
        VALUES (?, ?, ?, ?, 'admin')
    ");

    $stmt->execute([
        'Administrator',
        'admin@voting.com',
        'admin',
        $hash
    ]);
}

// Membuat periode awal jika belum tersedia.
$jumlahPeriode = $pdo->query("SELECT COUNT(*) FROM periode_voting")->fetchColumn();

if ((int)$jumlahPeriode === 0) {
    $stmt = $pdo->prepare("
        INSERT INTO periode_voting
        (nama_periode, tanggal_mulai, tanggal_selesai, status)
        VALUES (?, ?, ?, 'aktif')
    ");

    $stmt->execute([
        'Pemilihan Kakang Senduk Ponorogo',
        date('Y-m-d 00:00:00'),
        date('Y-m-d 23:59:59', strtotime('+30 days'))
    ]);
}