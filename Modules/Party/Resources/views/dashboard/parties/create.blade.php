@extends('apps::dashboard.layouts.app')
@section('title', __('party::dashboard.parties.routes.create'))
@section('css')
    <link href="{{asset('/admins/css/bootstrap-datetimepicker.min.css')}}">
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
        .select2-container--bootstrap{
            width: 100% !important;
        }
    </style>
@endsection
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
                        <a href="#">{{ __('party::dashboard.parties.routes.create') }}</a>
                    </li>
                </ul>
            </div>

            <h1 class="page-title"></h1>

            <div class="row">
                <form id="form" role="form" class="form-horizontal form-row-seperated" method="post"
                    enctype="multipart/form-data" action="{{ route('dashboard.parties.store') }}">
                    @csrf
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

{{--                                                <li class="">--}}
{{--                                                    <a href="#use" data-toggle="tab">--}}
{{--                                                        {{ __('party::dashboard.parties.form.tabs.use') }}--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}

{{--                                                <li class="">--}}
{{--                                                    <a href="#media" data-toggle="tab">--}}
{{--                                                        {{ __('party::dashboard.parties.form.tabs.media') }}--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}

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
                                                                    data-name="title.{{ $code }}">
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
                                                                          data-name="description.{{ $code }}"></textarea>
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
                                                <select name="package_id" class="form-control select2" multiple>
                                                    <option value=""></option>
                                                    @foreach($packages as $package)
                                                        <option value="{{$package->id}}" data-area="{{$package->invitations_limit}}" {{$model && $model->package_id == $package->id ? 'selected' : ''}}>{{$package->title}}</option>
                                                    @endforeach
                                                </select>
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
                                                        <option value="{{$invitee->id}}">{{$invitee->name}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        <div class="counts hidden mb-25">
                                            <div class="row" style="padding: 0;margin: 0">
                                                <div class="col-md-2"></div>
                                                <div class="col-md-9" style="border: 1px solid #ccc">
                                                    <h4 class="mx-25">{{__('party::dashboard.parties.form.counts_no')}}</h4>
                                                    <div class="data">

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
                                                    <input class="form-control" type="date" name="start_at" data-name="start_at">
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
                                                    <input type="date"  class="form-control " name="expired_at" data-name="expired_at">
                                                    <span class="input-group-btn">
                                                        <button class="btn default" type="button">
                                                            <i class="fa fa-calendar"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                        {!! field()->file('image',__('party::dashboard.parties.form.image'),null,['accept' => '.pdf,.jpg,.jpeg'])!!}

                                        <div class="form-group">
                                            <label class="col-md-2">
                                                {{ __('party::dashboard.parties.form.status') }}
                                            </label>
                                            <div class="col-md-9">
                                                <input type="checkbox" class="make-switch" id="test" data-size="small"
                                                    name="status">
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
                                                                          data-name="whatsapp_msg.{{ $code }}"></textarea>
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
                                                                              data-name="acceptance_reply.{{ $code }}"></textarea>
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
                                                                              data-name="rejection_reply.{{ $code }}"></textarea>
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
                                                                              data-name="reminder_msg.{{ $code }}"></textarea>
                                                                    <div class="help-block"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
{{--                                            {!! field()->file('invitation_file',__('party::dashboard.parties.form.invitation_file'))!!}--}}
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
{{--                                                <input type="text" class="form-control" id="address" data-size="small" name="address">--}}
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
{{--                                                        <option value="{{$city->id}}">{{$city->title}}</option>--}}
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
                                                <input type="text" class="form-control" name="address_link" value="">
                                                <div class="help-block"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade in" id="contacts">
                                    <div class="col-md-10">
                                        <h3 class="mb-25">{{__('contact::dashboard.contacts.routes.index')}}</h3>
                                        <div>
                                            <div class="tabbable">
                                                <ul class="nav nav-tabs bg-slate nav-tabs-component">
                                                    <li class="active">
                                                        <a href="#rounded-tab-general-1" data-toggle="tab" aria-expanded="false">
                                                            {{__('apps::dashboard.buttons.add_new')}}
                                                        </a>
                                                    </li>
                                                    <li class="second">
                                                        <a href="#rounded-tab-general-2" data-toggle="tab" aria-expanded="false">
                                                            {{__('apps::dashboard.buttons.import')}}
                                                        </a>
                                                    </li>
                                                    <li class="third">
                                                        <a href="#rounded-tab-general-3" data-toggle="tab" aria-expanded="false">
                                                            {{__('party::dashboard.parties.form.invitee_contacts')}}
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="tab-content">
                                                <div class="tab-pane active" id="rounded-tab-general-1">
                                                    {!! field()->text('name', __('contact::dashboard.contacts.form.name')) !!}
                                                    {!! field()->text('mobile', __('contact::dashboard.contacts.form.mobile')) !!}
                                                    {!! field()->email('email', __('contact::dashboard.contacts.form.email')) !!}
                                                    {!! field()->number('max_invitations', __('contact::dashboard.contacts.form.max_invitations'),1,['min'=>1]) !!}
                                                    <input type="hidden" name="contact_type" value="1">
                                                    <input type="hidden" name="contacts">
                                                    <div class="form-group">
                                                        <div class="col-md-2"></div>
                                                        <div class="col-md-9">
                                                            <button type="button" id="addContact" class="btn  btn-md blue mb-25">
                                                                {{ __('apps::dashboard.buttons.add_new') }}
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <table class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>{{__('contact::dashboard.contacts.form.name')}}</th>
                                                                <th>{{__('contact::dashboard.contacts.form.mobile')}}</th>
                                                                <th>{{__('contact::dashboard.contacts.form.email')}}</th>
                                                                <th>{{__('contact::dashboard.contacts.form.max_invitations')}}</th>
                                                                <th>{{__('contact::dashboard.contacts.datatable.options')}}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="emptyTr">
                                                                <td colspan="6">{{__('party::dashboard.parties.datatable.no_contacts_found')}}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="tab-pane " id="rounded-tab-general-2">
                                                    {!! field()->file('excel_file',__('contact::dashboard.contacts.excel_file'),null,['accept'=>".xlsx,.xls,application/vnd.openxmlformatsofficedocument.spreadsheetml.sheet,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",]) !!}
                                                    <div class="form-group" style="margin-top: -15px;">
                                                        <label for="" class="col-md-2"></label>
                                                        <div class="col-md-9">
                                                            <p style="margin: 0">
                                                                {{ __('apps::dashboard.buttons.excel_examples') }}
                                                                <a target="_blank" href="{{asset('uploads/contacts.xlsx')}}">{{ __('apps::dashboard.buttons.download') }}</a>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="rounded-tab-general-3">
                                                    <div class="form-group">
                                                        <label class="col-md-2">
                                                            {{ __('party::dashboard.parties.form.contacts') }}
                                                        </label>
                                                        <div class="col-md-9">
                                                            <select name="contact_id[]" class="form-control contact_id select2" multiple>
                                                            </select>
                                                            <div class="help-block"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>
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
                                    <button type="submit" id="submit" class="btn btn-lg blue">
                                        {{ __('apps::dashboard.buttons.add') }}
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
    <style>
        .bootstrap-switch{
            max-height: 32px;
        }
        #somecomponent div:nth-child(2){
            /*z-index: -1  !important;*/
        }
    </style>
    <script type="text/javascript" src='http://maps.google.com/maps/api/js?sensor=false&libraries=places'></script>
    <script src="{{asset('admin/js/locationpicker.jquery.js')}}"></script>
    <script src="{{asset('/admins/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            $('#contacts .nav-tabs-component li').on('click',function (){
                if($(this).hasClass('third')){
                    $('input[name="contact_type"]').val(3)
                }else if($(this).hasClass('second')){
                    $('input[name="contact_type"]').val(2)
                }else{
                    $('input[name="contact_type"]').val(1)
                }
            })

            $('#somecomponent').locationpicker({
                location: {latitude: 29.3759709, longitude: 47.9844442},
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
                                x+="<option value='"+item.id+"'>"+item.title+"</option>";
                            });
                           $('select[name="state_id"]').append(x).select2({
                               'placeholder' : "Select",
                           });
                       },
                    });
                }
            });
        });
    </script>

    <script type="text/javascript">
        $(function (){
            let max;
            $('select[name="package_id"]').on('change',function (e){
                max = $(this).children('option:selected').data('area');
            });

            function getInviteesContacts(id){
                $.ajax({
                    type:'get',
                    url: "{{route('dashboard.contacts.inviteesContacts')}}",
                    data:{
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                        'ids': id,
                    },
                    success: function (data){
                        let oldVal = $('select.contact_id').val()
                        $('select.contact_id').empty().select2('destroy');
                        let x = '';
                        $.each(data.contacts,function(index,item){
                            x+="<option value='"+item.id+"'>"+item.name + " === " + item.mobile +"</option>";
                        });
                        $('select.contact_id').append(x).select2({
                            'placeholder' : "Select",
                        });
                        $('select.contact_id').val(oldVal).trigger('change')
                    },
                });
            }

            $('select[name="invitee_id[]"]').on('change',function (){
                let data = $(this).select2('data');
                buildCountsInputs(data);
                getInviteesContacts($(this).val())
            })

            function buildCountsInputs(data){
                let x = '<div class="col-10">';
                for (let i = 0; i < data.length ; i++) {
                    x+= `<div class="col-md-12 rowItem">` +
                        '<div class="form-group">'+
                        '<label class="col-md-5">'+data[i].text+' ( {{__('party::dashboard.parties.form.invitations')}} ) </label>'+
                        '<div class="col-md-6">'+
                        `<input type="number" data-base="${max/data.length}" name="counts[${data[i].id}]" min="0" max="${max}" class="form-control" value="${max/data.length}">`+
                        '<div class="help-block"></div>'+
                        '</div>'+
                        '</div>'+
                        '</div>';
                }
                x+= '</div>';
                $('.counts .data').empty().html(x);
                $('.counts').removeClass('hidden')
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

            let contacts = [];
            let count = 0;
            $('#addContact').on('click',function (){
                count++;
                $('.table tbody .emptyTr').remove();
                let name = $('#contacts input[name="name"]').val();
                let mobile = $('#contacts input[name="mobile"]').val();
                let email = $('#contacts input[name="email"]').val();
                let max_invitations = $('#contacts input[name="max_invitations"]').val();

                if(name && mobile && max_invitations){
                    let row = '<tr>'+
                        `<td>${count}</td>`+
                        `<td>${name}</td>`+
                        `<td>${mobile}</td>`+
                        `<td>${email}</td>`+
                        `<td>${max_invitations}</td>`+
                        '<td>'+
                        `<a class="btn btn-xs btn-danger removeRow" data-area="${count}"> <i class="fa fa-trash"></i> </a>`
                    '</td>'+
                    '</tr>';

                    $('.table tbody').append(row);
                    $('#contacts input').val('');
                    $('#contacts input[name="max_invitations"]').val(1);

                    contacts[count] = {
                        'name': name,
                        'mobile': mobile,
                        'email': email,
                        'max_invitations': max_invitations,
                    };
                    $('#contacts input[name="contacts"]').val(JSON.stringify(contacts));
                }
            });

            $(document).on('click','.removeRow',function (){
                contacts.splice($(this).data('area'), 1);
                let tbody = $(this).parents('tbody');
                $(this).parents('tr').remove();
                if(!tbody.find('tr').length){
                    tbody.append('<tr class="emptyTr"><td colspan="6">{{__('party::dashboard.parties.datatable.no_contacts_found')}}</td></tr>')
                }
            });
        });
    </script>

@endsection
