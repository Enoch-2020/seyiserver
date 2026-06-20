<?php
session_start();

// 1. Load Database
$db_path = __DIR__ . '/../includes/db.php';
if (file_exists($db_path)) { 
    require_once $db_path; 
}

// 2. Login Logic using PDO
$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($pdo)) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Fetch user from admin_users table
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $admin = $stmt->fetch();

        if ($admin) {
            // Verify the hashed password
            if (password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['surname'] . " " . $admin['other_name'];
                
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "Account not found.";
        }
    } catch (PDOException $e) {
        $error = "Database Error: " . $e->getMessage();
    }
}

// 3. Header
$header_path = __DIR__ . '/../includes/header.php';
if (file_exists($header_path)) { require_once $header_path; }
?>

<style>
    /* Spacing fix for fixed headers */
    .admin-page-wrapper {
        padding-top: 140px; 
        padding-bottom: 80px;
        min-height: 100vh;
        background: #05010a;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .login-card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.08);
        padding: 40px;
        border-radius: 24px;
        width: 100%;
        max-width: 420px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(10px);
    }

    .form-input {
        width: 100%;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 14px 20px;
        border-radius: 12px;
        color: white;
        margin-top: 8px;
        font-size: 14px;
        transition: 0.3s;
    }

    .form-input:focus {
        outline: none;
        border-color: #7c3aed;
        background: rgba(124, 58, 237, 0.05);
    }

    .login-btn {
        width: 100%;
        background: #7c3aed;
        color: white;
        padding: 16px;
        border-radius: 12px;
        font-weight: 800;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 2px;
        margin-top: 20px;
        transition: 0.3s;
        cursor: pointer;
    }

    .login-btn:hover {
        background: white;
        color: black;
        transform: translateY(-2px);
    }
</style>

<div class="admin-page-wrapper">
    
    <div class="login-card">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-black text-white uppercase tracking-tighter">Admin Portal</h2>
            <p class="text-zinc-500 text-[10px] uppercase tracking-widest mt-2">Identification Required</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-500/10 border border-red-500/20 text-red-500 text-[11px] p-3 rounded-lg mb-6 text-center">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-5">
                <label class="text-zinc-400 text-[10px] uppercase font-bold ml-1">Admin Email</label>
                <input type="email" name="email" class="form-input" placeholder="name@company.com" required>
            </div>

            <div class="mb-6">
                <label class="text-zinc-400 text-[10px] uppercase font-bold ml-1">Secure Password</label>
                <input type="password" name="password" class="form-input" placeholder="••••••••" required>
            </div>

            <button type="submit" class="login-btn">
                Authorize Access
            </button>
        </form>
    </div>

</div>

<?php 
$footer_path = __DIR__ . '/../includes/footer.php';
if (file_exists($footer_path)) { require_once $footer_path; }
?>