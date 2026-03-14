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
    <title>Admin - Parish Priest</title>
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
            margin-bottom: 1.2rem;
            letter-spacing: 0.5px;
        }

        .add-form {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e3eafc;
        }

        label {
            font-weight: 500;
            color: #333;
            margin-bottom: 0.3rem;
            display: block;
            margin-top: 1rem;
        }

        label:first-child {
            margin-top: 0;
        }

        input, textarea {
            width: 100%;
            padding: 0.65rem 0.9rem;
            margin-top: 0.3rem;
            border-radius: 7px;
            border: 1.5px solid #c5cae9;
            font-size: 1rem;
            background: #f8fafc;
            transition: border 0.2s;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        input:focus, textarea:focus {
            border: 1.5px solid #3949ab;
            outline: none;
            background: #fff;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
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
            font-family: 'Poppins', sans-serif;
        }

        button[type="submit"]:hover {
            background: #1a237e;
        }

        button[type="submit"]:disabled {
            background: #9e9e9e;
            cursor: not-allowed;
        }

        #cancelEditBtn {
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
            font-family: 'Poppins', sans-serif;
        }

        #cancelEditBtn:hover {
            background: #78909c;
        }

        .current-priest {
            background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
            padding: 2rem;
            border-radius: 16px;
            border: 2px solid #667eea;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
        }

        .current-priest h3 {
            color: #1a237e;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .current-priest-info {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 2rem;
            align-items: center;
        }

        .current-priest-info img {
            width: 150px;
            height: 150px;
            border-radius: 12px;
            object-fit: cover;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .current-priest-details p {
            margin: 0.8rem 0;
            line-height: 1.6;
        }

        .current-priest-details p strong {
            color: #1a237e;
            font-size: 1.2rem;
            display: block;
            margin-bottom: 0.5rem;
        }

        .current-priest-details small {
            color: #666;
            font-size: 0.9rem;
        }

        #imagePreview img, #editImagePreview {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #c5cae9;
            margin-top: 10px;
        }

        #deleteImageIcon {
            display: inline-block;
            color: #d32f2f;
            cursor: pointer;
            margin-left: 10px;
            padding: 0.5rem 1rem;
            background: #ffebee;
            border-radius: 5px;
            transition: all 0.2s;
        }

        #deleteImageIcon:hover {
            background: #ffcdd2;
        }

        .error {
            color: #d32f2f;
            margin-top: 1rem;
            font-size: 1rem;
            padding: 0.75rem;
            background: #ffebee;
            border-radius: 8px;
            border-left: 4px solid #d32f2f;
        }

        .success {
            color: #388e3c;
            margin-top: 1rem;
            font-size: 1rem;
            padding: 0.75rem;
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

            .current-priest-info {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                text-align: center;
            }

            .current-priest-info img {
                margin: 0 auto;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <div class="header-title">
                <h1>Parish Priest Management</h1>
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
    
    <div id="currentPriestDisplay" class="current-priest" style="display: none;">
        <h3>Current Parish Priest</h3>
        <div class="current-priest-info">
            <img id="currentPriestImage" src="" alt="Current Priest">
            <div class="current-priest-details">
                <p><strong id="currentPriestName"></strong></p>
                <p id="currentPriestDescription"></p>
                <p><small id="currentPriestMeta"></small></p>
            </div>
        </div>
    </div>

    <div class="form-section">
        <h2 id="formTitle">Parish Priest Information</h2>
        <form class="add-form" id="addForm" enctype="multipart/form-data">
            <label>Priest Name
                <input type="text" name="name" placeholder="Enter priest name (e.g., Rev. Fr. Michael Joseph)" required>
            </label>
            <label>Description
                <textarea name="description" maxlength="1000" placeholder="Enter description about the priest" required></textarea>
            </label>
            <label>Serving Since
                <input type="text" name="serving_since" placeholder="Enter year or date (e.g., 2023)" required>
            </label>
            <label>Diocese
                <input type="text" name="diocese" placeholder="Enter diocese name (e.g., Diocese of Chengalpattu)" required>
            </label>
            <label>Priest Image
                <input type="file" name="image" accept="image/*">
                <div id="imagePreview" style="margin-top: 10px;"></div>
                <img id="editImagePreview" src="" alt="Current image" style="display: none;">
                <span id="deleteImageIcon" style="display: none;">🗑️ Delete Image</span>
            </label>
            <input type="hidden" name="delete_image" id="deleteImageFlag" value="0">
            <input type="hidden" name="is_current" value="1">
            <button type="submit" id="submitBtn">Save Parish Priest</button>
        </form>
        <div class="error" id="formError" style="display: none;"></div>
        <div class="success" id="formSuccess" style="display: none;"></div>
    </div>
</div>
<script>
let editPriestId = null;

function fetchCurrentPriest() {
    fetch('api/priestapi.php')
        .then(res => {
            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }
            return res.json();
        })
        .then(priest => {
            if (priest && priest.id) {
                displayCurrentPriest(priest);
                setFormToEdit(priest);
            } else {
                document.getElementById('currentPriestDisplay').style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error fetching current priest:', error);
            document.getElementById('currentPriestDisplay').style.display = 'none';
        });
}

function displayCurrentPriest(priest) {
    const displayDiv = document.getElementById('currentPriestDisplay');
    document.getElementById('currentPriestName').textContent = priest.name || 'Unknown';
    document.getElementById('currentPriestDescription').textContent = priest.description || '';
    document.getElementById('currentPriestImage').src = priest.image ? `../image/${priest.image}` : '../image/default-event.jpg';
    
    let meta = [];
    if (priest.serving_since) meta.push(`Serving Since: ${priest.serving_since}`);
    if (priest.diocese) meta.push(`Diocese: ${priest.diocese}`);
    document.getElementById('currentPriestMeta').textContent = meta.join(' • ');
    
    displayDiv.style.display = 'block';
}

function setFormToEdit(priest) {
    editPriestId = priest.id;
    const form = document.getElementById('addForm');
    document.getElementById('formTitle').innerText = 'Update Parish Priest Information';
    form.name.value = priest.name || '';
    form.description.value = priest.description || '';
    form.serving_since.value = priest.serving_since || '';
    form.diocese.value = priest.diocese || '';
    document.getElementById('submitBtn').textContent = "Update Parish Priest";
    
    // Show current image
    const imgPreview = document.getElementById('editImagePreview');
    const deleteIcon = document.getElementById('deleteImageIcon');
    const deleteFlag = document.getElementById('deleteImageFlag');
    if (priest.image) {
        imgPreview.src = "../image/" + priest.image;
        imgPreview.style.display = '';
        deleteIcon.style.display = '';
        deleteFlag.value = "0";
    } else {
        imgPreview.style.display = 'none';
        deleteIcon.style.display = 'none';
        deleteFlag.value = "0";
    }
    
    let cancelBtn = document.getElementById('cancelEditBtn');
    if (!cancelBtn) {
        cancelBtn = document.createElement('button');
        cancelBtn.type = 'button';
        cancelBtn.id = 'cancelEditBtn';
        cancelBtn.textContent = 'Cancel';
        form.appendChild(cancelBtn);
        cancelBtn.onclick = resetForm;
    } else {
        cancelBtn.style.display = '';
    }
}

function resetForm() {
    editPriestId = null;
    const form = document.getElementById('addForm');
    document.getElementById('formTitle').innerText = 'Parish Priest Information';
    form.reset();
    document.getElementById('submitBtn').textContent = "Save Parish Priest";
    let cancelBtn = document.getElementById('cancelEditBtn');
    if (cancelBtn) cancelBtn.style.display = 'none';
    document.getElementById('editImagePreview').style.display = 'none';
    document.getElementById('deleteImageIcon').style.display = 'none';
    document.getElementById('deleteImageFlag').value = "0";
    document.getElementById('imagePreview').innerHTML = '';
    document.getElementById('formError').style.display = 'none';
    document.getElementById('formSuccess').style.display = 'none';
    fetchCurrentPriest();
}

document.getElementById('addForm').onsubmit = function(e) {
    e.preventDefault();
    const form = e.target;
    const data = new FormData(form);
    
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Saving...';
    submitBtn.disabled = true;
    
    const errorDiv = document.getElementById('formError');
    const successDiv = document.getElementById('formSuccess');
    errorDiv.style.display = 'none';
    successDiv.style.display = 'none';

    if (editPriestId) {
        data.set('_method', 'PUT');
        data.set('id', editPriestId);
        data.set('is_current', '1'); // Always set as current when updating
        
        if (form.image.files[0]) {
            data.set('image', form.image.files[0]);
        }
        
        fetch('api/priestapi.php', { method: 'POST', body: data })
            .then(res => res.json())
            .then(result => {
                if (result.success !== false) {
                    successDiv.textContent = 'Parish priest updated successfully!';
                    successDiv.style.display = 'block';
                    setTimeout(() => {
                        resetForm();
                        fetchCurrentPriest();
                    }, 1000);
                } else {
                    errorDiv.textContent = result.error || 'Failed to update parish priest.';
                    errorDiv.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error updating priest:', error);
                errorDiv.textContent = 'Failed to update parish priest. Please try again.';
                errorDiv.style.display = 'block';
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
    } else {
        data.set('is_current', '1'); // Always set as current
        fetch('api/priestapi.php', { method: 'POST', body: data })
            .then(res => res.json())
            .then(result => {
                if (result.success !== false) {
                    successDiv.textContent = 'Parish priest added successfully!';
                    successDiv.style.display = 'block';
                    setTimeout(() => {
                        form.reset();
                        document.getElementById('imagePreview').innerHTML = '';
                        fetchCurrentPriest();
                        successDiv.style.display = 'none';
                    }, 1000);
                } else {
                    errorDiv.textContent = result.error || 'Failed to add parish priest.';
                    errorDiv.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error adding priest:', error);
                errorDiv.textContent = 'Failed to add parish priest. Please try again.';
                errorDiv.style.display = 'block';
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
    }
};

// Image preview functionality
const imageInput = document.getElementById('addForm').querySelector('input[type="file"]');
const imagePreview = document.getElementById('imagePreview');

imageInput.addEventListener('change', function() {
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.innerHTML = `<img src="${e.target.result}" alt="Preview" />`;
        };
        reader.readAsDataURL(this.files[0]);
    } else {
        imagePreview.innerHTML = '';
    }
});

document.getElementById('deleteImageIcon').onclick = function() {
    document.getElementById('editImagePreview').style.display = 'none';
    this.style.display = 'none';
    document.getElementById('deleteImageFlag').value = "1";
};

fetchCurrentPriest();
</script>
</body>
</html>

