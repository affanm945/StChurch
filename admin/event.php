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
    <title>Admin - Manage Events</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
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
h1 {
    text-align: center;
    color: #1a237e;
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 2rem;
    letter-spacing: 1px;
}
.form-section h2 {
    color: #283593;
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 1.2rem;
    letter-spacing: 0.5px;
}
.event-form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 1.2rem 2rem;
}
label {
    font-weight: 500;
    color: #333;
    margin-bottom: 0.3rem;
    display: block;
}
input, textarea, select {
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
input:focus, textarea:focus, select:focus {
    border: 1.5px solid #3949ab;
    outline: none;
    background: #fff;
}
textarea {
    min-height: 70px;
    resize: vertical;
}
button {
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
button:hover {
    background: #1a237e;
}
#cancelEdit {
    background: #b0bec5;
    color: #263238;
}
#cancelEdit:hover {
    background: #78909c;
}
.img-thumb {
    width: 60px;
    height: 40px;
    object-fit: cover;
    border-radius: 4px;
    border: 1px solid #e3e3e3;
}
.edit-row {
    background: #eaf6ff;
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
#mainImagePreview img, #galleryImagesPreview img {
    border: 1.5px solid #c5cae9;
}
#galleryImagesPreview > div {
    position: relative;
}
#galleryImagesPreview button {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #d32f2f;
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 22px;
    height: 22px;
    font-size: 1.1rem;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(0,0,0,0.10);
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 2.5rem;
    background: #f8fafc;
    border-radius: 10px;
    overflow: hidden;
}
th, td {
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
}
@media (max-width: 700px) {
    .header {
        padding: 1rem 1.5rem;
        flex-direction: column;
        gap: 1rem;
    }

    .header {
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
        padding: 1rem 0.3rem;
        margin: 20px auto;
    }
    .event-form-grid {
        grid-template-columns: 1fr;
    }
    table, th, td {
        font-size: 0.95rem;
    }
    .event-form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1.2rem 2rem;
    }
}
</style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <div class="header-title">
                <h1>Manage Events</h1>
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
        <h2 id="formTitle">Add New Event</h2>
        <form id="eventForm" enctype="multipart/form-data">
            <input type="hidden" name="id" id="eventId" />
            <div class="event-form-grid">
                <label>Title
                    <input type="text" name="title" id="title" required />
                </label>
                <label>Date
                    <input type="date" name="date" id="date" required />
                </label>
                <label>Start Time
                    <input type="time" name="start_time" id="start_time" />
                </label>
                <label>End Time
                    <input type="time" name="end_time" id="end_time" />
                </label>
                <label>Location
                    <input type="text" name="location" id="location" required />
                </label>
                <label>Instructor
                    <input type="text" name="instructor" id="instructor" required />
                </label>
            </div>
            <label>Description
                <textarea name="description" id="description" required></textarea>
            </label>
            <label style="margin-top:1.5rem;">Main Image
                <input type="file" name="image" id="mainImageInput" accept="image/*" />
                <div id="mainImageError" style="color:red;font-size:0.9em;"></div>
                <div id="mainImagePreview"></div>
            </label>
            <label style="margin-top:1.5rem;">Event Gallery (max 10 images)
                <input type="file" id="galleryImagesInput" accept="image/*" multiple />
                <div id="galleryImagesError" style="color:red;font-size:0.9em;"></div>
                <div id="galleryImagesPreview" style="display:flex;flex-wrap:wrap;gap:10px;margin-top:10px;"></div>
            </label>
            <button type="submit" id="submitBtn">Add Event</button>
            <button type="button" id="cancelEdit" style="display:none; background:#aaa;">Cancel</button>
        </form>
        <div class="error" id="formError"></div>
        <div class="success" id="formSuccess"></div>
    </div>
    <table id="eventsTable">
        <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Date</th>
                <th>Time</th>
                <th>Location</th>
                <th>Instructor</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<script>
let editingId = null;
let deletedGalleryImages = [];

function fetchEvents() {
    fetch('api/eventapi.php')
        .then(res => {
            console.log('API Response status:', res.status);
            return res.json();
        })
        .then(events => {
            console.log('Events data:', events);
            const tbody = document.querySelector('#eventsTable tbody');
            tbody.innerHTML = '';
            
            if (!events || !Array.isArray(events)) {
                console.log('No events data or not an array');
                return;
            }
            
            events.forEach(ev => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${ev.image ? `<img src="../image/${ev.image}" class="img-thumb" />` : ''}</td>
                    <td>${ev.title || ''}</td>
                    <td>${ev.date || ''}</td>
                    <td>${ev.start_time ? ev.start_time.substring(0,5) : ''} - ${ev.end_time ? ev.end_time.substring(0,5) : ''}</td>
                    <td>${ev.location || ''}</td>
                    <td>${ev.instructor || ''}</td>
                    <td class="actions">
                        <button onclick="editEvent(${ev.id})">Edit</button>
                        <button onclick="deleteEvent(${ev.id})" style="background:#d9534f;">Delete</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(error => {
            console.error('Error fetching events:', error);
        });
}

function editEvent(id) {
    fetch('api/eventapi.php?id=' + id)
        .then(res => {
            if (!res.ok) {
                throw new Error('Failed to fetch event');
            }
            return res.json();
        })
        .then(ev => {
            if (!ev || !ev.id) {
                alert('Event not found');
                return;
            }
            editingId = id;
            document.getElementById('formTitle').innerText = 'Edit Event';
            document.getElementById('submitBtn').innerText = 'Update Event';
            document.getElementById('cancelEdit').style.display = '';
            document.getElementById('eventId').value = ev.id;
            document.getElementById('title').value = ev.title || '';
            document.getElementById('date').value = ev.date || '';
            document.getElementById('start_time').value = ev.start_time || '';
            document.getElementById('end_time').value = ev.end_time || '';
            document.getElementById('location').value = ev.location || '';
            document.getElementById('instructor').value = ev.instructor || '';
            document.getElementById('description').value = ev.description || '';
            loadEventForEdit(ev);
            // Scroll to form
            document.querySelector('.form-section').scrollIntoView({ behavior: 'smooth', block: 'start' });
        })
        .catch(error => {
            console.error('Error loading event:', error);
            alert('Failed to load event. Please try again.');
        });
}

// Make editEvent globally accessible
window.editEvent = editEvent;

function deleteEvent(id) {
    if (!confirm('Are you sure you want to delete this event?')) return;
    fetch('api/eventapi.php?id=' + id, { method: 'DELETE' })
        .then(res => res.json())
        .then(resp => {
            fetchEvents();
        })
        .catch(error => {
            console.error('Error deleting event:', error);
            alert('Failed to delete event. Please try again.');
        });
}

// Make deleteEvent globally accessible
window.deleteEvent = deleteEvent;

const mainImageInput = document.getElementById('mainImageInput');
const mainImagePreview = document.getElementById('mainImagePreview');
const mainImageError = document.getElementById('mainImageError');
let mainImageFile = null;
let mainImageUrl = null;
let mainImageDeleted = false;

mainImageInput.addEventListener('change', function() {
    mainImageError.textContent = '';
    if (this.files && this.files[0]) {
        const file = this.files[0];
        mainImageFile = file;
        mainImageDeleted = false; // Reset deletion flag if a new image is selected
        showMainImagePreview(URL.createObjectURL(mainImageFile));
    }
});

function showMainImagePreview(src) {
    mainImagePreview.innerHTML = `
        <div style="display:flex;align-items:center;gap:10px;">
            <img src="${src}" style="width:100px;height:70px;object-fit:cover;border-radius:4px;" />
            <button type="button" onclick="removeMainImage()" style="background:#d9534f;color:#fff;border:none;border-radius:4px;padding:5px 10px;cursor:pointer;font-size:12px;">Delete</button>
        </div>
    `;
}

window.removeMainImage = function() {
    mainImageFile = null;
    mainImageInput.value = '';
    mainImagePreview.innerHTML = '';
    mainImageUrl = null;
    mainImageDeleted = true; // Mark for deletion on update
}

// --- Gallery Images Preview, Add, Delete ---
const galleryImagesInput = document.getElementById('galleryImagesInput');
const galleryImagesPreview = document.getElementById('galleryImagesPreview');
const galleryImagesError = document.getElementById('galleryImagesError');
let galleryImages = []; 

galleryImagesInput.addEventListener('change', function() {
    galleryImagesError.textContent = '';
    const files = Array.from(this.files);
    // Only allow up to 10 images
    if (galleryImages.length + files.length > 10) {
        galleryImagesError.textContent = 'You can only upload up to 10 gallery images.';
        this.value = '';
        return;
    }
    files.forEach(file => {
        galleryImages.push({ file, url: URL.createObjectURL(file) });
    });
    renderGalleryImages();
    this.value = '';
});

function renderGalleryImages() {
    galleryImagesPreview.innerHTML = '';
    galleryImages.forEach((img, idx) => {
        galleryImagesPreview.innerHTML += `
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;">
                <img src="${img.url}" style="width:80px;height:60px;object-fit:cover;border-radius:4px;" />
                <button type="button" onclick="removeGalleryImage(${idx})" style="background:#d9534f;color:#fff;border:none;border-radius:4px;width:24px;height:24px;cursor:pointer;font-size:14px;display:flex;align-items:center;justify-content:center;">&times;</button>
            </div>
        `;
    });

    if (galleryImages.length < 10) {
        galleryImagesInput.style.display = '';
    } else {
        galleryImagesInput.style.display = 'none';
    }
}
window.removeGalleryImage = function(idx) {
    // If existing image, mark for deletion (handle in backend)
    if (galleryImages[idx].id) {
        deletedGalleryImages.push(galleryImages[idx].id);
    }
    galleryImages.splice(idx, 1);
    renderGalleryImages();
}

function loadEventForEdit(ev) {
    mainImageUrl = ev.image ? `../image/${ev.image}` : null;
    if (mainImageUrl) {
        showMainImagePreview(mainImageUrl);
        mainImageInput.style.display = 'none';
    } else {
        mainImagePreview.innerHTML = '';
        mainImageInput.style.display = '';
    }

    galleryImages = [];
    deletedGalleryImages = []; // Reset deleted images tracker on edit
    if (ev.gallery && Array.isArray(ev.gallery)) {
        ev.gallery.forEach(imgName => {
            galleryImages.push({ url: `../image/${imgName}`, id: imgName });
        });
    }
    renderGalleryImages();
}

// --- On Form Submit: Attach Images to FormData ---
document.getElementById('eventForm').onsubmit = function(e) {
    e.preventDefault();
    const form = e.target;

    // --- Ensure browser validation is triggered ---
    if (!form.reportValidity()) {
        return; // Stop if invalid
    }

    const formData = new FormData(form);
    document.getElementById('formError').innerText = '';
    document.getElementById('formSuccess').innerText = '';

    // Main image
    if (mainImageFile) {
        formData.append('image', mainImageFile);
    }
    if (mainImageDeleted) {
        formData.append('delete_main_image', '1');
    }
    // If no new image selected and not marked for deletion, keep existing image
    if (!mainImageFile && !mainImageDeleted && mainImageUrl) {
        formData.append('keep_main_image', '1');
    }

    // Gallery images
    galleryImages.forEach(img => {
        if (img.file) {
            formData.append('gallery_images[]', img.file);
        }
    });

    if (editingId) {
        formData.append('delete_gallery', JSON.stringify(deletedGalleryImages));
        // Use POST with _method=PUT for compatibility with FormData
        formData.append('_method', 'PUT');
        formData.append('id', editingId);

        fetch('api/eventapi.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(resp => {
            if (resp.success) {
                fetchEvents();
                document.getElementById('formSuccess').innerText = 'Event updated!';
                // --- FULL RESET after update ---
                resetEventForm();
            } else {
                if (resp.error) {
                    mainImageError.textContent = resp.error;
                    galleryImagesError.textContent = resp.error;
                } else {
                    document.getElementById('formError').innerText = 'Update failed.';
                }
            }
        });
    } else {
        // Add event (POST)
        fetch('api/eventapi.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(resp => {
            if (resp.success) {
                fetchEvents();
                document.getElementById('formSuccess').innerText = 'Event added!';
                // --- FULL RESET after add ---
                resetEventForm();
            } else {
                if (resp.error) {
                    mainImageError.textContent = resp.error;
                    galleryImagesError.textContent = resp.error;
                } else {
                    document.getElementById('formError').innerText = 'Add failed.';
                }
            }
        });
    }
};

// --- Helper function to fully reset the form and all state ---
function resetEventForm() {
    editingId = null;
    document.getElementById('formTitle').innerText = 'Add New Event';
    document.getElementById('submitBtn').innerText = 'Add Event';
    document.getElementById('cancelEdit').style.display = 'none';
    document.getElementById('eventForm').reset();
    document.getElementById('eventId').value = '';
    document.getElementById('mainImagePreview').innerHTML = '';
    document.getElementById('galleryImagesPreview').innerHTML = '';
    document.getElementById('mainImageInput').value = '';
    document.getElementById('galleryImagesInput').value = '';
    mainImageFile = null;
    mainImageUrl = null;
    galleryImages = [];
    deletedGalleryImages = [];
    mainImageDeleted = false;
    // Also clear any error/success messages
    document.getElementById('formError').innerText = '';
    document.getElementById('formSuccess').innerText = '';
    mainImageError.textContent = '';
    galleryImagesError.textContent = '';
}

document.getElementById('cancelEdit').onclick = function() {
    // --- Use helper to fully clear everything ---
    resetEventForm();
};

fetchEvents();
</script>
</body>
</html>