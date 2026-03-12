<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sidebar</title>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet"/>
  <style>


    /* ── Sidebar ── */
    .sidebar {
      width: 230px;
      min-height: 100vh;
      background: #1e2a45;
      display: flex;
      flex-direction: column;
      padding: 24px 16px;
      position: fixed;
      top: 0;
      left: 0;
      bottom: 0;
    }

    /* Logo */
    .logo {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 4px 8px 28px;
      border-bottom: 1.5px solid rgba(255,255,255,.08);
      margin-bottom: 20px;
    }

    .logo-icon {
      width: 36px;
      height: 36px;
      background: #6c7ef8;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .logo-icon svg { width: 20px; height: 20px; color: white; }

    .logo-text {
      display: flex;
      flex-direction: column;
      line-height: 1.1;
    }

    .logo-text strong {
      font-size: 16px;
      font-weight: 800;
      color: white;
      letter-spacing: .3px;
    }

    .logo-text span {
      font-size: 11px;
      font-weight: 600;
      color: #6c7ef8;
      letter-spacing: .4px;
    }

    /* Nav */
    .nav-label {
      font-size: 10.5px;
      font-weight: 800;
      color: #4a5878;
      letter-spacing: .8px;
      text-transform: uppercase;
      padding: 0 10px;
      margin-bottom: 6px;
    }

    .sidebar ul {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 2px;
      flex: 1;
    }

    .sidebar ul li a {
      display: flex;
      align-items: center;
      gap: 11px;
      padding: 10px 12px;
      border-radius: 11px;
      text-decoration: none;
      color: #8a98bb;
      font-size: 14px;
      font-weight: 700;
      transition: background .18s, color .18s;
    }

    .sidebar ul li a svg {
      width: 18px;
      height: 18px;
      flex-shrink: 0;
      opacity: .8;
    }


    .sidebar ul li.active a {
      background: #6c7ef8;
      color: white;
    }

    .sidebar ul li.active a svg { opacity: 1; }

    /* spacer para empurrar logout */
    .spacer { flex: 1; }

    /* logout separado */
    .sidebar-footer {
      border-top: 1.5px solid rgba(255,255,255,.08);
      padding-top: 16px;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    /* User card */
    .user-card {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 12px;
      background: rgba(255,255,255,.05);
      border-radius: 12px;
      border: 1.5px solid rgba(255,255,255,.07);
    }

    .avatar {
      width: 36px;
      height: 36px;
      border-radius: 10px;
      background: linear-gradient(135deg, #6c7ef8, #4f5de4);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      font-weight: 800;
      color: white;
      flex-shrink: 0;
    }

    .user-info { flex: 1; overflow: hidden; }

    .user-info strong {
      display: block;
      font-size: 13px;
      font-weight: 800;
      color: #dde3f8;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .user-info span {
      font-size: 11px;
      font-weight: 600;
      color: #4a5878;
    }

    /* logout btn */
    .btn-logout {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 9px 12px;
      border-radius: 11px;
      text-decoration: none;
      color: #8a98bb;
      font-size: 13px;
      font-weight: 700;
      transition: background .18s, color .18s;
      cursor: pointer;
      background: none;
      border: none;
      font-family: 'Nunito', sans-serif;
      width: 100%;
      text-align: left;
    }

    .btn-logout svg { width: 17px; height: 17px; flex-shrink: 0; }

    .btn-logout:hover {
      background: rgba(239,68,68,.12);
      color: #f87171;
    }

    /* demo page area */
    .page-content {
      margin-left: 230px;
      flex: 1;
      padding: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #7a85a3;
      font-size: 15px;
      font-weight: 700;
    }
  </style>
</head>
<body>

<div class="sidebar">

  <!-- Logo -->
  <div class="logo">
    <div class="logo-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
        <polyline points="9 22 9 12 15 12 15 22"/>
      </svg>
    </div>
    <div class="logo-text">
      <strong>Gestão</strong>
      <span>Dashboard</span>
    </div>
  </div>

  <!-- Nav label -->
  <div class="nav-label">Menu</div>

  <!-- Nav links -->
  <ul>
    <li class="active">
      <a href="dashboard.php">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
          <rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>
        </svg>
        Dashboard
      </a>
    </li>

    <li>
      <a href="clientes.php">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
          <circle cx="9" cy="7" r="4"/>
          <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
        </svg>
        Clientes
      </a>
    </li>

    <li>
      <a href="produtos.php">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/>
          <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
          <line x1="12" y1="22.08" x2="12" y2="12"/>
        </svg>
        Produtos
      </a>
    </li>

    <li>
      <a href="pedidos.php">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
          <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/>
        </svg>
        Pedidos
      </a>
    </li>

    
  </ul>

  <!-- Footer -->
  <div class="sidebar-footer">

    <!-- User card -->
    <div class="user-card">
      <div class="avatar">KC</div>
      <div class="user-info">
        <strong>Kayky Costa</strong>
        <span>Administrador</span>
      </div>
    </div>

    <!-- Logout -->
    <button class="btn-logout" onclick="abrirModalLogout()">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
        <polyline points="16 17 21 12 16 7"/>
        <line x1="21" y1="12" x2="9" y2="12"/>
      </svg>
      Sair da conta
    </button>

  </div>

</div>


<script>
  function abrirModalLogout() {
    alert('Modal de logout aqui!');
  }

  // marca item ativo pelo href
  const links = document.querySelectorAll('.sidebar ul li a');
  links.forEach(link => {
    link.parentElement.classList.remove('active');
    if (link.getAttribute('href') === window.location.pathname.split('/').pop()) {
      link.parentElement.classList.add('active');
    }
  });
</script>

</body>
</html>