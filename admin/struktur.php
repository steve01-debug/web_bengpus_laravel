<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../entering.php');
    exit;
}
require_once '../config/db.php';
$conn = getDB();

$uploadDir = '../assets/images/struktur/';
if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }

$msg = ''; $msgType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES['gambar']['name']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','gif','webp']) && $_FILES['gambar']['size'] < 8*1024*1024) {
            $fname = 'struktur_organisasi_' . time() . '.' . $ext;
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $uploadDir . $fname)) {
                // Get old image path
                $old = '';
                $res = $conn->query("SELECT gambar FROM struktur_organisasi_image_db WHERE id = 1");
                if ($res && $row = $res->fetch_assoc()) {
                    $old = $row['gambar'];
                }
                
                // Delete old file from disk
                if ($old && file_exists('../' . $old) && strpos($old, 'assets/images/struktur/') === 0) {
                    @unlink('../' . $old);
                }
                
                // Update path in database
                $newPath = 'assets/images/struktur/' . $fname;
                $stmt = $conn->prepare("UPDATE struktur_organisasi_image_db SET gambar = ? WHERE id = 1");
                $stmt->bind_param("s", $newPath);
                $ok = $stmt->execute();
                $stmt->close();
                
                if ($ok) {
                    $msg = 'Gambar struktur organisasi berhasil diperbarui!';
                    $msgType = 'success';
                } else {
                    $msg = 'Gagal menyimpan data gambar ke database.';
                    $msgType = 'error';
                }
            } else {
                $msg = 'Gagal mengunggah file gambar ke server.';
                $msgType = 'error';
            }
        } else {
            $msg = 'File tidak valid. Gunakan JPG/JPEG/PNG/GIF/WEBP, maks 8MB.';
            $msgType = 'error';
        }
    } else {
        $msg = 'Silakan pilih file gambar terlebih dahulu.';
        $msgType = 'error';
    }
}

// Fetch current image
$currentImage = 'assets/images/hero-bg.png'; // default fallback
$res = $conn->query("SELECT gambar FROM struktur_organisasi_image_db WHERE id = 1");
if ($res && $row = $res->fetch_assoc()) {
    if (!empty($row['gambar'])) {
        $currentImage = $row['gambar'];
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Struktur Organisasi – Admin BENGPUSKOMLEKAD</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
    :root {
      --gold:#c9a84c; --gold-dark:#8b7028; --gold-light:#e8d48b;
      --navy-darkest:#060d1a; --navy-dark:#0a1628; --navy:#1b3a5c;
      --white:#ffffff; --gray-100:#e4e7ec; --gray-200:#c8cdd6;
      --gray-300:#9aa3b0; --gray-400:#6b7685;
    }
    body { font-family:'Inter',sans-serif; background:linear-gradient(135deg,#273343ff 0%,#060d1a 100%); color:var(--white); min-height:100vh; }
    body::before { content:''; position:fixed; inset:0;
      background-image:linear-gradient(rgba(201,168,76,0.03) 1px,transparent 1px),linear-gradient(90deg,rgba(201,168,76,0.03) 1px,transparent 1px);
      background-size:60px 60px; animation:gridMove 20s linear infinite; pointer-events:none; }
    @keyframes gridMove { 0%{background-position:0 0} 100%{background-position:60px 60px} }

    /* Sidebar */
    .sidebar { position:fixed; top:0; left:0; width:260px; height:100vh; border-right:1px solid rgba(201,168,76,0.15); display:flex; flex-direction:column; z-index:100; padding:28px 0; }
    .sidebar-logo { padding:0 24px 28px; border-bottom:1px solid rgba(201,168,76,0.1); }
    .sidebar-logo .logo-img-wrap { width:80px; height:80px; display:flex; align-items:center; justify-content:center; margin-bottom:12px; overflow:hidden; }
    .sidebar-logo .logo-img-wrap img { width:100%; height:100%; object-fit:contain; }
    .sidebar-logo h2 { font-family:'Oswald',sans-serif; font-size:0.95rem; font-weight:700; text-transform:uppercase; letter-spacing:2px; color:var(--white); }
    .sidebar-logo h2 span { color:var(--gold); }
    .sidebar-nav { padding:20px 0; flex:1; overflow-y:auto; }
    .sidebar-nav a { display:flex; align-items:center; gap:10px; padding:12px 24px; font-size:0.85rem; color:var(--gray-300); text-decoration:none; transition:all 0.2s; border-left:3px solid transparent; }
    .sidebar-nav a:hover, .sidebar-nav a.active { color:var(--gold); background:rgba(201,168,76,0.05); border-left-color:var(--gold); }
    .sidebar-nav a svg { width:16px; height:16px; flex-shrink:0; }
    .sidebar-footer { padding:16px 24px; border-top:1px solid rgba(201,168,76,0.1); }
    .logout-btn { display:flex; justify-content:center; align-items:center; gap:10px; width:100%; padding:15px; background:linear-gradient(to right,rgba(201,168,76,0) 0%,rgba(201,168,76,1) 50%,rgba(201,168,76,0) 100%); color:var(--navy-darkest); font-family:'Oswald',sans-serif; font-size:0.9rem; font-weight:700; text-transform:uppercase; letter-spacing:3px; border:none; cursor:pointer; transition:all 0.4s; text-decoration:none; }
    .logout-btn:hover { background:linear-gradient(to right,rgba(255,215,0,0) 0%,rgba(255,215,0,1) 50%,rgba(255,215,0,0) 100%); transform:translateY(-2px); }

    /* Main */
    .main-content { margin-left:260px; min-height:100vh; }
    .topbar { background:rgba(10,22,40,0.8); backdrop-filter:blur(20px); border-bottom:1px solid rgba(201,168,76,0.1); padding:20px 36px; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; z-index:50; }
    .topbar h1 { font-family:'Oswald',sans-serif; font-size:1.4rem; font-weight:700; text-transform:uppercase; letter-spacing:3px; }
    .topbar h1 span { color:var(--gold); }
    .content-area { padding:32px 36px; }

    /* Alert messages */
    .alert { display:flex; align-items:center; gap:12px; padding:14px 20px; border-radius:10px; margin-bottom:24px; font-size:0.9rem; }
    .alert-success { background:rgba(40,167,69,0.1); border:1px solid rgba(40,167,69,0.3); color:#28a745; }
    .alert-error { background:rgba(220,53,69,0.1); border:1px solid rgba(220,53,69,0.3); color:#dc3545; }

    /* Form card */
    .form-card { background:rgba(10,22,40,0.6); border:1px solid rgba(201,168,76,0.15); border-radius:12px; padding:28px; margin-bottom:32px; }
    .form-card h3 { font-family:'Oswald',sans-serif; font-size:1.1rem; text-transform:uppercase; letter-spacing:2px; color:var(--gold); margin-bottom:24px; padding-bottom:12px; border-bottom:1px solid rgba(201,168,76,0.1); }
    .form-group { margin-bottom:20px; }
    .form-group label { display:block; font-size:0.78rem; text-transform:uppercase; letter-spacing:1.5px; color:var(--gray-300); margin-bottom:10px; }
    .form-group input[type="file"] { width:100%; padding:11px 14px; background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.12); border-radius:8px; color:var(--white); font-size:0.9rem; font-family:'Inter',sans-serif; transition:all 0.2s; cursor:pointer; }
    .form-group input[type="file"]:focus { border-color:var(--gold); background:rgba(201,168,76,0.04); outline:none; }
    
    .current-preview-container { margin-top:20px; border: 1px solid rgba(201,168,76,0.15); border-radius:10px; background:rgba(6, 13, 26, 0.4); padding:20px; text-align:center; overflow:hidden; }
    .current-preview-title { font-family:'Oswald',sans-serif; font-size:0.85rem; text-transform:uppercase; letter-spacing:1.5px; color:var(--gold-light); margin-bottom:12px; }
    .img-preview { max-width:100%; max-height:500px; object-fit:contain; border-radius:6px; border:1px solid rgba(255,255,255,0.1); transition: transform 0.3s; }
    .img-preview:hover { transform: scale(1.02); }

    .form-actions { display:flex; gap:12px; margin-top:8px; }
    .btn-submit { display:inline-flex; align-items:center; gap:8px; padding:12px 28px; background:linear-gradient(135deg,var(--gold-dark),var(--gold)); color:var(--navy-darkest); font-family:'Oswald',sans-serif; font-size:0.9rem; font-weight:700; text-transform:uppercase; letter-spacing:2px; border:none; border-radius:8px; cursor:pointer; transition:all 0.2s; }
    .btn-submit:hover { transform:translateY(-2px); box-shadow:0 4px 16px rgba(201,168,76,0.3); }

    /* Confirmation Modal */
    .confirm-overlay { display:none; position:fixed; inset:0; background:rgba(6,13,26,0.85); backdrop-filter:blur(8px); z-index:2000; align-items:center; justify-content:center; }
    .confirm-overlay.show { display:flex; }
    .confirm-box { background:rgba(10,22,40,0.98); border:1px solid rgba(201,168,76,0.3); border-radius:16px; padding:40px 36px; max-width:440px; width:90%; text-align:center; box-shadow:0 20px 60px rgba(0,0,0,0.5); animation:scaleIn 0.2s ease; }
    @keyframes scaleIn { from{transform:scale(0.9);opacity:0} to{transform:scale(1);opacity:1} }
    .confirm-icon { font-size:2.5rem; margin-bottom:16px; }
    .confirm-box h3 { font-family:'Oswald',sans-serif; font-size:1.3rem; font-weight:700; text-transform:uppercase; letter-spacing:3px; color:var(--white); margin-bottom:12px; }
    .confirm-box p { color:var(--gray-300); font-size:0.95rem; line-height:1.6; margin-bottom:28px; }
    .confirm-btns { display:flex; gap:12px; justify-content:center; }
    .btn-no { padding:12px 28px; background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.15); border-radius:8px; color:var(--gray-200); font-family:'Oswald',sans-serif; font-size:0.9rem; font-weight:600; text-transform:uppercase; letter-spacing:2px; cursor:pointer; transition:all 0.2s; }
    .btn-no:hover { background:rgba(255,255,255,0.12); }
    .btn-yes { padding:12px 28px; background:linear-gradient(135deg,var(--gold-dark),var(--gold)); border:none; border-radius:8px; color:var(--navy-darkest); font-family:'Oswald',sans-serif; font-size:0.9rem; font-weight:700; text-transform:uppercase; letter-spacing:2px; cursor:pointer; transition:all 0.2s; }
    .btn-yes:hover { transform:translateY(-2px); box-shadow:0 4px 16px rgba(201,168,76,0.3); }

    .admin-hamburger { display:none; position:fixed; top:15px; right:20px; z-index:1002; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2); color:#fff; padding:8px 12px; border-radius:5px; font-size:1.5rem; cursor:pointer; }
    .admin-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:99; }
    .admin-overlay.active { display:block; }

    @media (max-width:900px) {
      .admin-hamburger { display:block; }
      .sidebar { transform:translateX(-100%); transition:transform 0.3s; background:#060d1a; }
      .sidebar.active { transform:translateX(0); }
      .main-content { margin-left:0; width:100%; }
      .topbar { padding:15px 20px; }
      .content-area { padding:16px; }
    }
  </style>
</head>
<body>

<button class="admin-hamburger" onclick="toggleSidebar()">☰</button>
<div class="admin-overlay" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-img-wrap">
      <img src="../assets/images/logo-bengpus.png" alt="Logo">
    </div>
    <h2>BENGPUS<span> PUSKOMLEKAD</span></h2>
  </div>
  <nav class="sidebar-nav">
    <a href="dashboard.php">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
      Dashboard
    </a>
    <a href="berita.php">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
      Kelola Berita
    </a>
    <a href="video.php">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"></rect><line x1="7" y1="2" x2="7" y2="22"></line><line x1="17" y1="2" x2="17" y2="22"></line><line x1="2" y1="12" x2="22" y2="12"></line><line x1="2" y1="7" x2="7" y2="7"></line><line x1="2" y1="17" x2="7" y2="17"></line><line x1="17" y1="17" x2="22" y2="17"></line><line x1="17" y1="7" x2="22" y2="7"></line></svg>
      Kelola Video Terkait
    </a>
    <a href="pimpinan.php">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      Kelola Pimpinan
    </a>
    <a href="struktur.php" class="active">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="8" y="2" width="8" height="8" rx="1" ry="1"></rect><path d="M12 10v3"></path><path d="M3 13h18"></path><path d="M3 13v3"></path><path d="M21 13v3"></path><rect x="1" y="16" width="6" height="6" rx="1" ry="1"></rect><rect x="9" y="16" width="6" height="6" rx="1" ry="1"></rect><rect x="17" y="16" width="6" height="6" rx="1" ry="1"></rect></svg>
      Kelola Struktur
    </a>
    <a href="../index.php" target="_blank">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
      Lihat Website
    </a>
  </nav>
  <div class="sidebar-footer">
    <a href="logout.php" class="logout-btn">Logout</a>
  </div>
</aside>

<!-- Main Content -->
<main class="main-content">
  <div class="topbar">
    <h1>Kelola <span>Struktur Organisasi</span></h1>
  </div>
  <div class="content-area">

    <?php if ($msg): ?>
    <div class="alert alert-<?= $msgType ?>">
      <?= $msgType === 'success' ? '✓' : '✕' ?> <?= htmlspecialchars($msg) ?>
    </div>
    <?php endif; ?>

    <!-- Form Upload Gambar Struktur -->
    <div class="form-card">
      <h3>Perbarui Gambar Struktur Organisasi</h3>
      <form method="POST" enctype="multipart/form-data" id="formStruktur">
        <div class="form-group">
          <label>Pilih File Gambar Bagan Baru (Format: JPG, JPEG, PNG, GIF, WEBP. Maks 8MB)</label>
          <input type="file" name="gambar" accept="image/*" required onchange="previewImg(this)">
        </div>

        <div class="form-actions">
          <button type="button" class="btn-submit" onclick="showConfirm()">
            Unggah & Simpan
          </button>
        </div>
      </form>
    </div>

    <!-- Preview Gambar Struktur Saat Ini -->
    <div class="current-preview-container">
      <div class="current-preview-title">Gambar Struktur Organisasi Saat Ini</div>
      <img src="../<?= htmlspecialchars($currentImage) ?>" class="img-preview" id="imgPreview" alt="Bagan Struktur Organisasi">
    </div>

  </div>
</main>

<!-- Confirmation Modal -->
<div class="confirm-overlay" id="confirmOverlay">
  <div class="confirm-box">
    <div class="confirm-icon">💾</div>
    <h3>Konfirmasi Perubahan</h3>
    <p>Apakah Anda yakin ingin memperbarui gambar bagan struktur organisasi dengan file yang dipilih?</p>
    <div class="confirm-btns">
      <button class="btn-no" onclick="closeConfirm()">Batal</button>
      <button class="btn-yes" onclick="submitForm()">Ya, Perbarui</button>
    </div>
  </div>
</div>

<script>
function toggleSidebar() {
  document.querySelector('.sidebar').classList.toggle('active');
  document.querySelector('.admin-overlay').classList.toggle('active');
}

function previewImg(input) {
  const prev = document.getElementById('imgPreview');
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => { 
      prev.src = e.target.result; 
    };
    reader.readAsDataURL(input.files[0]);
  }
}

function showConfirm() {
  const fileInput = document.querySelector('input[name="gambar"]');
  if (!fileInput.value) {
    alert('Silakan pilih file gambar terlebih dahulu.');
    return;
  }
  document.getElementById('confirmOverlay').classList.add('show');
}

function closeConfirm() {
  document.getElementById('confirmOverlay').classList.remove('show');
}

function submitForm() {
  document.getElementById('formStruktur').submit();
}

document.getElementById('confirmOverlay').addEventListener('click', function(e) {
  if (e.target === this) closeConfirm();
});
</script>
</body>
</html>
