
@extends('admin::base')
@section('main-before')
    {!! Form::model($category, ['method' => ($category) ? 'PUT' : 'POST', 'route' => [admin_prefix('content.roots.categories.'.$target), $root->slug,  ($category) ? $category->id : '']]) !!}
@endsection
@section('sidebar')
    <div class="wrapper">
        <div class="">
            <div class="m-b-sm text-md">{{ trans('content::default.posts.image') }}</div>
            @if(isset($image))
                @if(isset($cropped_coords))
                    <img id="img" class="img-thumbnail img-responsive" src="{{ '/'.config('media.policies.uploads.images.src_path').'/'.$image->id.'?'.$cropped_coords}}"/>
                @else
                    <img id="img" class="img-thumbnail img-responsive" src="{{isset($image)?$image->path:''}}"/>
                @endif
            @endif
            <label style="{{ isset($image)?'':'display:none' }}" for="image_title" class="control-label image_title">{{ trans('content::default.posts.image_title') }}</label>
            {!! Form::text('image_title', isset($image)&&!Input::old()?$image->pivot->title:'', ['class' => 'form-control image_title','style' => isset($image)?'':'display:none']) !!}
            <label style="{{ isset($image)?'':'display:none' }}" for="image_alt" class="control-label image_alt">{{ trans('content::default.posts.image_alt') }}</label>
            {!! Form::text('image_alt', isset($image) && !Input::old()?$image->pivot->alt:''  , ['class' => 'form-control image_alt', 'style' => isset($image)?'':'display:none']) !!}
            <br>
            <a style="margin-bottom:20px;" href="#" class="btn add_image btn-primary btn-block btn-addon add_button" data-toggle="modal" data-type="images" data-target="#add_image" data-input="some_input">
                @if(isset($image))
                    <i class="fa fa-cogs"></i>Change
                @else
                    <i class="fa fa-plus"></i>Add Image
                @endif
            </a>
            <a onclick="deleteImageFromPreview($(this))" style="margin-bottom:20px;{{isset($image)?'':'display:none'}}" href="#" class="btn  btn-block btn-danger btn-addon delete_button">
                <i class="fa fa-trash"></i>Delete</a>
            <input type="hidden" name="image_id" id="image_id" value="{{isset($image)?$image->id:''}}">
            <input type="hidden" name="cropped_coords" id="cropped_coords" value="{{isset($cropped_coords)?$cropped_coords:''}}">
        </div>
    </div>
@endsection
@section('content')
    @if($errors)
        <div>
            <ul>
                @foreach($errors->all('<li>:message</li>') as $error)
                    {!! $error !!}
                @endforeach
            </ul>
        </div>
    @endif
    <div>
        @if( ! (is_object($category) && $target == 'update' && $category->isRoot()))
            <label  class="col-sm-2 control-label" for="parent_id">{{ trans('content::default.categories.root') }}</label>
            {!! Form::select('parent_id', $categories, ($category) ? $category->parent_id : null) !!}
        @endif
    </div>
    <div class="wrapper-md">
        <div class="tab-container">
            <ul class="nav nav-tabs" role="tablist">
                <?php $first = true; ?>
                @foreach(config('app.locales') as $locale)
                    <li class="{{ ($first) ? 'active' : '' }}">
                        <a href="#lang-{{ $locale }}" role="tab" data-toggle="tab">
                            {{ $locale }}
                        </a>
                    </li>
                    <?php $first = false; ?>
                @endforeach
            </ul>
            <div class="form-horizontal">
                <div class="tab-content">
                    <?php $first = true; ?>
                    @foreach(config('app.locales') as $locale)
                        <div role="tabpanel" class="tab-pane{{ ($first) ? ' active' : '' }}" id="lang-{{ $locale }}">
                            <div class="form-group">
                                <div class="col-sm-10 col-sm-offset-2">
                                    <div class="checkbox">
                                        <label class="i-checks">
                                            {!! Form::checkbox($locale.'[published]', 1) !!}
                                            <i></i>
                                            {{ trans('content::default.posts.published') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="{{ $locale }}_title">{{ trans('content::default.posts.title') }}</label>
                                <div class="col-sm-10">
                                    {!! Form::text($locale.'[title]', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="line line-dashed b-b line-lg pull-in"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="{{ $locale }}_slug">{{ trans('content::default.posts.slug') }}</label>
                                <div class="col-sm-10">
                                    {!! Form::text($locale.'[slug]', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="line line-dashed b-b line-lg pull-in"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="{{ $locale }}_teaser">{{ trans('content::default.posts.description') }}</label>
                                <div class="col-sm-10">
                                    {!! Form::textarea($locale.'[description]', null, ['class' => 'form-control', 'ui-jq' => 'ckeditor', 'ui-options' => json_encode($ckeditorBasic)]) !!}
                                </div>
                            </div>
                            <div class="line line-dashed b-b line-lg pull-in"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="{{ $locale }}_seo_title">{{ trans('content::default.posts.seo_title') }}</label>
                                <div class="col-sm-10">
                                    {!! Form::text($locale.'[seo_title]', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="line line-dashed b-b line-lg pull-in"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="{{ $locale }}_seo_description">{{ trans('content::default.posts.seo_description') }}</label>
                                <div class="col-sm-10">
                                    {!! Form::text($locale.'[seo_description]', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="line line-dashed b-b line-lg pull-in"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="{{ $locale }}_seo_keywords">{{ trans('content::default.posts.seo_keywords') }}</label>
                                <div class="col-sm-10">
                                    {!! Form::text($locale.'[seo_keywords]', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="line line-dashed b-b line-lg pull-in"></div>
                        </div>
                        <?php $first = false; ?>
                    @endforeach
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                            {!!
                            Form::submit(
                            trans('admin::default.actions.save'),
                            ['class' => 'btn btn-primary']
                            )
                            !!}
                            {!!
                            Html::link(
                            admin_route('content.roots.categories.index', [$root->slug]),
                            trans('admin::default.actions.back'),
                            ['class' => 'btn btn-sm btn-default']
                            )
                            !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
@endsection
@section('scripts')
    @include('media::manager.modal',['image_ids'=>[isset($image)?$image->id:''],'params' => ['multiple' => 0], 'multiple' => 0])
@endsection