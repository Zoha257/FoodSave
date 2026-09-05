<?php
require_once(__DIR__ . '/../includes/config.php');

if (isLoggedIn()) {
    redirectTo('dashboard.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = sanitizeInput($_POST['full_name']);
    $phone = sanitizeInput($_POST['phone']);
    $address = sanitizeInput($_POST['address']);
    $city = sanitizeInput($_POST['city']);
    $state = sanitizeInput($_POST['state']);
    $zip_code = sanitizeInput($_POST['zip_code']);
    
    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
        $error = 'Please fill in all required fields.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } else {
        try {
            $pdo = getDBConnection();
            
            // Check if username or email already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            if ($stmt->fetch()) {
                $error = 'Username or email already exists.';
            } else {
                // Insert new user with pending status
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("
                    INSERT INTO users (username, email, password, user_type, full_name, phone, address, city, state, zip_code, status) 
                    VALUES (?, ?, ?, 'ngo', ?, ?, ?, ?, ?, ?, 'pending')
                ");
                $stmt->execute([$username, $email, $hashed_password, $full_name, $phone, $address, $city, $state, $zip_code]);
                
                $success = 'Registration successful! Your account is pending admin approval. You will be notified once approved.';
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
    <title>NGO Registration - FoodSave</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">
                <div class="card mt-5 mb-4 shadow-lg" style="min-width: 650px; border-radius: 15px;">
                    <div class="card-header text-white text-center py-3" style="background: linear-gradient(45deg, #1a5d1a, #1e4d92);">
                        <h4 class="mb-1">NGO Registration</h4>
                        <small class="fs-6">Register as an NGO or Food Bank</small>
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
                                        <span class="input-group-text"><i class="fas fa-building-user"></i></span>
                                        <input type="text" class="form-control" id="username" name="username" required placeholder="Username *" style="padding: 10px 12px; font-size: 1rem;">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" name="email" required placeholder="Email *" style="padding: 10px 12px; font-size: 1rem;">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control" id="password" name="password" required placeholder="Password *" style="padding: 12px 15px; font-size: 1.1rem;">
                                        <span class="input-group-text" style="cursor: pointer;" onclick="togglePassword('password')">
                                            <i class="fas fa-eye" data-for="password"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required placeholder="Confirm Password *" style="padding: 12px 15px; font-size: 1.1rem;">
                                        <span class="input-group-text" style="cursor: pointer;" onclick="togglePassword('confirm_password')">
                                            <i class="fas fa-eye" data-for="confirm_password"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                    <input type="text" class="form-control" id="full_name" name="full_name" required placeholder="Organization Name *" style="padding: 12px 15px; font-size: 1.1rem;">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="Phone Number" style="padding: 12px 15px; font-size: 1.1rem;">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    <textarea class="form-control" id="address" name="address" rows="3" placeholder="Address" style="padding: 12px 15px; font-size: 1.1rem;"></textarea>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text"><i class="fas fa-city"></i></span>
                                        <input type="text" class="form-control" id="city" name="city" placeholder="City" style="padding: 12px 15px; font-size: 1.1rem;">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text"><i class="fas fa-map"></i></span>
                                        <input type="text" class="form-control" id="state" name="state" placeholder="State" style="padding: 12px 15px; font-size: 1.1rem;">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text"><i class="fas fa-map-pin"></i></span>
                                        <input type="text" class="form-control" id="zip_code" name="zip_code" placeholder="ZIP Code" style="padding: 12px 15px; font-size: 1.1rem;">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                            
                            <div class="alert alert-info">
                                <small><strong>Note:</strong> NGO registrations require admin approval. You will be notified once your account is approved.</small>
                            </div>
                            
                            <button type="submit" class="btn btn-success w-100 py-3 mt-4" style="background: linear-gradient(45deg, #1a5d1a, #1e4d92);">Register</button>
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

