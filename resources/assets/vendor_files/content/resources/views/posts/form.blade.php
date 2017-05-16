@extends('admin::base')
@section('main-before')
    {!! Form::model($post, ['method' => ($post) ? 'PUT' : 'POST', 'route' => [admin_prefix('content.roots.posts.'.$target), $root->slug, ($post) ? $post->id : '']]) !!}
@endsection
@section('title-right')
    @if($post)
        <span class="text-muted m-l-sm pull-right">Last edited: {{ $post->updated_at->format('Y-m-d H:i:s') }}</span>
    @endif
@endsection
@section('sidebar')
    <div class="wrapper">
        <div class="">
            <div class="m-b-sm text-md">{{ trans('content::default.posts.information') }}</div>
            @if($users)
                <div class="">
                    <div class="form-group">
                        <label for="user_id" class="control-label">{{ trans('content::default.posts.user') }}</label>
                        {!! Form::select('user_id', $users, ($post) ? $post->user_id : null, ['required', 'class' => 'form-control']) !!}
                    </div>
                </div>
            @endif
            <div class="">
                <div class="form-group">
                    <label for="category_id">{{ trans('content::default.posts.category') }}</label>
                    {!! Form::select('category_id', $categories, ($post) ? $post->category_id : null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="line line-dashed b-b line-lg"></div>
        <div class="">
            <div class="m-b-sm text-md">Добавить в рассылку</div>
            <div class="form-group">
                <div class="checkbox">
                    <label class="i-checks">
                        {!! Form::checkbox('newsletter_enabled', null) !!}
                        <i></i>
                    </label>
                </div>
                @if(isset($postSubscriptionQueue) && $postSubscriptionQueue->sended)
                    Последняя рассылка
                    {{ $postSubscriptionQueue->updated_at }}
                @endif
            </div>
        </div>
        <div class="line line-dashed b-b line-lg"></div>
        <div class="">
            <div class="m-b-sm text-md">{{ trans('content::default.posts.moderation') }}</div>
            <div>
                @foreach(['pending', 'approved', 'discarded'] as $key => $status)
                    <div class="radio">
                        <label class="i-checks">
                            {!! Form::radio('status', $key) !!}
                            <i></i>
                            {{ trans('social::default.moderation.'.$status) }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="line line-dashed b-b line-lg"></div>
        <div class="">
            <div class="m-b-sm text-md">{{ trans('content::default.posts.image') }}</div>
            @if(isset($cropped_coords) && isset($image))
                <img id="img" class="img-thumbnail img-responsive" src="{{ '/'.config('media.policies.uploads.images.src_path').'/'.$image->id.'?'.$cropped_coords}}"/>
            @else
                <img id="img" class="img-thumbnail img-responsive" src="{{isset($image)?$image->path:''}}"/>
            @endif
            <label style="{{ isset($image)?'':'display:none' }}" for="image_title" class="control-label image_title">{{ trans('content::default.posts.image_title') }}</label>
            {!! Form::text('image_title', isset($image)&&!\Illuminate\Support\Facades\Input::old()?$image->pivot->title:'', ['class' => 'form-control image_title','style' => isset($image)?'':'display:none']) !!}
            <label style="{{ isset($image)?'':'display:none' }}" for="image_alt" class="control-label image_alt">{{ trans('content::default.posts.image_alt') }}</label>
            {!! Form::text('image_alt', isset($image) && !\Illuminate\Support\Facades\Input::old()?$image->pivot->alt:''  , ['class' => 'form-control image_alt', 'style' => isset($image)?'':'display:none']) !!}
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
        <div class="line line-dashed b-b line-lg"></div>
        <div class="">
            <div class="m-b-sm text-md">{{ trans('admin::default.actions.label') }}</div>
            {!!
            Form::submit(
            trans('admin::default.actions.save'),
            ['class' => 'btn btn-block btn-primary']
            )
            !!}
            {!!
            Html::link(
            admin_route('content.roots.posts.index', [$root->slug]),
            trans('admin::default.actions.back'),
            ['class' => 'btn btn-sm btn-block btn-default']
            )
            !!}
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
    <div class="wrapper-md">
        <div class="tab-container">
            <ul class="nav nav-tabs" role="tablist">
                <?php $first = true; ?>
                @foreach(config('app.locales') as $locale)
                    <li class="{{ ($first) ? 'active' : '' }}">
                        <a href="#lang-{{ $locale }}" role="tab" data-toggle="tab">{{ $locale }}</a>
                    </li>
                    <?php $first = false; ?>
                @endforeach
            </ul>
            <div class="form-horizontal">
                <div class="tab-content">
                    @if($root->slug == 'news' && $target == 'store')
                    <div class="form-group">
                        <label class="col-sm-2 control-label"></label>
                        <div class="col-sm-10">
                            <a class="btn btn-default" data-toggle="modal" data-target="#addArticles">Add auto articles</a>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="addArticles" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Add link of page:</h4>
                                    </div>
                                    <div class="modal-body" style="padding:50px;">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="aa_value" placeholder="http://example.com/">
                                        </div>
                                        <a class="btn btn-primary" onclick="aa_view()" style="width:100%;">View</a>

                                        <div id="aa_display"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="line line-dashed b-b line-lg pull-in"></div>
                    @endif
                    <?php $first = true; ?>
                    @foreach(config('app.locales') as $locale)
                        <div role="tabpanel" class="tab-pane{{ ($first) ? ' active' : '' }}" id="lang-{{ $locale }}">
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
                                <label class="col-sm-2 control-label" for="{{ $locale }}_teaser">{{ trans('content::default.posts.teaser') }}</label>
                                <div class="col-sm-10">
                                    {!! Form::textarea($locale.'[teaser]', null, ['class' => 'form-control', 'ui-jq' => 'ckeditor', 'ui-options' => json_encode($ckeditorBasic)]) !!}
                                </div>
                            </div>
                            <div class="line line-dashed b-b line-lg pull-in"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="{{ $locale }}_content">{{ trans('content::default.posts.content') }}</label>
                                <div class="col-sm-10">
                                    {!! Form::textarea($locale.'[content]', null, ['class' => 'form-control', 'ui-jq' => 'ckeditor', 'ui-options' => json_encode($ckeditorBasic)]) !!}
                                </div>
                            </div>
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
                            @foreach($root->fields as $field)
                                @if($field->type != 'file')
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="{{ $locale }}_fields_{{ $field->localeTitle }}">{{ $field->localeTitle }}</label>
                                        <div class="col-sm-10">
                                            @if($field->type == 'string')
                                                {!! Form::text($locale.'[fields]['.$field->slug.']', null, ['class' => 'form-control']) !!}
                                            @elseif($field->type == 'email')
                                                {!! Form::email($locale.'[fields]['.$field->slug.']', null, ['class' => 'form-control']) !!}
                                            @elseif($field->type == 'text' || $field->type == 'html')
                                                {!! Form::textarea($locale.'[fields]['.$field->slug.']', null, ['class' => 'form-control', 'ui-jq' => 'ckeditor', 'ui-options' => json_encode($ckeditorBasic)]) !!}
                                            @elseif($field->type == 'date')
                                                {!! Form::text($locale.'[fields]['.$field->slug.']', null, [ 'ui-jq' => "datetimepicker" , 'ui-options' => "{format: 'DD-MM-YYYY'}",'class' => 'form-control']) !!}
                                            @elseif($field->type == 'datetime')
                                                {!! Form::text($locale.'[fields]['.$field->slug.']', null, [ 'ui-jq' => "datetimepicker" , 'ui-options' => "{format: 'DD-MM-YYYY hh-mm'}",'class' => 'form-control']) !!}
                                            @elseif($field->type == 'select')
                                                {!! Form::select($locale.'[fields]['.$field->slug.']', $field->localeOptions , null, [ 'class' => 'form-control']) !!}
                                            @elseif($field->type == 'select_multiple')
                                                {!! Form::select($locale.'[fields]['.$field->slug.'][]', $field->localeOptions , null, [ 'multiple', 'class' => 'form-control','ui-jq' => 'chosen']) !!}
                                            @elseif($field->type == 'checkbox_multiple')
                                                @foreach($field->localeOptions as $index => $option)
                                                    <div class="checkbox">
                                                        <label class="col-sm-2 control-label" style = 'text-align:left'>
                                                            {!! Form::checkbox($locale.'[fields]['.$field->slug.']['.$index.']' , 1, isset($post->$locale->fields) ? array_get((array) $post->$locale->fields, $field->slug.'.'.$index) == 1 : 0, []) !!} {{ $option }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @elseif($field->type == 'radio')
                                                @foreach($field->localeOptions as $index => $option)
                                                    <div class="radio">
                                                        <label class="col-sm-2 control-label" style = 'text-align:left'>
                                                            {!! Form::radio($locale.'[fields]['.$field->slug.']' , $option, []) !!} {{ $option }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @elseif($field->type == 'checkbox')
                                                <div class="checkbox">
                                                    <label class="col-sm-2 control-label" style = 'text-align:left'>
                                                        {!! Form::checkbox($locale.'[fields]['.$field->slug.']' ,null, []) !!}
                                                    </label>
                                                </div>
                                            @elseif($field->type == 'masked_field')
                                                {!! Form::text($locale.'[fields]['.$field->slug.']',null, ['placeholder' => $field->options['mask'],'id' => $locale.'_fields_'.$field->slug, 'class' => 'form-control', 'ui-jq' => 'mask', 'ui-options' => '"'.$field->options['mask'].'"']) !!}
                                            @section('scripts')
                                            @endsection
                                            @endif
                                        </div>
                                    </div>
                                    <div class="line line-dashed b-b line-lg pull-in"></div>
                                @endif
                            @endforeach
                            @if($terms->count())
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="tags[]">{{ trans('content::default.posts.tags') }}</label>
                                    <div class="col-sm-10">
                                        @foreach($terms as $term)
                                            @if(isset($term->tags))
                                                <label class="col-sm-2 control-label" for="tags[{{ $locale }}][{{ $term->id }}][]">{{ $term->$locale->title }}</label>
                                                <div class="col-sm-10">
                                                    <select ui-jq="chosen" name="tags[{{ $locale }}][{{ $term->id }}][]" multiple="" class="w-md" >
                                                        @foreach($term->tags as $tag)
                                                            @if($tag->language_id==$locale)
                                                                <option  value="{{ $tag->id }}" {{isset($tags)&&array_key_exists($tag->id, $tags)?'selected':''}}> {{ $tag->content }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <br><br>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="line line-dashed b-b line-lg pull-in"></div>
                            @endif
                        </div>
                        <?php $first = false; ?>
                    @endforeach
                    @foreach($root->fields as $field)
                        @if($field->type == 'file')
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="fields_{{ $field->localeTitle }}">{{ $field->localeTitle }}</label>
                                <div class="col-sm-10">
                                    <div>
                                        <img style="width:50px;{{ isset($files) && $files->get($field->slug) ? ''  : 'display:none'}}" class="img-thumbnail file img-responsive" src="{{ isset($files) && $files->get($field->slug) ? $files->get($field->slug)->getThumbnail()  : ''}}"/>
                              <span class="file_name">
                                  {{ isset($files) && $files->get($field->slug) ? $files->get($field->slug)->title : ''}}
                              </span>
                                        {!! Html::link(
                                        '#',
                                        isset($files) && $files->get($field->slug) ? 'Change' : 'Add',
                                        ['class' => 'btn btn-primary add_image add_button', 'data-toggle' => 'modal', 'data-type' => 'files', 'data-target' => '#add_image'])
                                        !!}
                                        {!! Form::hidden('fields[files]['.$field->slug.']', isset($files) && $files->get($field->slug) ? $files->get($field->slug)->id : null , [ 'class' => 'form-control hidden_file']) !!}
                                        <a onclick="deleteFileFromPreview($(this))" style="{{ isset($files) && $files->get($field->slug) ? ''  : 'display:none'}}" href="#" class="btn btn-danger btn-addon delete_button">
                                            Delete</a>
                                    </div>
                                </div>
                            </div>
                            <div class="line line-dashed b-b line-lg pull-in"></div>
                        @endif
                    @endforeach
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="display_date">{{ trans('content::default.posts.display_date') }}</label>
                        <div class="col-sm-10">
                            {!! Form::text('display_date', isset($post)&&isset($post->display_date)?$post->display_date->format('d-m-Y H-i'):\Carbon\Carbon::now()->format('d-m-Y H-i'), [ 'ui-jq' => "datetimepicker" , 'ui-options' => "{ format: 'DD-MM-YYYY HH-mm'}",'class' => 'form-control']) !!}
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
                            admin_route('content.roots.posts.index', [$root->slug]),
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
@endsection
@section('main-after')
    {!! Form::close() !!}
@endsection
@section('scripts')
    @include('media::manager.modal',['image_ids'=>[''],'params' => ['multiple' => 0]])
    <script>
        function aa_view(){
            var aa_value = document.getElementById('aa_value');
            var aa_display = document.getElementById('aa_display');

            if (aa_value.value == '') aa_display.innerHTML = 'Add link to article!';
            else {
                aa_display.innerHTML = 'waiting...';
                jQuery.post('test',{"_token": "{{ csrf_token() }}", url:aa_value.value,opt:1},function(_data){
                    if (/^[\],:{}\s]*$/.test(_data.replace(/\\["\\\/bfnrtu]/g, '@').
                        replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
                        replace(/(?:^|:|,)(?:\s*\[)+/g, ''))){
                        var data = JSON.parse(_data);
                        for (var i=0; i<data.imgs.length; i++){
                            var checked = i==0 ? ' checked' : '';
                            data.imgs[i] = "<div><img src='"+data.imgs[i]+"' onclick='img_radio(this)' style='max-width:100%;' /><input type='radio' class='minilogo' name='minilogo'"+checked+" /></div>";
                        }
                        aa_display.innerHTML = "<div><p>Illustrations:</p><div id='dis_imgs'>"+data.imgs.join('')+"</div><p>Title:</p><h1 id='dis_title' contenteditable style='padding:8px; border:1px solid #888;'>"+data.title+"</h1><p>Content:</p><p id='dis_desc' contenteditable style='padding:8px; border:1px solid #888;'>"+data.desc+"</p><a onclick='assept(this)' class='btn btn-default'>Assept</a></div>";
                    } else if (typeof _data == 'string') aa_display.innerHTML = _data;
                }).fail(function(xhr, status, error) {
                    alert( error );
                    aa_display.innerHTML = error;
                });
            }
        }
        function assept(_this){
            var dis_imgs = document.getElementById('dis_imgs'), img = null;
            var title = document.getElementById('dis_title').innerHTML;
            var desc = document.getElementById('dis_desc').innerHTML;
            var aa_display = document.getElementById('aa_display');

            _this.disabled;

            var radios = dis_imgs.getElementsByClassName('minilogo');
            for (var i=0; i<radios.length; i++){
                if (radios[i].checked){
                    img = radios[i].parentNode.getElementsByTagName('img')[0].src;
                    break;
                }
            }

            aa_display.innerHTML = 'waiting...';
            jQuery.post('test',{"_token": "{{ csrf_token() }}", url:aa_value.value, title:title, desc:desc, img:img, opt:2},function(_data){
                aa_display.innerHTML = _data;
                window.location.href = "/admin/content/roots/news";

            });
        }
        function img_radio(_this){
            _this.parentNode.getElementsByTagName('input')[0].checked = true;
        }
    </script>
@endsection
