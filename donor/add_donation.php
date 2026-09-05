<?php
require_once '../includes/config.php';

if (!isLoggedIn() || getUserType() !== 'donor') {
    redirectTo('login.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitizeInput($_POST['title']);
    $description = sanitizeInput($_POST['description']);
    $category = sanitizeInput($_POST['category']);
    $quantity = sanitizeInput($_POST['quantity']);
    $expiration_date = sanitizeInput($_POST['expiration_date']);
    $pickup_address = sanitizeInput($_POST['pickup_address']);
    $pickup_city = sanitizeInput($_POST['pickup_city']);
    $pickup_state = sanitizeInput($_POST['pickup_state']);
    $pickup_zip = sanitizeInput($_POST['pickup_zip']);
    
    // Validation
    if (empty($title) || empty($category) || empty($quantity) || empty($expiration_date) || empty($pickup_address)) {
        $error = 'Please fill in all required fields.';
    } elseif (strtotime($expiration_date) <= time()) {
        $error = 'Expiration date must be in the future.';
    } else {
        try {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare("
                INSERT INTO food_donations (donor_id, title, description, category, quantity, expiration_date, pickup_address, pickup_city, pickup_state, pickup_zip) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $_SESSION['user_id'], $title, $description, $category, $quantity, 
                $expiration_date, $pickup_address, $pickup_city, $pickup_state, $pickup_zip
            ]);
            
            $success = 'Food donation added successfully!';
            
            // Clear form data
            $_POST = array();
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
    <title>Add Donation - FoodSave</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h5 class="text-white">FoodSave Donor</h5>
                        <small class="text-muted">Welcome, <?php echo $_SESSION['full_name']; ?></small>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="add_donation.php">
                                <i class="fas fa-plus"></i> Add Donation
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="my_donations.php">
                                <i class="fas fa-utensils"></i> My Donations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pickup_requests.php">
                                <i class="fas fa-truck"></i> Pickup Requests
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="food_safety.php">
                                <i class="fas fa-clipboard-check"></i> Food Safety Guidelines
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">
                                <i class="fas fa-user"></i> Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2" style="color:white; text-shadow: 2px 2px 4px #000;">Add Food Donation</h1>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <?php if ($error): ?>
                                    <div class="alert alert-danger"><?php echo $error; ?></div>
                                <?php endif; ?>
                                
                                <?php if ($success): ?>
                                    <div class="alert alert-success"><?php echo $success; ?></div>
                                <?php endif; ?>
                                
                                <form method="POST">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Food Title *</label>
                                        <input type="text" class="form-control" id="title" name="title" 
                                               value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="category" class="form-label">Category *</label>
                                            <select class="form-select" id="category" name="category" required>
                                                <option value="">Select Category</option>
                                                <option value="vegetables" <?php echo ($_POST['category'] ?? '') === 'vegetables' ? 'selected' : ''; ?>>Vegetables</option>
                                                <option value="fruits" <?php echo ($_POST['category'] ?? '') === 'fruits' ? 'selected' : ''; ?>>Fruits</option>
                                                <option value="dairy" <?php echo ($_POST['category'] ?? '') === 'dairy' ? 'selected' : ''; ?>>Dairy</option>
                                                <option value="meat" <?php echo ($_POST['category'] ?? '') === 'meat' ? 'selected' : ''; ?>>Meat</option>
                                                <option value="grains" <?php echo ($_POST['category'] ?? '') === 'grains' ? 'selected' : ''; ?>>Grains</option>
                                                <option value="prepared_food" <?php echo ($_POST['category'] ?? '') === 'prepared_food' ? 'selected' : ''; ?>>Prepared Food</option>
                                                <option value="other" <?php echo ($_POST['category'] ?? '') === 'other' ? 'selected' : ''; ?>>Other</option>
                                                <option value="other" <?php echo ($_POST['category'] ?? '') === 'other' ? 'selected' : ''; ?>>Fresh meal</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="quantity" class="form-label">Quantity *</label>
                                            <input type="text" class="form-control" id="quantity" name="quantity" 
                                                   value="<?php echo htmlspecialchars($_POST['quantity'] ?? ''); ?>" 
                                                   placeholder="e.g., 10 lbs, 20 portions" required>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="expiration_date" class="form-label">Expiration Date *</label>
                                        <input type="date" class="form-control" id="expiration_date" name="expiration_date" 
                                               value="<?php echo htmlspecialchars($_POST['expiration_date'] ?? ''); ?>" required>
                                    </div>
                                    
                                    <h5 class="mb-3">Pickup Location</h5>
                                    
                                    <div class="mb-3">
                                        <label for="pickup_address" class="form-label">Address *</label>
                                        <textarea class="form-control" id="pickup_address" name="pickup_address" rows="2" required><?php echo htmlspecialchars($_POST['pickup_address'] ?? ''); ?></textarea>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="pickup_city" class="form-label">City</label>
                                            <input type="text" class="form-control" id="pickup_city" name="pickup_city" 
                                                   value="<?php echo htmlspecialchars($_POST['pickup_city'] ?? ''); ?>">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="pickup_state" class="form-label">State</label>
                                            <input type="text" class="form-control" id="pickup_state" name="pickup_state" 
                                                   value="<?php echo htmlspecialchars($_POST['pickup_state'] ?? ''); ?>">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="pickup_zip" class="form-label">ZIP Code</label>
                                            <input type="text" class="form-control" id="pickup_zip" name="pickup_zip" 
                                                   value="<?php echo htmlspecialchars($_POST['pickup_zip'] ?? ''); ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <a href="dashboard.php" class="btn btn-secondary me-md-2">Cancel</a>
                                        <button type="submit" class="btn btn-success">Add Donation</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set minimum date to today
        document.getElementById('expiration_date').min = new Date().toISOString().split('T')[0];
    </script>
</body>
</html>

