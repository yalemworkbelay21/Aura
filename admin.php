<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
$username = $_SESSION['username'];
$email = $_SESSION['email'];
$profilePic = $_SESSION['profile_pic'] ?: './assets/images/Mequ.jpg';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard (PHP) - Aura Restaurant</title>

  <!-- Google Font Link -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&family=Forum&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="./assets/css/style.css">
  <style>
    :root {
      --bg-dark: #0a0b0c;
      --card-bg: rgba(22, 23, 24, 0.6);
      --gold: #E4C590;
      --text: #ffffff;
      --text-muted: #a9a9a9;
      --glass-border: rgba(255, 255, 255, 0.08);
    }

    body {
      background: url('./assets/images/login-bg.png') no-repeat center center fixed;
      background-size: cover;
      color: var(--text);
      font-family: 'DM Sans', sans-serif;
      margin: 0;
      display: flex;
      height: 100vh;
      overflow: hidden;
    }
    
    body::before {
      content: '';
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: radial-gradient(circle at center, rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0.85) 100%);
      z-index: -1;
    }

    .sidebar {
      width: 260px;
      height: 100vh;
      background-color: rgba(10, 11, 12, 0.8);
      backdrop-filter: blur(20px);
      border-right: 1px solid var(--glass-border);
      padding: 30px 15px;
      position: fixed;
      display: flex;
      flex-direction: column;
    }

    .sidebar-logo {
      font-family: 'Forum', serif;
      font-size: 28px;
      color: var(--gold);
      margin-bottom: 40px;
      text-align: center;
      letter-spacing: 3px;
      text-transform: uppercase;
    }

    .nav-list {
      list-style: none;
      padding: 0;
      flex-grow: 1;
    }

    .nav-item {
      padding: 12px 18px;
      margin-bottom: 8px;
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      display: flex;
      align-items: center;
      gap: 15px;
      color: var(--text-muted);
      font-size: 14px;
      font-weight: 500;
    }

    .nav-item:hover {
      background-color: rgba(228, 197, 144, 0.1);
      color: var(--gold);
      transform: translateX(5px);
    }

    .nav-item.active {
      background: linear-gradient(135deg, var(--gold) 0%, #D4B47C 100%);
      color: var(--bg-dark);
      box-shadow: 0 10px 20px rgba(228, 197, 144, 0.2);
    }

    .nav-item ion-icon {
      font-size: 20px;
    }

    .main-content {
      margin-left: 260px;
      flex-grow: 1;
      padding: 30px 50px;
      height: 100vh;
      overflow-y: auto;
      max-width: calc(100% - 260px);
      scroll-behavior: smooth;
    }

    .header-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
      padding: 15px 0;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px;
      margin-bottom: 40px;
    }

    .stat-card {
      background: var(--card-bg);
      backdrop-filter: blur(15px);
      padding: 20px;
      border-radius: 16px;
      border: 1px solid var(--glass-border);
      transition: all 0.4s ease;
      position: relative;
      overflow: hidden;
    }

    .stat-card::before {
      content: '';
      position: absolute;
      top: 0; left: 0; width: 100%; height: 4px;
      background: var(--gold);
      opacity: 0;
      transition: 0.4s;
    }

    .stat-card:hover {
      transform: translateY(-5px);
      border-color: rgba(228, 197, 144, 0.4);
      background: rgba(22, 23, 24, 0.8);
    }

    .stat-card:hover::before {
      opacity: 1;
    }

    .stat-label {
      color: var(--text-muted);
      font-size: 13px;
      margin-bottom: 8px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .stat-value {
      font-size: 24px;
      color: var(--gold);
      font-weight: 700;
      font-family: 'Forum', serif;
    }

    .data-table-container {
      background: var(--card-bg);
      backdrop-filter: blur(15px);
      border-radius: 16px;
      border: 1px solid var(--glass-border);
      overflow: hidden;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th {
      text-align: left;
      padding: 18px 25px;
      color: var(--gold);
      font-size: 13px;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      background: rgba(228, 197, 144, 0.05);
      border-bottom: 1px solid var(--glass-border);
    }

    td {
      padding: 16px 25px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.03);
      font-size: 14px;
    }

    tr:hover td {
      background: rgba(228, 197, 144, 0.02);
    }

    .status-badge {
      padding: 6px 14px;
      border-radius: 30px;
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .status-pending { background: rgba(255, 215, 0, 0.1); color: #ffd700; border: 1px solid rgba(255, 215, 0, 0.2); }
    .status-confirmed { background: rgba(0, 255, 0, 0.1); color: #00ff00; border: 1px solid rgba(0, 255, 0, 0.2); }

    .action-btn {
      width: 36px;
      height: 36px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 1px solid var(--glass-border);
      background: rgba(255, 255, 255, 0.05);
      color: var(--text-muted);
      cursor: pointer;
      transition: all 0.3s;
    }

    .action-btn:hover {
      background: var(--gold);
      color: var(--bg-dark);
      border-color: var(--gold);
      transform: scale(1.1);
    }

    .btn-delete:hover { background: #e74c3c; border-color: #e74c3c; color: white; }
    .btn-view:hover { background: #3498db; border-color: #3498db; color: white; }
    .btn-confirm:hover { background: #2ecc71; border-color: #2ecc71; color: white; }

    .chat-reply-box {
      margin-top: 15px;
      background: rgba(10, 11, 12, 0.4);
      padding: 15px;
      border-radius: 12px;
      border: 1px solid var(--glass-border);
    }

    .input-reply {
      flex-grow: 1;
      background-color: rgba(22, 23, 24, 0.5);
      border: 1px solid #444;
      color: white;
      padding: 10px;
      border-radius: 4px;
      outline: none;
    }

    .main-content::-webkit-scrollbar {
      width: 6px;
    }
    .main-content::-webkit-scrollbar-thumb {
      background: rgba(228, 197, 144, 0.3);
      border-radius: 10px;
    }
    .main-content::-webkit-scrollbar-thumb:hover {
      background: var(--gold);
    }
    .main-content::-webkit-scrollbar-track {
      background: rgba(0, 0, 0, 0.2);
    }

    .active-section {
      display: block;
    }
  </style>
</head>

<body>

  <div class="modal" id="viewModal">
    <div class="modal-content">
      <span class="modal-close" onclick="closeModal()">&times;</span>
      <h2 class="modal-title">Reservation Details</h2>
      <div id="modalBody"></div>
    </div>
  </div>

  <aside class="sidebar">
    <div class="sidebar-logo">AURA PHP</div>
    <ul class="nav-list">
      <li class="nav-item active" onclick="showSection('reservations')">
        <ion-icon name="calendar-outline"></ion-icon> Reservations
      </li>
      <li class="nav-item" onclick="showSection('chats')">
        <ion-icon name="chatbubbles-outline"></ion-icon> Chat Messages
      </li>
      <li class="nav-item" onclick="showSection('subscribers')">
        <ion-icon name="people-outline"></ion-icon> Subscribers
      </li>
      <li class="nav-item" onclick="showSection('menu-manager')">
        <ion-icon name="restaurant-outline"></ion-icon> Menu Manager
      </li>
      <li class="nav-item" onclick="showSection('gallery-manager')">
        <ion-icon name="images-outline"></ion-icon> Gallery Manager
      </li>
      <li class="nav-item" onclick="showSection('website-settings')">
        <ion-icon name="settings-outline"></ion-icon> Website Settings
      </li>
      <li class="nav-item" onclick="showSection('profile')">
        <ion-icon name="person-circle-outline"></ion-icon> My Profile
      </li>
      <li class="nav-item" style="margin-top: auto;">
        <a href="logout.php"
          style="color: #e74c3c; text-decoration: none; display: flex; align-items: center; gap: 15px;">
          <ion-icon name="log-out-outline"></ion-icon> Logout
        </a>
      </li>
      <li class="nav-item">
        <a href="index.html"
          style="color: inherit; text-decoration: none; display: flex; align-items: center; gap: 15px;">
          <ion-icon name="home-outline"></ion-icon> View Site
        </a>
      </li>
    </ul>
  </aside>

  <main class="main-content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
      <h1 style="font-family: 'Forum', serif; font-size: 36px; margin: 0;" id="pageTitle">Reservations</h1>
      <div style="display: flex; align-items: center; gap: 10px;">
        <span id="mgrName"><?php echo $username; ?></span>
        <img id="topProfilePic" src="<?php echo $profilePic; ?>" width="40" height="40"
          style="border-radius: 50%; border: 2px solid var(--gold);">
      </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
      <div class="stat-card">
        <p class="stat-label">Bookings</p>
        <p class="stat-value" id="totalRes">0</p>
      </div>
      <div class="stat-card">
        <p class="stat-label">Messages</p>
        <p class="stat-value" id="pendingChats">0</p>
      </div>
      <div class="stat-card">
        <p class="stat-label">Menu Items</p>
        <p class="stat-value" id="totalMenuItems">0</p>
      </div>
      <div class="stat-card">
        <p class="stat-label">Gallery</p>
        <p class="stat-value" id="totalGalleryItems">0</p>
      </div>
    </div>

    <!-- Section: Reservations -->
    <div id="reservations" class="data-table-container">
      <div style="padding: 20px 25px; border-bottom: 1px solid #333;">
        <h2 style="font-family: 'Forum', serif; color: var(--gold); margin: 0;">Reservation Pipeline</h2>
      </div>
      <table>
        <thead>
          <tr>
            <th>Customer</th>
            <th>Phone</th>
            <th>Date & Time</th>
            <th>Persons</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="resTable">
          <!-- Data populated by JS (fetched from api.php) -->
        </tbody>
      </table>
    </div>

    <!-- Section: Chats -->
    <div id="chats" class="data-table-container" style="display: none;">
      <div style="padding: 20px 25px; border-bottom: 1px solid #333;">
        <h2 style="font-family: 'Forum', serif; color: var(--gold); margin: 0;">Live Chat Interface</h2>
      </div>
      <div id="chatList" style="padding: 20px;">
        <!-- Data populated by JS (fetched from api.php) -->
      </div>
    </div>

    <!-- Section: Subscribers -->
    <div id="subscribers" class="data-table-container" style="display: none;">
      <div style="padding: 20px 25px; border-bottom: 1px solid #333;">
        <h2 style="font-family: 'Forum', serif; color: var(--gold); margin: 0;">Newsletter Subscribers</h2>
      </div>
      <table>
        <thead>
          <tr>
            <th>Email Address</th>
            <th>Subscription Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="subTable">
          <!-- Data populated by JS -->
        </tbody>
      </table>
    </div>

    <!-- Section: Menu Manager -->
    <div id="menu-manager" class="data-table-container" style="display: none;">
      <div style="padding: 20px 25px; border-bottom: 1px solid #333; display: flex; justify-content: space-between; align-items: center;">
        <h2 style="font-family: 'Forum', serif; color: var(--gold); margin: 0;">Menu Manager</h2>
        <button class="action-btn" onclick="openAddMenu()" style="width: 140px; border-radius: 8px; font-size: 13px;">+ Add Dish</button>
      </div>
      <table>
        <thead>
          <tr>
            <th>Dish</th>
            <th>Category</th>
            <th>Price</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="menuTable"></tbody>
      </table>
    </div>

    <!-- Section: Gallery Manager -->
    <div id="gallery-manager" class="data-table-container" style="display: none;">
      <div style="padding: 20px 25px; border-bottom: 1px solid #333; display: flex; justify-content: space-between; align-items: center;">
        <h2 style="font-family: 'Forum', serif; color: var(--gold); margin: 0;">Gallery Manager</h2>
        <button class="action-btn" onclick="openAddGallery()" style="width: 150px; border-radius: 8px; font-size: 13px;">+ Add Image</button>
      </div>
      <div id="galleryGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; padding: 25px;">
        <!-- Data populated by JS -->
      </div>
    </div>

    <!-- Section: Website Settings -->
    <div id="website-settings" class="data-table-container" style="display: none;">
      <div style="padding: 20px 25px; border-bottom: 1px solid #333;">
        <h2 style="font-family: 'Forum', serif; color: var(--gold); margin: 0;">Global Website Settings</h2>
      </div>
      <div style="padding: 40px; max-width: 800px;">
        <form onsubmit="saveSettings(event)">
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div style="margin-bottom: 20px;">
              <label style="display: block; color: var(--text-muted); margin-bottom: 8px;">Site Name</label>
              <input type="text" id="set_site_name" class="input-reply" style="width: 100%;">
            </div>
            <div style="margin-bottom: 20px;">
              <label style="display: block; color: var(--text-muted); margin-bottom: 8px;">Contact Phone</label>
              <input type="text" id="set_site_phone" class="input-reply" style="width: 100%;">
            </div>
            <div style="margin-bottom: 20px;">
              <label style="display: block; color: var(--text-muted); margin-bottom: 8px;">Contact Email</label>
              <input type="email" id="set_site_email" class="input-reply" style="width: 100%;">
            </div>
            <div style="margin-bottom: 20px;">
              <label style="display: block; color: var(--text-muted); margin-bottom: 8px;">Opening Hours</label>
              <input type="text" id="set_site_hours" class="input-reply" style="width: 100%;">
            </div>
          </div>
          <div style="margin-bottom: 20px;">
            <label style="display: block; color: var(--text-muted); margin-bottom: 8px;">Full Address</label>
            <input type="text" id="set_site_address" class="input-reply" style="width: 100%;">
          </div>
          
          <h3 style="color: var(--gold); margin-top: 30px; margin-bottom: 15px; font-family: 'Forum', serif;">Homepage Hero Content</h3>
          <div style="margin-bottom: 20px;">
            <label style="display: block; color: var(--text-muted); margin-bottom: 8px;">Hero Subtitle (e.g. Traditional & Hygine)</label>
            <input type="text" id="set_hero_subtitle" class="input-reply" style="width: 100%;">
          </div>
          <div style="margin-bottom: 20px;">
            <label style="display: block; color: var(--text-muted); margin-bottom: 8px;">Hero Title (Main headline)</label>
            <input type="text" id="set_hero_title" class="input-reply" style="width: 100%;">
          </div>

          <h3 style="color: var(--gold); margin-top: 30px; margin-bottom: 15px; font-family: 'Forum', serif;">About Aura Section</h3>
          <div style="margin-bottom: 30px;">
            <label style="display: block; color: var(--text-muted); margin-bottom: 8px;">Our Story (Description paragraph)</label>
            <textarea id="set_about_text" class="input-reply" style="width: 100%; height: 100px; resize: vertical;"></textarea>
          </div>
          
          <button type="submit" class="btn-action" style="width: 200px; padding: 12px;">Push Live Changes</button>
        </form>
      </div>
    </div>

    <!-- Section: Profile -->
    <div id="profile" class="data-table-container" style="display: none;">
      <div style="padding: 20px 25px; border-bottom: 1px solid #333;">
        <h2 style="font-family: 'Forum', serif; color: var(--gold); margin: 0;">Manager Account Settings</h2>
      </div>
      <div style="padding: 40px; max-width: 500px;">
        <!-- General Info -->
        <h3 style="color: var(--gold); margin-bottom: 20px;">General Info</h3>
        <form onsubmit="updateProfile(event)">
          <div style="margin-bottom: 25px;">
            <label style="display: block; color: var(--text-muted); margin-bottom: 8px;">Username</label>
            <input type="text" id="profUsername" value="<?php echo $username; ?>" class="input-reply" style="width: 100%;">
          </div>
          <div style="margin-bottom: 25px;">
            <label style="display: block; color: var(--text-muted); margin-bottom: 8px;">Email Address</label>
            <input type="email" id="profEmail" value="<?php echo $email; ?>" class="input-reply" style="width: 100%;">
          </div>
          <button type="submit" class="btn-action">Update General Info</button>
        </form>

        <hr style="border: 0; border-top: 1px solid #333; margin: 40px 0;">

        <!-- Profile Picture -->
        <h3 style="color: var(--gold); margin-bottom: 20px;">Profile Picture</h3>
        <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 20px;">
           <img id="displayProfPic" src="<?php echo $profilePic; ?>" width="80" height="80" style="border-radius: 50%; border: 2px solid var(--gold);">
           <div>
             <input type="file" id="picInput" style="display: none;" onchange="uploadPic(this)">
             <button class="btn-action" onclick="document.getElementById('picInput').click()">Upload New Photo</button>
           </div>
        </div>

        <hr style="border: 0; border-top: 1px solid #333; margin: 40px 0;">

        <!-- Change Password -->
        <h3 style="color: var(--gold); margin-bottom: 20px;">Security</h3>
        <form onsubmit="changePassword(event)">
          <div style="margin-bottom: 25px;">
            <label style="display: block; color: var(--text-muted); margin-bottom: 8px;">New Password</label>
            <input type="password" id="newPass" class="input-reply" style="width: 100%;" required>
          </div>
          <button type="submit" class="btn-action">Change Password</button>
        </form>
      </div>
    </div>

  </main>

  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

  <script>
    function showSection(id) {
      document.getElementById('reservations').style.display = 'none';
      document.getElementById('chats').style.display = 'none';
      document.getElementById('subscribers').style.display = 'none';
      document.getElementById('menu-manager').style.display = 'none';
      document.getElementById('gallery-manager').style.display = 'none';
      document.getElementById('website-settings').style.display = 'none';
      document.getElementById('profile').style.display = 'none';
      document.getElementById(id).style.display = 'block';
      document.getElementById('pageTitle').innerText = id.charAt(0).toUpperCase() + id.slice(1);

      const navItems = document.querySelectorAll('.nav-item');
      navItems.forEach(item => item.classList.remove('active'));
      const activeItem = Array.from(navItems).find(item => item.innerText.trim().toLowerCase().includes(id.toLowerCase()));
      if (activeItem) activeItem.classList.add('active');
    }

    async function loadData() {
      const response = await fetch('api.php?action=get_all');
      const data = await response.json();

      document.getElementById('totalRes').innerText = data.reservations.length;
      document.getElementById('pendingChats').innerText = data.chats.length;
      document.getElementById('totalMenuItems').innerText = data.menu.length;
      document.getElementById('totalGalleryItems').innerText = data.gallery.length;

      const resTable = document.getElementById('resTable');
      resTable.innerHTML = '';
      data.reservations.forEach((r) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${r.name}</td>
          <td>${r.phone}</td>
          <td>${r.date} at ${r.time}</td>
          <td>${r.person}</td>
          <td><span class="status-badge ${r.status === 'confirmed' ? 'status-confirmed' : 'status-pending'}">${r.status || 'Pending'}</span></td>
          <td>
            <div style="display: flex; gap: 8px;">
              <button class="action-btn btn-view" onclick="viewRes(${JSON.stringify(r).replace(/"/g, '&quot;')})" title="View Details">
                <ion-icon name="eye-outline"></ion-icon>
              </button>
              <button class="action-btn btn-confirm" onclick="handleRes(${r.id}, 'confirmed')" title="Confirm Reservation">
                <ion-icon name="checkmark-outline"></ion-icon>
              </button>
              <button class="action-btn btn-delete" onclick="deleteRes(${r.id})" title="Delete">
                <ion-icon name="trash-outline"></ion-icon>
              </button>
            </div>
          </td>
        `;
        resTable.appendChild(tr);
      });

      const chatList = document.getElementById('chatList');
      chatList.innerHTML = '';
      data.chats.forEach((c) => {
        const div = document.createElement('div');
        div.style.marginBottom = '20px';
        div.style.borderBottom = '1px solid #333';
        div.style.paddingBottom = '15px';
        
        if (c.is_admin === 0 || c.is_admin === '0') {
            div.innerHTML = `
              <p style="color: var(--gold); font-weight: bold; margin-bottom: 5px;">User Info | Sent at ${c.time}:</p>
              <p style="margin-bottom: 10px;">${c.msg}</p>
              <div class="chat-reply-box">
                <input type="text" id="reply_${c.id}" class="input-reply" placeholder="Type AI-assisted reply...">
                <button class="btn-action" onclick="replyChat(${c.id})">Send</button>
              </div>
            `;
        } else {
            div.style.marginLeft = '30px';
            div.style.borderLeft = '2px solid var(--gold)';
            div.style.paddingLeft = '15px';
            div.innerHTML = `
              <p style="color: #00ff00; font-weight: bold; margin-bottom: 5px;">Aura Admin | Sent at ${c.time}:</p>
              <p style="margin-bottom: 10px; color: #ccc;">${c.msg}</p>
            `;
        }
        chatList.appendChild(div);
      });

      const subTable = document.getElementById('subTable');
      subTable.innerHTML = '';
      data.subscribers.forEach((s) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${s.email}</td>
          <td>${s.created_at}</td>
          <td>
            <div style="display: flex; gap: 8px;">
              <button class="action-btn btn-view" onclick="sendMail('${s.email}')" title="Send Offer">
                <ion-icon name="mail-outline"></ion-icon>
              </button>
              <button class="action-btn btn-delete" onclick="deleteSub(${s.id})" title="Remove Subscriber">
                <ion-icon name="trash-outline"></ion-icon>
              </button>
            </div>
          </td>
        `;
        subTable.appendChild(tr);
      });

      const menuTable = document.getElementById('menuTable');
      menuTable.innerHTML = '';
      data.menu.forEach(m => {
        const tr = document.createElement('tr');
        const mJson = JSON.stringify(m).replace(/'/g, "&#39;");
        tr.innerHTML = `
          <td><div style="display:flex; align-items:center; gap:10px;"><img src="${m.image}" width="40" height="40" style="border-radius:8px"> ${m.title}</div></td>
          <td>${m.category}</td>
          <td>$${m.price}</td>
          <td>
            <div style="display:flex; gap:5px;">
               <button class="action-btn btn-view" onclick='viewMenu(${mJson})' title="View Details"><ion-icon name="eye-outline"></ion-icon></button>
               <button class="action-btn btn-confirm" onclick='editMenu(${mJson})' title="Edit Dish"><ion-icon name="create-outline"></ion-icon></button>
               <button class="action-btn btn-delete" onclick="deleteMenu(${m.id})" title="Delete Dish"><ion-icon name="trash-outline"></ion-icon></button>
            </div>
          </td>
        `;
        menuTable.appendChild(tr);
      });

      const galleryGrid = document.getElementById('galleryGrid');
      galleryGrid.innerHTML = '';
      data.gallery.forEach(g => {
        const div = document.createElement('div');
        const gJson = JSON.stringify(g).replace(/'/g, "&#39;");
        div.style.position = 'relative';
        div.innerHTML = `
          <img src="${g.image}" style="width:100%; height:150px; object-fit:cover; border-radius:12px; border: 1px solid var(--glass-border)">
          <div style="position:absolute; top:10px; right:10px; display:flex; gap:5px;">
            <button class="action-btn btn-confirm" onclick='editGallery(${gJson})' style="width:30px; height:30px" title="Edit Caption">
              <ion-icon name="create-outline"></ion-icon>
            </button>
            <button class="action-btn btn-delete" onclick="deleteGallery(${g.id})" style="width:30px; height:30px" title="Delete Image">
              <ion-icon name="trash-outline"></ion-icon>
            </button>
          </div>
        `;
        galleryGrid.appendChild(div);
      });

      // Update Settings Inputs
      if (data.settings) {
        Object.keys(data.settings).forEach(key => {
          const el = document.getElementById('set_' + key);
          if (el) el.value = data.settings[key];
        });
      }
    }

    window.saveSettings = async function (e) {
      e.preventDefault();
      const settings = {
        site_name: document.getElementById('set_site_name').value,
        site_phone: document.getElementById('set_site_phone').value,
        site_email: document.getElementById('set_site_email').value,
        site_hours: document.getElementById('set_site_hours').value,
        site_address: document.getElementById('set_site_address').value,
        hero_subtitle: document.getElementById('set_hero_subtitle').value,
        hero_title: document.getElementById('set_hero_title').value,
        about_text: document.getElementById('set_about_text').value
      };

      const response = await fetch('api.php?action=update_settings', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(settings)
      });
      if ((await response.json()).success) alert("Website settings updated successfully!");
      loadData();
    }

    window.openAddMenu = function() {
      renderMenuForm();
    }

    window.editMenu = function(m) {
      renderMenuForm(m);
    }

    window.viewMenu = function(m) {
       const body = document.getElementById('modalBody');
       body.innerHTML = `
         <div style="text-align:center; margin-bottom:20px;">
           <img src="${m.image}" style="width:150px; height:150px; border-radius:15px; border:2px solid var(--gold)">
         </div>
         <div class="modal-item"><span class="modal-label">Title:</span> ${m.title}</div>
         <div class="modal-item"><span class="modal-label">Category:</span> ${m.category}</div>
         <div class="modal-item"><span class="modal-label">Price:</span> $${m.price}</div>
         <div class="modal-item"><span class="modal-label">Description:</span> ${m.description || 'N/A'}</div>
       `;
       document.getElementById('viewModal').style.display = 'flex';
       document.querySelector('.modal-title').innerText = "Dish Details";
    }

    function renderMenuForm(m = null) {
      const body = document.getElementById('modalBody');
      body.innerHTML = `
        <form onsubmit="saveMenu(event)">
          <input type="hidden" id="mId" value="${m ? m.id : ''}">
          <div style="margin-bottom:15px"><input type="text" id="mTitle" placeholder="Dish Title" value="${m ? m.title : ''}" class="input-reply" style="width:100%" required></div>
          <div style="margin-bottom:15px">
            <select id="mCat" class="input-reply" style="width:100%">
              <option value="Breakfast" ${m && m.category === 'Breakfast' ? 'selected' : ''}>Breakfast</option>
              <option value="Appetizers" ${m && m.category === 'Appetizers' ? 'selected' : ''}>Appetizers</option>
              <option value="Main Course" ${m && m.category === 'Main Course' ? 'selected' : ''}>Main Course</option>
              <option value="Drinks" ${m && m.category === 'Drinks' ? 'selected' : ''}>Drinks</option>
              <option value="Desserts" ${m && m.category === 'Desserts' ? 'selected' : ''}>Desserts</option>
            </select>
          </div>
          <div style="margin-bottom:15px"><input type="number" step="0.01" id="mPrice" placeholder="Price" value="${m ? m.price : ''}" class="input-reply" style="width:100%" required></div>
          <div style="margin-bottom:15px"><textarea id="mDesc" placeholder="Description" class="input-reply" style="width:100%">${m ? m.description : ''}</textarea></div>
          <div style="margin-bottom:15px"><input type="text" id="mImg" placeholder="Image URL" value="${m ? m.image : ''}" class="input-reply" style="width:100%"></div>
          <button type="submit" class="btn-action" style="width:100%">${m ? 'Update Dish' : 'Add to Menu'}</button>
        </form>
      `;
      document.getElementById('viewModal').style.display = 'flex';
      document.querySelector('.modal-title').innerText = m ? "Edit Dish" : "Add New Dish";
    }

    window.saveMenu = async function(e) {
      e.preventDefault();
      const id = document.getElementById('mId').value;
      const data = {
        title: document.getElementById('mTitle').value,
        category: document.getElementById('mCat').value,
        price: document.getElementById('mPrice').value,
        description: document.getElementById('mDesc').value,
        image: document.getElementById('mImg').value
      };
      if (id) data.id = id;

      await fetch('api.php?action=save_menu', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data)
      });
      closeModal();
      loadData();
    }

    window.deleteMenu = async function(id) {
       if(!confirm("Delete this dish?")) return;
       await fetch('api.php?action=delete_menu', {
         method: 'POST',
         headers: {'Content-Type': 'application/json'},
         body: JSON.stringify({id})
       });
       loadData();
    }

    window.openAddGallery = function() {
      renderGalleryForm();
    }

    window.editGallery = function(g) {
      renderGalleryForm(g);
    }

    function renderGalleryForm(g = null) {
      const body = document.getElementById('modalBody');
      body.innerHTML = `
        <form onsubmit="saveGallery(event)">
          <input type="hidden" id="gId" value="${g ? g.id : ''}">
          <div style="margin-bottom:15px"><input type="text" id="gImg" placeholder="Image URL" value="${g ? g.image : ''}" class="input-reply" style="width:100%" required></div>
          <div style="margin-bottom:15px"><input type="text" id="gCap" placeholder="Caption" value="${g ? g.caption : ''}" class="input-reply" style="width:100%"></div>
          <button type="submit" class="btn-action" style="width:100%">${g ? 'Update Image' : 'Add to Gallery'}</button>
        </form>
      `;
      document.getElementById('viewModal').style.display = 'flex';
      document.querySelector('.modal-title').innerText = g ? "Edit Gallery Image" : "Add Gallery Image";
    }

    window.saveGallery = async function(e) {
      e.preventDefault();
      const id = document.getElementById('gId').value;
      const data = {
        image: document.getElementById('gImg').value,
        caption: document.getElementById('gCap').value
      };
      if (id) data.id = id;

      await fetch('api.php?action=save_gallery', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data)
      });
      closeModal();
      loadData();
    }

    window.deleteGallery = async function(id) {
       if(!confirm("Delete this image?")) return;
       await fetch('api.php?action=delete_gallery', {
         method: 'POST',
         headers: {'Content-Type': 'application/json'},
         body: JSON.stringify({id})
       });
       loadData();
    }

    window.deleteSub = async function (id) {
      if (!confirm("Are you sure you want to remove this subscriber?")) return;
      await fetch('api.php?action=delete_subscriber', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
      });
      loadData();
    }

    window.sendMail = function (email) {
      alert("Opening mail client to send offer to " + email);
      window.location.href = "mailto:" + email + "?subject=Special Offer from Aura Restaurant&body=Hello! We have a special gift for you.";
    }

    window.handleRes = async function (id, status) {
      await fetch('api.php?action=update_res', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id, status })
      });
      loadData();
    }

    window.deleteRes = async function (id) {
      if (!confirm("Are you sure you want to delete this reservation?")) return;
      await fetch('api.php?action=delete_res', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
      });
      loadData();
    }

    window.replyChat = async function (id) {
      const input = document.getElementById('reply_' + id);
      const msg = input.value.trim();
      if (!msg) return;

      await fetch('api.php?action=save_chat', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ msg: msg, is_admin: 1, time: new Date().toLocaleString() })
      });
      loadData();
    }

    window.showSection = showSection;

    window.updateProfile = async function (e) {
      e.preventDefault();
      const username = document.getElementById('profUsername').value;
      const email = document.getElementById('profEmail').value;

      const response = await fetch('auth_api.php?action=update_profile', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, email })
      });
      const data = await response.json();
      if (data.success) {
        alert("Profile updated successfully!");
        document.getElementById('mgrName').innerText = username;
      } else {
        alert(data.message);
      }
    }

    window.changePassword = async function (e) {
      e.preventDefault();
      const pass = document.getElementById('newPass').value;
      const response = await fetch('auth_api.php?action=change_password', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ password: pass })
      });
      const data = await response.json();
      if (data.success) {
        alert("Password changed successfully!");
        document.getElementById('newPass').value = '';
      }
    }

    window.uploadPic = async function (input) {
      if (!input.files || !input.files[0]) return;
      const formData = new FormData();
      formData.append('profile_pic', input.files[0]);

      const response = await fetch('auth_api.php?action=upload_profile_pic', {
        method: 'POST',
        body: formData
      });
      const data = await response.json();
      if (data.success) {
        document.getElementById('displayProfPic').src = data.path + '?v=' + new Date().getTime();
        document.getElementById('topProfilePic').src = data.path + '?v=' + new Date().getTime();
        alert("Profile picture updated!");
      } else {
        alert(data.message);
      }
    }

    window.viewRes = function (r) {
      const body = document.getElementById('modalBody');
      body.innerHTML = `
        <div class="modal-item"><span class="modal-label">Name:</span> ${r.name}</div>
        <div class="modal-item"><span class="modal-label">Phone:</span> ${r.phone}</div>
        <div class="modal-item"><span class="modal-label">Guests:</span> ${r.person}</div>
        <div class="modal-item"><span class="modal-label">Date:</span> ${r.date}</div>
        <div class="modal-item"><span class="modal-label">Time:</span> ${r.time}</div>
        <div class="modal-item"><span class="modal-label">Status:</span> ${r.status || 'Pending'}</div>
        <div class="modal-item"><span class="modal-label">Created:</span> ${r.created_at}</div>
      `;
      document.getElementById('viewModal').style.display = 'flex';
    }

    window.closeModal = function () {
      document.getElementById('viewModal').style.display = 'none';
    }

    loadData();
    setInterval(loadData, 5000); 
  </script>
</body>

</html>