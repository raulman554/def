<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Panel Admin') | Lotoup</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    :root {
      --sidebar-width: 260px;
      --sidebar-bg: #1a2236;
      --sidebar-hover: #283453;
      --primary-color: #3b82f6;
      --text-light: #e2e8f0;
      --text-muted: #94a3b8;
      --border-color: rgba(255, 255, 255, 0.1);
    }
    
    body {
      font-family: 'Inter', system-ui, sans-serif;
      display: flex;
      margin: 0;
      min-height: 100vh;
      background-color: #f8fafc;
    }
    
    .sidebar {
      width: var(--sidebar-width);
      background-color: var(--sidebar-bg);
      color: var(--text-light);
      position: fixed;
      height: 100vh;
      overflow-y: auto;
      transition: all 0.3s;
      z-index: 1000;
    }
    
    .sidebar-header {
      padding: 1.5rem;
      text-align: center;
      border-bottom: 1px solid var(--border-color);
    }
    
    .sidebar-header h4 {
      margin: 0;
      font-weight: 600;
      color: white;
    }
    
    .sidebar-menu {
      padding: 1rem 0;
      list-style: none;
      margin: 0;
    }
    
    .menu-item {
      margin-bottom: 0.25rem;
    }
    
    .menu-link {
      display: flex;
      align-items: center;
      padding: 0.75rem 1.5rem;
      color: var(--text-muted);
      text-decoration: none;
      transition: all 0.2s;
      border-left: 3px solid transparent;
      font-weight: 500;
    }
    
    .menu-link:hover, .menu-link.active {
      background-color: var(--sidebar-hover);
      color: white;
      border-left-color: var(--primary-color);
    }
    
    .menu-link i {
      font-size: 1.1rem;
      margin-right: 0.75rem;
      width: 20px;
      text-align: center;
    }
    
    .has-submenu > .menu-link::after {
      content: "\F282";
      font-family: "bootstrap-icons";
      margin-left: auto;
      transition: transform 0.3s;
    }
    
    .has-submenu.open > .menu-link::after {
      transform: rotate(90deg);
    }
    
    .submenu {
      list-style: none;
      padding: 0;
      margin: 0;
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.3s ease;
    }
    
    .has-submenu.open .submenu {
      max-height: 500px;
    }
    
    .submenu-link {
      padding: 0.5rem 0 0.5rem 3.25rem;
      font-size: 0.9rem;
      display: block;
      color: var(--text-muted);
      text-decoration: none;
      transition: all 0.2s;
    }
    
    .submenu-link:hover, .submenu-link.active {
      color: white;
      background-color: var(--sidebar-hover);
    }
    
    .content-wrapper {
      flex: 1;
      margin-left: var(--sidebar-width);
      width: calc(100% - var(--sidebar-width));
      transition: all 0.3s;
    }
    
    .content-header {
      padding: 1.5rem;
      background-color: white;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .content-header h2 {
      margin: 0;
      font-size: 1.5rem;
      font-weight: 600;
    }
    
    .content {
      padding: 1.5rem;
    }
    
    .card {
      border: none;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      border-radius: 0.5rem;
      margin-bottom: 1.5rem;
    }
    
    .card-header {
      background-color: white;
      border-bottom: 1px solid rgba(0, 0, 0, 0.05);
      font-weight: 600;
      padding: 1rem 1.25rem;
    }
    
    .logout-button {
      margin: 1.5rem;
      width: calc(100% - 3rem);
    }
    
    .user-dropdown {
      cursor: pointer;
    }
    
    /* Responsive */
    @media (max-width: 992px) {
      .sidebar {
        transform: translateX(-100%);
      }
      
      .sidebar.active {
        transform: translateX(0);
      }
      
      .content-wrapper {
        margin-left: 0;
        width: 100%;
      }
      
      .menu-toggle {
        display: block !important;
      }
    }
    
    .menu-toggle {
      display: none;
      background: none;
      border: none;
      color: #333;
      font-size: 1.25rem;
      cursor: pointer;
      margin-right: 1rem;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <div class="sidebar-header">
      <h4>LOTOUP ADMIN</h4>
    </div>
    
    <ul class="sidebar-menu">
      <!-- Dashboard -->
      <li class="menu-item">
        <a href="{{ route('admin.dashboard') }}" class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
          <i class="bi bi-speedometer2"></i> Dashboard
        </a>
      </li>
      
      <!-- SORTEOS -->
      <li class="menu-item has-submenu {{ request()->is('admin/tipos-sorteo*') || request()->is('admin/agenda*') || request()->is('admin/premios*') ? 'open' : '' }}">
        <a href="#" class="menu-link">
          <i class="bi bi-ticket-perforated"></i> SORTEOS
        </a>
        <ul class="submenu">
          <li>
            <a href="{{ route('tipos-sorteo.index') }}" class="submenu-link {{ request()->is('admin/tipos-sorteo') ? 'active' : '' }}">
              Cat¨¢logo
            </a>
          </li>
          <li>
            <a href="{{ route('instancias-sorteo.index') }}" class="submenu-link {{ request()->is('admin/instancias-sorteo*') ? 'active' : '' }}">
              Agenda
            </a>
          </li>
          <li>
            <a href="#" class="submenu-link {{ request()->is('admin/premios*') ? 'active' : '' }}">
              Premios
            </a>
          </li>
        </ul>
      </li>
      
      <!-- TICKETS -->
      <li class="menu-item has-submenu {{ request()->is('admin/tickets*') ? 'open' : '' }}">
        <a href="#" class="menu-link">
          <i class="bi bi-receipt"></i> TICKETS
        </a>
        <ul class="submenu">
          <li>
            <a href="#" class="submenu-link {{ request()->is('admin/tickets/pendientes*') ? 'active' : '' }}">
              Pendientes
            </a>
          </li>
          <li>
            <a href="#" class="submenu-link {{ request()->is('admin/tickets/activos*') ? 'active' : '' }}">
              Activos
            </a>
          </li>
          <li>
            <a href="#" class="submenu-link {{ request()->is('admin/tickets/resultados*') ? 'active' : '' }}">
              Resultados
            </a>
          </li>
        </ul>
      </li>
      
      <!-- FINANZAS -->
      <li class="menu-item has-submenu {{ request()->is('admin/finanzas*') ? 'open' : '' }}">
        <a href="#" class="menu-link">
          <i class="bi bi-cash-stack"></i> FINANZAS
        </a>
        <ul class="submenu">
          <li>
            <a href="#" class="submenu-link {{ request()->is('admin/finanzas/ingresos*') ? 'active' : '' }}">
              Ingresos
            </a>
          </li>
          <li>
            <a href="#" class="submenu-link {{ request()->is('admin/finanzas/retiros*') ? 'active' : '' }}">
              Retiros
            </a>
          </li>
          <li>
            <a href="#" class="submenu-link {{ request()->is('admin/finanzas/reportes*') ? 'active' : '' }}">
              Reportes
            </a>
          </li>
        </ul>
      </li>
      
      <!-- USUARIOS -->
      <li class="menu-item has-submenu {{ request()->is('admin/usuarios*') ? 'open' : '' }}">
        <a href="#" class="menu-link">
          <i class="bi bi-people"></i> USUARIOS
        </a>
        <ul class="submenu">
          <li>
            <a href="#" class="submenu-link {{ request()->is('admin/usuarios/clientes*') ? 'active' : '' }}">
              Clientes
            </a>
          </li>
          <li>
            <a href="#" class="submenu-link {{ request()->is('admin/usuarios/staff*') ? 'active' : '' }}">
              Staff
            </a>
          </li>
        </ul>
      </li>
      
      <!-- SISTEMA -->
      <li class="menu-item has-submenu {{ request()->is('admin/sistema*') ? 'open' : '' }}">
        <a href="#" class="menu-link">
          <i class="bi bi-gear"></i> SISTEMA
        </a>
        <ul class="submenu">
          <li>
            <a href="#" class="submenu-link {{ request()->is('admin/sistema/configuracion*') ? 'active' : '' }}">
              Configuraci¨®n
            </a>
          </li>
          <li>
            <a href="#" class="submenu-link {{ request()->is('admin/sistema/logs*') ? 'active' : '' }}">
              Logs
            </a>
          </li>
        </ul>
      </li>
    </ul>
    
    <form action="{{ route('admin.logout') }}" method="POST">
      @csrf
      <button type="submit" class="btn btn-danger logout-button">
        <i class="bi bi-box-arrow-right"></i> Cerrar Sesi¨®n
      </button>
    </form>
  </div>
  
  <div class="content-wrapper">
    <div class="content-header">
      <button class="menu-toggle">
        <i class="bi bi-list"></i>
      </button>
      <h2>@yield('header', 'Panel de Administraci¨®n')</h2>
      <div class="user-dropdown">
        <span class="text-dark">{{ auth('administrador')->user()->nombre ?? 'Administrador' }}</span>
      </div>
    </div>
    
    <div class="content">
      @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show">
          {{ session('status') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
      
      @yield('content')
    </div>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Toggle submenu
      const submenuItems = document.querySelectorAll('.has-submenu > .menu-link');
      
      submenuItems.forEach(item => {
        item.addEventListener('click', function(e) {
          e.preventDefault();
          const parent = this.parentElement;
          parent.classList.toggle('open');
        });
      });
      
      // Auto-open parent menu for active submenus
      const activeSubmenuItem = document.querySelector('.submenu-link.active');
      if (activeSubmenuItem) {
        activeSubmenuItem.closest('.has-submenu').classList.add('open');
      }
      
      // Toggle sidebar on mobile
      const menuToggle = document.querySelector('.menu-toggle');
      if (menuToggle) {
        menuToggle.addEventListener('click', function() {
          document.querySelector('.sidebar').classList.toggle('active');
        });
      }
    });
  </script>
</body>
</html>