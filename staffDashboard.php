<?php
require_once 'session.php';
checkUserAccess();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SnapCert</title>    
    <link rel="icon" type="image/png" sizes="32x32" href="image/icons/mkce_s.png">
    <link rel="stylesheet" href="global.css">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-5/bootstrap-5.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="fontFix.js"></script>
    <style>
        :root {
            --primary-color: #6366F1;
            --secondary-color: #4F46E5;
            --accent-color: #8B5CF6;
            --success-color: #10B981;
            --warning-color: #F59E0B;
            --danger-color: #EF4444;
            --light-color: #F9FAFB;
            --dark-color: #1E293B;
            --gray-color: #94A3B8;
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 70px;
            --topbar-height: 60px;
            --footer-height: 60px;
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        body {
            min-height: 100vh;
            margin: 0;
            background-color: #F1F5F9;
            font-family: 'Poppins', sans-serif;
            color: var(--dark-color);
        }

        /* Welcome Section Redesign */
        .welcome-section {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border-radius: 20px;
            padding: 30px;
            box-shadow: var(--card-shadow);
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .welcome-section::before {
            content: "";
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }

        .welcome-section::after {
            content: "";
            position: absolute;
            bottom: -30px;
            left: 30%;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }

        .welcome-section h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .welcome-section p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 0;
            color: rgba(255, 255, 255, 0.9);
        }

        .user-stats {
            margin-top: 20px;
            display: flex;
            gap: 20px;
        }

        .stat-box {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 15px;
            flex: 1;
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease;
        }

        .stat-box:hover {
            transform: translateY(-5px);
        }

        .stat-box h3 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-box p {
            font-size: 0.85rem;
            opacity: 0.8;
            margin: 0;
        }

        /* Semester Cards Redesign */
        .semester-cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }

        .semester-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            min-height: 160px;
        }

        .semester-card:hover {
            transform: translateY(-7px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .semester-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 7px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary-color), var(--accent-color));
        }

        .semester-card-content {
            padding: 25px;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .semester-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--dark-color);
            line-height: 1;
        }

        .semester-title {
            color: var(--gray-color);
            font-size: 1rem;
            margin-bottom: 15px;
        }

        .semester-icon {
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            box-shadow: 0 4px 10px rgba(99, 102, 241, 0.3);
        }

        .semester-details {
            margin-top: auto;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .semester-detail {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.9rem;
            color: var(--gray-color);
        }

        .semester-detail i {
            font-size: 0.85rem;
        }

        /* Progress Bar */
        .progress-container {
            margin-top: 15px;
            margin-bottom: 5px;
        }

        .progress-bar-custom {
            height: 5px;
            width: 100%;
            background-color: #E2E8F0;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(to right, var(--primary-color), var(--accent-color));
            border-radius: 10px;
            width: 75%;
            transition: width 0.5s ease;
        }

        /* Content Area */
        .content {
            margin-left: var(--sidebar-width);
            padding-top: var(--topbar-height);
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        .sidebar.collapsed+.content {
            margin-left: var(--sidebar-collapsed-width);
        }

        .container-fluid {
            padding: 30px;
        }

        /* Breadcrumb Redesign */
        .breadcrumb-area {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin: 20px;
            padding: 15px 25px;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .breadcrumb-item a:hover {
            color: var(--secondary-color);
        }

        .breadcrumb-item.active {
            color: var(--gray-color);
        }

        /* Loader Redesign */
        .loader-container {
            position: fixed;
            left: var(--sidebar-width);
            right: 0;
            top: var(--topbar-height);
            bottom: var(--footer-height);
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            transition: left 0.3s ease;
        }

        .loader {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            position: relative;
            animation: rotate 1s linear infinite;
        }

        .loader::before {
            content: "";
            box-sizing: border-box;
            position: absolute;
            inset: 0px;
            border-radius: 50%;
            border: 5px solid var(--primary-color);
            animation: prixClipFix 2s linear infinite;
        }

        @keyframes rotate {
            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes prixClipFix {
            0% {
                clip-path: polygon(50% 50%, 0 0, 0 0, 0 0, 0 0, 0 0);
            }

            25% {
                clip-path: polygon(50% 50%, 0 0, 100% 0, 100% 0, 100% 0, 100% 0);
            }

            50% {
                clip-path: polygon(50% 50%, 0 0, 100% 0, 100% 100%, 100% 100%, 100% 100%);
            }

            75% {
                clip-path: polygon(50% 50%, 0 0, 100% 0, 100% 100%, 0 100%, 0 100%);
            }

            100% {
                clip-path: polygon(50% 50%, 0 0, 100% 0, 100% 100%, 0 100%, 0 0);
            }
        }

        /* Hide loader when done */
        .loader-container.hide {
            display: none;
        }

        /* Content wrapper */
        .content-wrapper {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .content-wrapper.show {
            opacity: 1;
        }

        /* Responsive Styles */
        @media (max-width: 991px) {
            .semester-cards-container {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            }

            .user-stats {
                flex-direction: column;
                gap: 10px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width) !important;
            }

            .sidebar.mobile-show {
                transform: translateX(0);
            }

            .content {
                margin-left: 0 !important;
            }

            .loader-container {
                left: 0;
            }

            .welcome-section h1 {
                font-size: 2rem;
            }

            .container-fluid {
                padding: 20px;
            }
        }

        @media (max-width: 576px) {
            .semester-cards-container {
                grid-template-columns: 1fr;
            }

            .welcome-section {
                padding: 20px;
            }
        }

        /* Stats Card Styles */
        .card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-body {
            padding: 1.5rem;
        }

        .icon-container {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .bg-gradient-primary {
            background: linear-gradient(45deg, var(--primary-color), #818cf8);
        }

        .bg-gradient-warning {
            background: linear-gradient(45deg, var(--warning-color), #fbbf24);
        }

        .bg-gradient-success {
            background: linear-gradient(45deg, var(--success-color), #34d399);
        }

        .bg-gradient-danger {
            background: linear-gradient(45deg, var(--danger-color), #f87171);
        }

        .text-title {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .text-description {
            font-size: 0.8rem;
            color: var(--gray-color);
            margin-bottom: 0;
        }

        .card h2 {
            font-size: 1.8rem;
            line-height: 1.2;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <?php include '././utils/ui/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="content">

        <div class="loader-container" id="loaderContainer">
            <div class="loader"></div>
        </div>

        <!-- Topbar -->
        <?php include '././utils/ui/topbar.php'; ?>

        <!-- Breadcrumb -->
        <div class="breadcrumb-area">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Learning_Management</li>
                </ol>
            </nav>
        </div>
        <!-- Content Area -->
        <div class="container-fluid mt-4">
            <div class="welcome-section">
                <h1>Welcome, <?php echo $_SESSION['user']['name']; ?>!</h1>
                <p>Here's an overview of your staff metrics.</p>
            </div>
            <div class="row g-4">
                <!-- Total Courses Created Card -->
                <div class="col-md-3">
                    <div class="card shadow-lg">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="icon-container bg-gradient-primary text-white">
                                <i class="fas fa-chalkboard-teacher" style="font-size: 2rem;"></i>
                            </div>
                            <div class="text-end">
                                <h5 class="text-title text-primary">Total Created</h5>
                                <h2 class="fw-bold mb-0" id="totalCourses">-</h2>
                                <p class="text-description mt-1">Courses created</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Courses Card -->
                <div class="col-md-3">
                    <div class="card shadow-lg">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="icon-container bg-gradient-warning text-white">
                                <i class="fas fa-hourglass-half" style="font-size: 2rem;"></i>
                            </div>
                            <div class="text-end">
                                <h5 class="text-title text-warning">Pending</h5>
                                <h2 class="fw-bold mb-0" id="pendingCourses">-</h2>
                                <p class="text-description mt-1">Courses awaiting</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Accepted Courses Card -->
                <div class="col-md-3">
                    <div class="card shadow-lg">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="icon-container bg-gradient-success text-white">
                                <i class="fas fa-check-circle" style="font-size: 2rem;"></i>
                            </div>
                            <div class="text-end">
                                <h5 class="text-title text-success">Accepted</h5>
                                <h2 class="fw-bold mb-0" id="acceptedCourses">-</h2>
                                <p class="text-description mt-1">Courses approved</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rejected Courses Card -->
                <div class="col-md-3">
                    <div class="card shadow-lg">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="icon-container bg-gradient-danger text-white">
                                <i class="fas fa-exclamation-circle" style="font-size: 2rem;"></i>
                            </div>
                            <div class="text-end">
                                <h5 class="text-title text-danger">Rejected</h5>
                                <h2 class="fw-bold mb-0" id="rejectedCourses">-</h2>
                                <p class="text-description mt-1">Courses rejected</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>




        <!-- Footer -->
        <?php include '././utils/ui/footer.php'; ?>
    </div>
    <script>
        const loaderContainer = document.getElementById('loaderContainer');

        function showLoader() {
            loaderContainer.classList.add('show');
        }

        function hideLoader() {
            loaderContainer.classList.remove('show');
        }

        //    automatic loader
        document.addEventListener('DOMContentLoaded', function() {
            const loaderContainer = document.getElementById('loaderContainer');
            const contentWrapper = document.getElementById('contentWrapper');
            let loadingTimeout;

            function hideLoader() {
                loaderContainer.classList.add('hide');
                contentWrapper.classList.add('show');
            }

            function showError() {
                console.error('Page load took too long or encountered an error');
                // You can add custom error handling here
            }

            // Set a maximum loading time (10 seconds)
            loadingTimeout = setTimeout(showError, 10000);

            // Hide loader when everything is loaded
            window.onload = function() {
                clearTimeout(loadingTimeout);

                // Add a small delay to ensure smooth transition
                setTimeout(hideLoader, 500);
            };

            // Error handling
            window.onerror = function(msg, url, lineNo, columnNo, error) {
                clearTimeout(loadingTimeout);
                showError();
                return false;
            };
        });

        // Toggle Sidebar
        const hamburger = document.getElementById('hamburger');
        const sidebar = document.getElementById('sidebar');
        const body = document.body;
        const mobileOverlay = document.getElementById('mobileOverlay');

        function toggleSidebar() {
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('mobile-show');
                mobileOverlay.classList.toggle('show');
                body.classList.toggle('sidebar-open');
            } else {
                sidebar.classList.toggle('collapsed');
            }
        }
        hamburger.addEventListener('click', toggleSidebar);
        mobileOverlay.addEventListener('click', toggleSidebar);
        // Toggle User Menu
        const userMenu = document.getElementById('userMenu');
        const dropdownMenu = userMenu.querySelector('.dropdown-menu');

        userMenu.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', () => {
            dropdownMenu.classList.remove('show');
        });

        // Toggle Submenu
        const menuItems = document.querySelectorAll('.has-submenu');
        menuItems.forEach(item => {
            item.addEventListener('click', () => {
                const submenu = item.nextElementSibling;
                item.classList.toggle('active');
                submenu.classList.toggle('active');
            });
        });

        // Handle responsive behavior
        window.addEventListener('resize', () => {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('collapsed');
                sidebar.classList.remove('mobile-show');
                mobileOverlay.classList.remove('show');
                body.classList.remove('sidebar-open');
            } else {
                sidebar.style.transform = '';
                mobileOverlay.classList.remove('show');
                body.classList.remove('sidebar-open');
            }
        });

        function loadStaffStats() {
            $.ajax({
                url: "backend.php",
                type: "POST",
                data: {
                    action: "getStaffStats"
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $("#totalCourses").text(response.data.total);
                        $("#pendingCourses").text(response.data.pending);
                        $("#acceptedCourses").text(response.data.accepted);
                        $("#rejectedCourses").text(response.data.rejected);
                    }
                }
            });
        }

        // Call when document is ready
        $(document).ready(function() {
            loadStaffStats();
        });
    </script>

</body>

</html>