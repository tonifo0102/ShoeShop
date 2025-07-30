<header class="headerApp">
    <nav class="navbar-header">
        <div class="logo d-flex align-items-center">
            <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none">
                <img src="{{ asset('Images/' . $LOGO) }}" alt="Logo" class="img-fluid custom-logo">
                <span class="ms-2">{{ $TITLE }}</span>
            </a>
        </div>
        <form class="search" method="GET" action="{{ route('search') }}">
            <input type="text" placeholder="Tìm kiếm" name="keyword" required>
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
        <div class="box">
            <a href="{{ route('dashboard.orders') }}"><i class="fa fa-shopping-cart"></i></a>
            @if ($LOGGED)
            <a href="{{ route('dashboard.info') }}"><i class="fa fa-user"></i></a>
            @else
            <a href="{{ route('login') }}"><i class="fa fa-user-o"></i></a>
            @endif
        </div>
    </nav>
</header>