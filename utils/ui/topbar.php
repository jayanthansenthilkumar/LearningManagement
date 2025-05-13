<style>
    /* Topbar Styles */
    .topbar {
        position: fixed;
        top: 0;
        right: 0;
        left: var(--sidebar-width);
        height: var(--topbar-height);
        background: linear-gradient(135deg, #6200ea, #03dac6);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        padding: 0 20px;
        transition: all 0.3s ease;
        z-index: 999;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .brand-logo {
        display: none;
        color: white;
        font-size: 24px;
        margin: 0 auto;
    }

    .sidebar.collapsed+.content .topbar {
        left: var(--sidebar-collapsed-width);
    }

    .hamburger {
        cursor: pointer;
        font-size: 22px;
        color: white;
        transition: transform 0.3s ease;
    }
    
    .hamburger:hover {
        transform: scale(1.1);
    }

    .user-profile {
        margin-left: auto;
        color: white;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 500;
    }

    .user-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        transition: var(--transition);
        border: 2px solid rgba(255, 255, 255, 0.8);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .user-avatar:hover {
        transform: scale(1.1);
    }

    .online-indicator {
        position: absolute;
        width: 10px;
        height: 10px;
        background: var(--success-color);
        border-radius: 50%;
        bottom: 0;
        right: 0;
        border: 2px solid white;
        animation: blink 1.5s infinite;
    }

    @keyframes blink {
        0% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
        100% {
            opacity: 1;
        }
    }

    /* User Menu Dropdown */
    .user-menu {
        position: relative;
        cursor: pointer;
    }

    .dropdown-menu {
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        border-radius: 8px;
        display: none;
        min-width: 200px;
        margin-top: 10px;
        overflow: hidden;
    }

    .dropdown-menu.show {
        display: block;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            transform: translateY(-10px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .dropdown-item {
        padding: 12px 20px;
        color: #333;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
    }

    .dropdown-item:hover {
        background: #f5f5f5;
        color: #6200ea;
    }
    
    /* User avatar image styling */
    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }
</style>

<div class="topbar">
    <div class="hamburger" id="hamburger">
        <i class="fas fa-bars"></i>
    </div>
    <!-- <div class="brand-logo">
                <i class="fas fa-chart-line"></i>
                MIC
            </div> -->
    <div class="user-profile">
        <div class="user-menu" id="userMenu">
            <div class="user-avatar">
                <img src="/api/placeholder/35/35" alt="User">
                <div class="online-indicator"></div>
            </div>
            <div class="dropdown-menu">
                <a class="dropdown-item">
                    <i class="fas fa-key"></i>
                    Change Password
                </a>
                <a class="dropdown-item" href="Logout.php" onclick="handleLogout(event)">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </div>
        <span><?php echo isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : 'Guest'; ?></span>
    </div>
</div>

<script>
    function handleLogout(event) {
        event.preventDefault();

        // Clear sessionStorage
        sessionStorage.clear();

        // Redirect to logout.php
        window.location.href = 'Logout.php';
    }
</script>