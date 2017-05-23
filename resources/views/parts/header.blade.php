<header id="top-bar" class="navbar-fixed-top animated-header">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <div class="navbar-brand">
            <a href="/" style="margin-top: 0">
                <img src="/images/logo.jpg" alt="">
            </a>
        </div>
        <ul class="nav navbar-nav hidden-xs">
            @foreach($main_menu as $item)
                <li>
                    <a href="/{{ $lang }}/{{ $item->link }}">{{ $item->node->title }}</a>
                </li>
            @endforeach
        </ul>
    </div>
    <nav class="collapse navbar-collapse navbar-right" role="navigation">
        <ul class="nav navbar-nav visible-xs">
            @foreach($main_menu as $item)
                <li>
                    <a href="/{{ $lang }}/{{ $item->link }}">{{ $item->node->title }}</a>
                </li>
            @endforeach
                <li>
                    <a href="https://www.facebook.com/YDesk-423317454459866/" target="_blank" class="Facebook">
                        <i class="ion-social-facebook"></i>
                    </a>
                    <a href="https://twitter.com/YoursDesk" target="_blank" class="Twitter">
                        <i class="ion-social-twitter"></i>
                    </a>
                    <a href="https://www.linkedin.com/company-beta/10066832/" class="Linkedin">
                        <i class="fa fa-linkedin"></i>
                    </a>
                    <a href="https://plus.google.com/114044347762222676343?hl=en" target="_blank" class="Google Plus">
                        <i class="ion-social-googleplus"></i>
                    </a>
                </li>
        </ul>
        <div class="main-menu hidden-xs">
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="https://www.facebook.com/YDesk-423317454459866/" target="_blank" class="Facebook">
                        <i class="ion-social-facebook"></i>
                    </a>
                </li>
                <li>
                    <a href="https://twitter.com/YoursDesk" target="_blank" class="Twitter">
                        <i class="ion-social-twitter"></i>
                    </a>
                </li>
                <li>
                    <a href="https://www.linkedin.com/company-beta/10066832/" class="Linkedin">
                        <i class="fa fa-linkedin"></i>
                    </a>
                </li>
                <li>
                    <a href="https://plus.google.com/114044347762222676343?hl=en" target="_blank" class="Google Plus">
                        <i class="ion-social-googleplus"></i>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>