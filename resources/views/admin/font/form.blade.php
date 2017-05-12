@extends('admin::base')
@section('main-before')
    {!! Form::model($font, ['method' => ($font) ? 'PUT' : 'POST', 'route' => [admin_prefix('font.'.$target), ($font) ? $font->id : '']]) !!}
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
                    {{--<div class="form-group">--}}
                        {{--<label class="col-sm-2 control-label">Font url</label>--}}
                        {{--<div class="col-sm-10">--}}
                            {{--{!! Form::text('font_url', null, ['class' => 'form-control']) !!}--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--<label class="col-sm-2 control-label">Font size</label>--}}
                        {{--<div class="col-sm-10">--}}
                            {{--{!! Form::text('font_size', null, ['class' => 'form-control']) !!}--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Font family</label>
                        <div class="col-sm-10">
                            {!! Form::text('font_family', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
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
                            admin_route('font.index'),
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