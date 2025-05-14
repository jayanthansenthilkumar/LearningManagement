<html>

<head>
    <link rel="stylesheet" href="global.css">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-5/bootstrap-5.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="fontFix.js"></script>
    <!-- Add particles.js library -->
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
</head>

</html>
<?php

include("db.php");
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // username and password sent from form 
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $myusername = mysqli_real_escape_string($conn, $_POST['uid']);
    $mypassword = mysqli_real_escape_string($conn, $_POST['pass']);

    if ($type == "faculty") {
        $sql = "SELECT * FROM faculty WHERE id = ? AND pass = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $myusername, $mypassword);
    } elseif ($type == "student") {
        $sql = "SELECT * FROM student WHERE sid = ? AND pass = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $myusername, $mypassword);
    } else {
        // Handle invalid type
        echo "<script>
                swal.fire({
                    icon: 'error',
                    title: 'Login Failure',
                    text: 'Invalid user type'
                }).then(function() {
                    window.location = './index.php';
                });
              </script>";
        exit;
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;

    if ($count == 1) {
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['login_user'] = $myusername;
        $_SESSION['user_type'] = $type;
        $_SESSION['login_time'] = time();

        $user = $result->fetch_assoc();

        if ($type == "student") {
            // Store student data
            $userData = [
                'id' => $user['sid'],
                'name' => $user['sname'],
                'dept' => $user['dept'],
                'year' => $user['ayear'],
                'role' => 'student'
            ];
        } else {
            // For faculty
            $_SESSION['role'] = $user['role'] === 'HOD' ? 'hod' : 'staff';
            $userData = [
                'id' => $user['id'],
                'name' => $user['name'],
                'dept' => $user['dept'],
                'role' => $user['role'] === 'HOD' ? 'hod' : 'staff'
            ];
        }

        echo "<script>
                // Store user data in sessionStorage
                sessionStorage.setItem('userData', JSON.stringify(" . json_encode($userData) . "));
                
                swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Login Successful'
                }).then(function() {
                    window.location = 'dashboard.php';
                });
              </script>";
    } else {
        echo "<script>
                swal.fire({
                    icon: 'error',
                    title: 'Login Failure',
                    text: 'Check login credentials'                }).then(function() {
                    window.location = './index.php';
                });
              </script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MKCE - Learning Management System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --accent: #4cc9f0;
            --light: #f8f9fa;
            --dark: #212529;
            --success: #4CAF50;
            --info: #2196F3;
            --warning: #FF9800;
            --danger: #f44336;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            height: 100vh;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        
        .auth-container {
            height: 100vh;
            position: relative;
            background: linear-gradient(45deg, rgba(67, 97, 238, 0.05), rgba(76, 201, 240, 0.05));
            overflow: hidden;
            display: flex;
        }
        
        .auth-image {
            display: none;
        }
        
        .auth-card-container {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 2rem 4rem; /* Reduced top padding */
            position: relative;
            z-index: 10;
            overflow-y: auto;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        .auth-card-container::-webkit-scrollbar {
            display: none; /* Hide scrollbar for Chrome, Safari and Opera */
        }
        
        .floating-shape {
            position: absolute;
            border-radius: 50%;
            z-index: 1;
        }
        
        .shape-1 {
            width: 350px;
            height: 350px;
            background: linear-gradient(45deg, var(--primary), var(--accent));
            top: -100px;
            right: -150px;
            opacity: 0.2;
        }
        
        .shape-2 {
            width: 250px;
            height: 250px;
            background: linear-gradient(45deg, var(--secondary), var(--accent));
            bottom: -80px;
            left: -100px;
            opacity: 0.15;
        }
        
        .auth-card {
            width: 100%;
            max-width: 450px; /* Slightly reduced width */
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            position: relative;
            margin: auto; /* Center the card */
        }
        
        .brand-header {
            padding: 2rem;
            text-align: center;
            background-color: white;
            position: relative;
        }
        
        .brand-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(67, 97, 238, 0.3), 
                rgba(76, 201, 240, 0.3), 
                rgba(67, 97, 238, 0.3), 
                transparent
            );
        }
        
        .brand-logo {
            max-width: 180px;
            margin-bottom: 1rem;
            border-radius: 10px;
            padding: 8px;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        
        .brand-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        
        .brand-subtitle {
            font-size: 1rem;
            color: #6c757d;
            margin-bottom: 0;
        }
        
        .auth-body {
            padding: 2.5rem;
        }
        
        .auth-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .tab-switcher {
            display: flex;
            margin-bottom: 2rem;
            background: var(--light);
            border-radius: 12px;
            padding: 0.5rem;
            position: relative;
        }
        
        .tab-switcher .tab-btn {
            flex: 1;
            padding: 0.75rem 1rem;
            text-align: center;
            color: #6c757d;
            font-weight: 600;
            font-size: 0.95rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: transparent;
            border: none;
            position: relative;
            z-index: 10;
        }
        
        .tab-switcher .tab-indicator {
            position: absolute;
            top: 0.5rem;
            left: 0.5rem;
            width: calc(50% - 0.5rem);
            height: calc(100% - 1rem);
            background: white;
            border-radius: 8px;
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }
        
        .tab-switcher[data-active="faculty"] .tab-indicator {
            left: calc(50% + 0.25rem);
        }
        
        .tab-switcher .tab-btn.active {
            color: var(--primary);
        }
        
        .auth-form {
            display: none;
        }
        
        .auth-form.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .form-floating {
            margin-bottom: 1.5rem;
        }
        
        .form-floating .form-control {
            height: 60px;
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding: 1rem 1rem 0;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .form-floating .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        }
        
        .form-floating > label {
            padding: 1rem 1rem 0;
            color: #6c757d;
        }
        
        .form-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
            z-index: 10;
        }
        
        .btn-auth {
            height: 56px;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: none;
            position: relative;
            overflow: hidden;
        }
        
        .btn-auth::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                rgba(255,255,255,0.1), 
                rgba(255,255,255,0.2), 
                rgba(255,255,255,0.1)
            );
            transition: all 0.6s;
        }
        
        .btn-auth:hover::before {
            left: 100%;
        }
        
        .btn-student {
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            color: white;
        }
        
        .btn-faculty {
            background: linear-gradient(45deg, var(--accent), #3DAED8);
            color: white;
        }
        
        .btn-auth:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
            color: white;
        }
        
        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        .main-footer {
            background: white;
            padding: 0.8rem 0;
            text-align: center;
            font-size: 0.9rem;
            color: #6c757d;
            border-top: 1px solid #e9ecef;
            position: absolute;
            bottom: 0;
            right: 0;
            width: 100%;
            z-index: 5;
        }
        
        /* Particles container styling */
        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
        }
        
        /* Ensure content appears above particles */
        .auth-card-container {
            z-index: 10;
        }
        
        .auth-image {
            z-index: 5;
        }
        
        @media (min-width: 992px) {
            .auth-container {
                display: flex;
                flex-direction: row;
            }
            
            .auth-image {
                display: block;
                width: 50%;
                height: 100vh;
                position: relative; /* Changed from absolute to relative */
                background: linear-gradient(135deg, var(--primary), var(--secondary));
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                color: white;
                padding: 2rem;
                text-align: center;
            }
            
            .auth-card-container {
                width: 50%;
                padding-left: 2rem;
                padding-right: 2rem;
            }
            
            .main-footer {
                width: 50%;
            }
            
            .lms-icon {
                font-size: 5rem;
                margin-bottom: 2rem;
                padding: 2rem;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.1);
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            }
            
            .lms-title {
                font-size: 2.5rem;
                font-weight: 700;
                margin-bottom: 1rem;
                text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            }
            
            .lms-subtitle {
                font-size: 1.25rem;
                opacity: 0.9;
                max-width: 80%;
                margin: 0 auto;
                font-weight: 300;
            }
            
            .lms-features {
                margin-top: 3rem;
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 1.5rem;
            }
            
            .lms-feature {
                background: rgba(255, 255, 255, 0.1);
                border-radius: 12px;
                padding: 1rem 1.5rem;
                display: flex;
                align-items: center;
                gap: 1rem;
                width: 80%;
                backdrop-filter: blur(5px);
            }
            
            .feature-icon {
                font-size: 1.5rem;
                color: white;
            }
            
            .feature-text {
                font-size: 1rem;
                font-weight: 500;
                text-align: left;
            }
        }
        
        @media (max-width: 576px) {
            .auth-card {
                max-width: 100%;
            }
            
            .brand-header {
                padding: 1.5rem;
            }
            
            .auth-body {
                padding: 1.5rem;
            }
            
            .brand-logo {
                max-width: 150px;
            }
            
            .btn-auth {
                height: 50px;
            }
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <!-- Particles container -->
        <div id="particles-js"></div>
        
        <!-- Left side with image and features (visible on large screens) -->
        <div class="auth-image">
            <div class="lms-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <h1 class="lms-title">Learning Management System</h1>
            
            <div class="lms-features">
                <div class="lms-feature">
                    <div class="feature-icon">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <div class="feature-text">Access course materials anytime, anywhere</div>
                </div>
                <div class="lms-feature">
                    <div class="feature-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="feature-text">Track assignments and submissions</div>
                </div>
                <div class="lms-feature">
                    <div class="feature-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="feature-text">Communicate with instructors and peers</div>
                </div>
            </div>
        </div>
        
        <!-- Floating shapes for mobile view background -->
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        
        <!-- Right side with login form -->
        <div class="auth-card-container">
            <div class="auth-card">
                <div class="brand-header">
                    <h2 class="brand-title">Welcome Back</h2>
                    <p class="brand-subtitle">Sign in to access your account</p>
                </div>
                
                <div class="auth-body">
                    <div class="tab-switcher" data-active="student">
                        <button class="tab-btn active" data-target="student-form">Student</button>
                        <button class="tab-btn" data-target="faculty-form">Faculty</button>
                        <div class="tab-indicator"></div>
                    </div>
                    
                    <div class="auth-form active" id="student-form">
                        <form action="#" method="post">
                            <input type="hidden" name="type" value="student">
                            
                            <div class="form-floating">
                                <input type="text" class="form-control" id="studentId" name="uid" placeholder="Student ID" required>
                                <label for="studentId">Student ID</label>
                                <div class="form-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            
                            <div class="form-floating">
                                <input type="password" class="form-control" id="studentPassword" name="pass" placeholder="Password" required>
                                <label for="studentPassword">Password</label>
                                <div class="form-icon">
                                    <i class="fas fa-lock"></i>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-auth btn-student w-100">
                                <i class="fas fa-sign-in-alt me-2"></i> Sign in as Student
                            </button>
                        </form>
                    </div>
                    
                    <div class="auth-form" id="faculty-form">
                        <form action="#" method="post">
                            <input type="hidden" name="type" value="faculty">
                            
                            <div class="form-floating">
                                <input type="text" class="form-control" id="facultyId" name="uid" placeholder="Faculty ID" required>
                                <label for="facultyId">Faculty ID</label>
                                <div class="form-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            
                            <div class="form-floating">
                                <input type="password" class="form-control" id="facultyPassword" name="pass" placeholder="Password" required>
                                <label for="facultyPassword">Password</label>
                                <div class="form-icon">
                                    <i class="fas fa-lock"></i>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-auth btn-faculty w-100">
                                <i class="fas fa-sign-in-alt me-2"></i> Sign in as Faculty
                            </button>
                        </form>
                    </div>
                    
                    <div class="auth-footer">
                        <p>Having trouble logging in? Contact support.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <footer class="main-footer">
            <div class="container">
                <p class="mb-0">Copyright © 2024 Designed by PrisolTech. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Tab switching functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabSwitcher = document.querySelector('.tab-switcher');
            const authForms = document.querySelectorAll('.auth-form');
            
            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Update active state for buttons
                    tabBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Move indicator
                    const target = this.getAttribute('data-target');
                    if (target === 'faculty-form') {
                        tabSwitcher.setAttribute('data-active', 'faculty');
                    } else {
                        tabSwitcher.setAttribute('data-active', 'student');
                    }
                    
                    // Show corresponding form
                    authForms.forEach(form => {
                        form.classList.remove('active');
                        if (form.id === target) {
                            form.classList.add('active');
                        }
                    });
                });
            });
        });
        
        // Initialize particles.js
        document.addEventListener('DOMContentLoaded', function() {
            particlesJS('particles-js', {
                "particles": {
                    "number": {
                        "value": 80,
                        "density": {
                            "enable": true,
                            "value_area": 800
                        }
                    },
                    "color": {
                        "value": "#4361ee"
                    },
                    "shape": {
                        "type": "circle",
                        "stroke": {
                            "width": 0,
                            "color": "#000000"
                        },
                        "polygon": {
                            "nb_sides": 5
                        }
                    },
                    "opacity": {
                        "value": 0.5,
                        "random": false,
                        "anim": {
                            "enable": false,
                            "speed": 1,
                            "opacity_min": 0.1,
                            "sync": false
                        }
                    },
                    "size": {
                        "value": 3,
                        "random": true,
                        "anim": {
                            "enable": false,
                            "speed": 40,
                            "size_min": 0.1,
                            "sync": false
                        }
                    },
                    "line_linked": {
                        "enable": true,
                        "distance": 150,
                        "color": "#4361ee",
                        "opacity": 0.2,
                        "width": 1
                    },
                    "move": {
                        "enable": true,
                        "speed": 2,
                        "direction": "none",
                        "random": false,
                        "straight": false,
                        "out_mode": "out",
                        "bounce": false,
                        "attract": {
                            "enable": false,
                            "rotateX": 600,
                            "rotateY": 1200
                        }
                    }
                },
                "interactivity": {
                    "detect_on": "canvas",
                    "events": {
                        "onhover": {
                            "enable": true,
                            "mode": "grab"
                        },
                        "onclick": {
                            "enable": true,
                            "mode": "push"
                        },
                        "resize": true
                    },
                    "modes": {
                        "grab": {
                            "distance": 140,
                            "line_linked": {
                                "opacity": 1
                            }
                        },
                        "bubble": {
                            "distance": 400,
                            "size": 40,
                            "duration": 2,
                            "opacity": 8,
                            "speed": 3
                        },
                        "repulse": {
                            "distance": 200,
                            "duration": 0.4
                        },
                        "push": {
                            "particles_nb": 4
                        },
                        "remove": {
                            "particles_nb": 2
                        }
                    }
                },
                "retina_detect": true
            });
        });
    </script>
</body>

</html>