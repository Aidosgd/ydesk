@extends('admin::base')
@section('title-right')
    {!! Html::link(
    admin_route('content.roots.categories.create', [$root->slug]),
    trans('admin::default.actions.create'),
    ['class' => 'btn btn-success'])
    !!}
@endsection
@section('sidebar')
<div class="wrapper"></div>
@endsection
@section('content')
    <div class="wrapper-md">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row wrapper">
                    <div class="col-sm-4 hidden-xs js__disabledManipulation">
                        <select class="input-sm form-control w-sm inline v-middle">
                            <option value="delete">Delete selected</option>
                        </select>
                        <button data-action="batchAction" data-url="{{ $batchAction }}" class="btn btn-sm btn-default apply_bulk">Apply</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped b-t b-light">
                        <thead>
                        <tr>
                            <th><label  class="i-checks m-b-none js-check-checkbox"><input onchange="applyBulkCheck($(this))" type="checkbox"><i></i></label></th>
                            <th>{{ trans('content::default.categories.id') }}</th>
                            <th>{{ trans('content::default.posts.title') }}</th>
                            <th>{{ trans('admin::default.actions.label') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td class="v-middle" style="width:20px;"><label class="i-checks m-b-none js-check-checkbox active"><input type="checkbox" data-value="{{$category->id}}"><i></i></label></td>
                                <td>
                                    @if($category->isRoot())
                                        {{ $category->id }} - {{ $root->slug }}
                                    @else
                                        {{str_repeat('-', $category->depth)}} {{ $category->id }}
                                    @endif
                                </td>
                                <td>
                                    @if($category->node)
                                        {{ $category->node->title }}
                                    @endif
                                </td>
                                <td>
                                    <a class = 'btn btn-sm btn-default' href="{{ admin_route('content.roots.categories.edit', [$root->slug, $category->id]) }}">{{ trans('admin::default.actions.edit') }}</a>
                                    <a class = 'btn btn-sm btn-danger destroy-confirm' href="{{ admin_route('content.roots.categories.destroy', [$root->slug, $category->id]) }}">{{ trans('admin::default.actions.destroy') }}</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">{{ trans('content::default.categories.empty') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="row wrapper">
                    <div class="col-sm-4 hidden-xs js__disabledManipulation">
                        <select class="input-sm form-control w-sm inline v-middle">
                            <option value="delete">Delete selected</option>
                        </select>
                        <button data-action="batchAction" data-url="{{ $batchAction }}" class="btn btn-sm btn-default apply_bulk">Apply</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection