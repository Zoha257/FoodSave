<?php
require_once '../includes/config.php';

if (isLoggedIn() && getUserType() === 'volunteer') {
    redirectTo('browse_opportunities.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please fill in all fields.';
    } else {
        try {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare("SELECT id, username, password, user_type, full_name, status FROM users WHERE username = ? AND user_type = 'volunteer'");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                if ($user['status'] === 'active') {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['user_type'] = $user['user_type'];
                    $_SESSION['full_name'] = $user['full_name'];
                    redirectTo('browse_opportunities.php');
                } else {
                    $error = 'Your account is not active yet. Please contact support.';
                }
            } else {
                $error = 'Invalid username or password.';
            }
        } catch (PDOException $e) {
            $error = 'Database error. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Login - FoodSave</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="../assets/css/glass.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .glass-container {
            max-width: 500px;
        }
        .glass-container .input-group {
            background: transparent;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .glass-container .input-group-text {
            width: 45px;
            justify-content: center;
            height: 35px;
            background: transparent !important;
            border: none;
            color: white;
            display: flex;
            align-items: center;
            padding: 3px 15px 0 15px;
        }
        .glass-container .input-group-text i {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            font-size: 1rem;
        }
        .glass-container .form-control,
        .glass-container .form-control:focus,
        .glass-container .form-control:active,
        .glass-container .form-control[type="text"],
        .glass-container .form-control[type="password"] {
            background-color: transparent !important;
            border: none;
            color: white;
            padding: 12px 15px;
            font-size: 1.1rem;
            height: 35px;
            line-height: 20px;
            box-shadow: none;
            -webkit-text-fill-color: white;
            transition: none;
            text-align: left;
            letter-spacing: 0.5px;
            display: flex;
            align-items: flex-start;
        }
        .glass-container .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        .glass-container .form-control:-webkit-autofill,
        .glass-container .form-control:-webkit-autofill:hover,
        .glass-container .form-control:-webkit-autofill:focus,
        .glass-container .form-control:-webkit-autofill:active {
            -webkit-background-clip: text;
            -webkit-text-fill-color: white !important;
            transition: background-color 5000s ease-in-out 0s;
            box-shadow: inset 0 0 20px 20px transparent !important;
        }
    </style>
</head>
<body class="auth-page">
    <div class="auth-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="glass-container" style="max-width: 500px;">
                    <h2><i class="fas fa-hands-helping me-2"></i>Volunteer Login</h2>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST" class="mb-4">
                        <div class="mb-4">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="username" name="username" required placeholder="Username">
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" required placeholder="Password">
                                <span class="input-group-text" style="cursor: pointer;" onclick="togglePassword('password')">
                                    <i class="fas fa-eye" data-for="password"></i>
                                </span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-3 mt-4" style="background: linear-gradient(45deg, #0d6efd, #0a58ca); font-size: 1.1rem;"><i class="fas fa-sign-in-alt me-2"></i>Login</button>
                    </form>

                    <div class="text-center mt-4">
                        <p style="font-size: 1.05rem;">New to FoodSave? <a href="register.php">Register as a volunteer</a></p>
                        <a href="../index.php" class="d-block mt-2" style="font-size: 1.05rem; color: rgba(255, 255, 255, 0.9);"><i class="fas fa-home me-2"></i>Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const icon = document.querySelector(`[data-for="${inputId}"]`);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
