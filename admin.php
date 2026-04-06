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
      min-height: 100vh;
    }
    
    body::before {
      content: '';
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: radial-gradient(circle at center, rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0.85) 100%);
      z-index: -1;
    }

    .sidebar {
      width: 100%;
      height: 80px;
      background-color: rgba(10, 11, 12, 0.85);
      backdrop-filter: blur(25px);
      border-bottom: 1px solid var(--glass-border);
      padding: 0 40px;
      position: fixed;
      top: 0; left: 0;
      display: flex;
      flex-direction: row;
      align-items: center;
      justify-content: space-between;
      z-index: 1000;
    }

    .sidebar-logo {
      font-family: 'Forum', serif;
      font-size: 24px;
      color: var(--gold);
      margin: 0;
      letter-spacing: 2px;
      text-transform: uppercase;
    }

    .nav-list {
      list-style: none;
      padding: 0;
      margin: 0;
      display: flex;
      gap: 15px;
    }

    .nav-item {
      padding: 10px 18px;
      margin: 0;
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      display: flex;
      align-items: center;
      gap: 10px;
      color: var(--text-muted);
      font-size: 13px;
      font-weight: 600;
    }

    .nav-item:hover {
      background-color: rgba(228, 197, 144, 0.1);
      color: var(--gold);
      transform: translateY(-2px);
    }

    .nav-item.active {
      background: linear-gradient(135deg, var(--gold) 0%, #D4B47C 100%);
      color: var(--bg-dark);
      box-shadow: 0 10px 20px rgba(228, 197, 144, 0.2);
    }

    .nav-item ion-icon {
      font-size: 18px;
    }

    .main-content {
      margin: 120px auto 50px;
      padding: 0 20px;
      max-width: 1200px;
      width: 95%;
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
    <div class="sidebar-logo">AURA DASHBOARD</div>
    <ul class="nav-list">
      <li class="nav-item active" onclick="showSection('reservations')">
        <ion-icon name="calendar-outline"></ion-icon> Reservations
      </li>
      <li class="nav-item" onclick="showSection('chats')">
        <ion-icon name="chatbubbles-outline"></ion-icon> Chats
      </li>
      <li class="nav-item" onclick="showSection('subscribers')">
        <ion-icon name="people-outline"></ion-icon> Subscribers
      </li>
      <li class="nav-item" onclick="showSection('profile')">
        <ion-icon name="person-circle-outline"></ion-icon> Profile
      </li>
      <li class="nav-item">
        <a href="index.html" style="color: inherit; text-decoration: none; display: flex; align-items: center; gap: 8px;">
          <ion-icon name="home-outline"></ion-icon> Site
        </a>
      </li>
      <li class="nav-item" style="margin-left: 10px;">
        <a href="logout.php" style="color: #e74c3c; text-decoration: none; display: flex; align-items: center; gap: 8px;">
          <ion-icon name="log-out-outline"></ion-icon> Exit
        </a>
      </li>
    </ul>

    <div style="display: flex; align-items: center; gap: 15px; border-left: 1px solid var(--glass-border); padding-left: 20px; margin-left: 20px;">
      <div style="text-align: right;">
        <p id="mgrName" style="margin: 0; font-size: 13px; font-weight: 700; color: var(--gold);"><?php echo $username; ?></p>
        <p style="margin: 0; font-size: 11px; color: var(--text-muted);">Manager</p>
      </div>
      <img id="topProfilePic" src="<?php echo $profilePic; ?>" width="42" height="42"
        style="border-radius: 50%; border: 2px solid var(--gold); object-fit: cover;">
    </div>
  </aside>

  <main class="main-content">
    <div class="header-bar">
      <h1 style="font-family: 'Forum', serif; font-size: 42px; margin: 0; letter-spacing: 1px;" id="pageTitle">Reservations</h1>
      <div style="display: flex; gap: 10px; align-items: center;">
        <span class="status-badge" style="background: rgba(0, 255, 0, 0.1); color: #00ff00; border: 1px solid rgba(0, 255, 0, 0.2);"> System Live </span>
      </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
      <div class="stat-card">
        <p class="stat-label">Real-time Bookings</p>
        <p class="stat-value" id="totalRes">0</p>
      </div>
      <div class="stat-card">
        <p class="stat-label">System Messages</p>
        <p class="stat-value" id="pendingChats">0</p>
      </div>
      <div class="stat-card">
        <p class="stat-label">Total Subscribers</p>
        <p class="stat-value" id="totalSubs">0</p>
      </div>
      <div class="stat-card">
        <p class="stat-label">PHP Node Status</p>
        <p class="stat-value" style="color: #00ff00;">Online</p>
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
      document.getElementById('totalSubs').innerText = data.subscribers.length;

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