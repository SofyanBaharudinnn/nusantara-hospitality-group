<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Dashboard') — NHG</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  @stack('styles')
</head>
<body>
  <div class="mesh-bg"></div>

  <div class="dashboard-layout z1">

    {{-- ── SIDEBAR ── --}}
    <aside class="sidebar" id="sidebar">
      <div class="sidebar-header">
        <div class="sidebar-logo-icon">🏨</div>
        <div>
          <div class="sidebar-logo-text">NHG</div>
          <div class="sidebar-logo-sub">Nusantara Hospitality Group</div>
        </div>
      </div>

      <div style="flex:1;overflow-y:auto;padding-bottom:1rem;">

        @if(Auth::user()->role === 'admin')

          {{-- Analitik --}}
          <div class="sidebar-section">
            <div class="sidebar-section-label">Analitik</div>
            <ul class="sidebar-nav">
              <li>
                <a href="{{ route('admin.dashboard') }}"
                  class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                  <span class="nav-icon">📊</span> Dashboard
                </a>
              </li>
              <li>
                <a href="{{ route('admin.occupancy') }}"
                  class="{{ request()->routeIs('admin.occupancy') ? 'active' : '' }}">
                  <span class="nav-icon">🏨</span> Okupansi Hotel
                </a>
              </li>
              <li>
                <a href="{{ route('admin.customers') }}"
                  class="{{ request()->routeIs('admin.customers') ? 'active' : '' }}">
                  <span class="nav-icon">👥</span> Customer Behavior
                </a>
              </li>
              <li>
                <a href="{{ route('admin.trends') }}"
                  class="{{ request()->routeIs('admin.trends') ? 'active' : '' }}">
                  <span class="nav-icon">📈</span> Seasonal Trend
                </a>
              </li>
              <li>
                <a href="{{ route('admin.schema') }}"
                  class="{{ request()->routeIs('admin.schema') ? 'active' : '' }}">
                  <span class="nav-icon">🗄️</span> Star Schema
                </a>
              </li>
            </ul>
          </div>

          {{-- CRUD Data --}}
          <div class="sidebar-section">
            <div class="sidebar-section-label">Kelola Data</div>
            <ul class="sidebar-nav">
              <li>
                <a href="{{ route('admin.bookings.index') }}"
                  class="{{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                  <span class="nav-icon">📋</span> Kelola Booking
                </a>
              </li>
              <li>
                <a href="{{ route('admin.hotels.index') }}"
                  class="{{ request()->routeIs('admin.hotels.*') ? 'active' : '' }}">
                  <span class="nav-icon">🏩</span> Kelola Hotel
                </a>
              </li>
              <li>
                <a href="{{ route('admin.tamu.index') }}"
                  class="{{ request()->routeIs('admin.tamu.*') ? 'active' : '' }}">
                  <span class="nav-icon">🧑‍💼</span> Kelola Tamu
                </a>
              </li>
            </ul>
          </div>

          {{-- Manajemen --}}
          <div class="sidebar-section">
            <div class="sidebar-section-label">Manajemen</div>
            <ul class="sidebar-nav">
              <li>
                <a href="{{ route('admin.users') }}"
                  class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">
                  <span class="nav-icon">👤</span> Kelola User
                </a>
              </li>
              <li>
                <a href="{{ route('admin.reports') }}"
                  class="{{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                  <span class="nav-icon">📑</span> Laporan
                </a>
              </li>
            </ul>
          </div>

        @else

          {{-- User Menu --}}
          <div class="sidebar-section">
            <div class="sidebar-section-label">Utama</div>
            <ul class="sidebar-nav">
              <li>
                <a href="{{ route('user.dashboard') }}"
                  class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                  <span class="nav-icon">📊</span> Dashboard
                </a>
              </li>
              <li>
                <a href="{{ route('user.occupancy') }}"
                  class="{{ request()->routeIs('user.occupancy') ? 'active' : '' }}">
                  <span class="nav-icon">🏨</span> Okupansi
                </a>
              </li>
              <li>
                <a href="{{ route('user.trends') }}"
                  class="{{ request()->routeIs('user.trends') ? 'active' : '' }}">
                  <span class="nav-icon">📈</span> Tren Musiman
                </a>
              </li>
            </ul>
          </div>

        @endif
      </div>

      {{-- Footer Sidebar --}}
      <div class="sidebar-footer">
  <a href="{{ route('profile.show') }}"
    style="display:flex;align-items:center;gap:10px;margin-bottom:12px;text-decoration:none;padding:8px;border-radius:var(--radius-sm);transition:background 0.2s;"
    onmouseover="this.style.background='rgba(124,58,237,0.1)'"
    onmouseout="this.style.background='transparent'">
    <div class="avatar">{{ strtoupper(substr(Auth::user()->name,0,2)) }}</div>
    <div style="flex:1;min-width:0;">
      <div style="font-size:0.85rem;font-weight:600;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
        {{ Auth::user()->name }}
      </div>
      <div style="font-size:0.72rem;color:var(--text-muted);">
        {{ ucfirst(Auth::user()->role) }} — Ver Profil
      </div>
    </div>
    <span style="font-size:0.75rem;color:var(--text-muted);">›</span>
  </a>
  <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="btn btn-outline btn-sm"
      style="width:100%;justify-content:center;">
      🚪 Keluar
    </button>
  </form>
</div>
    </aside>

    {{-- ── MAIN CONTENT ── --}}
    <div class="dashboard-main">

      {{-- Topbar --}}
      <div class="dashboard-topbar">
        <div style="font-family:var(--font-display);font-size:1.1rem;font-weight:600;color:var(--text-primary);">
          @yield('page-title', 'Dashboard')
        </div>
        <div style="display:flex;align-items:center;gap:12px;">
          <div style="font-size:0.82rem;color:var(--text-muted);">
            {{ now()->isoFormat('D MMM YYYY') }}
          </div>
          <a href="{{ route('profile.show') }}"
            style="display:flex;align-items:center;gap:8px;text-decoration:none;">
            <div class="badge badge-purple" style="cursor:pointer;">
              {{ ucfirst(Auth::user()->role) }}
            </div>
            <div class="avatar" style="width:30px;height:30px;font-size:0.7rem;">
              {{ strtoupper(substr(Auth::user()->name,0,2)) }}
            </div>
          </a>
        </div>
      </div>

      {{-- Page Content --}}
      <div class="dashboard-content">
        @if(session('success'))
          <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
          <div class="alert alert-error">❌ {{ session('error') }}</div>
        @endif

        @yield('content')
      </div>

    </div>

  </div>

  @stack('scripts')
</body>
</html>