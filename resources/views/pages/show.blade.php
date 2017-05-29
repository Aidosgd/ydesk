@extends('layouts.app')

@section('content')
    <div class="container-fluid info-page">
        <div class="row">
            <div class="col-md-3">
                <h1>{{ $page->node->title }}</h1>
                <h2>{{ strip_tags($page->node->teaser) }}</h2>
            </div>
            <div class="col-md-9">
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
@endsection