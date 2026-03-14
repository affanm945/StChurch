<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            padding: 0;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 1.5rem 3rem;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-left h1 {
            color: #1a237e;
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .header-left p {
            color: #666;
            font-size: 0.9rem;
            margin-top: 0.2rem;
        }

        .logout-btn {
            background: #d32f2f;
            color: #fff;
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .logout-btn:hover {
            background: #b71c1c;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(211, 47, 47, 0.3);
        }

        .logout-btn svg {
            width: 18px;
            height: 18px;
            fill: currentColor;
        }
        
        .container {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 2rem;
        }

        .welcome-section {
            text-align: center;
            color: #fff;
            margin-bottom: 3rem;
            animation: fadeInUp 0.6s ease-out;
        }

        .welcome-section h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .welcome-section p {
            font-size: 1.1rem;
            opacity: 0.95;
        }
        
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            padding-bottom: 3rem;
        }
        
        .menu-item {
            background: #fff;
            border-radius: 20px;
            padding: 2.5rem 2rem;
            text-align: center;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            position: relative;
            overflow: hidden;
        }

        .menu-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .menu-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .menu-item:hover::before {
            transform: scaleX(1);
        }
        
        .menu-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
        }

        .menu-item:hover .menu-icon {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        
        .menu-icon svg {
            width: 40px;
            height: 40px;
            fill: #fff;
        }
        
        .menu-label {
            font-size: 1.3rem;
            font-weight: 600;
            color: #1a237e;
            margin-top: 0.5rem;
        }

        .menu-description {
            font-size: 0.9rem;
            color: #666;
            margin-top: 0.5rem;
        }
        
        /* Animation for page load */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .menu-item {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .menu-item:nth-child(1) { animation-delay: 0.1s; }
        .menu-item:nth-child(2) { animation-delay: 0.2s; }
        .menu-item:nth-child(3) { animation-delay: 0.3s; }
        .menu-item:nth-child(4) { animation-delay: 0.4s; }
        .menu-item:nth-child(5) { animation-delay: 0.5s; }
        .menu-item:nth-child(6) { animation-delay: 0.6s; }
        .menu-item:nth-child(7) { animation-delay: 0.7s; }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header {
                padding: 1rem 1.5rem;
                flex-direction: column;
                gap: 1rem;
            }

            .header-left h1 {
                font-size: 1.5rem;
            }

            .container {
                padding: 0 1rem;
                margin: 2rem auto;
            }

            .welcome-section h2 {
                font-size: 2rem;
            }

            .menu-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .menu-item {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <h1>Hi, <?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?></h1>
            <p>Manage your church content</p>
        </div>
        <a href="logout.php" class="logout-btn">
            <svg viewBox="0 0 24 24">
                <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.59L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
            </svg>
            Logout
        </a>
    </div>

    <div class="container">
        <div class="welcome-section">
            <h2>Welcome to Admin Panel</h2>
            <p>Select an option below to manage your content</p>
        </div>

        <div class="menu-grid">
            <a href="event.php" class="menu-item" title="Events">
                <div class="menu-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                    </svg>
                </div>
                <div class="menu-label">Events</div>
                <div class="menu-description">Manage church events</div>
            </a>
            
            <a href="gallery.php" class="menu-item" title="Gallery">
                <div class="menu-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M22 16V4c0-1.1-.9-2-2-2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2zm-11-4l2.03 2.71L16 11l4 5H8l3-4zM2 6v14c0 1.1.9 2 2 2h14v-2H4V6H2z"/>
                    </svg>
                </div>
                <div class="menu-label">Gallery</div>
                <div class="menu-description">Manage gallery images</div>
            </a>
            
            <a href="masstimings.php" class="menu-item" title="Mass Timings">
                <div class="menu-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/>
                        <path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                    </svg>
                </div>
                <div class="menu-label">Mass Timings</div>
                <div class="menu-description">Schedule mass times</div>
            </a>
            
            <a href="newsletter.php" class="menu-item" title="Newsletter">
                <div class="menu-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                    </svg>
                </div>
                <div class="menu-label">Newsletter</div>
                <div class="menu-description">Manage parish newsletters</div>
            </a>
            
            <!-- <a href="committies.php" class="menu-item" title="Committees">
                <div class="menu-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A1.5 1.5 0 0 0 18.54 8H17c-.8 0-1.54.37-2.01 1l-3.7 3.7V22h6zm-8-2c0 .55.45 1 1 1h1v-3h-1c-.55 0-1 .45-1 1z"/>
                        <path d="M8.5 11.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5S7 9.17 7 10s.67 1.5 1.5 1.5z"/>
                        <path d="M12 14c-1.66 0-3 1.34-3 3v1h2v-1c0-.55.45-1 1-1s1 .45 1 1v1h2v-1c0-1.66-1.34-3-3-3z"/>
                    </svg>
                </div>
                <div class="menu-label">Committees</div>
                <div class="menu-description">Manage committees</div>
            </a> -->
            
            <!-- <a href="associations.php" class="menu-item" title="Associations">
                <div class="menu-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                    </svg>
                </div>
                <div class="menu-label">Associations</div>
                <div class="menu-description">Manage associations</div>
            </a> -->

            <a href="priest.php" class="menu-item" title="Parish Priest">
                <div class="menu-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                    </svg>
                </div>
                <div class="menu-label">Parish Priest</div>
                <div class="menu-description">Manage parish priest info</div>
            </a>
            
            <a href="churchstats.php" class="menu-item" title="Church Statistics">
                <div class="menu-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                    </svg>
                </div>
                <div class="menu-label">Statistics</div>
                <div class="menu-description">Update church statistics</div>
            </a>
        </div>
    </div>
</body>
</html>
