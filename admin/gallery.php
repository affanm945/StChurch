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
    <title>Admin - Gallery</title>
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
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 6px 32px rgba(0, 0, 0, 0.10);
            padding: 2.5rem 2rem 2rem 2rem;
        }

        h1 {
            text-align: center;
            color: #1a237e;
        }

        .gallery-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 2rem;
        }

        .gallery-item {
            width: 180px;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            padding: 10px;
            text-align: center;
            position: relative;
        }

        .gallery-img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 6px;
        }

        .gallery-title {
            margin: 10px 0 5px 0;
            font-size: 1rem;
            color: #000;
        }

        .actions {
            display: flex;
            justify-content: center;
            gap: 8px;
        }

        .actions button {
            background: #0b3d91;
            color: #fff;
            border: none;
            padding: 5px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        .actions button.delete {
            background: #d9534f;
        }

        .actions button:hover {
            opacity: 0.85;
        }

        .form-section {
            margin-top: 2rem;
        }

        .form-section h2 {
            color: #0b3d91;
        }

        label {
            display: block;
            margin-top: 1rem;
        }

        input,
        textarea {
            width: 100%;
            padding: 0.5rem;
            margin-top: 0.3rem;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button[type=submit] {
            background: #0b3d91;
            color: #fff;
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 1rem;
        }

        button[type=submit]:hover {
            background: #092c6d;
        }

        #cancelEdit {
            background: #b0bec5;
            color: #263238;
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 1rem;
            margin-right: 0.7rem;
        }

        #cancelEdit:hover {
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
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="header-left">
            <div class="header-title">
                <h1>Gallery Management</h1>
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
            <h2 id="formTitle">Add New Image</h2>
            <form id="galleryForm" enctype="multipart/form-data">
                <input type="hidden" name="id" id="galleryId" />
                <label>Title <input type="text" name="title" id="title" /></label>
                <label>Image <input type="file" name="image" id="image" accept="image/*" /></label>
                <div id="imagePreview"></div>
                <button type="submit" id="submitBtn">Add Image</button>
                <button type="button" id="cancelEdit" style="display:none;">Cancel</button>
            </form>
            <div class="error" id="formError"></div>
            <div class="success" id="formSuccess"></div>
        </div>
        <div class="gallery-grid" id="galleryGrid"></div>
    </div>
    <script>
        let editingId = null;
        let imageFile = null;
        let imageUrl = null;

        function fetchGallery() {
            fetch('api/galleryapi.php')
                .then(res => res.json())
                .then(images => {
                    const grid = document.getElementById('galleryGrid');
                    grid.innerHTML = '';
                    if (!Array.isArray(images)) return;
                    images.forEach(img => {
                        const div = document.createElement('div');
                        div.className = 'gallery-item';
                        div.innerHTML = `
                    <img src="../image/${img.image}" class="gallery-img" />
                    <div class="gallery-title">${img.title || ''}</div>
                    <div class="actions">
                        <button onclick="editImage(${img.id})">Edit</button>
                        <button class="delete" onclick="deleteImage(${img.id})">Delete</button>
                    </div>
                `;
                        grid.appendChild(div);
                    });
                });
        }

        function editImage(id) {
            fetch('api/galleryapi.php')
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Failed to fetch images');
                    }
                    return res.json();
                })
                .then(images => {
                    const img = images.find(i => i.id == id);
                    if (!img) {
                        alert('Image not found');
                        return;
                    }
                    editingId = id;
                    document.getElementById('formTitle').innerText = 'Edit Image';
                    document.getElementById('submitBtn').innerText = 'Update Image';
                    document.getElementById('cancelEdit').style.display = '';
                    document.getElementById('galleryId').value = img.id;
                    document.getElementById('title').value = img.title || '';
                    imageUrl = `../image/${img.image}`;
                    showImagePreview(imageUrl);
                    // Scroll to form
                    document.querySelector('.form-section').scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                })
                .catch(error => {
                    console.error('Error loading image:', error);
                    alert('Failed to load image. Please try again.');
                });
        }

        // Make editImage globally accessible
        window.editImage = editImage;

        function deleteImage(id) {
            if (!confirm('Are you sure you want to delete this image?')) return;
            fetch('api/galleryapi.php?id=' + id, {
                    method: 'DELETE'
                })
                .then(res => res.json())
                .then(resp => {
                    fetchGallery();
                })
                .catch(error => {
                    console.error('Error deleting image:', error);
                    alert('Failed to delete image. Please try again.');
                });
        }

        // Make deleteImage globally accessible
        window.deleteImage = deleteImage;

        document.getElementById('cancelEdit').onclick = function() {
            editingId = null;
            document.getElementById('formTitle').innerText = 'Add New Image';
            document.getElementById('submitBtn').innerText = 'Add Image';
            document.getElementById('cancelEdit').style.display = 'none';
            document.getElementById('galleryForm').reset();
            document.getElementById('galleryId').value = '';
            document.getElementById('imagePreview').innerHTML = '';
            imageFile = null;
            imageUrl = null;
        };

        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                imageFile = this.files[0];
                showImagePreview(URL.createObjectURL(imageFile));
            }
        });

        function showImagePreview(src) {
            imagePreview.innerHTML = `<img src=\"${src}\" style=\"width:100px;height:70px;object-fit:cover;border-radius:4px;\" />`;
        }

        document.getElementById('galleryForm').onsubmit = function(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);

            if (imageFile) {
                formData.append('image', imageFile);
            }

            if (editingId) {
                formData.append('_method', 'PUT');
                formData.append('id', editingId);

                fetch('api/galleryapi.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(resp => {
                        if (resp.success) {
                            fetchGallery();
                            document.getElementById('formSuccess').innerText = 'Image updated!';
                            document.getElementById('galleryForm').reset();
                            document.getElementById('cancelEdit').onclick();
                            imageFile = null;
                            imageUrl = null;
                            imagePreview.innerHTML = '';
                        } else {
                            document.getElementById('formError').innerText = resp.error || 'Update failed.';
                        }
                    });
            } else {
                fetch('api/galleryapi.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(resp => {
                        if (resp.success) {
                            fetchGallery();
                            document.getElementById('formSuccess').innerText = 'Image added!';
                            document.getElementById('galleryForm').reset();
                            imageFile = null;
                            imageUrl = null;
                            imagePreview.innerHTML = '';
                        } else {
                            document.getElementById('formError').innerText = resp.error || 'Add failed.';
                        }
                    });
            }
        };

        fetchGallery();
    </script>
</body>

</html>