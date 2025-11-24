<!-- Navbar DaisyUI -->
<!-- Navbar para desktop -->
<nav class="hidden md:flex navbar bg-base-100 shadow-lg px-8">
  <!-- Logo / Título -->
  <div class="flex-1">
    <div class="flex space-x-4">
      <!-- Botón drawer -->
      <div class="xl:hidden drawer">
        <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content">
          <label for="my-drawer-2" class="btn drawer-button">
            <x-heroicon-s-bars-3 class="w-5 h-5" />
          </label>
        </div>

        <div class="drawer-side">
          <label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay"></label>

          <ul class="menu bg-base-200 min-h-full w-80 p-4">
            <x-localidad.sidebar-contenido />
          </ul>
        </div>
      </div>

      <!-- Logo y texto -->
      <a class="btn btn-ghost normal-case text-xl flex items-center space-x-2" href="#">
        <img src="{{ asset('storage/images/logo/cow.webp') }}" alt="Logo" class="w-11 h-11">
        <span>Acopio</span>
      </a>
    </div>
  </div>

  <!-- Menú horizontal -->
  <div class="flex flex-1 justify-center">
    <ul class="menu menu-horizontal p-0 text-md">
      <li><a href="{{ route('inicio') }}" wire:navigate class="hover:bg-gray-200 hover:text-black">Inicio</a></li>
      <li><a href="{{ route('localidad') }}" wire:navigate class="bg-primary text-white hover:bg-gray-200 hover:text-black">
          <img src="{{ asset('storage/images/icons/nicaragua.png') }}" class="filter invert" />
          Comarcas
        </a>
      </li>
      <li>
        <a href="{{ route('productor') }}" wire:navigate class="hover:bg-gray-200 hover:text-black">
          <img src="{{ asset('storage/images/icons/agricultor.png') }}" class="filter brightness-0 dark:brightness-0 dark:invert" />
          Productores
        </a>
      </li>

      <li><a href="#" class="hover:bg-gray-200">Reportes</a></li>
    </ul>
  </div>

  

  <div class="dropdown">
    <div tabindex="0" role="button" class="btn m-1">{{ Auth::user()->name }} ⬇️</div>
    <ul tabindex="-1" class="menu dropdown-content bg-base-100 rounded-box">
      <li>
        <a href="#" wire:navigate class="btn btn-ghost justify-between hover:bg-primary" title="Cerrar sesión">
          <x-heroicon-s-user class="w-5 h-5" />
          Perfil
        </a>
        <a href="{{ route('logout') }}" wire:navigate class="btn btn-ghost justify-between hover:bg-primary"
          title="Cerrar sesión">
          <x-heroicon-s-arrow-right-start-on-rectangle class="w-5 h-5" />
          Salir
        </a>
      </li>
    </ul>
  </div>

</nav>