<!-- Navbar para móvil -->
<div class="md:hidden navbar bg-base-100 shadow-sm fixed top-0 left-0 right-0 z-50">
  <div class="flex-1">
    <div class="drawer">
      <input id="my-drawer-1" type="checkbox" class="drawer-toggle" />
      <div class="drawer-content">
        <!-- Page content here -->
        <label for="my-drawer-1" class="btn drawer-button">
          <x-heroicon-s-bars-3 class="w-5 h-5" />
        </label>
      </div>
      <div class="drawer-side">
        <label for="my-drawer-1" aria-label="close sidebar" class="drawer-overlay"></label>
        <!-- Logo / Título -->
        
        <ul class="menu bg-base-200 min-h-full w-80 p-4">
          <!-- Sidebar content here -->
          <x-inicio.sidebar-contenido />
        </ul>
      </div>
    </div>
  </div>


  <div class="flex-none">
    <ul class="menu menu-horizontal flex items-center justify-start">
      <li><!-- Logo y texto -->
        <a class="btn" href="#">
          <img src="{{ asset('images/logo/cow.webp') }}" alt="Logo" class="w-9 h-9">
          <span>Acopio</span>
        </a>
      </li>
      <li>
        <details>
          <summary>Menu Principal</summary>
          <ul class="bg-base-100 rounded-t-none p-2">
            <li><a href="{{ route('inicio') }}" wire:navigate>Inicio</a></li>
            <li><a href="{{ route('localidad') }}" wire:navigate>Comarcas</a></li>
            <li><a href="{{ route('productor') }}" wire:navigate>Productores</a></li>
            <li><a href="{{ route('acopio') }}" wire:navigate>Acopio</a></li>            
            <li><a href="{{ route('logout') }}" wire:navigate>{{ Auth::user()->name }} (Salir)</a></li>            
          </ul>
        </details>
      </li>
    </ul>
  </div>


</div>