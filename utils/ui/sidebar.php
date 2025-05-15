<style>    /* Sidebar Styles */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: var(--sidebar-width);
        background: linear-gradient(135deg, #2c3e50, #1a242f);
        transition: var(--transition);
        z-index: 1000;
        overflow-y: auto;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        font-family: 'Poppins', sans-serif;
    }

    .sidebar::-webkit-scrollbar {
        width: 5px;
        display: block;
    }
    
    .sidebar::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.2);
        border-radius: 10px;
    }
    
    .sidebar:hover::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.3);
    }

    .sidebar {
        scrollbar-width: thin;
        scrollbar-color: rgba(255,255,255,0.2) transparent;
    }

    .sidebar.collapsed {
        width: var(--sidebar-collapsed-width);
    }

    .sidebar .logo {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px 20px;
        color: white;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        margin-bottom: 10px;
    }

    .sidebar .logo img {
        max-height: 80px;
        width: auto;
        transition: all 0.3s ease;
    }

    .sidebar .s_logo {
        display: none;
    }

    .sidebar.collapsed .logo img {
        display: none;
    }

    .sidebar.collapsed .logo .s_logo {
        display: flex;
        max-height: 45px;
        width: auto;
        align-items: center;
        justify-content: center;
    }

    .sidebar .menu {
        padding: 5px 10px;
    }

    .menu-item {
        padding: 12px 16px;
        color: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        cursor: pointer;
        border-radius: 8px;
        margin: 6px 0;
        transition: all 0.3s ease;
        position: relative;
        text-decoration: none;
        font-weight: 500;
        letter-spacing: 0.3px;
    }

    .menu-item:hover {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        transform: translateX(3px);
    }

    .menu-item i {
        min-width: 30px;
        font-size: 18px;
        transition: all 0.3s ease;
    }
    
    .menu-item:hover i {
        transform: scale(1.1);
    }

    .menu-item span {
        margin-left: 10px;
        transition: all 0.3s ease;
        flex-grow: 1;
    }

    .sidebar.collapsed .menu-item span {
        display: none;
    }
</style>

<div class="mobile-overlay" id="mobileOverlay"></div>
<div class="sidebar" id="sidebar">
    <div class="logo">
        <img src="image/mkce.png" alt="College Logo">
        <img class='s_logo' src="image/mkce_s.png" alt="College Logo">
    </div>

    <div class="menu">
        <a href="./dashboard.php" class="menu-item">
            <i class="fas fa-home text-primary"></i>
            <span>Dashboard</span>
        </a>

        <?php
        require_once $_SERVER['DOCUMENT_ROOT'] . '/LMS/session.php';

        if (!isset($_SESSION['user'])) {
            initializeUserSession();
        }

        $user_role = $_SESSION['user']['role'];

        if ($user_role === 'student') {
        ?>
            <a href="./student.php" class="menu-item">
                <i class="fas fa-user-graduate text-success"></i>
                <span>Student LMS</span>
            </a>
        <?php
        } elseif ($user_role === 'HOD') {
        ?>
            <a href="./hod.php" class="menu-item">
                <i class="fas fa-chalkboard-teacher text-success"></i>
                <span>HOD LMS</span>
            </a>
        <?php
        } else {
        ?>
            <a href="./staff.php" class="menu-item">
                <i class="fas fa-users text-success"></i>
                <span>Staff LMS</span>
            </a>
        <?php
        }
        ?>
    </div>
</div>