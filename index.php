<?php
require_once __DIR__ . '/includes/config.php';
$homeStats=['meals'=>0,'donors'=>0,'ngos'=>0,'success'=>0];
try{$pdo=getDBConnection();$homeStats['donors']=(int)$pdo->query("SELECT COUNT(*) FROM users WHERE user_type='donor' AND status='active'")->fetchColumn();$homeStats['ngos']=(int)$pdo->query("SELECT COUNT(*) FROM users WHERE user_type='ngo' AND status='active'")->fetchColumn();$homeStats['meals']=(int)$pdo->query("SELECT COUNT(*) FROM food_donations WHERE status IN ('picked_up','completed')")->fetchColumn();$total=(int)$pdo->query("SELECT COUNT(*) FROM food_donations")->fetchColumn();$done=(int)$pdo->query("SELECT COUNT(*) FROM food_donations WHERE status='completed'")->fetchColumn();$homeStats['success']=$total?round($done/$total*100):0;}catch(Exception $e){}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodSave - Online Food Waste Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet" type="text/css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .card {
            transition: all 0.4s ease;
        }
        .card:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 40px rgba(26, 93, 26, 0.15) !important;
        }
        .card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 12px 25px rgba(26, 93, 26, 0.2) !important;
        }
        .feature-icon {
            transition: all 0.4s ease;
        }
        .card:hover .card-title {
            background: linear-gradient(120deg, #1a5d1a, #2e8b57);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .navbar {
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .nav-link {
            transition: all 0.3s ease;
            position: relative;
            font-weight: 500;
        }
        .nav-link:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background: #ffd700;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            transition: width 0.3s ease;
        }
        .nav-link:hover {
            transform: translateY(-2px);
            color: #ffd700 !important;
        }
        .nav-link:hover:after {
            width: 70%;
        }
        .dropdown-item {
            transition: all 0.3s ease;
            font-weight: 500;
        }
        .dropdown-item:hover {
            background: linear-gradient(45deg, #1a5d1a20, #1e4d9220);
            transform: translateX(5px);
            color: #1a5d1a;
        }
        .dropdown-menu {
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            border: none;
            padding: 0.8rem 0.5rem;
        }
        .navbar-brand {
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        .nav-item {
            margin: 0 5px;
        }
        .btn-lg:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
            filter: brightness(110%);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(45deg, #1a5d1a, #1e4d92); min-height: 90px; padding: 15px 0;">
        <div class="container">
            <a class="navbar-brand" href="index.php" style="font-size: 2.2rem;">
                <i class="fas fa-utensils me-2"></i>
                <h2 class="d-inline align-middle mb-0" style="font-size: 2.2rem; font-weight: 600;">FoodSave</h2>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#about" style="color:white; font-size: 1.2rem; padding: 0.5rem 1.2rem;">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features" style="color:white; font-size: 1.2rem; padding: 0.5rem 1.2rem;">Features</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="loginDropdown" role="button" data-bs-toggle="dropdown" style="color:white; font-size: 1.2rem; padding: 0.5rem 1.2rem;">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                        <ul class="dropdown-menu" style="padding: 0.5rem;">
                            <li><a class="dropdown-item py-2" href="admin/login.php" style="font-size: 1.1rem;"><i class="fas fa-user-shield me-2"></i>Admin Login</a></li>
                            <li><a class="dropdown-item py-2" href="donor/login.php" style="font-size: 1.1rem;"><i class="fas fa-hand-holding-heart me-2"></i>Donor Login</a></li>
                            <li><a class="dropdown-item py-2" href="ngo/login.php" style="font-size: 1.1rem;"><i class="fas fa-building me-2"></i>NGO Login</a></li>
                            <li><a class="dropdown-item py-2" href="driver/login.php"><i class="fas fa-truck me-2"></i>Driver Login</a></li>
                            <li><a class="dropdown-item py-2" href="volunteer/login.php" style="font-size: 1.1rem;"><i class="fas fa-hands-helping me-2"></i>Volunteer Login</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="registerDropdown" role="button" data-bs-toggle="dropdown" style="color:white; font-size: 1.2rem; padding: 0.5rem 1.2rem;">
                            <i class="fas fa-user-plus me-2"></i>Register
                        </a>
                        <ul class="dropdown-menu" style="padding: 0.5rem;">
                            <li><a class="dropdown-item py-2" href="donor/register.php" style="font-size: 1.1rem;"><i class="fas fa-hand-holding-heart me-2"></i>Register as Donor</a></li>
                            <li><a class="dropdown-item py-2" href="ngo/register.php" style="font-size: 1.1rem;"><i class="fas fa-building me-2"></i>Register as NGO</a></li>
                            <li><a class="dropdown-item py-2" href="volunteer/register.php" style="font-size: 1.1rem;"><i class="fas fa-hands-helping me-2"></i>Register as Volunteer</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold" style="color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); letter-spacing: -0.5px; font-size: 3.5rem;">Reduce Food Waste <span style="color: #ffd700;">Feed the Hungry</span></h1>
                    <div class="mt-4 d-flex flex-wrap align-items-center gap-3">
                        <a href="donor/register.php" class="btn btn-lg" style="background: linear-gradient(45deg, #1a5d1a, #1e4d92); color: white; padding: 12px 30px; font-size: 1.2rem; font-weight: 500; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.2); transition: all 0.3s ease;"><i class="fas fa-hand-holding-heart me-2"></i>Donate Food</a>
                        <a href="ngo/register.php" class="btn btn-lg" style="background: linear-gradient(45deg, #1e4d92, #1a5d1a); color: white; padding: 12px 30px; font-size: 1.2rem; font-weight: 500; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.2); transition: all 0.3s ease;"><i class="fas fa-hands-helping me-2"></i>Request Food</a>
                        <a href="volunteer/register.php" class="btn btn-lg" style="background: linear-gradient(45deg, #ffd700, #ff8c00); color: #1a5d1a; padding: 12px 30px; font-size: 1.2rem; font-weight: 600; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.2); transition: all 0.3s ease;"><i class="fas fa-user-friends me-2"></i>Become a Volunteer</a>
                    </div>
                </div>
               
            </div>
        </div>
    </section>

    <!-- About and Features Section Container -->
    <div style="background: linear-gradient(120deg, rgba(255, 255, 255, 0.97), rgba(255, 255, 255, 0.93)); width: 100%; padding: 60px 30px; box-shadow: 0 0 40px rgba(0, 0, 0, 0.1);">
        <!-- About Section -->
        <div class="text-center mb-5">
            <div class="position-relative" style="margin-bottom: 3rem;">
                <h2 style="color: #1a5d1a; font-weight: 600; font-size: 2.2rem; position: relative; display: inline-block;">
                    <span style="background: linear-gradient(120deg, #1a5d1a, #2e8b57); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">About FoodSave</span>
                    <div style="position: absolute; bottom: -8px; left: 50%; transform: translateX(-50%); width: 60px; height: 3px; background: linear-gradient(90deg, #1a5d1a, #2e8b57);"></div>
                </h2>
            </div>
            <p class="lead mx-auto" style="color: #444; max-width: 900px; font-size: 1.1rem; line-height: 1.8; text-shadow: 0 1px 1px rgba(255,255,255,0.8);">
                FoodSave is a web-based platform designed to connect restaurants, grocery stores, and individuals with NGOs and food banks to efficiently donate surplus food. Our mission is to reduce food wastage, promote sustainability, and help combat hunger through a seamless, technology-driven process.
            </p>
        </div>

        <!-- Features Section -->
        <div class="mt-5">
            <div class="position-relative text-center" style="margin-bottom: 4rem;">
                <h2 style="color: #1a5d1a; font-weight: 600; font-size: 2.2rem; position: relative; display: inline-block;">
                    <span style="background: linear-gradient(120deg, #1a5d1a, #2e8b57); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Key Features</span>
                    <div style="position: absolute; bottom: -8px; left: 50%; transform: translateX(-50%); width: 60px; height: 3px; background: linear-gradient(90deg, #1a5d1a, #2e8b57);"></div>
                </h2>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100" style="border: none; background: linear-gradient(145deg, #ffffff, #f3f3f3); border-radius: 20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08); overflow: hidden;">
                        <div class="card-body text-center position-relative" style="padding: 2rem;">
                            <div class="feature-icon mb-4" style="width: 70px; height: 70px; margin: 0 auto; background: linear-gradient(135deg, #e8f5e9, #ffffff); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px rgba(26, 93, 26, 0.15);">
                                <i class="fas fa-utensils fa-lg" style="color: #1a5d1a; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));"></i>
                            </div>
                            <h5 class="card-title" style="color: #1a5d1a; font-size: 1.25rem; font-weight: 600; margin-bottom: 0.8rem;">Easy Food Listing</h5>
                            <p class="card-text" style="color: #555; font-size: 1rem; line-height: 1.5;">Donors can quickly list surplus food items with details like quantity, expiration date, and pickup location.</p>
                            <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, transparent, #1a5d1a, transparent);"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100" style="border: none; background: linear-gradient(145deg, #ffffff, #f3f3f3); border-radius: 20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08); overflow: hidden;">
                        <div class="card-body text-center position-relative" style="padding: 2rem;">
                            <div class="feature-icon mb-4" style="width: 70px; height: 70px; margin: 0 auto; background: linear-gradient(135deg, #e8f5e9, #ffffff); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px rgba(26, 93, 26, 0.15);">
                                <i class="fas fa-search fa-lg" style="color: #1a5d1a; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));"></i>
                            </div>
                            <h5 class="card-title" style="color: #1a5d1a; font-size: 1.25rem; font-weight: 600; margin-bottom: 0.8rem;">Smart Search</h5>
                            <p class="card-text" style="color: #555; font-size: 1rem; line-height: 1.5;">NGOs can browse and filter available donations by category, location, and expiration date.</p>
                            <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, transparent, #1a5d1a, transparent);"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100" style="border: none; background: linear-gradient(145deg, #ffffff, #f3f3f3); border-radius: 20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08); overflow: hidden;">
                        <div class="card-body text-center position-relative" style="padding: 2rem;">
                            <div class="feature-icon mb-4" style="width: 70px; height: 70px; margin: 0 auto; background: linear-gradient(135deg, #e8f5e9, #ffffff); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px rgba(26, 93, 26, 0.15);">
                                <i class="fas fa-truck fa-lg" style="color: #1a5d1a; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));"></i>
                            </div>
                            <h5 class="card-title" style="color: #1a5d1a; font-size: 1.25rem; font-weight: 600; margin-bottom: 0.8rem;">Pickup Management</h5>
                            <p class="card-text" style="color: #555; font-size: 1rem; line-height: 1.5;">Streamlined pickup request system with real-time tracking and automatic expiration alerts.</p>
                            <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, transparent, #1a5d1a, transparent);"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 mb-4">
                    <h3 style="color: white;"><?php echo $homeStats['meals']; ?></h3>
                    <p style="color:white;"><b>Meals Saved</b></p>
                </div>
                <div class="col-md-3 mb-4">
                    <h3 style="color: white;"><?php echo $homeStats['donors']; ?></h3>
                    <p style="color:white;"><b>Active Donors</b></p>
                </div>
                <div class="col-md-3 mb-4">
                    <h3 style="color: white;"><?php echo $homeStats['ngos']; ?></h3>
                    <p style="color:white;"><b>Partner NGOs</b></p>
                </div>
                <div class="col-md-3 mb-4">
                    <h3 style="color: white;"><?php echo $homeStats['success']; ?>%</h3>
                    <p style="color:white;"><b>Success Rate</b></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>FoodSave</h5>
                    <p>Reducing food waste, one donation at a time.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; <?php echo date("Y"); ?> FoodSave. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js"></script>
</body>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodSave</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
</html>

