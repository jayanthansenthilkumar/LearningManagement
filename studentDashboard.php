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
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
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
                    <li class="breadcrumb-item"><a href="#"><i class="fas fa-home me-1"></i>Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Learning Management</li>
                </ol>
            </nav>
        </div>

        <!-- Content Area -->
        <div class="container-fluid mt-4" id="contentWrapper">
            <div class="welcome-section">
                <h1>Welcome back, <?php echo isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : 'Guest'; ?>!</h1>
                <p>Track your academic journey and explore your semester courses</p>

            </div>
            <div class="semester-cards-container" id="semester-cards-container">
                <!-- Semester Cards will be dynamically generated here -->
            </div>
        </div>

        <!-- Footer -->
        <?php include '././utils/ui/footer.php'; ?>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const courseYear = '<?php echo $_SESSION['user']['year']; ?>';
            const semesterCardsContainer = document.getElementById('semester-cards-container');
            console.log(courseYear);
            let totalSemesters = 0;
            if (courseYear == 1) {
                totalSemesters = 2;
            } else if (courseYear == 2) {
                totalSemesters = 4;
            } else if (courseYear == 3) {
                totalSemesters = 6;
            } else if (courseYear == 4) {
                totalSemesters = 8;
            }

            for (let i = 1; i <= totalSemesters; i++) {
                // Random progress for demonstration
                const progress = Math.floor(Math.random() * 100);

                // Random number of courses for demonstration
                const courses = Math.floor(Math.random() * 5) + 3;

                const semesterCardHtml = `
                <div class="semester-card">
                    <div class="semester-card-content">
                        <div class="semester-number">${i}</div>
                        <div class="semester-title">Semester</div>

                        <div class="semester-details">
                            <div class="semester-detail">
                                <i class="fas fa-book"></i>
                                <span>${courses} Courses</span>
                            </div>
                            <div class="semester-detail">
                                <i class="fas fa-clock"></i>
                                <span>${progress}% Complete</span>
                            </div>
                        </div>
                    </div>
                </div>
                `;

                semesterCardsContainer.insertAdjacentHTML('beforeend', semesterCardHtml);

                // Attach click event to navigate to the student's semester courses page
                const semesterCard = semesterCardsContainer.lastElementChild;
                semesterCard.addEventListener('click', function() {
                    // Store the semester value in sessionStorage
                    sessionStorage.setItem('semester', i);

                    // Redirect to student.php without reloading the page
                    window.location.href = 'student.php';
                });
            }
        });

        const loaderContainer = document.getElementById('loaderContainer');

        function showLoader() {
            loaderContainer.classList.remove('hide');
        }

        function hideLoader() {
            loaderContainer.classList.add('hide');
        }

        // automatic loader
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
        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', toggleSidebar);
        }

        // Toggle User Menu
        const userMenu = document.getElementById('userMenu');
        if (userMenu) {
            const dropdownMenu = userMenu.querySelector('.dropdown-menu');

            userMenu.addEventListener('click', (e) => {
                e.stopPropagation();
                dropdownMenu.classList.toggle('show');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', () => {
                dropdownMenu.classList.remove('show');
            });
        }

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
                if (mobileOverlay) {
                    mobileOverlay.classList.remove('show');
                }
                body.classList.remove('sidebar-open');
            } else {
                sidebar.style.transform = '';
                if (mobileOverlay) {
                    mobileOverlay.classList.remove('show');
                }
                body.classList.remove('sidebar-open');
            }
        });
    </script>

</body>

</html>