@extends('layouts.app')

@section('content')
    <section class="global-page-header">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="block">
                        <h2>{{ $page->node->title }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="single-post">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @if($page->images()->first() || isset($page->node->fields->post_url))
                        <div class="post-img">
                            <img class="img-responsive" alt="" src="{{ isset($page->node->fields->post_url) ? $meta_og['image'] : $page->images()->first()->path }}">
                        </div>
                    @endif
                    <div class="post-content">
                        {!! isset($page->node->fields->post_url) ? $meta_og['description'] : $page->node->content !!}
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