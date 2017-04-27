@extends('layouts.app')
@section('content')
    <section id="hero-area" style="background: url({{ $main_slide->images()->first()->path }}) no-repeat 50%; background-size: cover; background-attachment: fixed;">
        {{--<div class="container">--}}
            {{--<div class="row">--}}
                {{--<div class="col-md-12 text-center">--}}
                    {{--<div class="block wow fadeInUp" data-wow-delay=".3s">--}}

                        {{--<!-- Slider -->--}}
                        {{--<section class="cd-intro">--}}
                            {{--<h1 class="wow fadeInUp animated cd-headline slide" data-wow-delay=".4s" >--}}
                                {{--<span>HI, MY NAME IS JONATHON & I AM A</span><br>--}}
                                {{--<span class="cd-words-wrapper">--}}
                                    {{--<b class="is-visible">DESIGNER</b>--}}
                                    {{--<b>DEVELOPER</b>--}}
                                    {{--<b>FATHER</b>--}}
                                {{--</span>--}}
                            {{--</h1>--}}
                        {{--</section> <!-- cd-intro -->--}}
                        {{--<!-- /.slider -->--}}
                        {{--<h2 class="wow fadeInUp animated" data-wow-delay=".6s" >--}}
                            {{--With 10 years experience, I've occupied many roles including digital design director,<br> web designer and developer. This site showcases some of my work.--}}
                        {{--</h2>--}}
                        {{--<a class="btn-lines dark light wow fadeInUp animated smooth-scroll btn btn-default btn-green" data-wow-delay=".9s" href="#works" data-section="#works" >View Works</a>--}}

                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    </section>

    <section id="about">
        <div class="container">
            <div class="row">
                {!! $first_block->node->content !!}
            </div>

            <hr>

            <div class="row">
                {!! $second_block->node->content !!}
            </div>

            <hr>

            <div class="row">
                {!! $third_block->node->content !!}
            </div>
        </div>
    </section>

    <section id="works" class="works">
        <div class="container">
            <div class="section-heading">
                <h1 class="title wow fadeInDown" data-wow-delay=".3s">Latest News</h1>
            </div>
            <div class="row">
                @foreach($news as $item)
                    <?php
                    if(isset($item->node->fields->post_url)){
                        $sites_html = file_get_contents($item->node->fields->post_url);
                        $html = new DOMDocument();
                    @$html->loadHTML($sites_html);
                    $meta_og = [
                    'title' => '',
                    'description' => '',
                    'image' => '',
                    ];

                    foreach($html->getElementsByTagName('meta') as $meta) {
                    if($meta->getAttribute('name')){
                    if($meta->getAttribute('name') == 'og:title'){
                    $meta_og['title'] = $meta->getAttribute('content');
                    }
                    if($meta->getAttribute('name') == 'og:description'){
                    $meta_og['description'] = $meta->getAttribute('content');
                    }
                    if($meta->getAttribute('name') == 'og:image'){
                    $meta_og['image'] = $meta->getAttribute('content');
                    }
                    }else{
                    if($meta->getAttribute('property') == 'og:title'){
                    $meta_og['title'] = $meta->getAttribute('content');
                    }
                    if($meta->getAttribute('property') == 'og:description'){
                    $meta_og['description'] = $meta->getAttribute('content');
                    }
                    if($meta->getAttribute('property') == 'og:image'){
                    $meta_og['image'] = $meta->getAttribute('content');
                    }
                    }

                    }
                    }
                    ?>
                    <div class="col-sm-4 col-xs-12">
                        <figure class="wow fadeInLeft animated portfolio-item" data-wow-duration="500ms" data-wow-delay="0ms">
                            <div class="img-wrapper">
                                @if($item->images()->first() || $item->node->fields->post_url)
                                    <img class="img-responsive" style="height: 240px; width: 100%" src="{{ isset($item->node->fields->post_url) ? $meta_og['image'] : $item->images()->first()->path }}" alt="" />
                                @endif
                            </div>
                            <figcaption>
                                <h4>
                                    <a {{ isset($item->node->fields->post_url) ? 'target=blank' : "" }} href="{{ isset($item->node->fields->post_url) ? $item->node->fields->post_url : "/$lang/news/$item->id" }}"">
                                        {{ isset($item->node->fields->post_url) ? $meta_og['title'] : $item->node->title }}
                                    </a>
                                </h4>
                                <p>
                                    {!! isset($item->node->fields->post_url) ? $meta_og['description'] : $item->node->teaser !!}
                                </p>
                            </figcaption>
                        </figure>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{--<section id="feature">--}}
        {{--<div class="container">--}}
            {{--<div class="section-heading">--}}
                {{--<h1 class="title wow fadeInDown" data-wow-delay=".3s">Offer From Me</h1>--}}
                {{--<p class="wow fadeInDown" data-wow-delay=".5s">--}}
                    {{--Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sed,<br> quasi dolores numquam dolor vero ex, tempora commodi repellendus quod laborum.--}}
                {{--</p>--}}
            {{--</div>--}}
            {{--<div class="row">--}}
                {{--<div class="col-md-4 col-lg-4 col-xs-12">--}}
                    {{--<div class="media wow fadeInUp animated" data-wow-duration="500ms" data-wow-delay="300ms">--}}
                        {{--<div class="media-left">--}}
                            {{--<div class="icon">--}}
                                {{--<i class="ion-ios-flask-outline"></i>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="media-body">--}}
                            {{--<h4 class="media-heading">Media heading</h4>--}}
                            {{--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatum, sint.</p>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-md-4 col-lg-4 col-xs-12">--}}
                    {{--<div class="media wow fadeInDown animated" data-wow-duration="500ms" data-wow-delay="600ms">--}}
                        {{--<div class="media-left">--}}
                            {{--<div class="icon">--}}
                                {{--<i class="ion-ios-lightbulb-outline"></i>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="media-body">--}}
                            {{--<h4 class="media-heading">Well documented.</h4>--}}
                            {{--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatum, sint.</p>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-md-4 col-lg-4 col-xs-12">--}}
                    {{--<div class="media wow fadeInDown animated" data-wow-duration="500ms" data-wow-delay="900ms">--}}
                        {{--<div class="media-left">--}}
                            {{--<div class="icon">--}}
                                {{--<i class="ion-ios-lightbulb-outline"></i>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="media-body">--}}
                            {{--<h4 class="media-heading">Well documented.</h4>--}}
                            {{--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatum, sint.</p>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-md-4 col-lg-4 col-xs-12">--}}
                    {{--<div class="media wow fadeInDown animated" data-wow-duration="500ms" data-wow-delay="1200ms">--}}
                        {{--<div class="media-left">--}}
                            {{--<div class="icon">--}}
                                {{--<i class="ion-ios-americanfootball-outline"></i>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="media-body">--}}
                            {{--<h4 class="media-heading">Free updates</h4>--}}
                            {{--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatum, sint.</p>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-md-4 col-lg-4 col-xs-12">--}}
                    {{--<div class="media wow fadeInDown animated" data-wow-duration="500ms" data-wow-delay="1500ms">--}}
                        {{--<div class="media-left">--}}
                            {{--<div class="icon">--}}
                                {{--<i class="ion-ios-keypad-outline"></i>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="media-body">--}}
                            {{--<h4 class="media-heading">Solid Support</h4>--}}
                            {{--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatum, sint.</p>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-md-4 col-lg-4 col-xs-12">--}}
                    {{--<div class="media wow fadeInDown animated" data-wow-duration="500ms" data-wow-delay="1800ms">--}}
                        {{--<div class="media-left">--}}
                            {{--<div class="icon">--}}
                                {{--<i class="ion-ios-barcode-outline"></i>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="media-body">--}}
                            {{--<h4 class="media-heading">Simple Installation</h4>--}}
                            {{--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatum, sint.</p>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</section>--}}

    {{--<section id="call-to-action">--}}
        {{--<div class="container">--}}
            {{--<div class="row">--}}
                {{--<div class="col-md-12">--}}
                    {{--<div class="block">--}}
                        {{--<h2 class="title wow fadeInDown" data-wow-delay=".3s" data-wow-duration="500ms">SO WHAT YOU THINK ?</h1>--}}
                            {{--<p class="wow fadeInDown" data-wow-delay=".5s" data-wow-duration="500ms">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nobis,<br>possimus commodi, fugiat magnam temporibus vero magni recusandae? Dolore, maxime praesentium.</p>--}}
                            {{--<a href="contact.html" class="btn btn-default btn-contact wow fadeInDown" data-wow-delay=".7s" data-wow-duration="500ms">Contact With Me</a>--}}
                    {{--</div>--}}
                {{--</div>--}}

            {{--</div>--}}
        {{--</div>--}}
    {{--</section>--}}
@endsection