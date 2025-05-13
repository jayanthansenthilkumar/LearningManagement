<?php
require_once 'session.php';
checkUserAccess();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIC</title>    <link rel="icon" type="image/png" sizes="32x32" href="image/icons/mkce_s.png">
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
        .welcome-section {
            background: linear-gradient(90deg, #42a5f5, #478ed1);
            color: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .welcome-section h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .welcome-section p {
            font-size: 1.2rem;
            font-weight: 300;
        }

        /* card styles */
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }

        .icon-container {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Gradient Backgrounds for Icons */
        .bg-gradient-warning {
            background: linear-gradient(45deg, #FFD54F, #FFA726);
        }

        .bg-gradient-success {
            background: linear-gradient(45deg, #66BB6A, #43A047);
        }

        .bg-gradient-primary {
            background: linear-gradient(45deg, #42A5F5, #1E88E5);
        }

        /* Card Header Styling */
        .card-header {
            border: none;
            font-weight: bold;
        }

        /* Subtle Card Body Shadow */
        .card-body {
            box-shadow: inset 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Text Styling */
        .text-title {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .text-description {
            font-size: 0.85rem;
            color: #666;
        }

        :root {
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 70px;
            --topbar-height: 60px;
            --footer-height: 60px;
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --dark-bg: #1a1c23;
            --light-bg: #f8f9fc;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* General Styles with Enhanced Typography */
        body {
            min-height: 100vh;
            margin: 0;
            background: var(--light-bg);
            overflow-x: hidden;
            padding-bottom: var(--footer-height);
            position: relative;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        /* Content Area Styles */
        .content {
            margin-left: var(--sidebar-width);
            padding-top: var(--topbar-height);
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        /* Content Navigation */
        .content-nav {
            background: linear-gradient(45deg, #4e73df, #1cc88a);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .content-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 20px;
            overflow-x: auto;
        }

        .content-nav li a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .content-nav li a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .sidebar.collapsed+.content {
            margin-left: var(--sidebar-collapsed-width);
        }

        .breadcrumb-area {
            background: white;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            margin: 20px;
            padding: 15px 20px;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb-item a:hover {
            color: #224abe;
        }



        /* Table Styles */
        .custom-table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .custom-table thead {
            background: var(--primary-color);
            color: white;
        }

        .custom-table th {
            font-weight: 500;
            padding: 15px;
        }

        th {
            background: linear-gradient(135deg, #4CAF50, #2196F3);
        }

        td {
            text-align: center;
        }

        .custom-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }





        /* Responsive Styles */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width) !important;
            }

            .sidebar.mobile-show {
                transform: translateX(0);
            }

            .topbar {
                left: 0 !important;
            }

            .mobile-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
                display: none;
            }

            .mobile-overlay.show {
                display: block;
            }

            .content {
                margin-left: 0 !important;
            }

            .brand-logo {
                display: block;
            }

            .user-profile {
                margin-left: 0;
            }

            .sidebar .logo {
                justify-content: center;
            }

            .sidebar .menu-item span,
            .sidebar .has-submenu::after {
                display: block !important;
            }

            body.sidebar-open {
                overflow: hidden;
            }

            .footer {
                left: 0 !important;
            }

            .content-nav ul {
                flex-wrap: nowrap;
                overflow-x: auto;
                padding-bottom: 5px;
            }

            .content-nav ul::-webkit-scrollbar {
                height: 4px;
            }

            .content-nav ul::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.3);
                border-radius: 2px;
            }
        }

        .container-fluid {
            padding: 20px;
        }


        /* loader */
        .loader-container {
            position: fixed;
            left: var(--sidebar-width);
            right: 0;
            top: var(--topbar-height);
            bottom: var(--footer-height);
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            /* Changed from 'none' to show by default */
            justify-content: center;
            align-items: center;
            z-index: 1000;
            transition: left 0.3s ease;
        }

        .sidebar.collapsed+.content .loader-container {
            left: var(--sidebar-collapsed-width);
        }

        @media (max-width: 768px) {
            .loader-container {
                left: 0;
            }
        }

        /* Hide loader when done */
        .loader-container.hide {
            display: none;
        }

        /* Loader Animation */
        .loader {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-radius: 50%;
            border-top: 5px solid var(--primary-color);
            border-right: 5px solid var(--success-color);
            border-bottom: 5px solid var(--primary-color);
            border-left: 5px solid var(--success-color);
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Hide content initially */
        .content-wrapper {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        /* Show content when loaded */
        .content-wrapper.show {
            opacity: 1;
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