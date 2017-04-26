@extends('admin::base')
@section('main-before')
    {!! Form::model($seo, ['method' => ($seo) ? 'PUT' : 'POST', 'route' => [admin_prefix('seo.'.$target), ($seo) ? $seo->id : '']]) !!}
@endsection
@section('content')
    @if($errors->any())
        <div>
            <ul>
                @foreach($errors->all('<li>:message</li>') as $error)
                    {!! $error !!}
                @endforeach
            </ul>
        </div>
    @endif
    <div class="wrapper-md" >
        <div class="panel panel-default">
            <div class="panel-heading">
                {{ $target == 'store'?'Create':'Edit' }} sector
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    @foreach(config('app.locales') as $locale)
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="{{ $locale }}_name">Title</label>
                            <div class="col-sm-10">
                                {!! Form::text($locale.'[title]', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="{{ $locale }}_name">Description</label>
                            <div class="col-sm-10">
                                {!! Form::text($locale.'[description]', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="{{ $locale }}_name">Keywords</label>
                            <div class="col-sm-10">
                                {!! Form::text($locale.'[keywords]', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    @endforeach
                    <div class="line line-dashed b-b line-lg pull-in"></div>
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
                            admin_route('seo.index'),
                            trans('admin::default.actions.back'),
                            ['class' => 'btn btn-sm btn-default']
                            )
                            !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!!  Form::close() !!}
@endsection