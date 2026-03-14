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
    <title>Admin - Church Statistics</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
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

        .header-left {
            display: flex;
            align-items: center;
        }

        .header-title {
            flex: 1;
        }

        .back-btn {
            background: #667eea;
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
            font-family: 'Poppins', sans-serif;
        }

        .back-btn:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .back-btn svg {
            width: 18px;
            height: 18px;
            fill: currentColor;
        }

        .header-title h1 {
            color: #1a237e;
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin: 0;
        }

        .container {
            max-width: 950px;
            margin: 40px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 6px 32px rgba(0,0,0,0.10);
            padding: 2.5rem 2rem 2rem 2rem;
        }

        .form-section h2 {
            color: #283593;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            letter-spacing: 0.5px;
        }
        .form-section {
            margin-bottom: 2rem;
        }

        .stats-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-item {
            background: linear-gradient(135deg, #f8fafc 0%, #e8f0fe 100%);
            padding: 2rem 1.5rem;
            border-radius: 12px;
            border: 2px solid #e3eafc;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .stat-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
            border-color: #667eea;
        }

        .stat-item label {
            font-weight: 600;
            color: #1a237e;
            margin-bottom: 0.75rem;
            display: block;
            font-size: 1rem;
        }

        .stat-item input[type="number"] {
            width: 100%;
            padding: 0.85rem 1rem;
            margin-top: 0.5rem;
            border-radius: 8px;
            border: 2px solid #c5cae9;
            font-size: 1.1rem;
            background: #fff;
            transition: all 0.2s;
            box-sizing: border-box;
            font-weight: 600;
            color: #1a237e;
            font-family: 'Poppins', sans-serif;
        }

        .stat-item input[type="number"]:focus {
            border-color: #3949ab;
            outline: none;
            box-shadow: 0 0 0 4px rgba(57, 73, 171, 0.1);
        }

        .submit-container {
            text-align: center;
            margin-top: 2rem;
        }

        button[type="submit"] {
            background: linear-gradient(135deg, #283593, #3949ab);
            color: #fff;
            border: none;
            padding: 1rem 3rem;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            box-shadow: 0 4px 15px rgba(40, 53, 147, 0.3);
        }

        button[type="submit"]:hover {
            background: linear-gradient(135deg, #1a237e, #283593);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 53, 147, 0.4);
        }

        button[type="submit"]:disabled {
            background: #9e9e9e;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .error {
            color: #d32f2f;
            margin-top: 1.5rem;
            font-size: 1rem;
            text-align: center;
            padding: 1rem;
            background: #ffebee;
            border-radius: 8px;
            border-left: 4px solid #d32f2f;
        }

        .success {
            color: #388e3c;
            margin-top: 1.5rem;
            font-size: 1rem;
            text-align: center;
            padding: 1rem;
            background: #e8f5e9;
            border-radius: 8px;
            border-left: 4px solid #388e3c;
        }

        @media (max-width: 700px) {
            .header {
                padding: 1rem 1.5rem;
                flex-direction: column;
                gap: 1rem;
            }

            .header-left {
                width: 100%;
            }

            .header-title h1 {
                font-size: 1.5rem;
            }

            .back-btn {
                width: 100%;
                justify-content: center;
            }

            .container {
                padding: 1.5rem 1rem;
                margin: 20px auto;
            }

            .stats-form {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            button[type="submit"] {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <div class="header-title">
                <h1>Church Statistics Management</h1>
            </div>
        </div>
        <a href="admin.php" class="back-btn">
            <svg viewBox="0 0 24 24">
                <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
            </svg>
            Back to Dashboard
        </a>
    </div>

<div class="container">
    <div class="form-section">
        <h2>Update Church Statistics</h2>
        <form id="statsForm">
            <div class="stats-form">
                <div class="stat-item">
                    <label>Total Families</label>
                    <input type="number" name="total_families" id="total_families" min="0" required>
                </div>
                <div class="stat-item">
                    <label>Church Members</label>
                    <input type="number" name="church_members" id="church_members" min="0" required>
                </div>
                <div class="stat-item">
                    <label>Ministries</label>
                    <input type="number" name="ministries" id="ministries" min="0" required>
                </div>
                <div class="stat-item">
                    <label>Total Anbiam</label>
                    <input type="number" name="total_anbiam" id="total_anbiam" min="0" required>
                </div>
            </div>
            <div class="submit-container">
                <button type="submit" id="submitBtn">Update Statistics</button>
            </div>
            <div id="message"></div>
        </form>
    </div>
</div>
<script>
function fetchStats() {
    fetch('api/churchstatsapi.php')
        .then(res => res.json())
        .then(data => {
            if (data.total_families) {
                document.getElementById('total_families').value = data.total_families.value || 0;
            }
            if (data.church_members) {
                document.getElementById('church_members').value = data.church_members.value || 0;
            }
            if (data.ministries) {
                document.getElementById('ministries').value = data.ministries.value || 0;
            }
            if (data.total_anbiam) {
                document.getElementById('total_anbiam').value = data.total_anbiam.value || 0;
            }
        })
        .catch(error => {
            console.error('Error fetching stats:', error);
        });
}

document.getElementById('statsForm').onsubmit = function(e) {
    e.preventDefault();
    const form = e.target;
    const messageDiv = document.getElementById('message');
    
    const stats = {
        total_families: parseInt(form.total_families.value) || 0,
        church_members: parseInt(form.church_members.value) || 0,
        ministries: parseInt(form.ministries.value) || 0,
        total_anbiam: parseInt(form.total_anbiam.value) || 0
    };
    
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Updating...';
    submitBtn.disabled = true;
    messageDiv.innerHTML = '';
    
    fetch('api/churchstatsapi.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(stats)
    })
    .then(res => {
        if (!res.ok) {
            throw new Error('Network response was not ok');
        }
        return res.json();
    })
    .then(result => {
        if (result.success) {
            messageDiv.innerHTML = '<div class="success">Statistics updated successfully!</div>';
            setTimeout(() => {
                messageDiv.innerHTML = '';
            }, 3000);
        } else {
            messageDiv.innerHTML = '<div class="error">' + (result.error || 'Failed to update statistics. Please try again.') + '</div>';
        }
    })
    .catch(error => {
        console.error('Error updating stats:', error);
        messageDiv.innerHTML = '<div class="error">Failed to update statistics. Please check your connection and try again.</div>';
    })
    .finally(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
};

fetchStats();
</script>
</body>
</html>

