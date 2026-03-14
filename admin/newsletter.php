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
    <title>Admin - Newsletter</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
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

        .header-title h1 {
            color: #1a237e;
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin: 0;
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

        .container {
            max-width: 1200px;
            margin: 40px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 6px 32px rgba(0, 0, 0, 0.10);
            padding: 2.5rem;
        }

        .form-section {
            background: #f9f9f9;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }

        .form-section h2 {
            color: #0b3d91;
            margin-bottom: 1.5rem;
        }

        .form-section label {
            display: block;
            margin-bottom: 1rem;
            color: #333;
            font-weight: 500;
        }

        .form-section input,
        .form-section input[type="file"] {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            margin-top: 0.5rem;
            font-family: 'Poppins', sans-serif;
        }

        .form-section button {
            background: #0b3d91;
            color: #fff;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            margin-right: 1rem;
            margin-top: 1rem;
        }

        .form-section button:hover {
            background: #092c6d;
        }

        .form-section button#cancelEdit {
            background: #78909c;
        }

        .error {
            color: #d32f2f;
            margin-top: 1rem;
        }

        .success {
            color: #388e3c;
            margin-top: 1rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background: #f5f5f5;
            font-weight: 600;
            color: #1a237e;
        }

        .actions {
            display: flex;
            gap: 0.5rem;
        }

        .actions button {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .actions button.edit {
            background: #0b3d91;
            color: #fff;
        }

        .actions button.delete {
            background: #d9534f;
            color: #fff;
        }

        .actions button:hover {
            opacity: 0.85;
        }

        @media (max-width: 700px) {
            .header {
                padding: 1rem 1.5rem;
                flex-direction: column;
                gap: 1rem;
            }

            .container {
                padding: 1.5rem;
                margin: 20px auto;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="header-left">
            <div class="header-title">
                <h1>Newsletter Management</h1>
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
        <div class="form-section">
            <h2 id="formTitle">Add New Newsletter</h2>
            <form id="newsletterForm" enctype="multipart/form-data">
                <input type="hidden" name="id" id="newsletterId" />
                <label>
                    Title
                    <input type="text" name="title" id="title" required />
                </label>
                <label>
                    Date
                    <input type="date" name="date" id="date" required />
                </label>
                <label>
                    Newsletter File (PDF)
                    <input type="file" name="file" id="file" accept=".pdf,.doc,.docx" />
                    <small style="color: #666;">Upload a PDF or document file</small>
                </label>
                <label>
                    OR Download URL
                    <input type="url" name="download_url" id="download_url" placeholder="https://..." />
                    <small style="color: #666;">Provide a direct download link if not uploading a file</small>
                </label>
                <button type="submit" id="submitBtn">Add Newsletter</button>
                <button type="button" id="cancelEdit" style="display:none;">Cancel</button>
            </form>
            <div class="error" id="formError"></div>
            <div class="success" id="formSuccess"></div>
        </div>

        <div style="overflow-x: auto;">
            <table id="newsletterTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Date</th>
                        <th>File</th>
                        <th>Download URL</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        let table;
        let editingId = null;

        $(document).ready(function() {
            table = $('#newsletterTable').DataTable({
                ajax: {
                    url: 'api/newsletterapi.php',
                    dataSrc: ''
                },
                columns: [
                    { data: 'id' },
                    { data: 'title' },
                    { 
                        data: 'date',
                        render: function(data) {
                            if (data) {
                                return new Date(data).toLocaleDateString();
                            }
                            return '';
                        }
                    },
                    { 
                        data: 'file',
                        render: function(data) {
                            if (data) {
                                return `<a href="../newsletters/${data}" target="_blank">View File</a>`;
                            }
                            return '-';
                        }
                    },
                    { 
                        data: 'download_url',
                        render: function(data) {
                            if (data) {
                                return `<a href="${data}" target="_blank">Download</a>`;
                            }
                            return '-';
                        }
                    },
                    {
                        data: 'id',
                        render: function(data) {
                            return `
                                <div class="actions">
                                    <button class="edit" onclick="editNewsletter(${data})">Edit</button>
                                    <button class="delete" onclick="deleteNewsletter(${data})">Delete</button>
                                </div>
                            `;
                        }
                    }
                ],
                order: [[2, 'desc']]
            });
        });

        document.getElementById('newsletterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const formError = document.getElementById('formError');
            const formSuccess = document.getElementById('formSuccess');
            
            formError.textContent = '';
            formSuccess.textContent = '';

            if (editingId) {
                formData.append('_method', 'PUT');
            }

            fetch('api/newsletterapi.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    formSuccess.textContent = editingId ? 'Newsletter updated successfully!' : 'Newsletter added successfully!';
                    this.reset();
                    editingId = null;
                    document.getElementById('formTitle').textContent = 'Add New Newsletter';
                    document.getElementById('submitBtn').textContent = 'Add Newsletter';
                    document.getElementById('cancelEdit').style.display = 'none';
                    table.ajax.reload();
                } else {
                    formError.textContent = data.error || 'An error occurred';
                }
            })
            .catch(error => {
                formError.textContent = 'Error: ' + error.message;
            });
        });

        function editNewsletter(id) {
            fetch(`api/newsletterapi.php?id=${id}`)
                .then(res => res.json())
                .then(data => {
                    if (data) {
                        editingId = id;
                        document.getElementById('newsletterId').value = data.id;
                        document.getElementById('title').value = data.title || '';
                        document.getElementById('date').value = data.date || '';
                        document.getElementById('download_url').value = data.download_url || '';
                        document.getElementById('formTitle').textContent = 'Edit Newsletter';
                        document.getElementById('submitBtn').textContent = 'Update Newsletter';
                        document.getElementById('cancelEdit').style.display = 'inline-block';
                        document.getElementById('formError').textContent = '';
                        document.getElementById('formSuccess').textContent = '';
                    }
                });
        }

        function deleteNewsletter(id) {
            if (confirm('Are you sure you want to delete this newsletter?')) {
                fetch(`api/newsletterapi.php?id=${id}`, {
                    method: 'DELETE'
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        table.ajax.reload();
                    } else {
                        alert('Error deleting newsletter');
                    }
                });
            }
        }

        document.getElementById('cancelEdit').addEventListener('click', function() {
            editingId = null;
            document.getElementById('newsletterForm').reset();
            document.getElementById('formTitle').textContent = 'Add New Newsletter';
            document.getElementById('submitBtn').textContent = 'Add Newsletter';
            this.style.display = 'none';
            document.getElementById('formError').textContent = '';
            document.getElementById('formSuccess').textContent = '';
        });
    </script>
</body>

</html>

