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
    <title>Admin - Mass Timings</title>
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
            box-shadow: 0 6px 32px rgba(0, 0, 0, 0.10);
            padding: 2.5rem 2rem 2rem 2rem;
        }

        h2 {
            text-align: left;
            color: #0b3d91;
        }

        .day-tabs {
            margin-bottom: 2rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .day-tabs button {
            padding: 0.7rem 1.5rem;
            border: none;
            background: #f0f0f0;
            color: #000;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        .day-tabs button:hover {
            background: #e0e0e0;
        }

        .day-tabs button.active {
            background: #333;
            color: #fff;
        }

        .add-form,
        .special-form {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e3eafc;
        }

        .form-row {
            display: flex;
            gap: 1rem;
            align-items: end;
        }

        .form-row input {
            flex: 1;
            min-width: 200px;
        }

        .form-row input[type="time"] {
            flex: 0 0 150px;
        }

        .form-row input[type="text"] {
            flex: 1;
            min-width: 250px;
        }

        .form-row button {
            margin-top: 0;
        }

        .button-row {
            display: flex;
            gap: 0.7rem;
            margin-top: 1.2rem;
        }

        .button-row button {
            padding: 0.7rem 2.2rem;
            border-radius: 7px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            border: none;
            min-width: 140px;
        }

        .add-form h3,
        .special-form h3 {
            color: #283593;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1.2rem;
            letter-spacing: 0.5px;
        }

        label {
            font-weight: 500;
            color: #333;
            margin-bottom: 0.3rem;
            display: block;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 0.65rem 0.9rem;
            margin-top: 0.3rem;
            border-radius: 7px;
            border: 1.5px solid #c5cae9;
            font-size: 1rem;
            background: #f8fafc;
            transition: border 0.2s;
            box-sizing: border-box;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border: 1.5px solid #3949ab;
            outline: none;
            background: #fff;
        }

        button[type="submit"] {
            background: #283593;
            color: #fff;
            border: none;
            padding: 0.7rem 2.2rem;
            border-radius: 7px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            margin-top: 1.2rem;
            margin-right: 0.7rem;
            transition: background 0.2s;
        }

        button[type="submit"]:hover {
            background: #1a237e;
        }

        #cancelEditBtn,
        #cancelSpecialEditBtn {
            background: #b0bec5;
            color: #263238;
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 7px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            margin-top: 1.2rem;
            margin-right: 0.7rem;
            transition: background 0.2s;
        }

        #cancelEditBtn:hover,
        #cancelSpecialEditBtn:hover {
            background: #78909c;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2.5rem;
            background: #f8fafc;
            border-radius: 10px;
            overflow: hidden;
        }

        th,
        td {
            padding: 0.8rem 0.7rem;
            border-bottom: 1px solid #e3e3e3;
            text-align: left;
            font-size: 1rem;
        }

        th {
            background: #e3eafc;
            color: #1a237e;
            font-weight: 600;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .actions button {
            margin-right: 0.5rem;
            padding: 0.5rem 1.2rem;
            font-size: 0.98rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.2s;
            margin-top: 3px;
        }

        .actions button:first-child {
            background: #283593;
            color: #fff;
        }

        .actions button:first-child:hover {
            background: #1a237e;
        }

        .actions button:last-child {
            background: #d9534f;
            color: #fff;
        }

        .actions button:last-child:hover {
            background: #c9302c;
        }

        .error {
            color: #d32f2f;
            margin-top: 1rem;
            font-size: 1rem;
        }

        .success {
            color: #388e3c;
            margin-top: 1rem;
            font-size: 1rem;
        }

        .tooltip-container {
            position: relative;
            display: inline-block;
        }

        .custom-tooltip {
            position: absolute;
            background: #333;
            color: #fff;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 14px;
            max-width: 300px;
            word-wrap: break-word;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s, visibility 0.3s;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            margin-bottom: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .custom-tooltip::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #333 transparent transparent transparent;
        }

        .tooltip-container:hover .custom-tooltip {
            opacity: 1;
            visibility: visible;
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
                padding: 1rem 1.5rem;
                margin: 20px auto;
            }

            table,
            th,
            td {
                font-size: 0.95rem;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="header-left">
            <div class="header-title">
                <h1>Mass Timings</h1>
            </div>
        </div>
        <a href="admin.php" class="back-btn">
            <svg viewBox="0 0 24 24">
                <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z" />
            </svg>
            Back to Dashboard
        </a>
    </div>

    <div class="container">
        <h2 id="formTitle">Add New Mass Timings</h2>
        <div class="day-tabs" id="dayTabs"></div>
        <form class="add-form" id="addForm">
            <input type="hidden" name="type" value="regular">
            <input type="hidden" name="day" id="formDay">
            <div class="form-row">
                <div class="tooltip-container">
                    <input type="time" name="time" required>
                    <div class="custom-tooltip" id="timeTooltip"></div>
                </div>
                <div class="tooltip-container">
                    <input type="text" name="description" placeholder="Description" required>
                    <div class="custom-tooltip" id="descriptionTooltip"></div>
                </div>
            </div>
            <div class="button-row">
                <button type="submit">Add Timing</button>
            </div>
        </form>
        <table id="timingsTable">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <div id="specialSection" style="display:none;">
            <form class="special-form" id="specialForm">
                <input type="hidden" name="type" value="special">
                <div class="form-row">
                    <div class="tooltip-container">
                        <input type="date" name="date" required>
                        <div class="custom-tooltip" id="specialDateTooltip"></div>
                    </div>
                    <div class="tooltip-container">
                        <input type="text" name="day" placeholder="Day/Occasion" required>
                        <div class="custom-tooltip" id="specialDayTooltip"></div>
                    </div>
                    <div class="tooltip-container">
                        <input type="time" name="time" required>
                        <div class="custom-tooltip" id="specialTimeTooltip"></div>
                    </div>
                    <div class="tooltip-container">
                        <input type="text" name="description" placeholder="Description" required>
                        <div class="custom-tooltip" id="specialDescriptionTooltip"></div>
                    </div>
                </div>
                <div class="button-row">
                    <button type="submit">Add Special Timing</button>
                </div>
            </form>
            <table id="specialTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Day/Occasion</th>
                        <th>Time</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <script>
        const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        let currentTab = days[0]; // can be a day or 'Special'
        let editId = null; // Track which row is being edited
        let specialEditId = null; // Track which special row is being edited

        function fetchTimings() {
            fetch('api/masstimingsapi.php')
                .then(res => res.json())
                .then(data => {
                    // Regular timings
                    const timings = data.regular[currentTab] || [];
                    const tbody = document.querySelector('#timingsTable tbody');
                    tbody.innerHTML = '';
                    if (days.includes(currentTab)) {
                        timings.forEach(row => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `<td>${row.time}</td><td>${row.description}</td>
                        <td class='actions'>
                            <button onclick='editTiming(${row.id}, "${row.time}", "${row.description.replace(/"/g, '&quot;')}")'>Edit</button>
                            <button onclick='deleteTiming(${row.id})'>Delete</button>
                        </td>`;
                            tbody.appendChild(tr);
                        });
                    }

                    // Special timings
                    const special = data.special || [];
                    const specialTbody = document.querySelector('#specialTable tbody');
                    specialTbody.innerHTML = '';
                    if (currentTab === 'Special') {
                        special.forEach(row => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `<td>${row.date}</td><td>${row.day}</td><td>${row.time}</td><td>${row.description}</td>
                        <td class='actions'>
                            <button onclick='editSpecial(${row.id}, "${row.date}", "${row.day}", "${row.time}", "${row.description.replace(/"/g, '&quot;')}")'>Edit</button>
                            <button onclick='deleteSpecial(${row.id})'>Delete</button>
                        </td>`;
                            specialTbody.appendChild(tr);
                        });
                    }
                });
        }

        function setTab(tab) {
            currentTab = tab;
            document.getElementById('formDay').value = days.includes(tab) ? tab : '';
            // Toggle visibility
            document.querySelector('.add-form').style.display = days.includes(tab) ? 'block' : 'none';
            document.getElementById('timingsTable').style.display = days.includes(tab) ? 'table' : 'none';
            document.getElementById('specialSection').style.display = tab === 'Special' ? 'block' : 'none';
            // Highlight active tab
            document.querySelectorAll('.day-tabs button').forEach(btn => {
                btn.classList.toggle('active', btn.textContent === tab || (tab === 'Special' && btn.dataset.special === '1'));
            });
            fetchTimings();
        }

        function setFormToEdit(id, time, description) {
            editId = id;
            document.querySelector('#addForm [name="time"]').value = time;
            document.querySelector('#addForm [name="description"]').value = description;
            document.querySelector('#addForm button[type="submit"]').textContent = "Update Timing";
            let cancelBtn = document.getElementById('cancelEditBtn');
            if (!cancelBtn) {
                cancelBtn = document.createElement('button');
                cancelBtn.type = 'button';
                cancelBtn.id = 'cancelEditBtn';
                cancelBtn.textContent = 'Cancel';
                document.querySelector('#addForm .button-row').appendChild(cancelBtn);
                cancelBtn.onclick = resetForm;
            } else {
                cancelBtn.style.display = '';
            }
        }

        function resetForm() {
            editId = null;
            document.getElementById('addForm').reset();
            document.querySelector('#addForm button[type="submit"]').textContent = "Add Timing";
            let cancelBtn = document.getElementById('cancelEditBtn');
            if (cancelBtn) cancelBtn.style.display = 'none';
        }

        function setSpecialFormToEdit(id, date, day, time, description) {
            specialEditId = id;
            const form = document.getElementById('specialForm');
            form.querySelector('[name="date"]').value = date;
            form.querySelector('[name="day"]').value = day;
            form.querySelector('[name="time"]').value = time;
            form.querySelector('[name="description"]').value = description;
            form.querySelector('button[type="submit"]').textContent = "Update Special Timing";
            let cancelBtn = document.getElementById('cancelSpecialEditBtn');
            if (!cancelBtn) {
                cancelBtn = document.createElement('button');
                cancelBtn.type = 'button';
                cancelBtn.id = 'cancelSpecialEditBtn';
                cancelBtn.textContent = 'Cancel';
                form.querySelector('.button-row').appendChild(cancelBtn);
                cancelBtn.onclick = resetSpecialForm;
            } else {
                cancelBtn.style.display = '';
            }
        }

        function resetSpecialForm() {
            specialEditId = null;
            const form = document.getElementById('specialForm');
            form.reset();
            form.querySelector('button[type="submit"]').textContent = "Add Special Timing";
            let cancelBtn = document.getElementById('cancelSpecialEditBtn');
            if (cancelBtn) cancelBtn.style.display = 'none';
        }

        document.getElementById('addForm').onsubmit = function(e) {
            e.preventDefault();
            const form = e.target;
            const data = new FormData(form);
            data.set('day', currentTab);
            if (editId) {
                data.set('_method', 'PUT');
                data.set('id', editId);
                data.set('type', 'regular');
                fetch('api/masstimingsapi.php', {
                        method: 'POST',
                        body: data
                    })
                    .then(res => res.json())
                    .then(() => {
                        resetForm();
                        fetchTimings();
                    });
            } else {
                fetch('api/masstimingsapi.php', {
                        method: 'POST',
                        body: data
                    })
                    .then(res => res.json())
                    .then(() => {
                        form.reset();
                        fetchTimings();
                    });
            }
        };

        document.getElementById('specialForm').onsubmit = function(e) {
            e.preventDefault();
            const form = e.target;
            const data = new FormData(form);
            if (specialEditId) {
                data.set('_method', 'PUT');
                data.set('id', specialEditId);
                data.set('type', 'special');
                fetch('api/masstimingsapi.php', {
                        method: 'POST',
                        body: data
                    })
                    .then(res => res.json())
                    .then(() => {
                        resetSpecialForm(); // <-- This resets the form and hides the update/cancel buttons
                        fetchTimings();
                    });
            } else {
                fetch('api/masstimingsapi.php', {
                        method: 'POST',
                        body: data
                    })
                    .then(res => res.json())
                    .then(() => {
                        form.reset();
                        fetchTimings();
                    });
            }
        };

        window.editTiming = function(id, time, description) {
            setFormToEdit(id, time, description);
        };

        window.deleteTiming = function(id) {
            if (!confirm('Delete this timing?')) return;
            fetch(`api/masstimingsapi.php?id=${id}&type=regular`, {
                    method: 'DELETE'
                })
                .then(res => res.json())
                .then(() => fetchTimings());
        };

        window.editSpecial = function(id, date, day, time, description) {
            setSpecialFormToEdit(id, date, day, time, description);
        };

        window.deleteSpecial = function(id) {
            if (!confirm('Delete this special timing?')) return;
            fetch(`api/masstimingsapi.php?id=${id}&type=special`, {
                    method: 'DELETE'
                })
                .then(res => res.json())
                .then(() => fetchTimings());
        };

        // Render day tabs + special tab
        const dayTabs = document.getElementById('dayTabs');
        days.forEach(day => {
            const btn = document.createElement('button');
            btn.textContent = day;
            btn.onclick = () => setTab(day);
            if (day === currentTab) btn.className = 'active';
            dayTabs.appendChild(btn);
        });
        const specialBtn = document.createElement('button');
        specialBtn.textContent = 'Special Mass Timings';
        specialBtn.dataset.special = '1';
        specialBtn.onclick = () => setTab('Special');
        dayTabs.appendChild(specialBtn);

        document.getElementById('formDay').value = currentTab;
        setTab(currentTab); // Initial load

        // Tooltip functionality
        function setupTooltips() {
            const inputs = document.querySelectorAll('input[type="text"], input[type="time"], input[type="date"]');
            inputs.forEach(input => {
                const tooltip = input.parentElement.querySelector('.custom-tooltip');
                if (tooltip) {
                    input.addEventListener('input', function() {
                        tooltip.textContent = this.value;
                    });
                    input.addEventListener('focus', function() {
                        tooltip.textContent = this.value;
                    });
                }
            });
        }

        // Initialize tooltips
        setupTooltips();
    </script>
</body>

</html>