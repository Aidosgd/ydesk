@extends('layouts.app')
@section('content')
    <div class="white">
        <section id="about">
            <div class="container">
                <div class="row">
                    {!! $first_block->node->content !!}
                </div>
            </div>

        <div class="container-fluid">
            <div class="row video-block">
                <div class="col-md-4 left">
                    <iframe width="100%" height="315" src="https://www.youtube.com/embed/9bZkp7q19f0" frameborder="0" allowfullscreen></iframe>
                </div>
                <div class="col-md-8 right">
                    <h2>WHO WE ARE</h2>
                    <p>This is Photoshop's version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet mauris.</p>

                    <p>This is Photoshop's version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum.</p>

                    <p>This is Photoshop's version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum.</p>
                </div>
            </div>
        </div>
        <div class="works" style="padding-top: 0">
            <div class="section-heading" style="margin-bottom: 40px;">
                <h1 class="title wow fadeInDown" data-wow-delay=".3s">OUR SERVICES</h1>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row third-block" style="padding: 0 30px;">
                {!! $third_block->node->content !!}
            </div>
        </div>
        </section>

        <div class="works">
            <div class="section-heading" style="margin-bottom: 40px;">
                <h1 class="title wow fadeInDown" data-wow-delay=".3s">OUR BENEFITS</h1>
            </div>
        </div>

        <div class="container-fluid fourth-block wow fadeInUpMid" data-wow-delay=".3s">
            <div class="container" >
                {!! $fourth_block->node->content !!}
            </div>
        </div>

        <section id="works" class="works">
            <div class="container-fluid" style="margin: 0 100px;">
                <div class="section-heading">
                    <h1 class="title wow fadeInDown" data-wow-delay=".3s">Latest News</h1>
                </div>
                <div class="row slick-slider">
                    @foreach($news as $item)
                        <div class="item">
                            <figure class="wow fadeInLeft animated portfolio-item" data-wow-duration="3s" data-wow-delay="0ms">
                                <div class="img-wrapper">
                                    @if($item->images()->first() || $item->node->fields->post_url)
                                        <img class="img-responsive" src="{{ empty(!$item->node->fields->post_url) ? strip_tags($item->node->teaser) : $item->images()->first()->path }}" alt="" />
                                    @endif
                                </div>
                                <figcaption>
                                    <div class="row">
                                        <div class="col-xs-3 col-md-3 date">
                                            <div class="day">{{ $item->created_at->format("d") }}</div>
                                            <div class="mY">{{ $item->created_at->format("M Y") }}</div>
                                        </div>
                                        <div class="col-xs-9 col-md-9 title">
                                            <h4>
                                                <a {{ isset($item->node->fields->post_url) ? 'target=blank' : "" }} href="{{ isset($item->node->fields->post_url) ? $item->node->fields->post_url : "/$lang/news/$item->id" }}">
                                                    {{ str_limit($item->node->title, 57) }}
                                                </a>
                                            </h4>
                                        </div>
                                    </div>
                                    <p>
                                        {!! isset($item->node->fields->post_url) ? str_limit($item->node->content, 70)  : str_limit($item->node->teaser, 70) !!}
                                    </p>
                                </figcaption>
                            </figure>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
@endsection