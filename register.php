<?php
// register.php - Role selection (user/petugas) 
require_once 'config/koneksi.php';

if (isset($_SESSION['user_id'])) {
    $role_redirect = $_SESSION['role'] == 'admin' ? 'admin/dashboard.php' : ($_SESSION['role'] == 'petugas' ? 'petugas/dashboard.php' : 'user/pinjam.php');
    redirect($role_redirect);
}

$success = '';
$error = '';
if ($_POST) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    if (!in_array($role, ['user', 'petugas'])) {
        $error = 'Role tidak valid!';
    } elseif (strlen($username) < 3) {
        $error = 'Username minimal 3 karakter!';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = 'Username sudah terdaftar!';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            if ($stmt->execute([$username, $hashed, $role])) {
                $success = "✅ Registrasi $role berhasil! Silakan <a href='index.php' class='text-decoration-none'>login</a>.";
            } else {
                $error = 'Error registrasi. Coba lagi.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Alat Tani</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/shopee.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .register-card { background: rgba(255,255,255,0.95); backdrop-filter: blur(25px); }
        .role-card { transition: all 0.3s; cursor: pointer; }
        .role-card:hover, .role-card.active { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(238,77,45,0.3); }
        .role-card.active { border-color: #EE4D2D !important; }
    </style>
</head>
<body class="min-vh-100 d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="register-card p-5 shadow-lg rounded-4 mx-auto">
                    <div class="text-center mb-5">
                        <h2 class="fw-bold mb-2" style="background: linear-gradient(135deg, #EE4D2D, #FF6B35); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            <i class="bi bi-person-plus me-2"></i>Daftar Akun
                        </h2>
                        <p class="text-muted">Pilih role Anda</p>
                    </div>

                    <?php if ($error): ?>
                        <div class="alert alert-danger mb-4"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success mb-4"><?php echo $success; ?></div>
                    <?php else: ?>
                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label fw-bold mb-3 d-block">👤 Username</label>
                            <input type="text" class="form-control rounded-4 shadow-sm" name="username" value="<?php echo $_POST['username'] ?? ''; ?>" required minlength="3" placeholder="Username minimal 3 char">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold mb-3 d-block">🔒 Password</label>
                            <input type="password" class="form-control rounded-4 shadow-sm" name="password" required minlength="6" placeholder="Password minimal 6 char">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold mb-3">🎭 Role</label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="role-card card border-primary p-4 text-center h-100 rounded-3 role-user" onclick="selectRole('user')">
                                        <i class="bi bi-person-gear fs-1 text-primary mb-3"></i>
                                        <h6 class="fw-bold mb-1">Petani</h6>
                                        <small class="text-muted">Pinjam alat untuk pertanian</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="role-card card border-warning p-4 text-center h-100 rounded-3 role-petugas" onclick="selectRole('petugas')">
                                        <i class="bi bi-person-badge fs-1 text-warning mb-3"></i>
                                        <h6 class="fw-bold mb-1">Petugas</h6>
                                        <small class="text-muted">Kelola booking & alat</small>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="role" id="roleInput" value="">
                        </div>
                        <button type="submit" class="btn w-100 rounded-4 shadow-lg" style="background: linear-gradient(135deg, #EE4D2D, #FF6B35); color: white; border: none; padding: 14px; font-weight: 600;">
                            <i class="bi bi-check2-all me-2"></i>Daftar Sekarang
                        </button>
                    </form>
                    <?php endif; ?>
                    
                    <div class="text-center mt-4 pt-4 border-top">
                        <p class="text-muted mb-0">Sudah punya akun? <a href="index.php" class="fw-semibold text-decoration-none">Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function selectRole(role) {
            document.querySelectorAll('.role-card').forEach(card => card.classList.remove('active', 'border-primary', 'border-warning'));
            document.getElementById('roleInput').value = role;
            event.target.closest('.role-card').classList.add('active');
            if (role == 'user') event.target.closest('.role-card').classList.add('border-primary');
            else event.target.closest('.role-card').classList.add('border-warning');
        }
    </script>
</body>
</html>
