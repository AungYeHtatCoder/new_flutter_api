    <!-- Header -->
    <header class="nav-fixed shadow shadow-none">
        <!-- Nav -->
        <div class="nav container">
          <!-- logo -->
          <a href="{{ url('/') }}" class="logo">Delight <span>Myanmar</span></a>
          <!-- login Btn -->
          @guest
          <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light">Login</a>
          @endguest
          @auth
          <a class="btn btn-sm btn-outline-light" href="" onclick="event.preventDefault(); document.getElementById('logout').submit();" class="login">Logout</a>
          <form class="d-none" id="logout" action="{{ route('logout') }}" method="post">
            @csrf
           </form>
          @endauth
        </div>
      </header>
