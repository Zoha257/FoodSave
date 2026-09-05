<?php
require_once '../includes/config.php';

if (isLoggedIn() && getUserType() === 'volunteer') {
    redirectTo('browse_opportunities.php');
}

$error = '';
$success = '';

$usernameValue = '';
$emailValue = '';
$fullNameValue = '';
$phoneValue = '';
$cityValue = '';
$stateValue = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameValue = sanitizeInput($_POST['username']);
    $emailValue = sanitizeInput($_POST['email']);
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $fullNameValue = sanitizeInput($_POST['full_name']);
    $phoneValue = sanitizeInput($_POST['phone']);
    $cityValue = sanitizeInput($_POST['city']);
    $stateValue = sanitizeInput($_POST['state']);

    if ($usernameValue === '' || $emailValue === '' || $password === '' || $fullNameValue === '') {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($emailValue, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } else {
        try {
            $pdo = getDBConnection();

            $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
            $stmt->execute([$usernameValue, $emailValue]);
            if ($stmt->fetch()) {
                $error = 'Username or email already exists.';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password, user_type, full_name, phone, city, state, status) VALUES (?, ?, ?, 'volunteer', ?, ?, ?, ?, 'active')");
                $stmt->execute([$usernameValue, $emailValue, $hashedPassword, $fullNameValue, $phoneValue, $cityValue, $stateValue]);
                $success = 'Registration successful! You can now log in.';
                $usernameValue = '';
                $emailValue = '';
                $fullNameValue = '';
                $phoneValue = '';
                $cityValue = '';
                $stateValue = '';
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
    <title>Volunteer Registration - FoodSave</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">
                <div class="card mt-5 mb-4 shadow-lg" style="min-width: 650px; border-radius: 15px;">
                    <div class="card-header text-white text-center py-3" style="background: linear-gradient(45deg, #1a5d1a, #1e4d92);">
                        <h4 class="mb-1">Volunteer Registration</h4>
                        <small class="fs-6">Join FoodSave as a community volunteer</small>
                    </div>
                    <div class="card-body p-4">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control" name="username" required placeholder="Username *" value="<?php echo htmlspecialchars($usernameValue); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control" name="email" required placeholder="Email *" value="<?php echo htmlspecialchars($emailValue); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control" id="password" name="password" required placeholder="Password *">
                                        <span class="input-group-text" style="cursor: pointer;" onclick="togglePassword('password')">
                                            <i class="fas fa-eye" data-for="password"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required placeholder="Confirm Password *">
                                        <span class="input-group-text" style="cursor: pointer;" onclick="togglePassword('confirm_password')">
                                            <i class="fas fa-eye" data-for="confirm_password"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                                    <input type="text" class="form-control" name="full_name" required placeholder="Full Name *" value="<?php echo htmlspecialchars($fullNameValue); ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="tel" class="form-control" name="phone" placeholder="Phone Number" value="<?php echo htmlspecialchars($phoneValue); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text"><i class="fas fa-city"></i></span>
                                        <input type="text" class="form-control" name="city" placeholder="City" value="<?php echo htmlspecialchars($cityValue); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text"><i class="fas fa-map"></i></span>
                                    <input type="text" class="form-control" name="state" placeholder="State" value="<?php echo htmlspecialchars($stateValue); ?>">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success w-100 py-3 mt-2" style="background: linear-gradient(45deg, #1a5d1a, #1e4d92);">Register</button>
                        </form>

                        <div class="text-center mt-3">
                            <p>Already have an account? <a href="login.php">Login here</a></p>
                            <a href="../index.php" class="text-dark">Back to Home</a>
                        </div>
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
