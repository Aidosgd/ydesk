@extends('layouts.app')
@section('content')
    <div class="main_slider">
        <img src="{{ $main_slide }}" class="img-responsive" alt="">
    </div>

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
            <div class="row slick-slider">
                @foreach($news as $item)
                    <div class="col-sm-3 col-xs-12 item">
                        <figure class="wow fadeInLeft animated portfolio-item" data-wow-duration="500ms" data-wow-delay="0ms">
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
                                                {{ str_limit($item->node->title, 80) }}
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
@endsection