<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../entering.php');
    exit;
}

require_once '../config/db.php';

$conn = getDB();

$totalBerita = 0;
$result = $conn->query("SELECT COUNT(*) as co FROM berita_db");
if ($result) {
    $row = $result->fetch_assoc();
    $totalBerita = $row['co'];
}

$totalVideo = 0;
$result = $conn->query("SELECT COUNT(*) as co FROM video_terkait_db");
if ($result) {
    $row = $result->fetch_assoc();
    $totalVideo = $row['co'];
}

$totalPimpinan = 0;
$result = $conn->query("SELECT COUNT(*) as co FROM pimpinan_db");
if ($result) {
    $row = $result->fetch_assoc();
    $totalPimpinan = $row['co'];
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
    :root {
      --gold: #c9a84c;
      --gold-dark: #8b7028;
      --gold-light: #e8d48b;
      --navy-darkest: #060d1a;
      --navy-dark: #0a1628;
      --navy: #1b3a5c;
      --white: #ffffff;
      --gray-100: #e4e7ec;
      --gray-200: #c8cdd6;
      --gray-300: #9aa3b0;
      --gray-400: #6b7685;
      --gray-500: #4a5568;
    }
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #273343ff 0%, #060d1a 100%);
      color: var(--white);
      min-height: 100vh;
    }

     body::before {
      content: '';
      position: fixed;
      inset: 0;
      background-image:
        linear-gradient(rgba(201,168,76,0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(201,168,76,0.03) 1px, transparent 1px);
      background-size: 60px 60px;
      animation: gridMove 20s linear infinite;
      pointer-events: none;
    }
    @keyframes gridMove {
      0%   { background-position: 0 0; }
      100% { background-position: 60px 60px; }
    }
    
    /* Sidebar */
    .sidebar {
      position: fixed;
      top: 0; left: 0;
      width: 260px;
      height: 100vh;
      background: transparent
      border: none; 
      border-right: 1px solid rgba(201,168,76,0.15); 
      display: flex;
      flex-direction: column;
      z-index: 100;
      padding: 28px 0;

    }
    .sidebar-logo {
      padding: 0 24px 28px;
      border-bottom: 1px solid rgba(201,168,76,0.1);
    }
    .sidebar-logo .logo-img-wrap {
      width: 100px;
      height: 100px;
      border-radius: 10px;
      /*background: rgba(201,168,76,0.1);
      border: 1px solid rgba(201,168,76,0.25);*/
      display: flex; align-items: center; justify-content: center;
      margin-bottom: 12px;
      overflow: hidden;
      padding: 4px;
    }
    .sidebar-logo .logo-img-wrap img { width:100%; height:100%; object-fit:contain; }
    .sidebar-logo h2 {
      font-family: 'Oswald', sans-serif;
      font-size: 1rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 2px;
      color: var(--white);
    }
    .sidebar-logo h2 span { color: var(--gold); }
    .sidebar-logo p {
      font-size: 0.72rem;
      color: var(--gray-400);
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-top: 2px;
    }
    .sidebar-nav {
      padding: 20px 0;
      flex: 1;
    }
    .sidebar-nav a {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 24px;
      font-size: 0.85rem;
      color: var(--gray-300);
      text-decoration: none;
      transition: all 0.2s;
      border-left: 3px solid transparent;
    }
    .sidebar-nav a:hover,
    .sidebar-nav a.active {
      color: var(--gold);
      background: rgba(201,168,76,0.05);
      border-left-color: var(--gold);
    }
    .sidebar-nav a .nav-icon { font-size: 1rem; }
    .sidebar-footer {
      padding: 16px 24px;
      border-top: 1px solid rgba(201,168,76,0.1);
    }
    .logout-btn {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 10px;
      width: 100%;
      padding: 15px;
      background: linear-gradient(to right, rgba(201, 168, 76, 0) 0%, rgba(201, 168, 76, 1) 50%, rgba(201, 168, 76, 0) 100%);
      color: var(--navy-darkest);
      font-family: 'Oswald', sans-serif;
      font-size: 0.95rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 3px;
      border: none;
      border-radius: 0;
      cursor: pointer;
      transition: all 0.4s ease;
      position: relative;
      overflow: hidden;
      text-decoration: none;
    }
    .logout-btn:hover { 
      color: #000000;
      background: linear-gradient(to right, rgba(255, 215, 0, 0) 0%, rgba(255, 215, 0, 1) 50%, rgba(255, 215, 0, 0) 100%);
      transform: translateY(-2px);
      filter: drop-shadow(0 0 12px rgba(201, 168, 76, 0.5));
    }
    /* Main content */
    .main-content {
      margin-left: 260px;
      min-height: 100vh;
      padding: 0;
    }
    .topbar {
      background: rgba(10,22,40,0.8);
      backdrop-filter: blur(20px);
      border-bottom: 1px solid rgba(201,168,76,0.1);
      padding: 20px 36px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 50;
    }
    .topbar h1 {
      font-family: 'Oswald', sans-serif;
      font-size: 1.4rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 3px;
      color: var(--white);
    }
    .topbar h1 span { color: var(--gold); }
    .topbar-info {
      display: flex;
      align-items: center;
      gap: 16px;
    }
    /*.admin-badge {
      padding: 6px 16px;
      background: rgba(201,168,76,0.1);
      border: 1px solid rgba(201,168,76,0.3);
      border-radius: 100px;
      font-family: 'Oswald', sans-serif;
      font-size: 0.72rem;
      text-transform: uppercase;
      letter-spacing: 2px;
      color: var(--gold);
    }*/
    .content-area {
      padding: 36px;
    }
    /* Stats cards */
    .stats-row {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      margin-bottom: 36px;
    }
    .stat-card {
      background: Colorless
      /*border: 1px solid rgba(201,168,76,0.15);
      border-radius: 12px;*/
      border: none; 
      border-right: 1px solid rgba(255,255,255,0.2); 
      border-radius: 0; 
      padding: 24px;
      transition: all 0.3s;
    }
    /*.stat-card:hover {
      border-color: rgba(201,168,76,0.3);
      transform: translateY(-2px);
    }*/
    .stat-card .stat-icon {
      font-size: 2rem;
      margin-bottom: 12px;
    }
    .stat-card .stat-num {
      font-family: 'Oswald', sans-serif;
      font-size: 2.5rem;
      font-weight: 700;
      color: var(--gold);
      line-height: 1;
    }
    .stat-card .stat-label {
      font-size: 0.78rem;
      text-transform: uppercase;
      letter-spacing: 2px;
      color: var(--gray-400);
      margin-top: 6px;
    }
    /* Feedback table */
    .section-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
    }
    .section-header h2 {
      font-family: 'Oswald', sans-serif;
      font-size: 1.2rem;
      text-transform: uppercase;
      letter-spacing: 2px;
      color: var(--white);
    }
    .section-header h2 span { color: var(--gold); }
    .gold-line-left {
      width: 50px;
      height: 2px;
      background: linear-gradient(90deg, var(--gold), transparent);
      margin-bottom: 20px;
    }
    .feedback-table-wrap {
      background: rgba(10,22,40,0.5);
      border: 1px solid rgba(201,168,76,0.12);
      border-radius: 12px;
      overflow: hidden;
    }
    .feedback-table {
      width: 100%;
      border-collapse: collapse;
    }
    .feedback-table thead tr {
      background: rgba(201,168,76,0.08);
      border-bottom: 1px solid rgba(201,168,76,0.15);
    }
    .feedback-table thead th {
      padding: 14px 20px;
      font-family: 'Oswald', sans-serif;
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 2px;
      color: var(--gold);
      text-align: left;
      font-weight: 600;
    }
    .feedback-table tbody tr {
      border-bottom: 1px solid rgba(255,255,255,0.04);
      transition: background 0.2s;
    }
    .feedback-table tbody tr:last-child { border-bottom: none; }
    .feedback-table tbody tr:hover { background: rgba(201,168,76,0.04); }
    .feedback-table tbody td {
      padding: 16px 20px;
      font-size: 0.88rem;
      color: var(--gray-200);
      vertical-align: top;
    }
    .feedback-table .td-nama { color: var(--white); font-weight: 500; }
    .feedback-table .td-email a {
      color: var(--white);
      text-decoration: none;
      font-size: 0.82rem;
    }
    .feedback-table .td-email a:hover { color: var(--gold-light); text-decoration: underline; }
    .feedback-table .td-pesan {
      color: var(--white);
      max-width: 340px;
      line-height: 1.6;
    }
    .feedback-table .td-waktu {
      font-size: 0.78rem;
      color: var(--white);
      white-space: nowrap;
    }
    .reply-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 7px 14px;
      background: linear-gradient(135deg, var(--gold-dark), var(--gold));
      color: var(--navy-darkest);
      font-family: 'Oswald', sans-serif;
      font-size: 0.72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      border-radius: 6px;
      text-decoration: none;
      transition: all 0.2s;
      white-space: nowrap;
    }
    .reply-btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(201,168,76,0.3);
    }
    .empty-state {
      text-align: center;
      padding: 60px 20px;
      color: var(--gray-400);
    }
    .empty-state .empty-icon { font-size: 3rem; margin-bottom: 16px; }
    .empty-state p { font-size: 0.9rem; }
    .badge-new {
      display: inline-block;
      padding: 2px 8px;
      background: rgba(201,168,76,0.15);
      border: 1px solid rgba(201,168,76,0.3);
      border-radius: 100px;
      font-size: 0.65rem;
      font-family: 'Oswald', sans-serif;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: var(--gold);
      margin-left: 8px;
      vertical-align: middle;
    }
    .badge-read {
      color: var(--white);
      font-size: 0.75rem;
      padding: 6px 12px;
      background: rgba(255,255,255,0.05);
      border-radius: 6px;
      display: inline-block;
    }
    .btn-read {
      display: inline-block;
      margin-top: 8px;
      padding: 6px 12px;
      background: rgba(40,167,69,0.1);
      border: 1px solid rgba(40,167,69,0.3);
      border-radius: 6px;
      color: #28a745;
      font-family: 'Oswald', sans-serif;
      font-size: 0.72rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      text-decoration: none;
      transition: all 0.2s;
    }
    .btn-read:hover {
      background: rgba(40,167,69,0.2);
    }
    .filter-tabs {
      display: flex;
      gap: 12px;
      margin-bottom: 24px;
    }
    .filter-tabs a {
      padding: 8px 16px;
      background: colorless;
      border: none; 
      border-right: 1px solid rgba(255,255,255,0.2); 
      border-radius: 0;
      color: var(--gray-300);
      font-size: 0.85rem;
      text-decoration: none;
      transition: all 0.2s;
    }
    .filter-tabs a:hover {
      border-color: rgba(201,168,76,0.3);
      color: var(--white);
    }
    .filter-tabs a.active {
      background: rgba(201,168,76,0.1);
      border-color: var(--gold);
      color: var(--gold);
    }
    /* Notice banner */
    .notice-banner {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 14px 20px;
      background: rgba(74,139,194,0.1);
      border: 1px solid rgba(74,139,194,0.2);
      border-radius: 10px;
      margin-bottom: 28px;
      font-size: 0.85rem;
      color: var(--gray-200);
    }
    .notice-banner .notice-icon { font-size: 1.2rem; }
    @media (max-width: 900px) {
      .sidebar { width: 220px; }
      .main-content { margin-left: 220px; }
      .stats-row { grid-template-columns: 1fr 1fr; }
    }
      /* Responsive & Mobile Admin */
    .admin-hamburger {
      display: none;
      position: fixed;
      top: 15px;
      right: 20px;
      z-index: 1002;
      background: rgba(255,255,255,0.1);
      border: 1px solid rgba(255,255,255,0.2);
      color: #fff;
      padding: 8px 12px;
      border-radius: 5px;
      font-size: 1.5rem;
      cursor: pointer;
    }
    .admin-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.5);
      z-index: 99;
    }
    .admin-overlay.active { display: block; }
    
    .td-pesan-wrapper {
      max-height: 48px;
      overflow: hidden;
      transition: max-height 0.3s;
    }
    .btn-expand-pesan {
      background: none;
      border: none;
      color: var(--gray-300);
      cursor: pointer;
      font-size: 0.75rem;
      margin-top: 5px;
      display: inline-block;
    }
    .btn-expand-pesan:hover { color: #fff; }

    @media (max-width: 900px) {
      .admin-hamburger { display: block; }
      .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s;
        background: #060d1a;
      }
      .sidebar.active { transform: translateX(0); }
      .main-content { margin-left: 0; width: 100%; }
      .topbar { padding: 15px 20px; }
      .content-area { padding: 15px; }
      .stats-row { grid-template-columns: 1fr; }
      .feedback-table-wrap { overflow-x: auto; }
    }

    /* Welcome Card & Navigation Cards */
    .welcome-card {
      background: rgba(10, 22, 40, 0.6);
      border: 1px solid rgba(201, 168, 76, 0.2);
      border-radius: 12px;
      padding: 30px;
      margin-bottom: 30px;
    }
    .welcome-card h2 {
      font-family: 'Oswald', sans-serif;
      font-size: 1.4rem;
      color: var(--gold);
      margin-bottom: 12px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    .welcome-card p {
      font-size: 0.95rem;
      color: var(--gray-200);
      line-height: 1.6;
    }
    .menu-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
      margin-top: 25px;
    }
    .menu-card {
      background: rgba(10, 22, 40, 0.4);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 10px;
      padding: 20px;
      transition: all 0.3s;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .menu-card:hover {
      border-color: var(--gold);
      background: rgba(201, 168, 76, 0.03);
      transform: translateY(-2px);
    }
    .menu-card h3 {
      font-family: 'Oswald', sans-serif;
      font-size: 1.1rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: var(--white);
      margin-bottom: 8px;
    }
    .menu-card p {
      font-size: 0.85rem;
      color: var(--gray-300);
      margin-bottom: 15px;
      line-height: 1.5;
    }
    .menu-link {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      color: var(--gold);
      font-size: 0.85rem;
      font-weight: 600;
      text-decoration: none;
      text-transform: uppercase;
      letter-spacing: 1px;
      transition: color 0.2s;
    }
    .menu-link:hover {
      color: var(--gold-light);
    }
    @media (max-width: 768px) {
      .menu-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>

<!-- Hamburger Menu -->
<button class="admin-hamburger" onclick="toggleAdminMenu()">☰</button>
<div class="admin-overlay" onclick="toggleAdminMenu()"></div>
<!-- Sidebar -->
<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-img-wrap">
      <img src="../assets/images/logo-bengpus.png" alt="Logo">
    </div>
    <h2>BENGPUS<span> PUSKOMLEKAD</span></h2>    <!--<p>Admin Panel</p>-->
  </div>
  <nav class="sidebar-nav">
    <a href="dashboard.php" class="active">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
      Dashboard
    </a>
    <a href="berita.php">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
      Kelola Berita
    </a>
    <a href="video.php">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"></rect><line x1="7" y1="2" x2="7" y2="22"></line><line x1="17" y1="2" x2="17" y2="22"></line><line x1="2" y1="12" x2="22" y2="12"></line><line x1="2" y1="7" x2="7" y2="7"></line><line x1="2" y1="17" x2="7" y2="17"></line><line x1="17" y1="17" x2="22" y2="17"></line><line x1="17" y1="7" x2="22" y2="7"></line></svg>
      Kelola Video Terkait
    </a>
    <a href="pimpinan.php">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      Kelola Pimpinan
    </a>
    <a href="struktur.php">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="8" y="2" width="8" height="8" rx="1" ry="1"></rect><path d="M12 10v3"></path><path d="M3 13h18"></path><path d="M3 13v3"></path><path d="M21 13v3"></path><rect x="1" y="16" width="6" height="6" rx="1" ry="1"></rect><rect x="9" y="16" width="6" height="6" rx="1" ry="1"></rect><rect x="17" y="16" width="6" height="6" rx="1" ry="1"></rect></svg>
      Kelola Struktur
    </a>
    <a href="../index.php" target="_blank">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
      Lihat Website
    </a>
  </nav>
  <div class="sidebar-footer">
    <a href="logout.php" class="logout-btn">
      <span>Logout</span>
    </a>
  </div>
</aside>

<!-- Main content -->
<main class="main-content">
  <div class="topbar">
    <h1>Dashboard <span>Admin</span></h1>
  </div>

  <div class="content-area">
    <!-- Stats -->
    <div class="stats-row">
      <div class="stat-card">
        <div class="stat-label">Total Berita</div>
        <div class="stat-num"><?= $totalBerita ?></div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Total Video Terkait</div>
        <div class="stat-num"><?= $totalVideo ?></div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Total Pimpinan</div>
        <div class="stat-num"><?= $totalPimpinan ?></div>
      </div>
    </div>

    <!-- Welcome Card -->
    <div class="welcome-card">
      <h2>Selamat Datang, Administrator</h2>
      <p>Ini adalah halaman Dashboard Admin BENGPUS PUSKOMLEKAD. Dari sini Anda dapat memperbarui informasi, berita, video profil terkait, pimpinan, dan bagan struktur organisasi yang ditampilkan pada website publik.</p>
      
      <div class="menu-grid">
        <div class="menu-card">
          <div>
            <h3>Kelola Berita</h3>
            <p>Tambah berita, edit artikel, atau hapus berita seputar kegiatan BENGPUS PUSKOMLEKAD.</p>
          </div>
          <a href="berita.php" class="menu-link">Kelola Berita →</a>
        </div>
        
        <div class="menu-card">
          <div>
            <h3>Kelola Video Terkait</h3>
            <p>Atur video profil terkait dan video dokumentasi kegiatan resmi yang bersumber dari YouTube.</p>
          </div>
          <a href="video.php" class="menu-link">Kelola Video →</a>
        </div>
        
        <div class="menu-card">
          <div>
            <h3>Kelola Pimpinan</h3>
            <p>Perbarui data Kepala BENGPUS PUSKOMLEKAD dari masa ke masa serta foto pimpinan saat ini.</p>
          </div>
          <a href="pimpinan.php" class="menu-link">Kelola Pimpinan →</a>
        </div>
        
        <div class="menu-card">
          <div>
            <h3>Kelola Struktur Organisasi</h3>
            <p>Perbarui bagan gambar struktur organisasi terbaru agar dapat dilihat oleh publik secara jelas.</p>
          </div>
          <a href="struktur.php" class="menu-link">Kelola Struktur →</a>
        </div>
      </div>
    </div>
  </div>
</main>

<script>
function toggleAdminMenu() {
  document.querySelector('.sidebar').classList.toggle('active');
  document.querySelector('.admin-overlay').classList.toggle('active');
}
</script>
</body>
</html>
