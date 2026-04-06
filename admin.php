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
      --card-bg: #161718;
      --gold: #E4C590;
      --text: #ffffff;
      --text-muted: #a9a9a9;
    }

    body {
      background: url('./assets/images/login-bg.png') no-repeat center center fixed;
      background-size: cover;
      color: var(--text);
      font-family: 'DM Sans', sans-serif;
      margin: 0;
      display: flex;
    }
    
    body::before {
      content: '';
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0, 0, 0, 0.70);
      z-index: -1;
    }

    .sidebar {
      width: 280px;
      height: 100vh;
      background-color: rgba(22, 23, 24, 0.6);
      backdrop-filter: blur(15px);
      border-right: 1px solid #333;
      padding: 30px 20px;
      position: fixed;
    }

    .sidebar-logo {
      font-family: 'Forum', serif;
      font-size: 32px;
      color: var(--gold);
      margin-bottom: 50px;
      text-align: center;
      letter-spacing: 2px;
    }

    .nav-list {
      list-style: none;
      padding: 0;
    }

    .nav-item {
      padding: 15px 20px;
      margin-bottom: 10px;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 15px;
      color: var(--text-muted);
    }

    .nav-item:hover,
    .nav-item.active {
      background-color: var(--gold);
      color: var(--bg-dark);
    }

    .nav-item ion-icon {
      font-size: 20px;
    }

    .main-content {
      margin-left: 280px;
      flex-grow: 1;
      padding: 40px;
      min-height: 100vh;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 30px;
      margin-bottom: 40px;
    }

    .stat-card {
      background-color: rgba(22, 23, 24, 0.65);
      backdrop-filter: blur(12px);
      padding: 25px;
      border-radius: 12px;
      border: 1px solid #333;
    }

    .stat-label {
      color: var(--text-muted);
      font-size: 14px;
      margin-bottom: 10px;
    }

    .stat-value {
      font-size: 28px;
      color: var(--gold);
      font-weight: bold;
    }

    .data-table-container {
      background-color: rgba(22, 23, 24, 0.65);
      backdrop-filter: blur(12px);
      border-radius: 12px;
      border: 1px solid #333;
      overflow: hidden;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th {
      text-align: left;
      padding: 15px 25px;
      color: var(--gold);
      font-size: 14px;
      text-transform: uppercase;
      border-bottom: 1px solid #333;
    }

    td {
      padding: 15px 25px;
      border-bottom: 1px solid #222;
      font-size: 15px;
    }

    tr:last-child td {
      border-bottom: none;
    }

    .status-badge {
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: bold;
    }

    .status-pending {
      background-color: #ffd70020;
      color: #ffd700;
    }

    .status-confirmed {
      background-color: #00ff0020;
      color: #00ff00;
    }

    .btn-action {
      background-color: transparent;
      color: var(--gold);
      border: 1px solid var(--gold);
      padding: 8px 16px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 13px;
      font-weight: 600;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      display: inline-flex;
      align-items: center;
      gap: 5px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .btn-action:hover {
      background-color: var(--gold);
      color: var(--bg-dark);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(228, 197, 144, 0.3);
    }

    .btn-view {
      border-color: #3498db;
      color: #3498db;
    }

    .btn-view:hover {
      background-color: #3498db;
      color: white;
      border-color: #3498db;
      box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
    }

    .btn-delete {
      border-color: #e74c3c;
      color: #e74c3c;
    }

    .btn-delete:hover {
      background-color: #e74c3c;
      color: white;
      border-color: #e74c3c;
      box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
    }

    .button-group {
      display: flex;
      gap: 10px;
      align-items: center;
    }

    /* Modal Styling */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.8);
      z-index: 2000;
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background: var(--card-bg);
      border: 1px solid var(--gold);
      padding: 30px;
      border-radius: 12px;
      width: 400px;
      position: relative;
    }

    .modal-close {
      position: absolute;
      top: 10px;
      right: 20px;
      font-size: 24px;
      cursor: pointer;
      color: var(--gold);
    }

    .modal-title {
      font-family: 'Forum', serif;
      color: var(--gold);
      margin-bottom: 20px;
    }

    .modal-item {
      margin-bottom: 10px;
      font-size: 15px;
    }

    .modal-label {
      color: var(--text-muted);
      font-weight: bold;
    }


    .chat-reply-box {
      margin-top: 10px;
      display: flex;
      gap: 10px;
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
            <div class="button-group">
              <button class="btn-action btn-view" onclick="viewRes(${JSON.stringify(r).replace(/"/g, '&quot;')})">
                <ion-icon name="eye-outline"></ion-icon> View
              </button>
              <button class="btn-action" onclick="handleRes(${r.id}, 'confirmed')">
                <ion-icon name="checkmark-outline"></ion-icon> Confirm
              </button>
              <button class="btn-action btn-delete" onclick="deleteRes(${r.id})">
                <ion-icon name="trash-outline"></ion-icon> Delete
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
            <div class="button-group">
              <button class="btn-action" onclick="sendMail('${s.email}')">
                <ion-icon name="mail-outline"></ion-icon> Send Offer
              </button>
              <button class="btn-action btn-delete" onclick="deleteSub(${s.id})">
                <ion-icon name="trash-outline"></ion-icon> Remove
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