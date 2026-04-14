<?php
// index.php - Halaman login Shopee-style (responsive)
require_once 'config/koneksi.php';

// Jika sudah login, redirect ke dashboard sesuai role
if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'user'])) {
    $redirect_map = [
        'admin' => 'admin/dashboard.php',
        'user' => 'user/dashboard.php'
    ];
    redirect($redirect_map[$_SESSION['role']] ?? 'user/dashboard.php');
}

$error = '';
if ($_POST) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if ($username && $password) {
        $stmt = $pdo->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'];
            
            $redirect_map = [
                'admin' => 'admin/dashboard.php',
                'user' => 'user/dashboard.php'
            ];
            redirect($redirect_map[$user['role']] ?? 'user/dashboard.php');

        } else {
            $error = 'Username atau password salah!';
        }
    } else {
        $error = 'Mohon isi username dan password!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Alat Tani</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/shopee-login.css" rel="stylesheet">
</head>
<body>
    <div class="shopee-container">
        <div class="shopee-card">
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="bi bi-tools"></i>
                </div>
                <h1 class="logo-title">Alat Tani</h1>
                <p class="logo-subtitle">Sistem Peminjaman Alat Pertanian</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger error-alert"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <i class="bi bi-person input-icon"></i>
                    <input type="text" class="form-control" name="username" placeholder="Username" required autofocus>
                </div>
                <div class="form-group">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-shopee">Masuk</button>
            </form>
            
            <div class="register-link">
                <a href="register.php">Daftar Akun Baru</a>
            </div>
            
            <div class="demo-info">
                <strong>Demo:</strong> admin / admin123
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
