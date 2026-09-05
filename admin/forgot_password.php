<?php
require_once(__DIR__ . '/../includes/config.php');
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = '<div class="alert alert-danger">Please enter a valid email address.</div>';
    } else {
        try {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                // Generate a token
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
                // Store token in a table (create if not exists)
                $pdo->prepare("CREATE TABLE IF NOT EXISTS password_resets (id INT AUTO_INCREMENT PRIMARY KEY, user_id INT, token VARCHAR(64), expires_at DATETIME)")->execute();
                $pdo->prepare("DELETE FROM password_resets WHERE user_id = ?")->execute([$user['id']]);
                $pdo->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)")->execute([$user['id'], $token, $expires]);
                // Simulate sending email (display link)
                $message = '<div class="alert alert-success">A password reset link has been sent to your email.</div>';
            } else {
                $message = '<div class="alert alert-danger">No account found with that email address.</div>';
            }
        } catch (Exception $e) {
            $message = '<div class="alert alert-danger">An error occurred. Please try again later.</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/auth.css" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <h2>Forgot Password</h2>
        <?php if ($message) echo $message; ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="email" class="form-label">Enter your email address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary">Send Reset Link</button>
            <div class="text-center mt-3">
                <a href="login.php">Back to Login</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>