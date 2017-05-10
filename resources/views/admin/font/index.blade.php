@extends('admin::base')
@section('title-right')
    {{--{!! Html::link(--}}
    {{--admin_route('font.create'),--}}
    {{--trans('admin::default.actions.create'),--}}
    {{--['class' => 'btn btn-success'])--}}
    {{--!!}--}}
@endsection
@section('content')
    <div class="wrapper-md">
        <div class="panel panel-default">
            <div class="panel-heading">
                Font
            </div>
            <div class="row wrapper">
                <div class="col-sm-4 hidden-xs js__disabledManipulation">
                    <select class="input-sm form-control w-sm inline v-middle">
                        <option value="delete">Delete selected</option>
                    </select>
                    <button data-action="batchAction" data-url="{{ $batchAction }}" class="btn btn-sm btn-default apply_bulk">Apply</button>
                </div>
                <div class="col-sm-5 m-b-xs"></div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <input type="text" class="input-sm form-control" placeholder="Search">
                        <span class="input-group-btn">
            <button class="btn btn-sm btn-default" type="button"><i class="fa fa-search"></i></button>
          </span>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped b-t b-light">
                    <thead>
                    <tr>
                        <th><label  class="i-checks m-b-none js-check-checkbox"><input onchange="applyBulkCheck($(this))" type="checkbox"><i></i></label></th>
                        <th>{{ trans('acl::default.users.id') }}</th>
                        <th>Font url</th>
                        <th>Font size</th>
                        <th>Font family</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($font as $font_f)
                        <tr >
                            <td class="v-middle" style="width:20px;"><label class="i-checks m-b-none js-check-checkbox active"><input type="checkbox" data-value="{{$font_f->id}}"><i></i></label></td>
                            <td>{{ $font_f->id }}</td>
                            <td>{{ $font_f->font_url }}</td>
                            <td>{{ $font_f->font_size }}</td>
                            <td>{{ $font_f->font_family }}</td>
                            <td>
                                {!!
                                  Html::link(
                                    admin_route('font.edit', $font_f->id),
                                    trans('admin::default.actions.edit'),
                                    ['class' => 'btn btn-sm btn-default']
                                  )
                                !!}
                                {{--{!!--}}
                                {{--Html::link(--}}
                                {{--admin_route('seo.destroy', $font_f->id),--}}
                                {{--trans('admin::default.actions.destroy'),--}}
                                {{--['class' => 'btn btn-sm btn-danger destroy-confirm']--}}
                                {{--)--}}
                                {{--!!}--}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="alert alert-danger">
                                    нет ни одной отрасли
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <footer class="panel-footer">
                <div class="row">
                    <div class="col-sm-4 hidden-xs js__disabledManipulation">
                        <select class="input-sm form-control w-sm inline v-middle">
                            <option value="delete">Delete selected</option>
                        </select>
                        <button data-action="batchAction" data-url="{{ $batchAction }}" class="btn btn-sm btn-default apply_bulk">Apply</button>
                    </div>
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4 text-right text-center-xs">
                        {!! $font->render() !!}
                    </div>
                </div>
            </footer>
        </div>
    </div>
@endsection