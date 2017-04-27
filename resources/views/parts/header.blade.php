<header id="top-bar" class="navbar-fixed-top animated-header">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="navbar-brand">
                <a href="/" >
                    <img src="/images/logo.png" alt="">
                </a>
            </div>
        </div>
        <nav class="collapse navbar-collapse navbar-right" role="navigation">
            <div class="main-menu">
                <ul class="nav navbar-nav navbar-right">
                    @foreach($main_menu as $item)
                        <li>
                            <a href="/{{ $lang }}/{{ $item->link }}">{{ $item->node->title }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </nav>
    </div>
</header>