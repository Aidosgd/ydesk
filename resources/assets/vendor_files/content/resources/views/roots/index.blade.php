@extends('admin::base')
@section('content')
  <div class="wrapper-md">
    <div class="row">
      <div class="col-xs-12 col-md-8">
        <div class="panel panel-default">
          <table class="table">
            <thead>
              <tr>
                <td>{{ trans('content::default.roots.slug') }}</td>
                <td>{{ trans('admin::default.actions.label') }}</td>
              </tr>
            </thead>
            <tbody>
            @forelse($roots as $root)
              <tr>
                <td>{{ $root->slug }}</td>
                <td>
                  <div class="btn-group">
                    <a class="btn btn-sm btn-default" href="{{ admin_route('content.roots.edit', [$root->slug]) }}">
                      {!! icon('pencil') !!}
                    </a>
                    <a class="btn btn-sm btn-default" href="{{ admin_route('content.roots.show', [$root->slug]) }}">
                      {{ trans('content::default.roots.categories') }}
                    </a>
                    <a class="btn btn-sm btn-danger" href="{{ admin_route('content.roots.destroy', [$root->slug]) }}">
                      {!! icon('trash') !!}
                    </a>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5">{{ trans('content::default.roots.empty') }}</td>
              </tr>
            @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-xs-12 col-md-4">

      </div>
    </div>
<a href="{{ admin_route('content.roots.create') }}">{{ trans('admin::default.actions.create') }}</a>

  </div>
@endsection
@section('sidebar')
  <div class="wrapper">
    {!! Form::open(['method' => 'POST', 'route' => admin_prefix('content.roots.store')]) !!}
    <div class="form-group">
      <label for="slug">{{ trans('content::default.roots.slug') }}</label>
      {!! Form::text('slug', null, ['required', 'class' => 'form-control']) !!}
    </div>
    <div class="form-group">
      {!! Form::submit(trans('admin::default.actions.save'), ['class' => 'btn btn-primary btn-block']) !!}
    </div>
    {!! Form::close() !!}
  </div>
@endsection