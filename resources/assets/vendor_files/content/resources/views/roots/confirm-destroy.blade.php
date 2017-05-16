@extends('admin::base')
@section('title-right')
@endsection
@section('content')
  <div class="wrapper-md">
      <div class="col-sm-12">
          <div class="panel panel-default">
              <div class="panel-heading">
                 <h4>{{ ucfirst($root->slug) }} confirm destroy.</h4>
              </div>
              <div class="panel-body" ui-jq="slimScroll" ui-options="{height:'380px', size:'8px'}">
                  <div class="alert alert-warning">
                      Если вы удалите этот рут, удалятся все посты, категории, тёрмы и тэги, связанные с рутом.
                  </div>
                  {!! Html::link(
                  admin_route('content.roots.show', [$root->slug]),
                  trans('admin::default.actions.back'),
                  ['class' => 'btn btn-default'])
                  !!}
                  {!! Html::link(
                  admin_route('content.roots.destroy', [$root->slug]),
                  trans('admin::default.actions.destroy'),
                  ['class' => 'btn btn-danger destroy-confirm'])
                  !!}
              </div>
          </div>
      </div>
  </div>
@endsection