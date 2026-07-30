<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../entering.php');
    exit;
}
require_once '../config/db.php';
$conn = getDB();

// Buat folder upload jika belum ada
$uploadDir = '../assets/images/berita/';
if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }

$msg = ''; $msgType = 'success'; $editData = null; $showForm = false;

// ─── Handle POST ────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $act = $_POST['act'] ?? '';

    if ($act === 'add' || $act === 'update') {
        $judul    = trim($_POST['judul'] ?? '');
        $kategori = trim($_POST['kategori'] ?? 'Umum');
        $tanggal  = $_POST['tanggal'] ?? date('Y-m-d');
        $isi      = trim($_POST['isi'] ?? '');
        $gambar   = $_POST['gambar_old'] ?? '';

        if (!empty($_FILES['gambar']['name']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','gif','webp']) && $_FILES['gambar']['size'] < 5*1024*1024) {
                $fname = 'berita_' . time() . '_' . rand(1000,9999) . '.' . $ext;
                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $uploadDir . $fname)) {
                    $old = $_POST['gambar_old'] ?? '';
                    if ($old && strpos($old, 'assets/images/berita/') === 0) @unlink('../' . $old);
                    $gambar = 'assets/images/berita/' . $fname;
                }
            } else { $msg = 'File tidak valid. Gunakan JPG/PNG/GIF/WEBP, maks 5MB.'; $msgType = 'error'; }
        }

        if (!$msg && $judul) {
            if ($act === 'add') {
                $stmt = $conn->prepare("INSERT INTO berita_db (judul, kategori, tanggal, gambar, isi) VALUES (?,?,?,?,?)");
                $stmt->bind_param("sssss", $judul, $kategori, $tanggal, $gambar, $isi);
                $ok = $stmt->execute(); $stmt->close();
                $msg = $ok ? 'Berita berhasil ditambahkan!' : 'Gagal menambahkan berita.';
                $msgType = $ok ? 'success' : 'error';
            } else {
                $id = (int)($_POST['id'] ?? 0);
                $stmt = $conn->prepare("UPDATE berita_db SET judul=?,kategori=?,tanggal=?,gambar=?,isi=? WHERE id=?");
                $stmt->bind_param("sssssi", $judul, $kategori, $tanggal, $gambar, $isi, $id);
                $ok = $stmt->execute(); $stmt->close();
                $msg = $ok ? 'Berita berhasil diupdate!' : 'Gagal mengupdate berita.';
                $msgType = $ok ? 'success' : 'error';
            }
            if ($ok ?? false) { header("Location: berita.php?msg=" . urlencode($msg) . "&type=$msgType"); exit; }
        }
    }

    elseif ($act === 'delete') {
        $id = (int)($_POST['del_id'] ?? 0);
        if ($id) {
            $res = $conn->query("SELECT gambar FROM berita_db WHERE id=$id");
            if ($res && $row = $res->fetch_assoc()) {
                if ($row['gambar'] && strpos($row['gambar'], 'assets/images/berita/') === 0) @unlink('../' . $row['gambar']);
            }
            $conn->query("DELETE FROM berita_db WHERE id=$id");
            header("Location: berita.php?msg=" . urlencode('Berita berhasil dihapus.') . "&type=success"); exit;
        }
    }
}

// Ambil msg dari redirect
if (isset($_GET['msg'])) { $msg = $_GET['msg']; $msgType = $_GET['type'] ?? 'success'; }

// Edit mode
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $res = $conn->query("SELECT * FROM berita WHERE id=$id");
    if ($res && $res->num_rows > 0) { $editData = $res->fetch_assoc(); $showForm = true; }
}
if (isset($_GET['add'])) { $showForm = true; }

// Ambil semua berita
$beritaList = [];
$res = $conn->query("SELECT * FROM berita_db ORDER BY tanggal DESC, id DESC");
if ($res) { while ($row = $res->fetch_assoc()) { $beritaList[] = $row; } }
$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Berita – Admin BENGPUSKOMLEKAD</title>
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

    /* Toggle form btn */
    .btn-add { display:inline-flex; align-items:center; gap:8px; padding:12px 24px; background:linear-gradient(135deg,var(--gold-dark),var(--gold)); color:var(--navy-darkest); font-family:'Oswald',sans-serif; font-size:0.9rem; font-weight:700; text-transform:uppercase; letter-spacing:2px; border:none; border-radius:8px; cursor:pointer; text-decoration:none; transition:all 0.2s; }
    .btn-add:hover { transform:translateY(-2px); box-shadow:0 4px 16px rgba(201,168,76,0.3); }

    /* Form card */
    .form-card { background:rgba(10,22,40,0.6); border:1px solid rgba(201,168,76,0.15); border-radius:12px; padding:28px; margin-bottom:32px; }
    .form-card h3 { font-family:'Oswald',sans-serif; font-size:1.1rem; text-transform:uppercase; letter-spacing:2px; color:var(--gold); margin-bottom:24px; padding-bottom:12px; border-bottom:1px solid rgba(201,168,76,0.1); }
    .form-row { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
    .form-row-3 { display:grid; grid-template-columns:2fr 1fr 1fr; gap:16px; }
    .form-group { margin-bottom:16px; }
    .form-group label { display:block; font-size:0.78rem; text-transform:uppercase; letter-spacing:1.5px; color:var(--gray-300); margin-bottom:8px; }
    .form-group input, .form-group textarea, .form-group select { width:100%; padding:11px 14px; background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.12); border-radius:8px; color:var(--white); font-size:0.9rem; font-family:'Inter',sans-serif; transition:all 0.2s; }
    .form-group input:focus, .form-group textarea:focus, .form-group select:focus { border-color:var(--gold); background:rgba(201,168,76,0.04); outline:none; box-shadow:0 0 0 3px rgba(201,168,76,0.1); }
    .form-group textarea { min-height:120px; resize:vertical; }
    .form-group select option { background:var(--navy-dark); }
    .form-group input[type="file"] { cursor:pointer; }
    .img-preview { width:120px; height:90px; object-fit:cover; border-radius:8px; border:1px solid rgba(201,168,76,0.2); margin-top:8px; display:none; }
    .img-preview.show { display:block; }
    .form-actions { display:flex; gap:12px; margin-top:8px; flex-wrap:wrap; }
    .btn-submit { display:inline-flex; align-items:center; gap:8px; padding:12px 28px; background:linear-gradient(135deg,var(--gold-dark),var(--gold)); color:var(--navy-darkest); font-family:'Oswald',sans-serif; font-size:0.9rem; font-weight:700; text-transform:uppercase; letter-spacing:2px; border:none; border-radius:8px; cursor:pointer; transition:all 0.2s; }
    .btn-submit:hover { transform:translateY(-2px); box-shadow:0 4px 16px rgba(201,168,76,0.3); }
    .btn-cancel { display:inline-flex; align-items:center; gap:8px; padding:12px 24px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.15); color:var(--gray-200); font-family:'Oswald',sans-serif; font-size:0.9rem; font-weight:600; text-transform:uppercase; letter-spacing:2px; border-radius:8px; cursor:pointer; text-decoration:none; transition:all 0.2s; }
    .btn-cancel:hover { background:rgba(255,255,255,0.1); }

    /* Table */
    .section-hdr { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; flex-wrap:wrap; gap:12px; }
    .section-hdr h2 { font-family:'Oswald',sans-serif; font-size:1.1rem; text-transform:uppercase; letter-spacing:2px; color:var(--white); }
    .section-hdr h2 span { color:var(--gold); }
    .gold-line { width:50px; height:2px; background:linear-gradient(90deg,var(--gold),transparent); margin-bottom:20px; }
    .table-wrap { background:rgba(10,22,40,0.5); border:1px solid rgba(201,168,76,0.12); border-radius:12px; overflow:hidden; overflow-x:auto; }
    .data-table { width:100%; border-collapse:collapse; min-width:700px; }
    .data-table thead tr { background:rgba(201,168,76,0.08); border-bottom:1px solid rgba(201,168,76,0.15); }
    .data-table thead th { padding:14px 16px; font-family:'Oswald',sans-serif; font-size:0.72rem; text-transform:uppercase; letter-spacing:2px; color:var(--gold); text-align:left; font-weight:600; }
    .data-table tbody tr { border-bottom:1px solid rgba(255,255,255,0.04); transition:background 0.2s; }
    .data-table tbody tr:last-child { border-bottom:none; }
    .data-table tbody tr:hover { background:rgba(201,168,76,0.04); }
    .data-table tbody td { padding:14px 16px; font-size:0.88rem; color:var(--gray-200); vertical-align:middle; }
    .td-thumb { width:64px; height:48px; object-fit:cover; border-radius:6px; border:1px solid rgba(201,168,76,0.15); }
    .td-no-img { width:64px; height:48px; background:rgba(201,168,76,0.05); border:1px dashed rgba(201,168,76,0.2); border-radius:6px; display:flex; align-items:center; justify-content:center; font-size:0.65rem; color:var(--gray-400); text-align:center; }
    .badge-kategori { display:inline-block; padding:3px 10px; background:rgba(201,168,76,0.12); border:1px solid rgba(201,168,76,0.25); border-radius:100px; font-size:0.65rem; font-family:'Oswald',sans-serif; text-transform:uppercase; letter-spacing:1px; color:var(--gold); }
    .btn-edit { display:inline-flex; align-items:center; gap:5px; padding:7px 14px; background:rgba(74,139,194,0.15); border:1px solid rgba(74,139,194,0.3); border-radius:6px; color:#6bb3e0; font-family:'Oswald',sans-serif; font-size:0.72rem; text-transform:uppercase; letter-spacing:1px; text-decoration:none; cursor:pointer; transition:all 0.2s; }
    .btn-edit:hover { background:rgba(74,139,194,0.25); }
    .btn-del { display:inline-flex; align-items:center; gap:5px; padding:7px 14px; background:rgba(220,53,69,0.1); border:1px solid rgba(220,53,69,0.25); border-radius:6px; color:#dc3545; font-family:'Oswald',sans-serif; font-size:0.72rem; text-transform:uppercase; letter-spacing:1px; cursor:pointer; transition:all 0.2s; }
    .btn-del:hover { background:rgba(220,53,69,0.2); }
    .empty-state { text-align:center; padding:48px 20px; color:var(--gray-400); }

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
    .btn-yes-danger { background:linear-gradient(135deg,#b02a37,#dc3545); color:#fff; }
    .btn-yes-danger:hover { box-shadow:0 4px 16px rgba(220,53,69,0.3); }

    /* Hamburger */
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
      .form-row, .form-row-3 { grid-template-columns:1fr; }
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
    <a href="berita.php" class="active">
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
    <a href="struktur.php">
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
    <h1>Kelola <span>Berita</span></h1>
  </div>
  <div class="content-area">

    <?php if ($msg): ?>
    <div class="alert alert-<?= $msgType ?>">
      <?= $msgType === 'success' ? '✓' : '✕' ?> <?= htmlspecialchars($msg) ?>
    </div>
    <?php endif; ?>

    <!-- Tombol Tambah -->
    <?php if (!$showForm): ?>
    <div style="margin-bottom:24px;">
      <a href="berita.php?add=1" class="btn-add">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Berita Baru
      </a>
    </div>
    <?php endif; ?>

    <!-- Form Tambah / Edit -->
    <?php if ($showForm): ?>
    <div class="form-card">
      <h3><?= $editData ? 'Edit Berita' : 'Tambah Berita Baru' ?></h3>
      <form method="POST" enctype="multipart/form-data" id="formBerita">
        <input type="hidden" name="act" value="<?= $editData ? 'update' : 'add' ?>">
        <?php if ($editData): ?>
        <input type="hidden" name="id" value="<?= $editData['id'] ?>">
        <input type="hidden" name="gambar_old" value="<?= htmlspecialchars($editData['gambar']) ?>">
        <?php endif; ?>

        <div class="form-group">
          <label>Judul Berita</label>
          <input type="text" name="judul" required placeholder="Masukkan judul berita..." value="<?= htmlspecialchars($editData['judul'] ?? '') ?>">
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Kategori</label>
            <select name="kategori">
              <?php foreach(['TEKNOLOGI','KEGIATAN'] as $k): ?>
              <option value="<?= $k ?>" <?= ($editData['kategori'] ?? '') === $k ? 'selected' : '' ?>><?= $k ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Tanggal</label>
            <input type="date" name="tanggal" value="<?= $editData['tanggal'] ?? date('Y-m-d') ?>">
          </div>
        </div>

        <div class="form-group">
          <label>Foto<?= $editData ? ' (kosongkan jika tidak diganti)' : '' ?></label>
          <input type="file" name="gambar" accept="image/*" onchange="previewImg(this)">
          <?php if (!empty($editData['gambar'])): ?>
          <img src="../<?= htmlspecialchars($editData['gambar']) ?>" class="img-preview show" id="imgPreview" alt="preview">
          <?php else: ?>
          <img class="img-preview" id="imgPreview" alt="preview">
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label>Isi Berita</label>
          <textarea name="isi" placeholder="Tulis berita..."><?= htmlspecialchars($editData['isi'] ?? '') ?></textarea>
        </div>

        <div class="form-actions">
          <button type="button" class="btn-submit" onclick="showConfirm('<?= $editData ? 'Update Berita' : 'Tambah Berita' ?>', '<?= $editData ? 'Apakah Anda yakin ingin mengupdate berita ini?' : 'Apakah Anda yakin ingin menambahkan berita ini?' ?>', 'formBerita', false)">
            <?= $editData ? 'Update Berita' : 'Simpan Berita' ?>
          </button>
          <a href="berita.php" class="btn-cancel">Batal</a>
        </div>
      </form>
    </div>
    <?php endif; ?>

    <!-- Daftar Berita -->
    <div class="section-hdr">
      <h2>Daftar <span>Berita</span></h2>
      <?php if (!$showForm): ?>
      <span style="font-size:0.82rem;color:var(--gray-400);"><?= count($beritaList) ?> berita tersimpan</span>
      <?php endif; ?>
    </div>
    <div class="gold-line"></div>

    <div class="table-wrap">
      <?php if (empty($beritaList)): ?>
      <div class="empty-state">
        <div style="font-size:2.5rem;margin-bottom:12px;">📰</div>
        <p>Belum ada berita. Klik "Tambah Berita Baru" untuk mulai.</p>
      </div>
      <?php else: ?>
      <table class="data-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Foto</th>
            <th>Judul</th>
            <th>Kategori</th>
            <th>Tanggal</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($beritaList as $i => $b): ?>
          <tr>
            <td style="color:var(--gray-400);font-size:0.78rem;"><?= $i+1 ?></td>
            <td>
              <?php if ($b['gambar']): ?>
              <img src="../<?= htmlspecialchars($b['gambar']) ?>" class="td-thumb" alt="">
              <?php else: ?>
              <div class="td-no-img">No<br>Image</div>
              <?php endif; ?>
            </td>
            <td style="color:var(--white);font-weight:500;max-width:280px;"><?= htmlspecialchars($b['judul']) ?></td>
            <td><span class="badge-kategori"><?= htmlspecialchars($b['kategori']) ?></span></td>
            <td style="white-space:nowrap;"><?= date('d M Y', strtotime($b['tanggal'])) ?></td>
            <td>
              <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <a href="berita.php?edit=<?= $b['id'] ?>" class="btn-edit">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                  Edit
                </a>
                <button class="btn-del" onclick="confirmDelete(<?= $b['id'] ?>, '<?= addslashes(htmlspecialchars($b['judul'])) ?>')">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                  Hapus
                </button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php endif; ?>
    </div>

  </div>
</main>

<!-- Hidden delete form -->
<form id="formDelete" method="POST" style="display:none;">
  <input type="hidden" name="act" value="delete">
  <input type="hidden" name="del_id" id="delId" value="">
</form>

<!-- Confirmation Modal -->
<div class="confirm-overlay" id="confirmOverlay">
  <div class="confirm-box">
    <div class="confirm-icon" id="confirmIcon">⚠️</div>
    <h3 id="confirmTitle">Konfirmasi</h3>
    <p id="confirmMsg">Apakah Anda yakin?</p>
    <div class="confirm-btns">
      <button class="btn-no" onclick="closeConfirm()">Tidak</button>
      <button class="btn-yes" id="btnYes" onclick="doConfirm()">Ya, Lanjutkan</button>
    </div>
  </div>
</div>

<script>
var pendingForm = null;
var pendingCallback = null;

function toggleSidebar() {
  document.querySelector('.sidebar').classList.toggle('active');
  document.querySelector('.admin-overlay').classList.toggle('active');
}

function previewImg(input) {
  const prev = document.getElementById('imgPreview');
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => { prev.src = e.target.result; prev.classList.add('show'); };
    reader.readAsDataURL(input.files[0]);
  }
}

function showConfirm(title, msg, formId, isDanger) {
  document.getElementById('confirmTitle').textContent = title;
  document.getElementById('confirmMsg').textContent = msg;
  document.getElementById('confirmIcon').textContent = isDanger ? '🗑️' : '💾';
  const yesBtn = document.getElementById('btnYes');
  yesBtn.className = 'btn-yes' + (isDanger ? ' btn-yes-danger' : '');
  yesBtn.textContent = isDanger ? 'Ya, Hapus' : 'Ya, Lanjutkan';
  pendingForm = document.getElementById(formId);
  document.getElementById('confirmOverlay').classList.add('show');
}

function confirmDelete(id, judul) {
  document.getElementById('delId').value = id;
  document.getElementById('confirmTitle').textContent = 'Hapus Berita';
  document.getElementById('confirmMsg').textContent = 'Apakah Anda yakin ingin menghapus berita "' + judul + '"? Tindakan ini tidak dapat dibatalkan.';
  document.getElementById('confirmIcon').textContent = '🗑️';
  const yesBtn = document.getElementById('btnYes');
  yesBtn.className = 'btn-yes btn-yes-danger';
  yesBtn.textContent = 'Ya, Hapus';
  pendingForm = document.getElementById('formDelete');
  document.getElementById('confirmOverlay').classList.add('show');
}

function closeConfirm() {
  document.getElementById('confirmOverlay').classList.remove('show');
  pendingForm = null;
}

function doConfirm() {
  if (pendingForm) pendingForm.submit();
  closeConfirm();
}

// Close on backdrop click
document.getElementById('confirmOverlay').addEventListener('click', function(e) {
  if (e.target === this) closeConfirm();
});
</script>
</body>
</html>
