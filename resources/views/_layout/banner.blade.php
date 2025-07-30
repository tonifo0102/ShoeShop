<nav class="banner">
    <div class="categories">
        @foreach ($CATEGORIES as $CATEGORY)
            <a href="{{ route("category.search", ['slug' => $CATEGORY->slug]) }}">{{ $CATEGORY->name }}</a>
        @endforeach
    </div>
    <div class="slide-banner slide">
        <div class="slide-item">
            <img src="{{ asset('Images/banner1.png') }}" alt="">
        </div>
        <div class="slide-item">
            <img src="{{ asset('Images/banner2.png') }}" alt="">
        </div>
        <div class="slide-item">
            <img src="{{ asset('Images/banner3.png') }}" alt="">
        </div>
    </div>
</nav>

<script>
    $(document).ready(function(){
        $('.slide-banner').slick({
            autoplay: true,
            autoplaySpeed: 5000,
            dots: true,
            arrows: false,
            fade: true,
            cssEase: 'linear',
            pauseOnHover: false,
            slidesToShow: 1,
            slidesToScroll: 1,
            infinite: true,
            speed: 500,
            adaptiveHeight: true,
            lazyLoad: 'ondemand'
        });
    });
</script>