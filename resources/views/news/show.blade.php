@extends('layouts.app')

@section('content')
    <section class="global-page-header">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="block">
                        <h2>{{ $post->node->fields->post_url ? $meta_og['title'] : $post->node->title }}</h2>
                        <div class="portfolio-meta">
                            <span>{{ $post->created_at->format('d.m.Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="single-post">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @if($post->images()->first() || $post->node->fields->post_url)
                        <div class="post-img">
                            <img class="img-responsive" alt="" src="{{ $post->node->fields->post_url ? $meta_og['image'] : $post->images()->first()->path }}">
                        </div>
                    @endif
                    <div class="post-content">
                        {!! $post->node->fields->post_url ? $meta_og['description'] : $post->node->content !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="call-to-action">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="block">
                        <h2 class="title wow fadeInDown" data-wow-delay=".3s" data-wow-duration="300ms">SO WHAT YOU THINK ?</h1>
                            <p class="wow fadeInDown" data-wow-delay=".5s" data-wow-duration="300ms">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nobis,</br>possimus commodi, fugiat magnam temporibus vero magni recusandae? Dolore, maxime praesentium.</p>
                            <a href="#" class="btn btn-default btn-contact wow fadeInDown" data-wow-delay=".7s" data-wow-duration="300ms">Contact With Me</a>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection