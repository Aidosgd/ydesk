@extends('admin::base')
@section('title-right')
    {!! Html::link(
    admin_route('content.roots.edit', [$root->slug]),
    trans('admin::default.actions.edit'),
    ['class' => 'btn btn-default'])
    !!}
    {!! Html::link( 'admin/content/roots/'.$root->slug.'/confirmDestroy',
    trans('admin::default.actions.destroy'),
    ['class' => 'btn btn-danger '])
    !!}
@endsection
@section('content')
    <div class="wrapper-md">
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span class="label bg-dark">{{ $childPostsCount }}</span> Posts
                  <span class="pull-right" style="margin-top: -5px;">
                      <a class="btn btn-sm btn-primary" href="{{ admin_route('content.roots.posts.index', [$root->slug]) }}">{{ trans('content::default.posts.all') }}</a>
                      <a class="btn btn-sm btn-success" href="{{ admin_route('content.roots.posts.create', [$root->slug]) }}">{{ trans('content::default.create') }}</a>
                  </span>
                </div>
                <div class="panel-body" ui-jq="slimScroll" ui-options="{height:'380px', size:'8px'}">
                    @foreach($childPosts as $post)
                        <div class="media">
                            <span class="pull-left thumb"><img src="{{ $post->images()->count()?$post->images()->first()->getPath($post, 'small') : '' }}" alt="NO"></span>
                            <div class="media-body">
                              <span class="pull-right">{!! Html::link( admin_route('content.roots.categories.edit',
                                  [$root->slug, $post->category->id]), $post->category->node->title, ['class' => 'label bg-light dk']) !!}</span>
                                <div class="pull-right text-center text-muted">
                                    <strong class="h4"></strong><br>
                                    <small class="label bg-light"></small>
                                </div>
                                <a href="{{ admin_route('content.roots.posts.edit',[$root->slug, $post->id]) }}" class="h4">{{ $post->node->title }}</a>
                                <small style="margin-top: 7px;" class="block"><a href="{{ admin_route('acl.users.show', $post->author->id) }}" class="">{{ $post->author->name }}</a> @foreach($post->author->roles as $role)<span class="label label-success" style="margin-right:5px">{{ $role->name }}</span>@endforeach</small>
                            </div>
                        </div>
                        <div class="line line-dashed b-b line-lg"></div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span class="label bg-dark">{{ $childCategoriesCount }}</span> Categories
                  <span class="pull-right" style="margin-top: -5px;">
                      <a class="btn btn-sm btn-primary" href="{{ admin_route('content.roots.categories.index', [$root->slug]) }}">{{ trans('content::default.categories.all') }}</a>
                      <a class="btn btn-sm btn-success" href="{{ admin_route('content.roots.categories.create', [$root->slug]) }}">{{ trans('content::default.create') }}</a>
                  </span>
                </div>
                <div class="panel-body" ui-jq="slimScroll" ui-options="{height:'380px', size:'8px'}">
                    @foreach($childCategories as $category)
                        <div class="media">
                            <span class="pull-left thumb"><img src="{{ $category->images()->count() ? $category->images()->first()->path : '' }}" alt="NO"></span>
                            <div class="media-body">
                                <div class="pull-right text-center text-muted">
                                    <strong class="h4"></strong><br>
                                    <small class="label bg-light"></small>
                                </div>
                                <a href="{{ admin_route('content.roots.categories.edit',[$root->slug, $category->id]) }}" class="h4">{{ $category->node->title }}</a>
                                <span class="pull-right"><span class="label bg-light dk">{{ $category->posts->count() }}</span></span>
                            </div>
                        </div>
                        <div class="line line-dashed b-b line-lg"></div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection