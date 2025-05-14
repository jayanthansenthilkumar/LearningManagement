<style>
    .footer {
        position: fixed;
        bottom: 0;
        left: var(--sidebar-width);
        right: 0;
        height: var(--footer-height);
        background: linear-gradient(135deg, #6200ea, #03dac6);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center; 
        padding: 0 20px;
        transition: all 0.3s ease;
        z-index: 999;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
    }

    .sidebar.collapsed+.content .footer {
        left: var(--sidebar-collapsed-width);
    }

    .footer-copyright {
        text-align: center;
        font-weight: 500;
        letter-spacing: 0.3px;
    }

    .footer-links {
        display: flex;
        gap: 20px;
    }

    .footer-links a {
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .footer-links a:hover {
        opacity: 0.8;
    }    .footer-content {
        display: flex;
        flex-direction: column;
        align-items: center; 
    }
    
    .footer-highlight {
        background: white;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: 0px 0px 3px rgba(255,255,255,0.2);
        padding: 0 2px;
        font-weight: 700;
    }
</style>
<footer class="footer">
    <div class="footer-content">        
        <div class="footer-copyright">
            <p>Copyright © 2024 Designed by <span class="footer-highlight">PrisolTech</span> All rights reserved.</p>
        </div>
    </div>
</footer>