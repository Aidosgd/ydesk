@extends('layouts.app')

@section('content')
    <section class="global-page-header">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="block">
                        <h2>Full Width Blog</h2>
                        <ol class="breadcrumb">
                            <li>
                                <a href="index.html">
                                    <i class="ion-ios-home"></i>
                                    Home
                                </a>
                            </li>
                            <li class="active">Blog</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="blog-full-width">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @foreach($news as $item)
                        <article class="wow fadeInDown" data-wow-delay=".3s" data-wow-duration="500ms">
                            <div class="blog-post-image">
                                <a href="/news/{{ $item->id }}">
                                    <img class="img-responsive" src="{{ $item->images()->first()->path }}" alt="" />
                                </a>
                            </div>
                            <div class="blog-content">
                                <h2 class="blogpost-title">
                                    <a href="/news/{{ $item->id }}">{{ $item->node->title }}</a>
                                </h2>
                                <div class="blog-meta">
                                    <span>{{ $item->created_at->format('d.m.Y') }}</span>
                                </div>
                                <p>{!! $item->node->teaser !!}</p>
                                <a href="/news/{{ $item->id }}" class="btn btn-dafault btn-details">Continue Reading</a>
                            </div>
                        </article>
                    @endforeach
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
                            <p class="wow fadeInDown" data-wow-delay=".5s" data-wow-duration="300ms">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nobis,<br>possimus commodi, fugiat magnam temporibus vero magni recusandae? Dolore, maxime praesentium.</p>
                            <a href="contact.html" class="btn btn-default btn-contact wow fadeInDown" data-wow-delay=".7s" data-wow-duration="300ms">Contact With Me</a>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection