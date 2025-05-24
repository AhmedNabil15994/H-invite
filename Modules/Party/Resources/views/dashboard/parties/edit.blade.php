@extends('apps::dashboard.layouts.app')
@section('css')
    <style>
        .hidden{
            display: none;
        }
        textarea{
            min-height: 150px;
            max-height: 200px;
        }
        .mb-30{
            margin-bottom: 30px;
        }
        .mb-25{
            margin-bottom: 25px;
        }
        .mx-25{
            margin-top: 25px;
            margin-bottom: 25px;
        }
        .bootstrap-switch{
            max-height: 32px;
        }
        #somecomponent div:nth-child(2){
            /*z-index: -1  !important;*/
        }
        .select2-container--bootstrap{
            width: 100% !important;
        }
        .invitation{
            overflow: hidden;
            display: block;
            margin: auto;
            position: relative;
            background-image: url("{{$dimensions['image']}}");
            height: 880px;
            width: 560px;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .qrImage{
            border: 2px solid #000;
            border-radius: 5px;
            background: #FFF;
            padding: 10px;
            width: max-content;
            cursor: pointer;
            position: absolute;
            top: {{$dimensions['distY']}}px;
            left: {{$dimensions['distX']}}px;

        }
        .invitation .code{
            border: 2px solid #000;
            padding: 0 8px;
            text-align: center;
            margin-top: 5px;
            height: 25px;
            width: {{setting('qr_width')}}px;
        }

        .invitation .actions{
            position: absolute;
            text-align: center;
            margin-top: 5px;
            padding: 3px 0;
            background: transparent;
            width: {{setting('qr_width')}}px;
            top: {{$dimensions['distY2']}}px;
            left: {{$dimensions['distX2']}}px;
        }
        .invitation .actions p{
            margin: 0;
            margin-top: 15px;
            font-weight: bold;
            font-size: 15px;
            color: #777;
        }
        .actions a{
            border: 1px solid #000;
            border-radius: 20px;
            color: #777;
            background: #FFF;
            margin: 10px 0;
            padding: 6px 0;
            display: block;
            font-weight: bold;
            text-decoration: none;
            font-size: 13px;
        }
        .actions a:hover,
        .actions a:active,
        .actions a:focus{
            text-decoration: none;
        }
    </style>
@endsection
@section('title', __('party::dashboard.parties.routes.update'))
@section('content')
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li>
                        <a href="{{ url(route('dashboard.home')) }}">{{ __('apps::dashboard.index.title') }}</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="{{ url(route('dashboard.parties.index')) }}">
                            {{ __('party::dashboard.parties.routes.index') }}
                        </a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="#">{{ __('party::dashboard.parties.routes.update') }}</a>
                    </li>
                </ul>
            </div>

            <h1 class="page-title"></h1>
            <div class="row">
                <form id="updateForm" page="form" class="form-horizontal form-row-seperated" method="post"
                    enctype="multipart/form-data" action="{{ route('dashboard.parties.update', $model->id) }}" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="col-md-12">

                        {{-- RIGHT SIDE --}}
                        <div class="col-md-3">
                            <div class="panel-group accordion scrollable" id="accordion2">
                                <div class="panel panel-default">
                                    {{-- <div class="panel-heading">
                                        <h4 class="panel-title"><a class="accordion-toggle"></a></h4>
                                    </div> --}}
                                    <div id="collapse_2_1" class="panel-collapse in">
                                        <div class="panel-body">
                                            <ul class="nav nav-pills nav-stacked">
                                                <li class="active">
                                                    <a href="#global_setting" data-toggle="tab">
                                                        {{ __('party::dashboard.parties.form.tabs.general') }}
                                                    </a>
                                                </li>

                                                <li class="">
                                                    <a href="#whatsapp_msg" data-toggle="tab">
                                                        {{ __('party::dashboard.parties.form.tabs.whatsapp_msg') }}
                                                    </a>
                                                </li>

                                                <li class="">
                                                    <a href="#location" data-toggle="tab">
                                                        {{ __('party::dashboard.parties.form.tabs.location') }}
                                                    </a>
                                                </li>

                                                <li class="">
                                                    <a href="#contacts" data-toggle="tab">
                                                        {{ __('party::dashboard.parties.form.tabs.contacts') }}
                                                    </a>
                                                </li>

                                                <li class="">
                                                    <a href="#invitations" data-toggle="tab">
                                                        {{ __('apps::dashboard._layout.aside.all_invitations') }}
                                                    </a>
                                                </li>

                                                <li class="">
                                                    <a href="#invitation_design" data-toggle="tab">
                                                        {{ __('party::dashboard.parties.form.tabs.invitation_design') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- PAGE CONTENT --}}
                        <div class="col-md-9">
                            <div class="tab-content">
                                {{-- CREATE FORM --}}

                                <div class="tab-pane active fade in" id="global_setting">
                                    {{-- <h3 class="page-title">{{__('coupon::dashboard.coupons.form.tabs.general')}}</h3> --}}
                                    <div class="col-md-10">

                                        <div>
                                            <div class="tabbable">
                                                <ul class="nav nav-tabs bg-slate nav-tabs-component">
                                                    @foreach (config('laravellocalization.supportedLocales') as $code => $lang)
                                                        <li class=" {{ ($code == locale()) ? 'active' : '' }}">
                                                            <a href="#colored-rounded-tab-general-{{$code}}" data-toggle="tab" aria-expanded="false"> {{ $lang['native'] }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>

                                            <div class="tab-content">
                                                @foreach (config('laravellocalization.supportedLocales') as $code => $lang)
                                                    <div class="tab-pane @if ($code == locale()) active @endif"
                                                         id="colored-rounded-tab-general-{{ $code }}">
                                                        <div class="form-group">
                                                            <label class="col-md-2">
                                                                {{ __('party::dashboard.parties.form.title') }}
                                                            </label>
                                                            <div class="col-md-9">
                                                                <input type="text" name="title[{{ $code }}]"
                                                                       class="form-control"
                                                                       data-name="title.{{ $code }}" value="{{$model?->getTranslations('title')[$code]}}">
                                                                <div class="help-block"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-md-2">
                                                                {{ __('party::dashboard.parties.form.description') }}
                                                            </label>
                                                            <div class="col-md-9">
                                                                <textarea name="description[{{ $code }}]"
                                                                          class="form-control "
                                                                          data-name="description.{{ $code }}">{{$model?->getTranslations('description')[$code] ?? ''}}</textarea>
                                                                <div class="help-block"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <hr>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('user::dashboard.users.create.form.package') }}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" value="{{$model->package->title}}" readonly>
                                                <input type="hidden" class="form-control" value="{{$model->package_id}}" readonly name="package_id">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('party::dashboard.parties.form.seller') }}
                                            </label>
                                            <div class="col-md-9">
                                                <select name="invitee_id[]" class="form-control select2" multiple>
                                                    <option value=""></option>
                                                    @foreach($invitees as $invitee)
                                                        <option value="{{$invitee->id}}" {{$model->invitees->contains($invitee->id) ? 'selected' : ''}}>{{$invitee->name}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="counts mb-25">
                                            <div class="row" style="padding: 0;margin: 0">
                                                <div class="col-md-2"></div>
                                                <div class="col-md-9" style="border: 1px solid #ccc">
                                                    <h4 class="mx-25">{{__('party::dashboard.parties.form.counts_no')}}</h4>
                                                    <div class="data">
                                                        @foreach($model->invitees as $invitee)
                                                            <div class="col-md-12 rowItem">
                                                                <div class="form-group">
                                                                    <label class="col-md-5">{{$invitee->name}} ( {{__('party::dashboard.parties.form.invitations')}} ) </label>
                                                                    <div class="col-md-6">
                                                                        <input type="number"
                                                                               name="counts[{{$invitee->id}}]" min="0" class="form-control" value="{{$invitee->pivot->count}}">
                                                                        <div class="help-block"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('party::dashboard.parties.form.start_at') }}
                                            </label>
                                            <div class="col-md-9">
                                                <div class="input-append date input-group input-medium">
                                                    <input class="form-control" type="date" name="start_at" data-name="start_at" value="{{date('Y-m-d',strtotime($model->start_at))}}">
                                                    <span class="input-group-btn">
                                                        <button class="btn default" type="button">
                                                            <i class="fa fa-calendar"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('party::dashboard.parties.form.expired_at') }}
                                            </label>
                                            <div class="col-md-9">
                                                <div class="input-append date input-group input-medium">
                                                    <input type="date"  class="form-control " name="expired_at" data-name="expired_at" value="{{date('Y-m-d',strtotime($model->expired_at))}}">
                                                    <span class="input-group-btn">
                                                        <button class="btn default" type="button">
                                                            <i class="fa fa-calendar"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        {!! field()->file('image',__('party::dashboard.parties.form.image'),$model->getFirstMediaUrl('images'),['accept' => '.pdf,.jpg,.jpeg'])!!}

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('party::dashboard.parties.form.status') }}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="checkbox" class="make-switch" id="test" data-size="small"
                                                       name="status" {{$model->status ? 'checked' : ''}}>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade in" id="whatsapp_msg">
                                    <div class="tab-content">
                                        <div class="tab-pane active fade in">
                                            <div>
                                                <div class="tabbable">
                                                    <ul class="nav nav-tabs bg-slate nav-tabs-component">
                                                        @foreach (config('laravellocalization.supportedLocales') as $code => $lang)
                                                            <li class=" {{ ($code == locale()) ? 'active' : '' }}">
                                                                <a href="#colored-tab-general-{{$code}}" data-toggle="tab" aria-expanded="false"> {{ $lang['native'] }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>

                                                <div class="tab-content">
                                                    @foreach (config('laravellocalization.supportedLocales') as $code => $lang)
                                                        <div class="tab-pane @if ($code == locale()) active @endif"
                                                             id="colored-tab-general-{{ $code }}">
                                                            <div class="form-group">
                                                                <label class="col-md-2">
                                                                    {{ __('party::dashboard.parties.form.whatsapp_msg') }}
                                                                </label>
                                                                <div class="col-md-9">
                                                                    <textarea name="whatsapp_msg[{{ $code }}]"
                                                                          class="form-control "
                                                                          data-name="whatsapp_msg.{{ $code }}">{{$model->getTranslations('whatsapp_msg')[$code] ?? ''}}</textarea>
                                                                    <div class="help-block"></div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-2">
                                                                    {{ __('party::dashboard.parties.form.acceptance_reply') }}
                                                                </label>
                                                                <div class="col-md-9">
                                                                    <textarea name="acceptance_reply[{{ $code }}]"
                                                                          class="form-control "
                                                                          data-name="acceptance_reply.{{ $code }}">{{$model->getTranslations('acceptance_reply')[$code] ?? ''}}</textarea>
                                                                    <div class="help-block"></div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-2">
                                                                    {{ __('party::dashboard.parties.form.rejection_reply') }}
                                                                </label>
                                                                <div class="col-md-9">
                                                                    <textarea name="rejection_reply[{{ $code }}]"
                                                                          class="form-control "
                                                                          data-name="rejection_reply.{{ $code }}">{{$model->getTranslations('rejection_reply')[$code] ?? ''}}</textarea>
                                                                    <div class="help-block"></div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-2">
                                                                    {{ __('party::dashboard.parties.form.reminder_msg') }}
                                                                </label>
                                                                <div class="col-md-9">
                                                                    <textarea name="reminder_msg[{{ $code }}]"
                                                                              class="form-control "
                                                                              data-name="reminder_msg.{{ $code }}">{{$model->getTranslations('reminder_msg')[$code] ?? ''}}</textarea>
                                                                    <div class="help-block"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
{{--                                            {!! field()->file('invitation_file',__('party::dashboard.parties.form.invitation_file'),$model->getFirstMediaUrl('invitations'))!!}--}}

                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade in" id="location">
                                    <div class="col-md-10">
{{--                                        <div class="form-group">--}}
{{--                                            <label class="col-md-2">--}}
{{--                                                {{ __('party::dashboard.parties.form.address') }}--}}
{{--                                            </label>--}}
{{--                                            <div class="col-md-9">--}}
{{--                                                <input type="text" class="form-control" value="{{$model->address}}" id="address" data-size="small" name="address">--}}
{{--                                                <div class="help-block"></div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label class="col-md-2">--}}
{{--                                                {{ __('party::dashboard.parties.form.city') }}--}}
{{--                                            </label>--}}
{{--                                            <div class="col-md-9">--}}
{{--                                                <select name="city_id" class="form-control select2">--}}
{{--                                                    <option value=""></option>--}}
{{--                                                    @foreach($cities as $city)--}}
{{--                                                        <option value="{{$city->id}}" {{$model->city_id == $city->id ? 'selected' : ''}}>{{$city->title}}</option>--}}
{{--                                                    @endforeach--}}
{{--                                                </select>--}}
{{--                                                <div class="help-block"></div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label class="col-md-2">--}}
{{--                                                {{ __('party::dashboard.parties.form.state') }}--}}
{{--                                            </label>--}}
{{--                                            <div class="col-md-9">--}}
{{--                                                <select name="state_id" class="form-control select2">--}}
{{--                                                    <option value=""></option>--}}
{{--                                                </select>--}}
{{--                                                <div class="help-block"></div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('party::dashboard.invitations.form.address_link') }}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="address_link" value="{{$model->address_link}}">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade in" id="contacts">
                                    <div class="col-md-10">
                                        <h3 class="mb-25">{{__('contact::dashboard.contacts.routes.index')}}</h3>
                                        <table class="table table-striped table-bordered table-hover"id="dataTable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>{{__('contact::dashboard.contacts.datatable.name')}}</th>
                                                    <th>{{__('contact::dashboard.contacts.datatable.mobile')}}</th>
                                                    <th>{{__('contact::dashboard.contacts.datatable.email')}}</th>
                                                    <th>{{__('contact::dashboard.contacts.datatable.status')}}</th>
                                                    <th>{{__('contact::dashboard.contacts.datatable.created_at')}}</th>
                                                    <th>{{__('contact::dashboard.contacts.datatable.options')}}</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane fade in" id="invitations">
                                    <div class="col-md-10">
                                        <h3 class="mb-25">{{__('party::dashboard.invitations.routes.index')}}</h3>
                                        <table class="table table-striped table-bordered table-hover" id="dataTable2">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{__('party::dashboard.invitations.form.number')}}</th>
                                                <th>{{__('party::dashboard.invitations.form.code')}}</th>
                                                <th>{{__('party::dashboard.invitations.form.contact')}}</th>
                                                <th>{{__('party::dashboard.invitations.form.party')}}</th>
                                                <th>{{__('party::dashboard.invitations.form.attended_at')}}</th>
                                                <th>{{__('party::dashboard.invitations.form.scanned_at')}}</th>
                                                <th>{{__('party::dashboard.invitations.datatable.status')}}</th>
                                                <th>{{__('party::dashboard.invitations.datatable.created_at')}}</th>
                                                <th>{{__('party::dashboard.invitations.datatable.options')}}</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane fade in" id="invitation_design">
                                    <div class="col-md-12">
                                        <h3>{{__('party::dashboard.parties.form.tabs.invitation_design')}}</h3>
                                        <p>{{__('party::dashboard.parties.form.invitation_design_p')}}</p>
                                        @include('party::dashboard.parties.components.invitation_design')
                                    </div>
                                </div>
                                {{-- END CREATE FORM --}}
                            </div>
                        </div>

                        {{-- PAGE ACTION --}}
                        <div class="col-md-12">
                            <div class="form-actions">
                                @include('apps::dashboard.layouts._ajax-msg')
                                <div class="form-group">
                                    <button type="submit" id="submit" class="btn btn-lg green">
                                        {{ __('apps::dashboard.buttons.edit') }}
                                    </button>
                                    <a href="{{ url(route('dashboard.parties.index')) }}" class="btn btn-lg red">
                                        {{ __('apps::dashboard.buttons.back') }}
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script type="text/javascript" src='http://maps.google.com/maps/api/js?sensor=false&libraries=places'></script>
    <script src="{{asset('admin/js/locationpicker.jquery.js')}}"></script>

    <script>
        $(function(){
            $( "#draggable" ).draggable({
                stop: function( event, ui ) {
                    $('input[name="dimensions[dist_x]"]').val(Math.round(ui.position.left));
                    $('input[name="dimensions[dist_y]"]').val(Math.round(ui.position.top));
                }
            });

            $('input[name="dimensions[dist_x]"],input[name="dimensions[dist_y]"]').on('change',function (){
                $('.qrImage').css('top',$('input[name="dimensions[dist_y]"]').val()+'px');
                $('.qrImage').css('left',$('input[name="dimensions[dist_x]"]').val()+'px');
            });

            $('input[name="dimensions[invitation_height]"],input[name="dimensions[invitation_width]"]').on('change',function (){
                $('input[name="dimensions[dist_y]"],input[name="dimensions[dist_x]"]').val(0).trigger('change')
                $('.invitation').css('height',$('input[name="dimensions[invitation_height]"]').val()+'px');
                $('.invitation').css('width',$('input[name="dimensions[invitation_width]"]').val()+'px');
            });

            $( "#draggable3" ).draggable({
                stop: function( event, ui ) {
                    $('input[name="dimensions[dist_x2]"]').val(Math.round(ui.position.left));
                    $('input[name="dimensions[dist_y2]"]').val(Math.round(ui.position.top));
                }
            });

            $('input[name="dimensions[dist_x2]"],input[name="dimensions[dist_y2]"]').on('change',function (){
                $('.actions').css('top',$('input[name="dimensions[dist_y2]"]').val()+'px');
                $('.actions').css('left',$('input[name="dimensions[dist_x2]"]').val()+'px');
            });

            function buildImagePreview(url){
                return '<div class="file-preview-frame float-{{locale() == 'ar' ? 'right' : 'left'}}">'+
                            '<div class="fileinput-remove"><i class="fa fa-times"></i></div>'+
                            `<img src="${url}" class="file-preview-image" title="Screenshot" alt="Screenshot" style="width:160px;height:160px;">`+
                        '</div>'
            }

            function readURL(input, previewId,multiple) {
                if (input.files && input.files[0]) {
                    $.each(input.files,function (index,item){
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            let url = e.target.result
                            let item = buildImagePreview(url);
                            if(!multiple){
                                $(previewId + ' .file-preview-thumbnails').empty();
                            }
                            $(previewId + ' .file-preview-thumbnails').append(item)
                        }
                        reader.readAsDataURL(input.files[index]);
                    })
                }
            }

            $("#images").change(function() {
                readURL(this, '#images_preview',$(this).attr('multiple') == 'multiple' ? true : false);
            });

            $("#main_image").change(function() {
                readURL(this, '#main_image_preview',$(this).attr('multiple') == 'multiple' ? true : false);
            });

            $(".btn-file").on('click',function (){
                $(this).siblings('input[type="file"]')[0].click()
            });

            $(document).on('click','.fileinput-remove',function (){
                if($(this).attr('multiple')){
                    $(this).val('');
                }else{
                    $.ajax({
                        type:'post',
                        url: "{{route('dashboard.parties.deleteMediaFiles')}}",
                        data:{
                            '_token': $('meta[name="csrf-token"]').attr('content'),
                            'id' : [$(this).data('id')]
                        },
                        success: function (data){
                           if(data[0]){
                               toastr['success'](data[1]);
                           }
                        },
                    });
                }
                $(this).parent('.file-preview-frame').remove();
            });
        })
    </script>

    <script type="text/javascript">
        $(function () {

            @if($dimensions['background'])
            $('.qrImage').css('background','#222').css('borderColor','#fff').css('color','#fff')
            $('.code').css('borderColor','#fff')
            @endif

            $('.background').on('switchChange.bootstrapSwitch', function (event, state) {
                if(state){
                    $('.qrImage').css('background','#222').css('borderColor','#fff').css('color','#fff')
                    $('.code').css('borderColor','#fff')
                }else{
                    $('.qrImage').css('background','#fff').css('borderColor','#000').css('color','#000')
                    $('.code').css('borderColor','#000');
                }
            });

            $('#somecomponent').locationpicker({
                location: {latitude:  "{{$model->lat ?? '29.3759709'}}",  longitude: "{{$model->lng ?? '47.9844442'}}"},
                zoom: 8,
                onchanged: function(currentLocation, radius, isMarkerDropped) {
                    $('input[name="lat"]').val(currentLocation.latitude);
                    $('input[name="lng"]').val(currentLocation.longitude);
                }
            });

            $('select[name="city_id"]').on('change',function (){
                if($(this).val()){
                    $.ajax({
                        type:'get',
                        url: "{{route('dashboard.states.getByCityId',['city_id'=>':id'])}}".replace(':id',$(this).val()),
                        success: function (data){
                            $('select[name="state_id"]').empty().select2('destroy');
                            let x = '<option value=""></option>';
                            $.each(data,function(index,item){
                                let extraString = "{{$model->state_id}}" == item.id ? 'selected' : '';
                                x+="<option value='"+item.id+"'  "+extraString+">"+item.title+"</option>";
                            });
                            $('select[name="state_id"]').append(x).select2({
                                'placeholder' : "Select",
                            });
                        },
                    });
                }
            });

            $('select[name="city_id"]').val('{{$model->city_id}}').trigger('change')

            let max = {{$model->package->invitations_limit ?? 0}};
            $('select[name="package_id"]').on('change',function (e){
                max = $(this).children('option:selected').data('area');
            });

            $('select[name="invitee_id[]"]').on('change',function (){
                let data = $(this).select2('data');
                buildCountsInputs(data);
            })

            function buildCountsInputs(data){
                let x = '<div class="col-10">';
                let rate ;
                let firstClass;
                for (let i = 0; i < data.length ; i++) {
                    if( i === 0){
                        firstClass = 'firstNumber';
                        rate = Math.round(Math.round(max/data.length) + (data.length * ((max/data.length) - Math.round(max/data.length)) ))
                    }else{
                        firstClass = '';
                    }
                    x+= `<div class="col-md-12 rowItem">` +
                            '<div class="form-group">'+
                                '<label class="col-md-5">'+data[i].text+' ( {{__('party::dashboard.parties.form.invitations')}} ) </label>'+
                                '<div class="col-md-6">'+
                                    `<input type="number" data-base="${max/data.length}" name="counts[${data[i].id}]" min="0" max="${max}" class="${firstClass} form-control" value="${Math.round(max/data.length)}">`+
                                    '<div class="help-block"></div>'+
                                '</div>'+
                            '</div>'+
                        '</div>';
                }
                x+= '</div>';
                $('.counts .data').empty().html(x);
                $('.counts input.firstNumber').val(rate)
            }

            $(document).on('change','.counts input[type="number"]', function() {
                if ($(this).val() !== '') {
                    let step = $(this).data('base') - $(this).val()
                    let nextItem = $(this).parents('.rowItem').siblings('.rowItem').find($('.counts input[type="number"]'))
                    if(parseInt(nextItem.data('base')) + parseInt(step) >= nextItem.attr('min') && parseInt(nextItem.data('base')) + parseInt(step) <= nextItem.attr('max')){
                        nextItem.val(parseInt(nextItem.data('base')) + parseInt(step))
                    }
                }
            })
        });
    </script>

    <script type="text/javascript">
        $(function (){
            function tableGenerate(data = '') {

                var dataTable =
                    $('#dataTable').DataTable({
                        "createdRow": function (row, data, dataIndex) {
                            if (data["deleted_at"] != null) {
                                $(row).addClass('danger');
                            }
                        },
                        ajax: {
                            url: "{{ url(route('dashboard.contacts.datatable')) }}?party_id={{$model->id}}",
                            type: "GET",
                            data: {
                                req: data,
                            },
                        },
                        language: {
                            url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/{{ucfirst(LaravelLocalization::getCurrentLocaleName())}}.json"
                        },
                        stateSave: true,
                        processing: true,
                        serverSide: true,
                        responsive: !0,
                        order: [[1, "desc"]],
                        columns: [
                            {data: 'id', className: 'dt-center'},
                            {data: 'name' , className: 'dt-center'},
                            {data: 'mobile' , className: 'dt-center'},
                            {data: 'email' , className: 'dt-center'},
                            {data: 'status' , className: 'dt-center'},
                            {data: 'created_at', className: 'dt-center'},
                            {data: 'id',responsivePriority: 1},
                        ],
                        columnDefs: [
                            {
                                targets: -3,
                                width: '30px',
                                className: 'dt-center',
                                render: function (data, type, full, meta) {
                                    if (data === 1) {
                                        return '<span class="badge badge-success"> {{__('apps::dashboard.datatable.active')}} </span>';
                                    } else {
                                        return '<span class="badge badge-danger"> {{__('apps::dashboard.datatable.unactive')}} </span>';
                                    }
                                },
                            },
                            {
                                targets: -1,
                                responsivePriority:1,
                                width: '13%',
                                title: '{{__('contact::dashboard.contacts.datatable.options')}}',
                                className: 'dt-center',
                                orderable: false,
                                render: function (data, type, full, meta) {

                                    // Edit
                                    var editUrl = '{{ route("dashboard.contacts.edit", ":id") }}';
                                    editUrl = editUrl.replace(':id', data);

                                    // Delete
                                    var deleteUrl = '{{ route("dashboard.contacts.destroy", ":id") }}';
                                    deleteUrl = deleteUrl.replace(':id', data);

                                    return `
                   @can('delete_contacts')
                                    <a href="` + editUrl + `" class="btn btn-sm blue" title="Edit">
                    <i class="fa fa-edit"></i>
                 </a>
                 @endcan
                                    @can('delete_contacts')
                                    @csrf
                                    <a href="javascript:;" onclick="deleteRow('` + deleteUrl + `')" class="btn btn-sm red">
                    <i class="fa fa-trash"></i>
                  </a>
                @endcan`;
                                },
                            },
                        ],
                        dom: 'Bfrtip',
                        lengthMenu: [
                            [10, 25, 50, 100, 500],
                            ['10', '25', '50', '100', '500']
                        ],
                        buttons: [
                            {
                                extend: "pageLength", className: "btn blue btn-outline",
                                text: "{{__('apps::dashboard.datatable.pageLength')}}",
                                exportOptions: {
                                    stripHtml: false,
                                    columns: ':visible',
                                    columns: [1, 2, 3, 4, 5]
                                }
                            },
                            {
                                extend: "print", className: "btn blue btn-outline",
                                text: "{{__('apps::dashboard.datatable.print')}}",
                                exportOptions: {
                                    stripHtml: false,
                                    columns: ':visible',
                                    columns: [1, 2, 3, 4, 5]
                                }
                            },
                            {
                                extend: "pdf", className: "btn blue btn-outline",
                                text: "{{__('apps::dashboard.datatable.pdf')}}",
                                exportOptions: {
                                    stripHtml: false,
                                    columns: ':visible',
                                    columns: [1, 2, 3, 4, 5]
                                }
                            },
                            {
                                extend: "excel", className: "btn blue btn-outline ",
                                text: "{{__('apps::dashboard.datatable.excel')}}",
                                exportOptions: {
                                    stripHtml: false,
                                    columns: ':visible',
                                    columns: [1, 2, 3, 4, 5]
                                }
                            },
                            {
                                extend: "colvis", className: "btn blue btn-outline",
                                text: "{{__('apps::dashboard.datatable.colvis')}}",
                                exportOptions: {
                                    stripHtml: false,
                                    columns: ':visible',
                                    columns: [1, 2, 3, 4, 5]
                                }
                            }
                        ]
                    });
            }

            function tableGenerate2(data='') {

                var dataTable2 =
                    $('#dataTable2').DataTable({
                        "createdRow": function( row, data, dataIndex ) {
                            if ( data["deleted_at"] != null ) {
                                $(row).addClass('danger');
                            }
                        },
                        ajax : {
                            url   : "{{ url(route('dashboard.invitations.datatable')) }}?party_id={{$model->id}}",
                            type  : "GET",
                            data  : {
                                req : data,
                            },
                        },
                        language: {
                            url:"//cdn.datatables.net/plug-ins/1.10.16/i18n/{{ucfirst(LaravelLocalization::getCurrentLocaleName())}}.json"
                        },
                        stateSave: true,
                        processing: true,
                        serverSide: true,
                        responsive: !0,
                        order     : [[ 1 , "desc" ]],
                        columns: [
                            {data: 'id' 		 	        , className: 'dt-center'},
                            {data: 'invitation_number' 			      , className: 'dt-center'},
                            {data: 'code' 			      , className: 'dt-center'},
                            {data: 'contact' 			      , className: 'dt-center'},
                            {data: 'party' 			      , className: 'dt-center'},
                            {data: 'attended_at' 			      , className: 'dt-center'},
                            {data: 'scanned_at' 			      , className: 'dt-center'},
                            {data: 'status' 	        , className: 'dt-center'},
                            {data: 'created_at' 		  , className: 'dt-center'},
                            {data: 'id'},
                        ],
                        columnDefs: [
                            {
                                targets: -3,
                                width: '30px',
                                className: 'dt-center',

                            },
                            {
                                targets: -1,
                                responsivePriority:1,
                                width: '13%',
                                title: '{{__('party::dashboard.invitations.datatable.options')}}',
                                className: 'dt-center',
                                orderable: false,
                                render: function(data, type, full, meta) {

                                    // Edit
                                    var editUrl = '{{ route("dashboard.invitations.edit", ":id") }}';
                                    editUrl = editUrl.replace(':id', data);

                                    // Delete
                                    var deleteUrl = '{{ route("dashboard.invitations.destroy", ":id") }}';
                                    deleteUrl = deleteUrl.replace(':id', data);

                                    // Show
                                    var showUrl = '{{ route("dashboard.invitations.show", ":id") }}';
                                    showUrl = showUrl.replace(':id', data);

                                    return `
                @can('edit_invitations')
                                    <a href="`+showUrl+`" class="btn btn-sm yellow" title="Show">
                      <i class="fa fa-eye"></i>
                    </a>
                    <a href="`+editUrl+`" class="btn btn-sm blue" title="Edit">
                      <i class="fa fa-edit"></i>
                    </a>


                @endcan

                                    @can('delete_invitations')
                                    @csrf
                                    <a href="javascript:;" onclick="deleteRow('`+deleteUrl+`')" class="btn btn-sm red">
                    <i class="fa fa-trash"></i>
                  </a>
                @endcan`;
                                },
                            },
                        ],
                        dom: 'Bfrtip',
                        lengthMenu: [
                            [ 10, 25, 50 , 100 , 500 ],
                            [ '10', '25', '50', '100' , '500']
                        ],
                        buttons:[
                            {
                                extend: "pageLength",
                                className: "btn blue btn-outline",
                                text: "{{__('apps::dashboard.datatable.pageLength')}}",
                                exportOptions: {
                                    stripHtml : false,
                                    columns: ':visible',
                                    columns: [ 1 , 2 , 3 , 4 , 5]
                                }
                            },
                            {
                                extend: "print",
                                className: "btn blue btn-outline" ,
                                text: "{{__('apps::dashboard.datatable.print')}}",
                                exportOptions: {
                                    stripHtml : false,
                                    columns: ':visible',
                                    columns: [ 1 , 2 , 3 , 4 , 5]
                                }
                            },
                            {
                                extend: "pdf",
                                className: "btn blue btn-outline" ,
                                text: "{{__('apps::dashboard.datatable.pdf')}}",
                                exportOptions: {
                                    stripHtml : false,
                                    columns: ':visible',
                                    columns: [ 1 , 2 , 3 , 4 , 5]
                                }
                            },
                            {
                                extend: "excel",
                                className: "btn blue btn-outline " ,
                                text: "{{__('apps::dashboard.datatable.excel')}}",
                                exportOptions: {
                                    stripHtml : false,
                                    columns: ':visible',
                                    columns: [ 1 , 2 , 3 , 4 , 5]
                                }
                            },
                            {
                                extend: "colvis",
                                className: "btn blue btn-outline",
                                text: "{{__('apps::dashboard.datatable.colvis')}}",
                                exportOptions: {
                                    stripHtml : false,
                                    columns: ':visible',
                                    columns: [ 1 , 2 , 3 , 4 , 5]
                                }
                            }
                        ]
                    });
            }

            jQuery(document).ready(function () {
                tableGenerate();
                tableGenerate2();
            });
        });
    </script>
@endsection
