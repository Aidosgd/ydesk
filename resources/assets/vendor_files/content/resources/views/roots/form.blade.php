@extends('admin::base')
@section('content')
  <div class="wrapper-md" data-ng-controller="RootFormCtrl">
      <div class="tab-container">
          <div class="form-horizontal">
              <div class="tab-content">
                    {!! Form::model($root, ['method' => ($root) ? 'PUT' : 'POST', 'route' => [admin_prefix('content.roots.'.$target), ($root) ? $root->slug : '']]) !!}
                    @if($errors)
                        <div>
                            <ul>
                                @foreach($errors->all('<li>:message</li>') as $error)
                                    {!! $error !!}
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="form-group " >
                        <label class="col-sm-2 control-label" for="slug">{{ trans('content::default.roots.slug') }}</label>
                        <div class="col-sm-10">
                            {!! Form::text('slug', null, ['required','class' => 'form-control']) !!}
                        </div>
                    </div>
                    @foreach(config('app.locales') as $locale)
                    <div class="form-group " >
                        <label class="col-sm-2 control-label"  for="slug">{{ trans('content::default.roots.title') }} - {{ $locale }}</label>
                        <div class="col-sm-10">
                            {!! Form::text('title[' . $locale  . ']', null,['class' => 'form-control'] ) !!}
                        </div>
                    </div>
                    @endforeach
                    <div>
                      <fieldset>
                        <legend>{{ trans('content::default.posts.options.title') }}</legend>
                        <div class="row">
                          @foreach(['likes', 'rates', 'comments', 'moderation'] as $option)
                            <div class="col-xs-3">
                              <div class="checkbox">
                                <label class="i-checks">
                                  {!! Form::checkbox('config[posts][options]['.$option.']', true, isset($root->config)?array_get($root->config, 'posts.options.'.$option):'') !!}
                                  <i></i>
                                  {{ trans('content::default.posts.options.'.$option) }}
                                </label>
                              </div>
                            </div>
                          @endforeach
                        </div>
                      </fieldset>
                    </div>
                  <br>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                            <a class='btn btn-sm btn-default' href="{{ admin_route('content.roots.index') }}">{{ trans('admin::default.actions.back') }}</a>
                            {!! Form::submit(trans('admin::default.actions.save'),['class' => 'btn btn-sm btn-primary']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                  </div>

          </div>
      </div>
  </div>
@endsection