@extends('layouts.app')
@section('content')
    <section id="hero-area" style="background: url({{ $main_slide }}) no-repeat 50%; background-size: cover; background-attachment: fixed;">
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
                    <div class="col-sm-4 col-xs-12">
                        <figure class="wow fadeInLeft animated portfolio-item" data-wow-duration="500ms" data-wow-delay="0ms">
                            <div class="img-wrapper">
                                @if($item->images()->first() || $item->node->fields->post_url)
                                    <img class="img-responsive" style="height: 240px; width: 100%" src="{{ empty(!$item->node->fields->post_url) ? strip_tags($item->node->teaser) : $item->images()->first()->path }}" alt="" />
                                @endif
                            </div>
                            <figcaption>
                                <h4>
                                    <a {{ isset($item->node->fields->post_url) ? 'target=blank' : "" }} href="{{ isset($item->node->fields->post_url) ? $item->node->fields->post_url : "/$lang/news/$item->id" }}">
                                        {{ $item->node->title }}
                                    </a>
                                </h4>
                                <p>
                                    {!! isset($item->node->fields->post_url) ? $item->node->content  : $item->node->teaser !!}
                                </p>
                            </figcaption>
                        </figure>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection