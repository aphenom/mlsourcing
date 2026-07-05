<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('adminTheme/assets/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('adminTheme/assets/img/favicon.png') }}">
  <!-- SEO PRINCIPAL -->
  <title>ML SOURCING | Plateforme de sourcing & approvisionnement direct usine</title>

  <meta name="description" content="ML SOURCING est une plateforme de sourcing B2B spécialisée dans l’approvisionnement direct usine. Nous connectons les entreprises aux fabricants pour garantir qualité et prix d’usine.">

  <meta name="keywords" content="sourcing, plateforme de sourcing, approvisionnement industriel, sourcing B2B, prix usine, fournisseurs fabricants, sourcing international">

  <meta name="robots" content="index, follow">
  <meta name="author" content="ML SOURCING">

  <!-- RESPONSIVE -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- OPEN GRAPH (réseaux sociaux) -->
  <meta property="og:title" content="ML SOURCING | Sourcing direct usine au prix fabricant">
  <meta property="og:description" content="Plateforme de sourcing reliant directement les entreprises aux usines pour des produits de haute qualité aux prix d’usine.">
  <meta property="og:type" content="website">
  <meta property="og:locale" content="fr_FR">

  <!-- TWITTER CARD -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="ML SOURCING | Plateforme de sourcing B2B">
  <meta name="twitter:description" content="Approvisionnement direct fabricant, sans intermédiaire, pour des produits qualitatifs aux prix d’usine.">
  <meta name="csrf-token" content="{{ csrf_token() }}">


  <!-- Fonts and icons -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="{{ asset('adminTheme/assets/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('adminTheme/assets/css/nucleo-svg.css') }}" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

  <!-- CSS Files -->
  <link id="pagestyle" href="{{ asset('adminTheme/assets/css/soft-ui-dashboard.css?v=1.0.7') }}" rel="stylesheet" />

  <!-- Mobile-responsive overrides -->
  <style>
    /* ── Sidebar overlay on mobile ─────────────────────────────── */
    .sidenav-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,.45);
      z-index: 994;
      cursor: pointer;
    }
    @media (max-width: 1199.98px) {
      body.g-sidenav-pinned .sidenav-overlay { display: block; }
      /* Un-pin sidebar when overlay is shown so it can be dismissed */
    }

    /* ── Main content: allow scroll on all screen sizes ───────── */
    .main-content {
      max-height: none !important;
      height: auto !important;
      overflow-y: visible !important;
    }

    /* ── DataTables: horizontal scroll on small screens ───────── */
    .dataTables_wrapper {
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
    }
    table.dataTable { min-width: 500px; }

    /* ── Modals: fit viewport on mobile ───────────────────────── */
    @media (max-width: 767.98px) {
      .modal-dialog {
        margin: 0.5rem auto;
        max-width: calc(100vw - 1rem);
      }
      .modal-body { max-height: 75vh; overflow-y: auto; }

      /* Navbar top bar wrapping */
      .navbar-main .container-fluid { flex-wrap: nowrap; overflow-x: auto; }

      /* Cards grid spacing */
      .card { margin-bottom: 1rem; }

      /* Sidebar: full-width overlay on xs/sm */
      .sidenav {
        width: 100vw !important;
        max-width: 280px;
        border-radius: 0 !important;
        margin: 0 !important;
      }

      /* Currency/lang buttons in topbar */
      .navbar-main .nav-item.dropdown .btn { font-size: .75rem; padding: 6px 10px; }
    }

    /* ── Ensure body background extends below fold ─────────────── */
    html, body { min-height: 100vh; }
  </style>

  <!-- DataTables CSS -->
  <!-- Nepcha Analytics -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>

  <!-- Include jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <link rel="stylesheet" href="{{ asset('adminTheme/assets/css/soft-ui-icons.css') }}">

  
  <link href="https://cdn.datatables.net/2.1.2/css/dataTables.dataTables.min.css" rel="stylesheet" />
  <link href="https://cdn.datatables.net/select/2.0.3/css/select.dataTables.css" rel="stylesheet" />
  <link href="https://code.highcharts.com/css/highcharts.css" rel="stylesheet" />
  

  <script src="https://cdn.datatables.net/2.1.2/js/dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/select/2.0.3/js/dataTables.select.js"></script>
  <script src="https://cdn.datatables.net/select/2.0.3/js/select.dataTables.js"></script>
  <script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

  

  <!-- DataTables JS -->

  <style>
    /* ── Notification dropdown : plein écran sur mobile ─────────────────── */
    @media (max-width: 767.98px) {
      #notifDropdown ~ .dropdown-menu {
        position: fixed !important;
        top: 58px !important;
        left: 10px !important;
        right: 10px !important;
        min-width: auto !important;
        max-width: none !important;
        max-height: 80vh;
        overflow-y: auto;
        border-radius: 12px !important;
        box-shadow: 0 8px 32px rgba(0,0,0,.18) !important;
        z-index: 9999 !important;
      }
    }
  </style>
</head>



<body class="g-sidenav-show bg-gray-100">
  
@include('auth.theme.nav-side')

  {{-- Overlay that closes the mobile sidebar when tapped --}}
  <div class="sidenav-overlay" id="sidenav-overlay"></div>

  <main class="main-content position-relative border-radius-lg">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          
          <h6 class="font-weight-bolder mb-0">@yield('title', 'Dashboard')</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            
          </div>
          <ul class="navbar-nav  justify-content-end">

            <!-- Tarifs -->
            <li class="nav-item pe-2">
              <a href="{{ asset('documents/tarifs-frais-service.pdf') }}"
                 target="_blank" rel="noopener" download
                 class="nav-link text-body p-1 btn btn-outline-info"
                 style="border-radius: 25px; padding: 8px 16px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-download me-1" viewBox="0 0 16 16">
                  <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                  <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
                </svg> {{ __('global.menu_tarifs') }}
              </a>
            </li>

            <!-- Langue -->
            <li class="nav-item dropdown pe-2">
              <a href="#"
                class="nav-link text-body p-0 position-relative"
                id="dropdownMenuButtonLang"
                data-bs-toggle="dropdown"
                aria-expanded="false">
                @if(Session::get('locale')=="en")
                <img width="16" alt="Tinker Tailwind HTML Admin Template" src="https://flagcdn.com/w40/gb.png">
                @else
                <img width="16" alt="Tinker Tailwind HTML Admin Template" src="https://flagcdn.com/w40/fr.png">
                @endif
              </a>

              <!-- MENU DEROULANT -->
              <ul class="dropdown-menu dropdown-menu-end px-2 py-3"
                  aria-labelledby="dropdownMenuButtonLang">
                  <li class="p-2">
                    <div class="font-medium">{{ __('global.choix_langue') }}</div>
                    <div class="text-xs text-white/70 mt-0.5 dark:text-slate-500"></div>
                </li>
                <li>
                    <hr class="dropdown-divider border-white/[0.08]">
                </li>
                <li>
                    <a href="{{ route('switch_language') }}?locale=fr" class="dropdown-item hover:bg-white/5">
                        <i data-feather="flag" class="w-4 h-4 mr-2"></i> Français
                    </a>
                </li>

                <li>
                    <a href="{{ route('switch_language') }}?locale=en" class="dropdown-item hover:bg-white/5">
                        <i data-feather="flag" class="w-4 h-4 mr-2"></i> English
                    </a>
                </li>
              </ul>
            </li>

            <!-- Devise -->
            <li class="nav-item dropdown pe-2">
              <a href="#"
                class="nav-link text-body p-1 position-relative btn btn-info"
                id="dropdownMenuButtonDev"
                data-bs-toggle="dropdown"
                aria-expanded="false" style="border-radius: 25px; padding: 15px 20px;">
                <!--
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cash" viewBox="0 0 16 16">
                  <path d="M8 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4"/>
                  <path d="M0 4a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V6a2 2 0 0 1-2-2z"/>
                </svg> 
                -->
                @php $cur = session('currency','XOF'); @endphp
                {{ $cur === 'USD' ? 'USD $' : ($cur === 'RMB' ? 'RMB ¥' : 'FCFA') }}
              </a>

              <!-- MENU DEROULANT -->
              <ul class="dropdown-menu dropdown-menu-end px-2 py-3"
                  aria-labelledby="dropdownMenuButtonDev">
                  <li class="p-2">
                    <div class="font-medium">{{ __('global.choix_devise') }}</div>
                    <div class="text-xs text-white/70 mt-0.5 dark:text-slate-500"></div>
                </li>
                <li>
                    <hr class="dropdown-divider border-white/[0.08]">
                </li>
                <li>
                    <a href="{{ route('switch_currency') }}?currency=XOF" class="dropdown-item hover:bg-white/5">
                    XOF (FCFA)
                    </a>
                </li>

                <li>
                    <a href="{{ route('switch_currency') }}?currency=USD" class="dropdown-item hover:bg-white/5">
                    USD ($)
                    </a>
                </li>

                <li>
                    <a href="{{ route('switch_currency') }}?currency=RMB" class="dropdown-item hover:bg-white/5">
                       RMB (¥)       
                    </a>
                </li>
              </ul>
            </li>
 
            <!-- CLOCHE NOTIFICATIONS -->
            @php
                $unreadNotifs = Auth::user()->unreadNotifications()->take(5)->get();
                $unreadCount  = Auth::user()->unreadNotifications()->count();
            @endphp
            <li class="nav-item dropdown pe-2">
              <a href="#"
                class="nav-link text-body p-0 position-relative"
                id="notifDropdown"
                data-bs-toggle="dropdown"
                aria-expanded="false">

                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                  <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4 4 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4 4 0 0 0-3.203-3.92zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5 5 0 0 1 13 6c0 .88.32 4.2 1.22 6"/>
                </svg>

                @if($unreadCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                      style="font-size:9px; padding:3px 5px;">
                  {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                </span>
                @endif
              </a>

              <ul class="dropdown-menu dropdown-menu-end py-0 overflow-hidden"
                  style="min-width:340px; border-radius:12px; box-shadow:0 8px 24px rgba(0,0,0,.12);"
                  aria-labelledby="notifDropdown">

                {{-- Header --}}
                <li class="px-3 py-2 d-flex justify-content-between align-items-center border-bottom bg-light">
                  <span class="text-sm font-weight-bold">{{ __('pages.notifications') }}</span>
                  @if($unreadCount > 0)
                  <form method="POST" action="{{ route('notifications.markAllRead') }}" class="mb-0">
                    @csrf
                    <button type="submit" class="btn btn-link p-0 text-xs text-muted" style="font-size:11px;">
                      {{ __('pages.mark_all_read') }}
                    </button>
                  </form>
                  @endif
                </li>

                {{-- Notification items --}}
                @forelse($unreadNotifs as $notif)
                <li>
                  <a class="dropdown-item px-3 py-2 border-bottom"
                     href="{{ route('notifications.markRead', $notif->id) }}"
                     style="white-space:normal;">
                    <div class="d-flex align-items-start gap-2">
                      <span class="mt-1 flex-shrink-0"
                            style="width:8px;height:8px;border-radius:50%;background:#00A752;display:inline-block;"></span>
                      <div>
                        <div class="text-sm font-weight-bold mb-0">{{ is_array($notif->data['subject'] ?? '') ? '' : ($notif->data['subject'] ?? '') }}</div>
                        <div class="text-xs text-secondary mb-1" style="line-height:1.3;">
                          {{ \Illuminate\Support\Str::limit(is_array($notif->data['message'] ?? '') ? '' : ($notif->data['message'] ?? ''), 70) }}
                        </div>
                        <div class="text-xs text-muted">
                          <i class="fa fa-clock me-1"></i>{{ $notif->created_at->diffForHumans() }}
                        </div>
                      </div>
                    </div>
                  </a>
                </li>
                @empty
                <li class="text-center py-4 text-muted text-sm">
                  <i class="fa fa-bell-slash me-1"></i>{{ __('pages.no_new_notifications') }}
                </li>
                @endforelse

                {{-- Footer --}}
                <li class="text-center py-2 bg-light border-top">
                  <a href="{{ route('notifications.index') }}" class="text-sm font-weight-bold" style="color:#00A752;">
                    {{ __('pages.view_all_notifications') }}
                  </a>
                </li>
              </ul>
            </li>

            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
    @yield('content')
  </main>

  <!--   Core JS Files   -->
  @stack('scripts')

  <script src="{{ asset('adminTheme/assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('adminTheme/assets/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('adminTheme/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('adminTheme/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
  <script src="{{ asset('adminTheme/assets/js/plugins/chartjs.min.js') }}"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{ asset('adminTheme/assets/js/soft-ui-dashboard.min.js?v=1.0.7') }}"></script>
  <script>
    // Close sidebar when overlay is tapped on mobile
    (function () {
      var overlay = document.getElementById('sidenav-overlay');
      var toggler = document.getElementById('iconNavbarSidenav');
      if (overlay && toggler) {
        overlay.addEventListener('click', function () {
          toggler.click();
        });
      }
    })();
  </script>
</body>

</html>