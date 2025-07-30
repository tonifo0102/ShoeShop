<nav class="dashboard">
    <div class="header">
        <a href="{{ route('home') }}">
            <img src="{{ asset('Images/' . $LOGO) }}" alt="Logo">
            <span>{{ $TITLE }}</span>
        </a>
    </div>
    <div class="menu">
        @foreach ($FEATURES as $FEATURE)
            <a href="{{ route($FEATURE->route) }}" @if ($URI == $FEATURE->route) {{ 'class=active' }} @endif>
                <i class="{{ $FEATURE->icon }}"></i>
                <span>{{ $FEATURE->name }}</span>
            </a>
        @endforeach
    </div>
</nav>
