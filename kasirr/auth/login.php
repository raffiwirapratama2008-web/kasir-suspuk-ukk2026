<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Kasir Suspuk</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #a78847, #e2b45f);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.login-card {
    background: #ffffff;
    border-radius: 20px;
    padding: 40px;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.logo {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #a78847, #e2b45f);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    color: white;
    font-size: 1.8rem;
}

.login-title {
    text-align: center;
    margin-bottom: 25px;
}

.login-title h2 {
    font-weight: 700;
    margin-bottom: 5px;
}

.login-title p {
    font-size: 0.9rem;
    color: #6c757d;
}

.form-control {
    border-radius: 12px;
    padding: 12px 15px;
}

.input-group-text {
    border-radius: 12px 0 0 12px;
    background: #f1f3f5;
}

.btn-login {
    background: linear-gradient(135deg, #a78847, #e2b45f);
    border: none;
    border-radius: 12px;
    padding: 12px;
    font-weight: 600;
    color: white;
    transition: 0.3s;
}

.btn-login:hover {
    opacity: 0.9;
}

.alert-custom {
    font-size: 0.85rem;
    border-radius: 10px;
}

.footer {
    text-align: center;
    font-size: 0.75rem;
    margin-top: 20px;
    color: #999;
}
</style>
</head>

<body>

<div class="login-card">

    <div class="logo">
        <i class="fas fa-cash-register"></i>
    </div>

    <div class="login-title">
        <h2>Kasir Suspuk</h2>
        <p>Masuk untuk mengelola toko</p>
    </div>

    <?php if(isset($_GET['pesan']) && $_GET['pesan'] == "gagal"): ?>
        <div class="alert alert-danger alert-custom">
            Username atau Password salah!
        </div>
    <?php endif; ?>

    <form action="auth.php" method="POST">

        <div class="mb-3">
            <label class="form-label">Username</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" name="username" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" name="password" id="password" class="form-control" required>
                <span class="input-group-text" onclick="togglePassword()" style="cursor:pointer;">
                    <i class="fas fa-eye" id="eye"></i>
                </span>
            </div>
        </div>

        <button type="submit" class="btn btn-login w-100">
            Masuk
        </button>

    </form>

    <div class="footer">
        © 2026 Kasir SUSPUK
    </div>

</div>

<script>
function togglePassword() {
    const password = document.getElementById("password");
    const eye = document.getElementById("eye");

    if (password.type === "password") {
        password.type = "text";
        eye.classList.replace("fa-eye", "fa-eye-slash");
    } else {
        password.type = "password";
        eye.classList.replace("fa-eye-slash", "fa-eye");
    }
}
</script>

</body>
</html>
