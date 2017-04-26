@extends('layouts.app')

@section('content')
    <section class="global-page-header">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="block">
                        <h2>News</h2>
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
                        <?php
                            if($item->node->fields->post_url){
                                $sites_html = file_get_contents($item->node->fields->post_url);
                                $html = new DOMDocument();
                                @$html->loadHTML($sites_html);
                                $meta_og = [
                                    'title' => '',
                                    'description' => '',
                                    'image' => '',
                                ];

                                foreach($html->getElementsByTagName('meta') as $meta) {
                                    if($meta->getAttribute('property')=='og:title'){
                                        $meta_og['title'] = $meta->getAttribute('content');
                                    }
                                    if($meta->getAttribute('property')=='og:description'){
                                        $meta_og['description'] = $meta->getAttribute('content');
                                    }
                                    if($meta->getAttribute('property')=='og:image'){
                                        $meta_og['image'] = $meta->getAttribute('content');
                                    }
                                }
                            }
                        ?>
                        <article class="wow fadeInDown" data-wow-delay=".3s" data-wow-duration="500ms">
                            @if($item->images()->first() || $item->node->fields->post_url)
                                <div class="blog-post-image">
                                    <a href="/{{ $lang }}/news/{{ $item->id }}">
                                        <img class="img-responsive" src="{{ $item->node->fields->post_url ? $meta_og['image'] : $item->images()->first()->path }}" alt="" />
                                    </a>
                                </div>
                            @endif
                            <div class="blog-content">
                                <h2 class="blogpost-title">
                                    <a href="/{{ $lang }}/news/{{ $item->id }}">{{ $item->node->fields->post_url ? $meta_og['title'] : $item->node->title }}</a>
                                </h2>
                                <div class="blog-meta">
                                    <span>{{ $item->created_at->format('d.m.Y') }}</span>
                                </div>
                                <p>{!! $item->node->fields->post_url ? $meta_og['description'] : $item->node->teaser !!}</p>
                                <a href="/{{ $lang }}/news/{{ $item->id }}" class="btn btn-dafault btn-details">Continue Reading</a>
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